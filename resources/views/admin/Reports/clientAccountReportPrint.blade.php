<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير حساب عميل - تفصيلي</title>

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

        .subtitle {
            text-align: center;
            font-size: 15px;
            margin-bottom: 15px;
            font-weight: bold;
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
    تقرير حساب عميل - تفصيلي
</div>

<div class="subtitle">
    {{ $account->client ?? '' }}
</div>

<!-- TABLE -->
@php
    $totalCredit = 0;
    $totalDebit  = 0;

    // الرصيد الافتتاحي
    if ($account) {
        $totalCredit += $account->opening_balance_credit;
        $totalDebit  += $account->opening_balance_debit;
    }

    // رصيد ما قبل الفترة
    foreach ($brfors as $b) {
        $totalCredit += $b['credit'];
        $totalDebit  += $b['debit'];
    }

    // الحركات
    foreach ($data as $row) {
        if (in_array($row->type, [0,3,5])) {
            $totalDebit += $row->amount;
        } else {
            $totalCredit += $row->amount;
        }
    }

    // الرصيد النهائي
    $finalBalance = $totalDebit - $totalCredit;
@endphp



<table>
    <thead>
    <tr>
        <th>#</th>
        <th>نوع المستند</th>
        <th>التاريخ</th>
        <th>رقم المستند</th>
        <th>دائن</th>
        <th>مدين</th>
        <th>الرصيد</th>
    </tr>
    </thead>

    <tbody>
    {{-- Opening Balance --}}
    @if($account)
        <tr>
            <td>—</td>
            <td>رصيد افتتاحي</td>
            <td>—</td>
            <td>—</td>
            <td class="credit">{{ number_format($account->opening_balance_credit,2) }}</td>
            <td class="debit">{{ number_format($account->opening_balance_debit,2) }}</td>
            <td>
                {{ number_format($account->opening_balance_credit - $account->opening_balance_debit,2) }}
            </td>
        </tr>
    @endif

    {{-- Before Period --}}
    @foreach($brfors as $b)
        <tr>
            <td>—</td>
            <td>رصيد ما قبل الفترة</td>
            <td>—</td>
            <td>—</td>
            <td class="credit">{{ number_format($b['credit'],2) }}</td>
            <td class="debit">{{ number_format($b['debit'],2) }}</td>
            <td>{{ number_format($b['debit'] - $b['credit'],2) }}</td>
        </tr>
    @endforeach

    {{-- Transactions --}}
    @foreach($data as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ __('main.doc'.$row->type) }}</td>
            <td>{{ \Carbon\Carbon::parse($row->docDate)->format('Y-m-d') }}</td>
            <td>{{ $row->docNumber }}</td>

            <td class="credit">
                {{ in_array($row->type,[0,3,5]) ? '0.00' : number_format($row->amount,2) }}
            </td>

            <td class="debit">
                {{ in_array($row->type,[0,3,5]) ? number_format($row->amount,2) : '0.00' }}
            </td>

            <td>
                {{ in_array($row->type,[2,4])
                    ? number_format($row->amount,2)
                    : number_format(-1 * $row->amount,2) }}
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4" style="font-size:15px;font-weight:bold">
            الإجمالي
        </th>

        <th class="credit" style="font-size:15px;font-weight:bold">
            {{ number_format($totalCredit, 2) }}
        </th>

        <th class="debit" style="font-size:15px;font-weight:bold">
            {{ number_format($totalDebit, 2) }}
        </th>

        <th style="font-size:15px;font-weight:bold"
            class="{{ $finalBalance >= 0 ? 'debit' : 'credit' }}">
            {{ number_format($finalBalance, 2) }}
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
