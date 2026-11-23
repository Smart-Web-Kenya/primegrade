<?php

namespace App\Support;

use App\Models\Cms\FacilityItem;
use App\Models\Cms\GalleryImage;
use App\Models\Cms\Media;
use App\Models\Cms\Page;
use App\Models\Cms\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CmsMediaUploader
{
    public function upload(UploadedFile $file, string $folder = 'general'): string
    {
        $folder = trim(preg_replace('/[^A-Za-z0-9_-]+/', '-', $folder) ?? '', '-');
        $folder = $folder !== '' ? $folder : 'general';
        $directory = public_path("uploads/cms/{$folder}");

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = Str::uuid().($extension !== '' ? ".{$extension}" : '');

        $file->move($directory, $filename);

        return "uploads/cms/{$folder}/{$filename}";
    }

    public function deleteIfUnreferenced(string $path): void
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        $isReferenced = Page::query()->where('og_image', $path)->exists()
            || Page::query()->where('content', 'like', '%'.$path.'%')->exists()
            || FacilityItem::query()->where('image_path', $path)->exists()
            || GalleryImage::query()->where('image_path', $path)->exists()
            || Setting::query()->where('key', 'logo')->where('value', $path)->exists();

        if ($isReferenced) {
            return;
        }

        $fullPath = public_path($path);
        $uploadsRoot = realpath(public_path('uploads/cms'));
        $directory = realpath(dirname($fullPath));

        if ($uploadsRoot && $directory && str_starts_with($directory, $uploadsRoot) && is_file($fullPath)) {
            unlink($fullPath);
        }

        Media::query()->where('path', $path)->delete();
    }

    public function url(?string $path): string
    {
        return $path ? asset(ltrim($path, '/')) : '';
    }
}
