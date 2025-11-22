<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'order'];

    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }
}
