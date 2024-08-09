<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveStorageUploadsToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-storage-uploads-to-s3';

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
        $folders = [
            ['path' => 'uploads/w/k/a', 'label' => 'KT ward'],
            ['path' => 'uploads/l/k/h', 'label' => 'KT HLA lab'],
            ['path' => 'uploads/p/a/o', 'label' => 'Acute HD OPD consent'],
            ['path' => 'uploads/p/a/i', 'label' => 'Acute HD IPD consent'],
        ];

        foreach ($folders as $folder) {
            $this->info("Moving {$folder['label']} uploads to S3...");
            $files = Storage::disk('local')->allFiles($folder['path']);
            $run = 1;
            $count = count($files);
            foreach ($files as $file) {
                $this->info("Moving $run/$count $file...");
                Storage::disk('s3')->put($file, Storage::disk('local')->get($file));
                $run++;
            }
            $this->line('');
        }

        $this->info('Done!');
    }
}
