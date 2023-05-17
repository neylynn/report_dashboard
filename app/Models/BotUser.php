<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUser extends Model
{
    use HasFactory;

    protected $fillable = ['telegram_user_id', 'name', 'user_name', 'status', 'created_at', 'updated_at'];

    public function botUserTags()
    {
        return $this->hasMany(BotUserTag::class);
    }
}
