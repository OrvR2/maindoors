<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'keyword', 'description', 'parent_id'];

    public function product()
    {
        return $this->hasMany('App\Product');
    }

    public function blogs()
    {
        return $this->hasMany('App\Blog');
    }
}
