<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEngagement extends Model
{
    use HasFactory;

    protected $fillable = ['bot_user_tag_id', 'engage_count', 'engage_date'];

    public function botUserTag()
    {
        return $this->belongsTo(BotUserTag::class);
    }

}
