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
        <p>Period: <?php echo e($startDate); ?> - <?php echo e($endDate); ?></p>
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
                <td>Rp <?php echo e(number_format($subscriptionRevenue, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td>Subscription Transactions</td>
                <td><?php echo e(number_format($subscriptionTransactions)); ?></td>
            </tr>
            <tr>
                <td>Active Subscribers</td>
                <td><?php echo e(number_format($activeSubscribers)); ?></td>
            </tr>
            <tr>
                <td>Average Revenue per User (ARPU)</td>
                <td>Rp <?php echo e(number_format($arpu, 0, ',', '.')); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <p>Note: All figures reflect subscription revenue only. No expenses, profit, or tax figures are included.</p>
    </div>
</body>
</html>
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views\super_admin\financial_report_pdf.blade.php ENDPATH**/ ?>