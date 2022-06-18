<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportConversation extends Model
{
    use HasFactory;

    protected $guarded = [];
//    protected $appends = ['last_admin'];
//
//    public function getLastAdminAttribute(){
//        return $this->support_messages()->where('senderable_type', '!=', User::class)->first()->senderable->name;
//    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function support_messages(){
        return $this->hasMany(SupportMessage::class);
    }
}
