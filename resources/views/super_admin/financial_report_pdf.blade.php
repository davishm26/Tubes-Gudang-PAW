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
        <h1>Financial Report</h1>
        <p>Period: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Subscription Revenue</td>
                <td>Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Subscription Transactions</td>
                <td>{{ number_format($subscriptionTransactions) }}</td>
            </tr>
            <tr>
                <td>Active Subscribers</td>
                <td>{{ number_format($activeSubscribers) }}</td>
            </tr>
            <tr>
                <td>Average Revenue per User (ARPU)</td>
                <td>Rp {{ number_format($arpu, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <p>Note: All figures reflect subscription revenue only. No expenses, profit, or tax figures are included.</p>
    </div>
</body>
</html>






