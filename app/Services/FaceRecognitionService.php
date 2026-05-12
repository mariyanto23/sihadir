<?php

namespace App\Services;

use App\Models\FaceDescriptor;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FaceRecognitionService
{
    public function storeDescriptor(Student $student, array $descriptor, string $source = 'camera'): FaceDescriptor
    {
        if (count($descriptor) < 16 || collect($descriptor)->contains(fn ($value) => ! is_numeric($value))) {
            throw ValidationException::withMessages([
                'descriptor' => 'Descriptor wajah tidak valid.',
            ]);
        }

        return DB::transaction(function () use ($student, $descriptor, $source) {
            $faceDescriptor = $student->faceDescriptors()->create([
                'descriptor' => array_map('floatval', $descriptor),
                'source' => $source,
            ]);

            $overflow = $student->faceDescriptors()->oldest()->take(
                max(0, $student->faceDescriptors()->count() - 10),
            )->pluck('id');

            if ($overflow->isNotEmpty()) {
                FaceDescriptor::query()->whereIn('id', $overflow)->delete();
            }

            $this->syncEmbeddingFlag($student);

            return $faceDescriptor;
        });
    }

    public function syncEmbeddingFlag(Student $student): void
    {
        $student->update([
            'has_embedding' => $student->faceDescriptors()->exists(),
        ]);
    }

    public function descriptorsFor(Student $student): array
    {
        return $student->faceDescriptors()->latest()->pluck('descriptor')->all();
    }
}
