<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaceDescriptorRequest;
use App\Models\FaceDescriptor;
use App\Models\Student;
use App\Services\FaceRecognitionService;

class FaceDescriptorController extends Controller
{
    public function store(FaceDescriptorRequest $request, Student $student, FaceRecognitionService $service)
    {
        $validated = $request->validated();

        $service->storeDescriptor($student, $validated['descriptor'], $validated['source'] ?? 'camera');

        return response()->json([
            'message' => 'Descriptor wajah berhasil disimpan.',
            'has_embedding' => $student->fresh()->has_embedding,
        ]);
    }

    public function destroy(Student $student, FaceDescriptor $descriptor, FaceRecognitionService $service)
    {
        abort_unless($descriptor->student_id === $student->id, 404);

        $descriptor->delete();
        $service->syncEmbeddingFlag($student);

        return back()->with('success', 'Descriptor wajah berhasil dihapus.');
    }
}
