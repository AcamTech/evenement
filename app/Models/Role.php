<?php
/**
 * Created by PhpStorm.
 * User: adrian
 * Date: 15/03/19
 * Time: 2:23 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany('App\User')
            ->using('App\UserRole');
    }
}