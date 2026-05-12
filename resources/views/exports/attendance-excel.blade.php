<table>
    <thead>
        <tr>
            <th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Status</th><th>Jam</th><th>Metode</th><th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->date->format('Y-m-d') }}</td>
                <td>{{ $record->student->name }}</td>
                <td>{{ $record->student->classRoom->name }}</td>
                <td>{{ $record->status->value }}</td>
                <td>{{ $record->check_in_time }}</td>
                <td>{{ $record->method->value }}</td>
                <td>{{ $record->notes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
