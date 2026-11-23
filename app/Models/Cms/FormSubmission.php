<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    public const TYPE_CONTACT = 'contact';

    public const TYPE_ADMISSION = 'admission';

    public const STATUS_NEW = 'new';

    public const STATUS_READ = 'read';

    public const STATUS_ARCHIVED = 'archived';

    protected $table = 'cms_form_submissions';

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'student_name',
        'grade_applying',
        'parent_name',
        'meta',
        'status',
        'read_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function markRead(): void
    {
        if ($this->status === self::STATUS_NEW) {
            $this->update([
                'status' => self::STATUS_READ,
                'read_at' => now(),
            ]);
        }
    }

    public function isContact(): bool
    {
        return $this->type === self::TYPE_CONTACT;
    }

    public function isAdmission(): bool
    {
        return $this->type === self::TYPE_ADMISSION;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_ADMISSION => 'Admission request',
            default => 'Contact message',
        };
    }
}
