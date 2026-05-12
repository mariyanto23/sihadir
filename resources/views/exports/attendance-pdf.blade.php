<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #cbd5e1; padding: 6px; text-align: left; }
        th { background: #eff6ff; }
    </style>
</head>
<body>
    <h2>Laporan Presensi HadirKu</h2>
    <p>Periode {{ $filters['start_date'] }} sampai {{ $filters['end_date'] }}</p>
    <table>
        <thead><tr><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Status</th><th>Jam</th><th>Metode</th></tr></thead>
        <tbody>
            @foreach ($records as $record)
                <tr><td>{{ $record->date->format('d/m/Y') }}</td><td>{{ $record->student->name }}</td><td>{{ $record->student->classRoom->name }}</td><td>{{ $record->status->value }}</td><td>{{ $record->check_in_time ?: '-' }}</td><td>{{ $record->method->value }}</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
