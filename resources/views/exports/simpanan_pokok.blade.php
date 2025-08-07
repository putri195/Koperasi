<table class="table align-middle text-center">
    <thead class="table-light">
        <tr>
            <th>No. anggota</th>
            <th>Nama anggota</th>
            <th>Bayar</th>
            <th>Ambil</th>
            <th>Sisa</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row['member_number'] }}</td>
                <td class="text-start">{{ $row['name'] }}</td>
                <td>{{ number_format($row['bayar'] ?? '-' ) }}</td>
                <td>{{ number_format($row['ambil'] ?? '-' ) }}</td>
                <td>{{ number_format($row['sisa'] ?? '-' ) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>