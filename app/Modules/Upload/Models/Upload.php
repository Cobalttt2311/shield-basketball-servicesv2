<?php

namespace App\Modules\Upload\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = 'uploads';

    protected $fillable = [
        'file_name',
        'file_path',
        'file_url',
        'file_type'
    ];
}