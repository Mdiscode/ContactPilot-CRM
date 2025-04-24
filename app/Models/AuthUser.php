<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthUser extends Model
{
    protected $table = "auth_user";
    protected $fillable =[
        "user_id",
        "userName",
        "email",
        "access_token",
        "expires_at",
        "refresh_token",
        "refresh_token_expires",
    ];
}
