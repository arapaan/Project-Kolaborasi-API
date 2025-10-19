<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',        
        'section_key',
        'content',
        'order',
        'file_name',
        'file_path',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
