<?php

namespace App\Jobs\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ShouldNotifyOrderSubmittedWithoutConsentForm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected AcuteHemodialysisOrderNote $order
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->order->load('caseRecord');
        $case = $this->order->caseRecord;
        if ($case->form['opd_consent_form'] || $case->form['ipd_consent_form']) {
            return;
        }
        $sticker = collect([
            ['packageId' => 446, 'stickerId' => 2004],
            ['packageId' => 446, 'stickerId' => 2005],
            ['packageId' => 446, 'stickerId' => 2007],
            ['packageId' => 789, 'stickerId' => 10879],
            ['packageId' => 789, 'stickerId' => 10879],
            ['packageId' => 789, 'stickerId' => 10881],
            ['packageId' => 789, 'stickerId' => 10884],
            ['packageId' => 6325, 'stickerId' => 10979922],
            ['packageId' => 11537, 'stickerId' => 52002750],
            ['packageId' => 11537, 'stickerId' => 52002751],
            ['packageId' => 11537, 'stickerId' => 52002765],
            ['packageId' => 11537, 'stickerId' => 52002767],
        ])->random();
        $this->order->load('author');
        $message = "คนไข้ {$this->order->meta['name']} ยังไม่ได้ลงข้อมูล Consent Form ในระบบ \n";
        $message .= "แจ้งเตือนจากการ set order โดย พ.{$this->order->author->first_name}";
        Http::withToken(config('line_notify_group_chat.acute_hd'))
            ->asForm()
            ->post('https://notify-api.line.me/api/notify', [
                'message' => $message,
                'stickerPackageId' => $sticker['packageId'],
                'stickerId' => $sticker['stickerId'],
            ]);
        // COUNT LINE NOTIFY
        $cacheKey = now()->format('Ym').'-LINE-NOTIFY-COUNT';
        cache()->increment($cacheKey);
    }
}
