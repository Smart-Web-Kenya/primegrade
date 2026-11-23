<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'cms_media';

    protected $fillable = ['name', 'path', 'mime_type', 'size', 'alt_text', 'folder'];

    protected function casts(): array
    {
        return ['size' => 'integer'];
    }

    public function scopeInFolder(Builder $query, string $folder): Builder
    {
        return $query->where('folder', $folder);
    }
}
