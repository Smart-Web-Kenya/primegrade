<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'cms_programs';

    protected $fillable = [
        'title', 'slug', 'subtitle', 'grades_label', 'summary', 'body', 'bullets',
        'boarding_note', 'meta_tags', 'sort_order', 'is_published', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'bullets' => 'array',
            'meta_tags' => 'array',
            'sort_order' => 'integer',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
