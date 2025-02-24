<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'path_trailer',
        'about',
        'thumbnail',
        'teacher_id',
        'category_id',
    ];

    // category_id is the foreign key
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Hubungan one to many antara course dan course_video
    public function course_videos(){
        return $this->hasMany(CourseVideo::class);
    }

    public function course_keypoints(){
        return $this->hasMany(CourseKeypoint::class);
    }

    // Hubungan many to many antara course dan student
    // Belongs to many karena satu course bisa diikuti oleh banyak student
    public function students(){
        return $this->belongsToMany(User::class, 'course_students');
    }

}
