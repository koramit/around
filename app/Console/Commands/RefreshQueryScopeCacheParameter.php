<?php

namespace App\Console\Commands;

use App\Models\Resources\NoteType;
use App\Models\Resources\Registry;
use Illuminate\Console\Command;

class RefreshQueryScopeCacheParameter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:query-scope-params';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $scopeParamList = [
            'registry-id-acute_hd' => fn () => Registry::query()->where('name', 'acute_hd')->first()->id,
            'registry-id-kt_hla_typing' => fn () => Registry::query()->where('name', 'kt_hla_typing')->first()->id,
            'registry-id-kt_admission' => fn () => Registry::query()->where('name', 'kt_admission')->first()->id,
            'note-type-id-acute_hd_order' => fn () => NoteType::query()->where('name', 'acute_hd_order')->first()->id,
            'note-type-id-kt_addition_tissue_typing_note' => fn () => NoteType::query()->where('name', 'kt_addition_tissue_typing_note')->first()->id,
            'note-type-id-kt_hla_cxm_note' => fn () => NoteType::query()->where('name', 'kt_hla_cxm_note')->first()->id,
            'note-type-id-kt_hla_typing_note' => fn () => NoteType::query()->where('name', 'kt_hla_typing_note')->first()->id,
            'note-type-id-kt_hla_typing_report' => fn () => NoteType::query()->where('name', 'kt_hla_typing_report')->first()->id,
        ];

        foreach ($scopeParamList as $key => $value) {
            $value = $value();
            $cacheValue = cache($key);
            $this->line("'$key' => $value, $cacheValue");
            if ($value !== $cacheValue) {
                cache()->pull($key);
                cache()->put($key, $value);
                $this->line("'$key' UPDATED!");
            }
        }
    }
}
