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
    تقرير حركة مخزون حسب الوجبة
</div>

<!-- TABLE -->
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>الوجبة</th>
        <th>المخزن</th>
        <th>الصنف</th>
        <th>الكمية الواردة</th>
        <th>الكمية الصادرة</th>
        <th>الرصيد</th>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $i => $doc)
        <tr >
            <td class="text-center"> {{ $loop -> index + 1}} </td>
            <td class="text-center"> {{ $doc -> cheese_meal	 }}
                <br> {{$doc -> symbol}}
            </td>
            <td class="text-center"> {{ $doc -> store	 }} </td>

            <td class="text-center"> {{ $doc -> item }} </td>
            <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{$doc -> quantity_in}} </td>
            <td class="text-center text-danger" style="font-size: 18px ; font-weight: bold ;"> {{$doc -> quantity_out}} </td>
            <td class="text-center  @if($doc -> balance + $doc -> opening_quantity > 0) text-success @else text-danger  @endif " style="font-size: 18px ; font-weight: bold ;"> {{$doc -> balance}} </td>
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
