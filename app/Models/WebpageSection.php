<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebpageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'webpage_id',
        'type',
        'title',
        'content',
        'image_path',
        'button_text',
        'button_link',
        'button_style',
        'order',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function webpage()
    {
        return $this->belongsTo(Webpage::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }
}

