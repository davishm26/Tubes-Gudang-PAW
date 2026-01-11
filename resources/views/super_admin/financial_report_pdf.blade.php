<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        .header { text-align: center; margin-bottom: 18px; }
        .header h1 { margin: 0 0 6px 0; font-size: 18px; }
        .header p { margin: 0; font-size: 12px; color: #374151; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th, .table td { border: 1px solid #111827; padding: 8px; font-size: 12px; }
        .table th { background: #F3F4F6; text-align: left; }
        .amount { text-align: right; white-space: nowrap; }
        .summary { margin-top: 14px; font-size: 11px; color: #374151; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="amount">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Pendapatan Langganan</td>
                <td class="amount">Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Transaksi Langganan</td>
                <td class="amount">{{ number_format($subscriptionTransactions) }}</td>
            </tr>
            <tr>
                <td>Pelanggan Aktif</td>
                <td class="amount">{{ number_format($activeSubscribers) }}</td>
            </tr>
            <tr>
                <td>ARPU</td>
                <td class="amount">Rp {{ number_format($arpu, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <p>Catatan: Semua angka hanya berasal dari langganan. Tidak termasuk biaya, laba, maupun pajak.</p>
    </div>
</body>
</html>






