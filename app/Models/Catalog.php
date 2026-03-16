<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $table = 'catalog';

    protected $fillable = [
        'brand_name',
        'catalog_name',
        'catalog_file',
        'catalog_cover',
    ];
    
}
