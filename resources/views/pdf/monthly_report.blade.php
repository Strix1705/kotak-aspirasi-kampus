<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Aspirasi {{ now()->format('F Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #18181b; font-size: 11px; }
        .page { padding: 32px; }
        .header { border-bottom: 3px solid #e11d48; padding-bottom: 12px; margin-bottom: 18px; }
        .brand { font-size: 18px; font-weight: bold; }
        .sub { color: #71717a; font-size: 11px; margin-top: 3px; }
        .stats { margin-bottom: 16px; }
        .stat { display: inline-block; border: 1px solid #d4d4d8; border-radius: 6px; padding: 8px 14px; margin-right: 8px; text-align: center; }
        .stat .num { font-size: 18px; font-weight: bold; }
        .stat .cap { font-size: 9px; color: #71717a; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #18181b; color: #fff; padding: 7px 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 7px 8px; border-bottom: 1px solid #e4e4e7; vertical-align: top; }
        tr:nth-child(even) td { background: #fafafa; }
        .footer { margin-top: 20px; font-size: 9px; color: #a1a1aa; text-align: center; border-top: 1px solid #d4d4d8; padding-top: 10px; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="brand">REKAP LAPORAN ASPIRASI &amp; PENGADUAN</div>
        <div class="sub">Periode {{ now()->translatedFormat('F Y') }} &mdash; Dicetak {{ now()->format('d M Y H:i') }}</div>
    </div>

    <div class="stats">
        <div class="stat"><div class="num">{{ $aspirations->count() }}</div><div class="cap">Total</div></div>
        <div class="stat"><div class="num">{{ $aspirations->where('category', 'Fasilitas')->count() }}</div><div class="cap">Fasilitas</div></div>
        <div class="stat"><div class="num">{{ $aspirations->where('category', 'Akademik')->count() }}</div><div class="cap">Akademik</div></div>
        <div class="stat"><div class="num">{{ $aspirations->where('category', 'Layanan')->count() }}</div><div class="cap">Layanan</div></div>
        <div class="stat"><div class="num">{{ $aspirations->where('status', 'Menunggu')->count() }}</div><div class="cap">Menunggu</div></div>
        <div class="stat"><div class="num">{{ $aspirations->where('status', 'Selesai')->count() }}</div><div class="cap">Selesai</div></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 70px;">Tgl</th>
                <th style="width: 90px;">NIM</th>
                <th style="width: 110px;">Pelapor</th>
                <th style="width: 70px;">Kategori</th>
                <th>Judul Laporan</th>
                <th style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($aspirations as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->created_at->format('d/m') }}</td>
                    <td>{{ $item->user->nim }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center; padding: 20px; color: #a1a1aa;">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Rekap otomatis Portal Aspirasi &amp; Pengaduan Kampus &mdash; {{ now()->format('Y-m-d H:i:s') }}
    </div>
</div>
</body>
</html>
