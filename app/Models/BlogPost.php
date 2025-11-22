<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image', 'status', 'is_featured', 'meta_title', 'meta_description', 'author_id', 'categories', 'tags', 'published_at', 'posted_to_discord', 'discord_message_id',
    ];

    protected $casts = [
        'categories' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'posted_to_discord' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
