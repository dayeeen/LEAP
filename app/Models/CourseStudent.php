<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseStudent extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'course_id',
    ];

    // Tidak perlu membuat relasi ke model User dan Course
    // karena ini adalah tabel pivot

    // public function user() {
    //     return $this->belongsTo(User::class);
    // }

    // public function course() {
    //     return $this->belongsTo(Course::class);
    // }
}
