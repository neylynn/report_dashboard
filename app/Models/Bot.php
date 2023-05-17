<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'telegram_bot_id', 'name', 'user_name', 'api_token', 'image', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function botUserTags()
    {
        return $this->hasMany(BotUserTag::class);
    }

    public function botTemplates()
    {
        return $this->hasOne(BotTemplate::class);
    }
}
