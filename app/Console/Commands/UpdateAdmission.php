<?php

namespace App\Console\Commands;

use App\Managers\Resources\AdmissionManager;
use App\Models\Resources\Admission;
use Illuminate\Console\Command;

class UpdateAdmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admission:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update admission status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $manager = new AdmissionManager();
        Admission::query()
            ->whereNull('dismissed_at')
            ->where('updated_at', '<', now()->subHours(6))
            ->each(fn ($admission) => $manager->manage($admission->an));

        return Command::SUCCESS;
    }
}
