<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إشعار استلام نقدية من عميل</title>

    <style>
        body {
            font-family: "Cairo", Tahoma, Arial;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0;
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
            margin: 20px 0;
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px 10px;
        }

        table th {
            background: #f2f2f2;
            width: 25%;
            text-align: right;
        }

        /* Notes */
        .notes {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 10px;
            min-height: 60px;
        }

        /* Signatures */
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
        إشعار استلام نقدية من عميل
    </div>

    <!-- Data Table -->
    <table>
        <tr>
            <th>رقم الإشعار</th>
            <td>{{ $doc->bill_number ?? '' }}</td>

            <th>التاريخ</th>
            <td>{{ $doc->date ? \Carbon\Carbon::parse($doc->date) -> format('d-m-Y') : '' }}</td>
        </tr>

        <tr>
            <th>العميل</th>
            <td>{{ $doc->name ?? '' }}</td>

            <th>الخزينة</th>
            <td>{{ $doc->safe ?? '' }}</td>
        </tr>

        <tr>
            <th>المبلغ</th>
            <td>{{ number_format($doc->amount ?? 0, 2) }}</td>

            <th>طريقة الدفع</th>
            <td>
                {{ $doc->payment_method == 0 ? __('main.payment_method0') : __('main.payment_method1') }}
            </td>
        </tr>
    </table>

    <!-- Notes -->
    <div class="notes">
        <strong>ملاحظات:</strong><br>
        {{ $doc->notes ?? '' }}
    </div>

    <!-- Signatures -->
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
