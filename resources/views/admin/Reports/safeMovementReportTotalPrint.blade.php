<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير كشف حركة الخزنة - إجمالي</title>
    <style>

        @page {
            size: A4;
            margin: 20mm;
        }


        /* ======== Page & Font ======== */
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }

        .page {
            width: 100%;
            box-sizing: border-box;
        }

        /* ======== Header ======== */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        /* ======== Table ======== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        td.text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        td.text-success {
            color: #28a745;
            font-weight: bold;
        }

        tfoot th {
            background: #e9ecef;
        }

        /* ======== Footer ======== */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }

    </style>
</head>
<body>

<div class="page">
    <!-- Header -->
    <div class="header">
        <h1>تقرير كشف حركة الخزنة - إجمالي</h1>
        @if($safe)
            <p>الخزنة: {{ $safe->name }}</p>
        @endif
        @if($fromDate && $toDate)
            <p>الفترة من: {{ $fromDate }} إلى: {{ $toDate }}</p>
        @elseif($fromDate)
            <p>من تاريخ: {{ $fromDate }}</p>
        @elseif($toDate)
            <p>حتى تاريخ: {{ $toDate }}</p>
        @endif
        <p>تاريخ الطباعة: {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
    </div>

    <!-- Table -->
    <table>
        <thead>
        <tr>
            <th>الخزنة</th>
            <th> الرصيد الافتتاحي </th>
            <th>المدين</th>
            <th>الدائن</th>
            <th>الرصيد</th>
        </tr>
        </thead>
        <tbody>
        @foreach($totals as $doc)
            @php
                $balance = $doc['inMoney'] - $doc['outMoney'] + $doc['opening_balance'];
            @endphp
            <tr>

                <td>{{ $doc['safe'] }}</td>
                <td class=" @if($doc['opening_balance'] < 0)  text-danger @else text-success  @endif ">{{ number_format($doc['opening_balance'], 2) }}</td>
                <td class="text-danger">{{ number_format($doc['outMoney'], 2) }}</td>
                <td class="text-success">{{ number_format($doc['inMoney'], 2) }}</td>
                <td class="{{ $balance < 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($balance, 2) }}
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

    <!-- Footer -->
    <div class="footer">
        هذا التقرير تم إنشاؤه آلياً بواسطة النظام.
    </div>
</div>

<script>
    window.onload = function () {
        window.print();
    };
</script>

</body>
</html>
