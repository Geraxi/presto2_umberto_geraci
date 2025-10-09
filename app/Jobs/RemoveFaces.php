<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Image\Enums\Fit;
use App\Models\Image;
use Spatie\Image\Image as SpatieImage;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Spatie\Image\Enums\AlignPosition;
use Illuminate\Foundation\Bus\Dispatchable; 
use Illuminate\Queue\InteractsWithQueue;     
use Illuminate\Queue\SerializesModels;  

class RemoveFaces implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $article_image_id;

    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    public function handle()
    {
        $i = Image::find($this->article_image_id);
        if (!$i) return;

        $srcPath = storage_path('app/public/' . $i->path);
        $image = file_get_contents($srcPath);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json')); // Fixed typo

        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->faceDetection($image);
        $faces = $response->getFaceAnnotations();

        foreach ($faces as $face) {
            $vertices = $face->getBoundingPoly()->getVertices();

            $bounds = [];
            foreach ($vertices as $vertex) {
                $bounds[] = [$vertex->getX(), $vertex->getY()];
            }
            $w = $bounds[2][0] - $bounds[0][0];
            $h = $bounds[2][1] - $bounds[0][1];

            $img = SpatieImage::load($srcPath);

            // Choose watermark asset (prefer smile.png; fallback to watermark.png); if none exist, skip gracefully
            $smile = base_path('resources/img/smile.png');
            $wm = base_path('resources/img/watermark.png');
            $overlay = file_exists($smile) ? $smile : (file_exists($wm) ? $wm : null);
            if ($overlay) {
                $img->watermark(
                    $overlay,
                    AlignPosition::TopLeft,
                    paddingX: $bounds[0][0],
                    paddingY: $bounds[0][1],
                    width: $w,
                    height: $h,
                    fit: Fit::Stretch
                );
                $img->save($srcPath);
            }
        }
        $imageAnnotator->close();
    }
}
