<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Export' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4f46e5;
        }
        .header h1 {
            font-size: 18px;
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 11px;
            color: #666;
        }
        .header .date {
            font-size: 9px;
            color: #888;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #4f46e5;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #f3f4f6;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #888;
            padding: 10px;
            border-top: 1px solid #e5e7eb;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-yellow {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-gray {
            background-color: #f3f4f6;
            color: #374151;
        }
        .badge-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            background-color: #f3f4f6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .summary-box .stat {
            display: inline-block;
            margin-right: 20px;
        }
        .summary-box .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #4f46e5;
        }
        .summary-box .stat-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title ?? 'Export' }}</h1>
        @if(isset($subtitle))
            <div class="subtitle">{{ $subtitle }}</div>
        @endif
        <div class="date">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</div>
    </div>

    @yield('content')

    <div class="footer">
        Enterprise Management System &bull; Page {PAGE_NUM} of {PAGE_COUNT}
    </div>
</body>
</html>
