<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function sections()
    {
        return $this->hasMany(PageSection::class, 'page_id');
    }
}
