<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .summary { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pemasukan Langganan</td>
                <td>Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pemasukan Operasional</td>
                <td>Rp {{ number_format($operationalIncome, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pemasukan</td>
                <td>Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pengeluaran</td>
                <td>Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Profit</strong></td>
                <td><strong>Rp {{ number_format($profit, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
