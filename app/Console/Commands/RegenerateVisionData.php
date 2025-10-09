<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Image;
use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\GoogleVisionLabelImage;

class RegenerateVisionData extends Command
{
    protected $signature = 'images:regenerate-vision';
    protected $description = 'Dispatch Google Vision jobs for all stored images (labels and safe search)';

    public function handle(): int
    {
        $count = 0;
        Image::chunk(200, function ($images) use (&$count) {
            foreach ($images as $image) {
                GoogleVisionSafeSearch::dispatch($image->id);
                GoogleVisionLabelImage::dispatch($image->id);
                $count++;
            }
        });

        $this->info("Dispatched Google Vision jobs for {$count} images.");
        return self::SUCCESS;
    }
}
