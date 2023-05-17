<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUserTag extends Model
{
    use HasFactory;

    protected $fillable = ['bot_id', 'bot_user_id', 'first_engage', 'last_engage', 'reply_status', 'kick_status', 'status', 'created_at', 'updated_at'];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    public function botUser()
    {
        return $this->belongsTo(BotUser::class);
    }

    public function userEngagement()
    {
        return $this->hasMany(UserEngagement::class);
    }
}
