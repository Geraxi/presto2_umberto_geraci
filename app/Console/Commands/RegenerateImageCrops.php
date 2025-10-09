<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Image;
use App\Jobs\ResizeImage;

class RegenerateImageCrops extends Command
{
    protected $signature = 'images:regenerate-crops {--w=300} {--h=300}';
    protected $description = 'Dispatch resize jobs for all stored images to regenerate crops';

    public function handle(): int
    {
        $width = (int) $this->option('w');
        $height = (int) $this->option('h');

        $count = 0;
        Image::chunk(200, function ($images) use (&$count, $width, $height) {
            foreach ($images as $image) {
                ResizeImage::dispatch($image->path, $width, $height);
                $count++;
            }
        });

        $this->info("Dispatched resize jobs for {$count} images ({$width}x{$height}).");
        return self::SUCCESS;
    }
}


