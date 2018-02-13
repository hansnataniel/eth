<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    // public function galleryalbum()
    // {
        // return $this->belongsTo('App\Models\Galleryalbum');
    // }

    public function usermh()
    {
        return $this->belongsTo('App\Models\Purchase');
    }
}