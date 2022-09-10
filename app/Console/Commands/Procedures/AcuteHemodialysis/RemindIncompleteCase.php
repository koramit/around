<?php

namespace App\Console\Commands\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisCaseRecordStatus;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use App\Notifications\MessagingApp;
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

    protected function getConsent(): array
    {
        $consent = [];
        $status = new AcuteHemodialysisCaseRecordStatus();
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta'])
            ->where('status', $status->getCode('active'))
            ->whereHas('firstPerformedOrder')
            ->whereNull('form->opd_consent_form')
            ->whereNull('form->ipd_consent_form')
            ->with(['firstPerformedOrder' => fn ($query) => $query->with('author:id,name')])
            ->get()
            ->each(function ($case) use (&$consent) {
                if (! isset($consent[$case->firstPerformedOrder->author->name])) {
                    $consent[$case->firstPerformedOrder->author->name] = [];
                }
                $consent[$case->firstPerformedOrder->author->name][] = $case->meta['name'].' **'.substr($case->meta['hn'], 6, 2);
            });

        return $consent;
    }

    protected function getComplete(): array
    {
        $complete = [];
        $status = new AcuteHemodialysisCaseRecordStatus();
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta'])
            ->whereIn('status', [$status->getCode('dismissed'), $status->getCode('discharged')])
            ->whereHas('firstPerformedOrder')
            ->with(['lastPerformedOrder' => fn ($query) => $query->with('author:id,name')])
            ->get()
            ->each(function ($case) use (&$complete) {
                if (! isset($complete[$case->lastPerformedOrder->author->name])) {
                    $complete[$case->lastPerformedOrder->author->name] = [];
                }
                $complete[$case->lastPerformedOrder->author->name][] = $case->meta['name'].' **'.substr($case->meta['hn'], 6, 2);
            });

        return $complete;
    }

    private function notifyAuthor()
    {
        // case has no consent form
        $consent = $this->getConsent();
        // complete case
        $complete = $this->getComplete();
        // authors
        $authors = [];

        foreach ($consent as $author => $cases) {
            $authors[$author]['consent'] = implode("\n", $cases);
            $authors[$author]['complete'] = '';
        }

        foreach ($complete as $author => $cases) {
            if (! isset($authors[$author])) {
                $authors[$author]['consent'] = '';
            }
            $authors[$author]['complete'] = implode("\n", $cases);
        }

        User::query()
            ->whereIn('name', array_keys($authors))
            ->get()
            ->each(function ($notifiable) use (&$authors) {
                $message = '';
                if ($authors[$notifiable->name]['consent']) {
                    $message .= '* เคสที่ยังไม่มีใบ consent';
                    $message .= "\n\n{$authors[$notifiable->name]['consent']}\n\n";
                }
                if ($authors[$notifiable->name]['complete']) {
                    $message .= '* เคสที่ยังค้างสรุป';
                    $message .= "\n\n{$authors[$notifiable->name]['complete']}\n\n";
                }

                // $this->line("$notifiable->name\n$message");
                $notifiable->notify(new MessagingApp($message));
            });
    }

    private function reportManager()
    {
        // case has no consent form
        $consent = $this->getConsent();
        // complete case
        $complete = $this->getComplete();
        // authors
        $authors = [];

        foreach ($consent as $author => $cases) {
            $authors[$author]['consent'] = $cases;
            $authors[$author]['complete'] = [];
        }

        foreach ($complete as $author => $cases) {
            if (! isset($authors[$author])) {
                $authors[$author]['consent'] = [];
            }
            $authors[$author]['complete'] = $cases;
        }

        $message = '';
        User::query()
            ->whereIn('name', array_keys($authors))
            ->get()
            ->each(function ($author) use (&$authors, &$message) {
                $text = '';
                $count = count($authors[$author->name]['consent']);
                if ($count) {
                    $text .= "ยังไม่มีใบ consent $count เคส\n";
                }
                $count = count($authors[$author->name]['complete']);
                if ($count) {
                    $text .= "ยังไม่สรุป $count เคส\n";
                }
                $message .= "พ.$author->first_name\n$text\n\n";
            });

        $this->line($message);
    }
}
