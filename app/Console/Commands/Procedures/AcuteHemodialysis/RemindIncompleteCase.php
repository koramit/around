<?php

namespace App\Console\Commands\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisCaseRecordStatus;
use App\Models\EventBasedNotification;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\MessagingApp;
use App\Notifications\Procedures\AcuteHemodialysis\IncompleteCaseDailyReport;
use Illuminate\Console\Command;

class RemindIncompleteCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acute-hd:remind-incomplete-case {mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->argument('mode') === 'notify') {
            $this->notifyAuthor();
        } elseif ($this->argument('mode') === 'report') {
            $this->reportManager();
        }

        return 0;
    }

    protected function getNoConsent(): array
    {
        $noConsent = [];
        $status = new AcuteHemodialysisCaseRecordStatus;
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta'])
            ->where('status', $status->getCode('active'))
            ->whereHas('firstPerformedOrder')
            ->whereNull('form->opd_consent_form')
            ->whereNull('form->ipd_consent_form')
            ->with(['firstPerformedOrder' => fn ($query) => $query->with('author:id,name')])
            ->get()
            ->each(function ($case) use (&$noConsent) {
                if (! isset($noConsent[$case->firstPerformedOrder->author->name])) {
                    $noConsent[$case->firstPerformedOrder->author->name] = [];
                }
                $noConsent[$case->firstPerformedOrder->author->name][] = $case->meta['name'].' **'.substr($case->meta['hn'], 6, 2);
            });

        return $noConsent;
    }

    protected function getIncomplete(): array
    {
        $incomplete = [];
        $status = new AcuteHemodialysisCaseRecordStatus;
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta'])
            ->whereIn('status', [$status->getCode('dismissed'), $status->getCode('discharged')])
            ->whereHas('firstPerformedOrder')
            ->with(['lastPerformedOrder' => fn ($query) => $query->with('author:id,name')])
            ->get()
            ->each(function ($case) use (&$incomplete) {
                if (! isset($incomplete[$case->lastPerformedOrder->author->name])) {
                    $incomplete[$case->lastPerformedOrder->author->name] = [];
                }
                $incomplete[$case->lastPerformedOrder->author->name][] = $case->meta['name'].' **'.substr($case->meta['hn'], 6, 2);
            });

        return $incomplete;
    }

    private function notifyAuthor()
    {
        // case has no consent form
        $noConsent = $this->getNoConsent();
        // complete case
        $incomplete = $this->getIncomplete();
        // authors
        $authors = [];

        foreach ($noConsent as $author => $cases) {
            $count = 1;
            $text = '';
            foreach ($cases as $case) {
                $text .= "$count. $case\n";
                $count++;
            }
            $authors[$author]['noConsent'] = $text;
            $authors[$author]['incomplete'] = '';
        }

        foreach ($incomplete as $author => $cases) {
            if (! isset($authors[$author])) {
                $authors[$author]['noConsent'] = '';
            }
            $count = 1;
            $text = '';
            foreach ($cases as $case) {
                $text .= "$count. $case\n";
                $count++;
            }
            $authors[$author]['incomplete'] = $text;
        }

        User::query()
            ->whereIn('name', array_keys($authors))
            ->get()
            ->each(function ($notifiable) use (&$authors) {
                $message = 'แจ้งเตือนเคส Acute HD ค้างสรุป วันที่ '.now(+7)->format('d M y')."\n\n";
                if ($authors[$notifiable->name]['noConsent']) {
                    $message .= '* ไม่มีใบ consent';
                    $message .= "\n\n{$authors[$notifiable->name]['noConsent']}\n\n";
                }
                if ($authors[$notifiable->name]['incomplete']) {
                    $message .= '* ค้างสรุป';
                    $message .= "\n\n{$authors[$notifiable->name]['incomplete']}\n\n";
                }

                $message .= ' ✌️✌️😃';

                // $this->line("$notifiable->name\n$message");
                $notifiable->notify(new MessagingApp($message));
            });
    }

    private function reportManager()
    {
        $event = EventBasedNotification::query()
            ->where('notification_class_name', IncompleteCaseDailyReport::class)
            ->first();

        $subscribers = Subscription::query()
            ->where('subscribable_type', $event::class)
            ->where('subscribable_id', $event->id)
            ->first()
            ->subscribers;

        if ($subscribers->count() === 0) {
            return;
        }

        // case has no consent form
        $noConsent = $this->getNoConsent();
        // complete case
        $incomplete = $this->getIncomplete();
        // authors
        $authors = [];

        foreach ($noConsent as $author => $cases) {
            $authors[$author]['noConsent'] = $cases;
            $authors[$author]['incomplete'] = [];
        }

        foreach ($incomplete as $author => $cases) {
            if (! isset($authors[$author])) {
                $authors[$author]['noConsent'] = [];
            }
            $authors[$author]['incomplete'] = $cases;
        }

        $message = '';
        User::query()
            ->whereIn('name', array_keys($authors))
            ->get()
            ->each(function ($author) use (&$authors, &$message) {
                $text = '';
                $count = count($authors[$author->name]['noConsent']);
                if ($count) {
                    $text .= "ยังไม่มีใบ consent $count เคส\n";
                }
                $count = count($authors[$author->name]['incomplete']);
                if ($count) {
                    $text .= "ยังไม่สรุป $count เคส\n";
                }
                $message .= "พ.$author->first_name\n$text\n\n";
            });

        if ($message) {
            $message = 'รายงานเคส Acute HD ค้างสรุปวันที่ '.now(+7)->format('d M Y')."\n\n".trim($message, "\n");
        } else {
            $message = 'วันนี้ไม่มีเคส Acute HD ค้างสรุป';
        }

        $subscribers->each(fn ($subscriber) => $subscriber->notify(new IncompleteCaseDailyReport($message)));
    }
}
