<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Export' }}</title>
    <style>
        /** 
            Set the margins of the page to 0, so the footer and the header
            can be of the full height and width !
         **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
            background-color: #f8f9fa;
            border-bottom: 2px solid #1e40af;
            padding: 0.5cm 2cm;
            color: #333;
        }

        /** Define the footer rules **/
        footer {
            position: fixed; 
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 1.5cm;
            background-color: #f8f9fa;
            border-top: 1px solid #e5e7eb;
            padding: 0.3cm 2cm;
            color: #666;
            font-size: 8px;
            text-align: center;
        }

        /* Content Styling */
        h1 {
            font-size: 18px;
            color: #1e40af; /* Primary Blue */
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-weight: bold;
        }

        .meta-info {
            font-size: 9px;
            color: #555;
            margin-top: 5px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
        }

        th {
            background-color: #1e40af;
            color: #ffffff;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #1e40af;
        }

        td {
            padding: 7px 10px;
            border: 1px solid #e5e7eb;
            font-size: 9px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f3f4f6;
        }
        
        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Summary Box */
        .summary-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .summary-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            border-bottom: 1px solid #bfdbfe;
            padding-bottom: 5px;
        }

        .stat-grid {
            display: table;
            width: 100%;
        }
        
        .stat-item {
            display: table-cell;
            padding-right: 20px;
            vertical-align: top;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            display: block;
        }
        
        .stat-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        /* Utilities */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .mt-4 { margin-top: 16px; }
        .mb-2 { margin-bottom: 8px; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: capitalize;
        }
        .badge-green { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-yellow { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-red { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-gray { background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

        /* Backward Compatibility */
        .stat {
            display: inline-block;
            margin-right: 20px;
            vertical-align: top;
        }

        /* Organization Branding in Header */
        .org-logo {
            float: left;
            width: 60px;
            height: auto;
            margin-right: 15px;
        }
        
        .org-details {
            float: left;
        }
        
        .org-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
        }
        
        .org-meta {
            font-size: 9px;
            color: #6b7280;
        }
        
        .doc-details {
            float: right;
            text-align: right;
        }
        
        /* Clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        
        /* Page Numbers */
        .page-number:after {
            content: counter(page);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Fixed Header -->
    <header>
        <div class="clearfix">
            <div class="org-details">
                <div class="org-name">EAR SHYOGWE DIOCESE</div>
                <div class="org-meta">
                    Enterprise Management System<br>
                    Generated Report
                </div>
            </div>
            
            <div class="doc-details">
                <h1>{{ $title ?? 'Report' }}</h1>
                @if(isset($subtitle))
                    <div class="subtitle">{{ $subtitle }}</div>
                @endif
                <div class="meta-info">
                    Date: {{ now()->format('d M Y, H:i') }}<br>
                    Generated by: {{ auth()->user()->name ?? 'System' }}
                </div>
            </div>
        </div>
    </header>

    <!-- Fixed Footer -->
    <footer>
        <div class="clearfix">
            <div style="float: left; width: 33%;">
                {{ date('Y') }} &copy; EAR SHYOGWE DIOCESE
            </div>
            <div style="float: left; width: 33%; text-align: center;">
                CONFIDENTIAL DOCUMENT
            </div>
            <div style="float: right; width: 33%; text-align: right;">
                Page <span class="page-number"></span>
            </div>
        </div>
    </footer>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
</body>
</html>
