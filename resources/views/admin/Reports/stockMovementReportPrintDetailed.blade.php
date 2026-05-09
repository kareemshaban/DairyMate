<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير حركة المخزون - تفصيلي</title>

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

        .in {
            color: #006400;
            font-weight: bold;
        }

        .out {
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
    تقرير حركة المخزون – تفصيلي
</div>

@php
    $totalQtyIn = 0;
    $totalQtyOut = 0;
    $totalWeightIn = 0;
    $totalWeightOut = 0;
@endphp

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>المخزن</th>
        <th>الصنف</th>
        <th>نوع المستند</th>
        <th>الكمية الواردة</th>
        <th>الكمية المنصرفة</th>
        <th>الوزن الوارد</th>
        <th>الوزن المنصرف</th>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $doc)
        @php
            $totalQtyIn     += $doc->quantity_in;
            $totalQtyOut    += $doc->quantity_out;
            $totalWeightIn  += $doc->weight_in;
            $totalWeightOut += $doc->weight_out;
        @endphp

        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $doc->store }}</td>
            <td>{{ $doc->item }}</td>
            <td>{{ __('main.sDoc'.$doc->type) }}</td>

            <td class="in">{{ number_format($doc->quantity_in,2) }}</td>
            <td class="out">{{ number_format($doc->quantity_out,2) }}</td>
            <td class="in">{{ number_format($doc->weight_in,2) }}</td>
            <td class="out">{{ number_format($doc->weight_out,2) }}</td>
        </tr>
    @endforeach
    </tbody>

    <!-- TOTAL -->
    <tfoot>
    <tr>
        <th colspan="4" style="font-size:15px;font-weight:bold">الإجمالي</th>

        <th class="in" style="font-size:15px">
            {{ number_format($totalQtyIn,2) }}
        </th>

        <th class="out" style="font-size:15px">
            {{ number_format($totalQtyOut,2) }}
        </th>

        <th class="in" style="font-size:15px">
            {{ number_format($totalWeightIn,2) }}
        </th>

        <th class="out" style="font-size:15px">
            {{ number_format($totalWeightOut,2) }}
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
