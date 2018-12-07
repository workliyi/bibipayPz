<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QzAdminUser extends Model
{
    protected $table = 'qz_admin_user';
    protected $hidden = [
        'password',
    ];
}
