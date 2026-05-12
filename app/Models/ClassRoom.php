<?php

namespace App\Models;

use Database\Factories\ClassRoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    /** @use HasFactory<ClassRoomFactory> */
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['name', 'level'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
