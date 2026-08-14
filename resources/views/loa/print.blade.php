<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Letter of Agreement - {{ $loa->uuid }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; margin: 0; padding: 20px; font-size: 12pt; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18pt; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10pt; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 20px; font-size: 14pt; }
        .content { line-height: 1.5; }
        .content p { margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        .signatures { margin-top: 50px; width: 100%; display: table; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
        .signature-line { margin-top: 80px; font-weight: bold; text-decoration: underline; }
        @media print {
            body { padding: 0; }
            .print-btn { display: none; }
        }
        .print-btn { padding: 10px 20px; background: #007bff; color: #fff; border: none; cursor: pointer; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Cetak Dokumen</button>

    <div class="header">
        <h1>Gunung Pancar</h1>
        <p>Taman Wisata Alam Gunung Pancar, Babakan Madang, Bogor, Jawa Barat</p>
        <p>Telp: 0811-1000-2727 | Web: gunungpancar.co.id</p>
    </div>

    <div class="title">LETTER OF AGREEMENT (LOA)</div>

    <div class="content">
        <p>Pada hari ini, tanggal <strong>{{ \Carbon\Carbon::parse($loa->created_at)->translatedFormat('d F Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>
        
        <p>
            <strong>I. Pihak Pertama (Gunung Pancar)</strong><br>
            Nama: PT Wana Wisata Indah<br>
            Jabatan: Pengelola Taman Wisata Alam Gunung Pancar
        </p>
        
        <p>
            <strong>II. Pihak Kedua (Klien)</strong><br>
            Nama: <strong>{{ $loa->lead->name }}</strong><br>
            Instansi/Perusahaan: <strong>{{ $loa->lead->company_name ?? '-' }}</strong><br>
            Email: {{ $loa->lead->email }}<br>
            No. Telepon: {{ $loa->lead->phone }}
        </p>

        <p>Kedua belah pihak sepakat untuk mengikatkan diri dalam kesepakatan pelaksanaan kegiatan dengan rincian sebagai berikut:</p>
        
        <table>
            <tr>
                <th style="width: 30%">Nama Kegiatan</th>
                <td>{{ $loa->event_name ?? 'Kegiatan Wisata/Outbound' }}</td>
            </tr>
            <tr>
                <th>Tanggal Pelaksanaan</th>
                <td>{{ $loa->event_date ? \Carbon\Carbon::parse($loa->event_date)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Jumlah Peserta</th>
                <td>{{ $loa->participant_count ?? 0 }} Orang</td>
            </tr>
            <tr>
                <th>Total Biaya Kesepakatan</th>
                <td>Rp {{ number_format($loa->total_amount ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>

        <p><strong>Syarat & Ketentuan:</strong></p>
        <div style="margin-left: 20px;">
            {!! nl2br(e($loa->terms_conditions ?? "1. Pembayaran DP minimal 50% dibayarkan saat penandatanganan LOA ini.\n2. Pelunasan dilakukan selambat-lambatnya H-3 sebelum pelaksanaan kegiatan.\n3. Pembatalan sepihak oleh Pihak Kedua akan mengakibatkan DP hangus.")) !!}
        </div>

        <p>Demikian Surat Kesepakatan (Letter of Agreement) ini dibuat dalam keadaan sadar dan tanpa paksaan dari pihak manapun untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <p>Pihak Pertama</p>
            <div class="signature-line">Gunung Pancar</div>
        </div>
        <div class="signature-box">
            <p>Pihak Kedua</p>
            <div class="signature-line">{{ $loa->lead->name }}</div>
        </div>
    </div>
</body>
</html>
