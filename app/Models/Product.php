<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category(){

        return $this->belongsTo(category::class,'category_id');
    }

    public function brand(){

        return $this->belongsTo(Brand::class,'brand_id');
    }
}
