<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asset extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'filename',
        'original_filename',
        'title', // Tambahkan ini
        'file_type',
        'file_size',
        'blob_url',
        'caption',
        'folder_id',
        'uploaded_by',
        'hearts',
        'thumbnail_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function hearts()
    {
        return $this->hasMany(AssetHeart::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'asset_hearts');
    }

    public function isLikedBy(User $user)
    {
        return $this->hearts()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->hearts()->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}