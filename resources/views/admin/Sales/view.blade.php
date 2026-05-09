<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع رقم {{ $doc->bill_number }}</title>

    <style>
        body {
            font-family: "Cairo", Tahoma, Arial;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .a4 {
            width: 210mm;
            padding: 10mm;
            margin: auto;
            box-sizing: border-box;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .company-info h3 {
            margin: 0;
            font-size: 20px;
        }

        .company-info p {
            margin: 3px 0;
            font-size: 13px;
        }

        .print-info {
            text-align: left;
            font-size: 13px;
        }

        /* Title */
        .title {
            text-align: center;
            margin: 15px 0 25px;
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Invoice Info */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #000;
            padding: 8px 10px;
        }

        .info-table th {
            background: #f2f2f2;
            width: 20%;
            text-align: right;
        }

        /* Items Table */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }

        table.items th,
        table.items td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table.items th {
            background: #f2f2f2;
        }

        /* Totals */
        .totals {
            width: 40%;
            margin-top: 15px;
            margin-right: auto;
            border-collapse: collapse;
        }

        .totals td {
            border: 1px solid #000;
            padding: 8px;
        }

        .totals tr td:first-child {
            background: #f2f2f2;
            font-weight: bold;
        }

        /* Notes */
        .notes {
            margin-top: 20px;
            border: 1px solid #000;
            padding: 10px;
            min-height: 60px;
        }

        /* Footer / Signatures */
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .sign-box {
            width: 30%;
            text-align: center;
        }

        .sign-box p {
            margin-bottom: 40px;
            font-weight: bold;
        }

        .sign-line {
            border-top: 1px solid #000;
        }

        @media print {
            body {
                background: #fff;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

<div class="a4">

    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h3>مصنع أولاد بلال</h3>
            <p>العنوان : النسايمة - المنزلة - الدقهلية</p>
            <p>ت / 01281350125 - 0503597046</p>
        </div>

        <div class="print-info">
            <p><strong>تاريخ الطباعة:</strong></p>
            <p>{{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <!-- Title -->
    <div class="title">
        فاتورة بيع
    </div>

    <!-- Invoice Info -->
    <table class="info-table">
        <tr>
            <th>رقم الفاتورة</th>
            <td>{{ $doc->bill_number }}</td>

            <th>التاريخ</th>
            <td>{{ \Carbon\Carbon::parse($doc->date)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>العميل</th>
            <td>{{ $doc->client_name ?? '-' }}</td>

            <th>المخزن</th>
            <td>{{ $doc->store_name ?? 'مخزن متعدد' }}</td>
        </tr>
    </table>

    <!-- Items -->
    <table class="items">
        <thead>
        <tr>
            <th>#</th>
            <th>الصنف</th>
            <th>الكمية</th>
            <th>الوزن</th>
            <th>السعر</th>
            <th>الإجمالي</th>
        </tr>
        </thead>
        <tbody>
        @foreach($details as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div class="fw-bold">
                        {{ $item->item_code }} - {{ $item->item_name }}
                    </div>
                    <small class="text-muted">
                        {{ $item->store_name }}
                    </small>
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->weight }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table class="totals">
        <tr>
            <td>الإجمالي</td>
            <td>{{ number_format($doc->total, 2) }}</td>
        </tr>
        <tr>
            <td>الخصم</td>
            <td>{{ number_format($doc->discount, 2) }}</td>
        </tr>
        <tr>
            <td>الصافي</td>
            <td><strong>{{ number_format($doc->net, 2) }}</strong></td>
        </tr>
    </table>

    <!-- Notes -->
    <div class="notes">
        <strong>ملاحظات:</strong><br>
        {{ $doc->notes ?? '-' }}
    </div>

    <!-- Footer Signatures -->
    <div class="signatures">
        <div class="sign-box">
            <p>المحاسب</p>
            <div class="sign-line"></div>
        </div>

        <div class="sign-box">
            <p>المستلم</p>
            <div class="sign-line"></div>
        </div>

        <div class="sign-box">
            <p>المدير</p>
            <div class="sign-line"></div>
        </div>
    </div>

</div>

</body>
</html>
