<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_method',
        'request_params',
        'user_id',
        'token_id',
    ];

    protected $casts = [
        'request_params' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
