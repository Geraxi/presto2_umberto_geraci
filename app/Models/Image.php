<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'path'
    ];

    protected $table = 'images'; 


    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }


    public static function getUrlByFilePath($filePath, $w = null, $h = null)
    {
        if (!$w && !$h) {
            return Storage::url($filePath);
        }
        $path = dirname($filePath); // Fixed: dinname → dirname
        $filename = basename($filePath);
        $file = "{$path}/crop_{$w}x{$h}_{$filename}"; // Fixed brackets and formatting
        return Storage::url($file);
    }

    public function getUrl($width, $height)
{
    return self::getUrlByFilePath($this->path, $width, $height);
}

    protected function casts(): array
    {
        return[
            'labels'=>'array',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}

