<table class="table align-middle text-center">
    <thead class="table-light">
        <tr>
            <th rowspan="2" class="text-center align-middle">No. Anggota</th>
            <th rowspan="2" class="text-center align-middle">Nama Anggota</th>
            <th rowspan="2" class="text-center align-middle">Sisa simpanan sebelumnya</th>
            @for($bulan = 1; $bulan <= 12; $bulan++)
                <th colspan="2" class="text-center align-middle">
                    {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                </th>
                <th rowspan="2" class="text-center align-middle">Sisa</th>
            @endfor
        </tr>
        <tr>
            @for($bulan = 1; $bulan <= 12; $bulan++)
                <th>Bayar</th>
                <th>Ambil</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row['member_number'] }}</td>
                <td class="text-start">{{ $row['name'] }}</td>
                <td>{{ $row['sisa_awal'] }}</td>

                @for($bulan = 1; $bulan <= 12; $bulan++)
                    <td>{{ $row['bulan'][$bulan]['bayar'] ?? 0 }}</td>
                    <td>{{ $row['bulan'][$bulan]['ambil'] ?? 0 }}</td>
                    <td>{{ $row['bulan'][$bulan]['sisa'] ?? 0 }}</td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
