<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Image\Image;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\CropPosition;

class ResizeImage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

     private $w,$h,$fileName,$path;
    public function __construct($filePath,$w,$h)
    {
        $this->path=dirname($filePath);
        $this->fileName=basename($filePath);
        $this->w=$w;
        $this->h=$h;
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $w = $this->w;
        $h = $this->h;
        $srcPath = storage_path() . '/app/public/' . $this->path . '/' . $this->fileName;
        $destPath = storage_path() . '/app/public/' . $this->path . "/crop_{$w}x{$h}_" . $this->fileName; // Fixed filename format

        $processor = Image::load($srcPath)
            ->crop($w, $h, CropPosition::Center);

        // Apply watermark if an asset is available
        $wmPrimary = base_path('resources/img/watermark.png');
        $wmAlt = base_path('resources/img/logo.png');
        $wm = file_exists($wmPrimary) ? $wmPrimary : (file_exists($wmAlt) ? $wmAlt : null);
        if ($wm) {
            $processor->watermark(
                $wm,
                width: 50,
                height: 50,
                paddingX: 5,
                paddingY: 5,
                paddingUnit: Unit::Percent
            );
        }

        $processor->save($destPath);
    }
}
