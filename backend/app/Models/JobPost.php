<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPost extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approve';
    public const STATUS_SPAM = 'spam';

    // Source constants
    public const SOURCE_INTERNAL = 'internal';
    public const SOURCE_EXTERNAL = 'external';

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }

    protected $fillable = [
        'title', 'description', 'email', 'source', 'source_url', 'status'
    ];
}
