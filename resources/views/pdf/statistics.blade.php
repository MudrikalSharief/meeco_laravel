<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            color: #333;
            margin: 0 0 5px 0;
        }
        .date-range {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .summary-grid {
            margin-bottom: 30px;
            width: 100%;
            border-collapse: collapse;
        }
        .summary-grid td {
            padding: 15px;
            width: 33%;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #1a202c;
        }
        .chart-container {
            margin-bottom: 30px;
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="date-range">{{ $dateRange }}</div>
    </div>

    <table class="summary-grid">
        <tr>
            @foreach($summaryItems as $item)
            <td>
                <div class="summary-label">{{ $item['label'] }}</div>
                <div class="summary-value">{{ $item['value'] }}</div>
            </td>
            @endforeach
        </tr>
    </table>

    @if(isset($tableData) && count($tableData) > 0)
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th style="padding: 10px; text-align: left; border: 1px solid #e2e8f0;">{{ $tableData['headerLeft'] }}</th>
                <th style="padding: 10px; text-align: right; border: 1px solid #e2e8f0;">{{ $tableData['headerRight'] }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableData['rows'] as $row)
            <tr>
                <td style="padding: 10px; text-align: left; border: 1px solid #e2e8f0;">{{ $row['label'] }}</td>
                <td style="padding: 10px; text-align: right; border: 1px solid #e2e8f0;">{{ $row['value'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Generated on {{ date('F j, Y, g:i a') }} | Meeco Statistics Report</p>
    </div>
</body>
</html>
