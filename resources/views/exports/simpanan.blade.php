<table class="table align-middle text-center">
    <thead class="table-light">
        <tr>
            <th rowspan="2" class="text-center align-middle">No. anggota</th>
            <th rowspan="2" class="text-center align-middle">Nama anggota</th>
            <th colspan="3">Sisa simpanan sebelumnya</th>
            <th colspan="3">Pembayaran Januari 2025</th>
            <th colspan="3">Pengambilan Januari 2025</th>
            <th rowspan="2" class="text-center align-middle">Total</th>
        </tr>
        <tr>
            <th class="text-center align-middle">S. pokok</th>
            <th class="text-center align-middle">S. wajib</th>
            <th class="text-center align-middle">S. sukarela</th>
            <th class="text-center align-middle">S. pokok</th>
            <th class="text-center align-middle">S. wajib</th>
            <th class="text-center align-middle">S. sukarela</th>
            <th class="text-center align-middle">S. pokok</th>
            <th class="text-center align-middle">S. wajib</th>
            <th class="text-center align-middle">S. sukarela</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td>{{ $row['member_number'] }}</td>
                <td>{{ $row['name'] }}</td>

                {{-- Simpanan Sebelumnya --}}
                <td>{{ $row['sebelumnya']->pokok ?? '-' }}</td>
                <td>{{ $row['sebelumnya']->wajib ?? '-' }}</td>
                <td>{{ $row['sebelumnya']->sukarela ?? '-' }}</td>

                {{-- Pembayaran bulan terpilih --}}
                <td>{{ $row['pembayaran']->pokok ?? '-' }}</td>
                <td>{{ $row['pembayaran']->wajib ?? '-' }}</td>
                <td>{{ $row['pembayaran']->sukarela ?? '-' }}</td>

                {{-- Pengambilan bulan terpilih --}}
                <td>{{ $row['pengambilan']->pokok ?? '-' }}</td>
                <td>{{ $row['pengambilan']->wajib ?? '-' }}</td>
                <td>{{ $row['pengambilan']->sukarela ?? '-' }}</td>

                {{-- Total --}}
                <td>{{ $row['total'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>