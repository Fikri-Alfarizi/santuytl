<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = ['user_id', 'bio', 'rating', 'is_active'];
    public function user() { return $this->belongsTo(User::class); }
}