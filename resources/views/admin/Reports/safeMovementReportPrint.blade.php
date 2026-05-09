<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير حركة خزنة</title>

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
            font-size: 13px;
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

        .debit { color: #b30000; font-weight: bold; }
        .credit { color: #006400; font-weight: bold; }

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
    تقرير حركة خزنة
</div>

<!-- TABLE -->
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>الخزنة</th>
        <th>نوع المستند</th>
        <th>من / إلى</th>
        <th>التاريخ</th>
        <th>رقم المستند</th>
        <th>مدين</th>
        <th>دائن</th>
        <th>الرصيد</th>
    </tr>
    </thead>

    <tbody>

    @php
        $openingBalance = (float) ($oBalance->opening_balance ?? 0);
        $openingDebit = $openingBalance < 0 ? abs($openingBalance) : 0;
        $openingCredit = $openingBalance >= 0 ? $openingBalance : 0;
        $runningBalance = $openingBalance;
    @endphp

    <!-- Row for Opening Balance -->
    <tr style="background:#f8f9fa;font-weight:bold;">
        <td>1</td>
        <td>{{ $data->first()->safe ?? '' }}</td>
        <td><span class="badge bg-secondary">رصيد افتتاحي</span></td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td class="debit">{{ $openingDebit ? number_format($openingDebit,2) : '0.00' }}</td>
        <td class="credit">{{ $openingCredit ? number_format($openingCredit,2) : '0.00' }}</td>
        <td class="{{ $runningBalance < 0 ? 'debit' : 'credit' }}">{{ number_format($runningBalance,2) }}</td>
    </tr>

    <!-- Loop through transactions -->
    @foreach($data as $i => $doc)
        @php
            $isDebit = in_array($doc->type,[0,3,4,7,9,10]);
            $debit = $isDebit ? $doc->amount : 0;
            $credit = $isDebit ? 0 : $doc->amount;
            $runningBalance += ($credit - $debit);
        @endphp
        <tr>
            <td>{{ $i + 2 }}</td>
            <td>{{ $doc->safe }}</td>
            <td>{{ __('main.doc'.$doc->type) }}</td>
            <td>{{ $doc->client }}</td>
            <td>{{ \Carbon\Carbon::parse($doc->docDate)->format('Y-m-d') }}</td>
            <td>{{ $doc->docNumber }}</td>
            <td class="debit">{{ $debit ? number_format($debit,2) : '0.00' }}</td>
            <td class="credit">{{ $credit ? number_format($credit,2) : '0.00' }}</td>
            <td class="{{ $runningBalance < 0 ? 'debit' : 'credit' }}">{{ number_format($runningBalance,2) }}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<!-- SIGNATURE -->
<div class="sign">
    <div>المحاسب<span></span></div>
    <div>المراجع<span></span></div>
    <div>المدير<span></span></div>
</div>

</body>
</html>
