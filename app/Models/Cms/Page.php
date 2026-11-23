<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'cms_pages';

    protected $fillable = [
        'slug', 'title', 'excerpt', 'content', 'seo_title', 'seo_description',
        'seo_keywords', 'og_title', 'og_description', 'og_image', 'is_published',
    ];

    protected function casts(): array
    {
        return ['content' => 'array', 'is_published' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
