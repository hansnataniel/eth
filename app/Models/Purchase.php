<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'usermhs';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}