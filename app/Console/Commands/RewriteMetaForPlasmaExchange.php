<?php

namespace App\Console\Commands;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Console\Command;

class RewriteMetaForPlasmaExchange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rewrite-meta-for-plasma-exchange';

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
        // rewrite meta['dialysis_type'], search `TPE` replace with `PE`
        AcuteHemodialysisOrderNote::query()
            ->with('subscription')
            ->each(function (AcuteHemodialysisOrderNote $note) {
                foreach (['hd', 'hf', 'sledd'] as $type) {
                    if (isset($note->form[$type])) {
                        $note->form[$type]['catheter_lock'] = null;
                    }
                }

                if (isset($note->form['tpe'])) {
                    $note->meta['dialysis_type'] = str_replace('TPE', 'PE', $note->meta['dialysis_type']);
                    $note->form['pe'] = $note->form['tpe'];
                    $note->form['pe']['dialyzer_second'] = null;
                    $note->form['pe']['percent_discard'] = null;
                    $note->form['pe']['technique'] = 'TPE';
                    $note->form['pe']['catheter_lock'] = null;
                    unset($note->form['tpe']);
                }

                $note->save();
            });

        $this->info('Done');
    }
}
