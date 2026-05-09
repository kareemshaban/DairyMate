<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير حساب عميل - إجمالي</title>

    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            font-family: "Cairo", Tahoma, Arial;
            direction: rtl;
            color: #000;
            background: #fff;
            font-size: 13px;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .company h3 {
            margin: 0;
            font-size: 20px;
        }

        .company p {
            margin: 3px 0;
            font-size: 13px;
        }

        .meta {
            text-align: left;
            font-size: 13px;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        thead th {
            background: #f1f1f1;
            font-weight: bold;
        }

        tfoot th {
            background: #e9e9e9;
            font-weight: bold;
        }

        .debit {
            color: #006400;
            font-weight: bold;
        }

        .credit {
            color: #b30000;
            font-weight: bold;
        }

        /* ===== SIGN ===== */
        .sign {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .sign div {
            width: 30%;
            text-align: center;
        }

        .sign span {
            display: block;
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>

<body onload="window.print()">

<!-- HEADER -->
<div class="header">
    <div class="company">
        <h3>مصنع أولاد بلال</h3>
        <p>العنوان : النسايمة - المنزلة - الدقهلية</p>
        <p>ت / 01281350125 - 0503597046</p>
    </div>

    <div class="meta">
        <strong>تاريخ الطباعة</strong><br>
        {{ now()->format('Y-m-d H:i') }}
    </div>
</div>

<div class="title">
    تقرير حساب عميل - إجمالي
</div>

<!-- TABLE -->
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>اسم العميل</th>
        <th>دائن</th>
        <th>مدين</th>
        <th>الرصيد النهائي</th>
    </tr>
    </thead>

    <tbody>
    @php
        $grandCredit = 0;
        $grandDebit = 0;
    @endphp

    @foreach($totals as $i => $client)
        @php
            $balance = $client['debit'] - $client['credit'];
            $grandCredit += $client['credit'];
            $grandDebit  += $client['debit'];
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $client['client'] }}</td>
            <td class="credit">{{ number_format($client['credit'],2) }}</td>
            <td class="debit">{{ number_format($client['debit'],2) }}</td>
            <td class="{{ $balance >=0 ? 'debit' : 'credit' }}">
                {{ number_format($balance,2) }}
            </td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="2">إجمالي الرصيد</th>
        <th class="credit">{{ number_format($grandCredit,2) }}</th>
        <th class="debit">{{ number_format($grandDebit,2) }}</th>
        <th class="{{ ($grandDebit-$grandCredit) >=0 ? 'debit' : 'credit' }}">
            {{ number_format($grandDebit-$grandCredit,2) }}
        </th>
    </tr>
    </tfoot>
</table>

<!-- SIGNATURE -->
<div class="sign">
    <div>المحاسب<span></span></div>
    <div>المراجع<span></span></div>
    <div>المدير<span></span></div>
</div>

</body>
</html>
