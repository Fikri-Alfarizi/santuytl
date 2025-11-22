<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = ['user_id', 'action', 'details', 'created_at'];
    public $timestamps = false;
    public function user() { return $this->belongsTo(User::class); }
}