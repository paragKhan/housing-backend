<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Application extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_RESUBMIT = 'resubmit';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';

    protected $guarded = [];

    protected $with = ['media'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subdivision(){
        return $this->belongsTo(Subdivision::class);
    }

    public function housingModel(){
        return $this->belongsTo(HousingModel::class);
    }

    public function forwarder(){
        return $this->morphTo('forwardable');
    }

    public function approver(){
        return $this->morphTo('approvable');
    }
}
