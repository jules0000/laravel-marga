<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Webpage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'meta_description',
        'meta_keywords',
        'is_published',
        'order',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections()
    {
        return $this->hasMany(WebpageSection::class)->orderBy('order');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $value ?: Str::slug($this->title);
    }

    public function getUrlAttribute()
    {
        return url('/pages/' . $this->slug);
    }
}

