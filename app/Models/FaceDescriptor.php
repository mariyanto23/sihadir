<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceDescriptor extends Model
{
    protected $fillable = ['student_id', 'descriptor', 'source'];

    protected function casts(): array
    {
        return [
            'descriptor' => 'array',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
