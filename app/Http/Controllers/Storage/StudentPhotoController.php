<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentPhotoController extends Controller
{
    public function show(Student $student)
    {
        $this->authorize('view', $student);

        abort_if(! $student->photo_path || ! Storage::disk('local')->exists($student->photo_path), 404);

        return response()->file(Storage::disk('local')->path($student->photo_path));
    }
}
