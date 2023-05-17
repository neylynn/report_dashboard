<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['bot_id', 'flow'];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
