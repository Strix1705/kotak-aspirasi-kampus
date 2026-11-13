<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Aspirasi #{{ $aspiration->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #18181b; font-size: 12px; }
        .page { padding: 36px; }
        .header { border-bottom: 3px solid #e11d48; padding-bottom: 14px; margin-bottom: 20px; }
        .brand { font-size: 20px; font-weight: bold; letter-spacing: 1px; }
        .sub { color: #71717a; font-size: 11px; margin-top: 3px; }
        .ticket-box { border: 1px solid #d4d4d8; border-radius: 8px; padding: 18px; margin-bottom: 18px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: bold; color: #fff; background: {{ $aspiration->status === 'Selesai' ? '#059669' : ($aspiration->status === 'Diproses' ? '#0284c7' : ($aspiration->status === 'Ditolak' ? '#e11d48' : '#d97706')) }}; }
        .row { display: table; width: 100%; margin-bottom: 8px; }
        .label { display: table-cell; width: 140px; color: #71717a; font-size: 11px; text-transform: uppercase; }
        .value { display: table-cell; font-weight: bold; }
        h2 { font-size: 14px; margin: 14px 0 6px; border-left: 4px solid #e11d48; padding-left: 8px; }
        .desc { white-space: pre-wrap; line-height: 1.6; background: #f4f4f5; padding: 12px; border-radius: 6px; }
        .response-box { background: #fff1f2; border: 1px solid #fecdd3; padding: 12px; border-radius: 6px; white-space: pre-wrap; line-height: 1.6; }
        .footer { margin-top: 26px; padding-top: 12px; border-top: 1px solid #d4d4d8; font-size: 10px; color: #a1a1aa; text-align: center; }
        .qr-note { font-size: 10px; color: #71717a; margin-top: 4px; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="brand">PORTAL ASPIRASI &amp; PENGADUAN KAMPUS</div>
        <div class="sub">Bukti Tiket Laporan Resmi &mdash; Dicetak {{ now()->format('d F Y H:i') }}</div>
    </div>

    <div class="ticket-box">
        <div class="row">
            <span class="label">No. Tiket</span>
            <span class="value">ASP-{{ str_pad($aspiration->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="row">
            <span class="label">Status</span>
            <span class="value"><span class="badge">{{ $aspiration->status }}</span></span>
        </div>
        <div class="row">
            <span class="label">Pelapor</span>
            <span class="value">{{ $aspiration->user->name }}</span>
        </div>
        <div class="row">
            <span class="label">NIM</span>
            <span class="value">{{ $aspiration->user->nim }}</span>
        </div>
        <div class="row">
            <span class="label">Email</span>
            <span class="value">{{ $aspiration->user->email }}</span>
        </div>
        <div class="row">
            <span class="label">Kategori</span>
            <span class="value">{{ $aspiration->category }}</span>
        </div>
        <div class="row">
            <span class="label">Diajukan</span>
            <span class="value">{{ $aspiration->created_at->format('d M Y H:i') }}</span>
        </div>
    </div>

    <h2>JUDUL LAPORAN</h2>
    <p style="font-weight: bold; margin-bottom: 4px;">{{ $aspiration->title }}</p>
    <div class="qr-note">Simpan tiket ini sebagai bukti pelaporan.</div>

    <h2>DESKRIPSI</h2>
    <div class="desc">{{ $aspiration->description }}</div>

    @if ($aspiration->response)
        <h2>TANGGAPAN PIHAK KAMPUS</h2>
        <div class="response-box">{{ $aspiration->response }}</div>
    @endif

    <div class="footer">
        Dokumen dihasilkan otomatis oleh Portal Aspirasi Kampus &mdash; {{ now()->format('Y-m-d H:i:s') }}<br>
        Dokumen ini sah tanpa tanda tangan basah.
    </div>
</div>
</body>
</html>
