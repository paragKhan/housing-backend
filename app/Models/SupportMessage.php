<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function support_conversation(){
        return $this->belongsTo(SupportConversation::class);
    }
}
