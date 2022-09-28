<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Photo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];
    protected $appends = ['urls'];
    protected $hidden = ['media'];

    protected function getUrlsAttribute(){
        $photo = $this->getFirstMedia();
        return $photo ? [
            'original' => $photo->original_url,
            'thumb' => $photo->getUrl('thumb')
        ]: [
            'original' => null,
            'thumb' => null
        ];
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400);
    }
}
