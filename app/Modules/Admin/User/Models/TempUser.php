<?php

declare(strict_types=1);

namespace App\Modules\Admin\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TempUser extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable =[
        'firstname',
        'lastname',
        'phone',
        'verified',
        'otp_secret'
    ];

    protected $hidden = [
        'password'
    ];

}
