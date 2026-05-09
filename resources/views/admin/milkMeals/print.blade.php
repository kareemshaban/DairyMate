<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تقرير توريد ألبان - A5</title>

    <style>
        /* إعدادات الطباعة الأساسية لمقاس A5 */
        @page {
            size: A5 portrait;
            margin: 8mm; /* هامش متوازن للمساحة الصغيرة */
        }

        body {
            font-family: "Tahoma", "Arial", sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #000;
            background: #fff;
            -webkit-print-color-adjust: exact;
        }

        /* حاوية المحتوى - بدون ارتفاع ثابت لمنع القص */
        .page-content {
            width: 100%;
            display: block;
        }

        /* الهيدر */
        .print-header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .print-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .print-header .meta {
            font-size: 9px;
            margin-top: 3px;
        }

        /* الصفوف المعلوماتية */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 11px;
            padding: 0 2px;
        }

        /* تنسيق الجداول */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2 !important;
            font-weight: bold;
            font-size: 10px;
        }

        /* منع انقسام الصفوف */
        tr { page-break-inside: avoid; }

        /* جدول الحسابات النهائي - محاذاة لليسار */
        .summary-table {
            width: 75%;
            margin-right: auto; /* دفع الجدول لليسار في الواجهة العربية */
            margin-left: 0;
        }

        .summary-table td:first-child {
            background-color: #f9f9f9 !important;
            text-align: right;
            padding-right: 8px;
            width: 60%;
        }

        .summary-table td:last-child {
            font-weight: bold;
        }

        /* الفوتر */
        .print-footer {
            text-align: center;
            font-size: 9px;
            border-top: 1px solid #000;
            margin-top: 15px;
            padding-top: 5px;
        }

        .text-bold { font-weight: bold; }
    </style>
</head>

<body>

<div class="page-content">

    <div class="print-header">
        <h2>مصنع أولاد بلال لمنتجات الألبان</h2>
        <div class="meta">
            تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    <div class="info-row">
        <div>
            <strong>{{ __('main.supplier') }}:</strong>
            <span class="text-bold">{{ $supplier->name }}</span>
        </div>
        <div>
            <strong>الفترة:</strong>
            ({{ \Carbon\Carbon::parse($startOfWeek)->format('Y-m-d') }} — {{ \Carbon\Carbon::parse($endOfWeek)->format('Y-m-d') }})
        </div>
    </div>

    @php
        use Carbon\Carbon;
        use Carbon\CarbonPeriod;

        $start = Carbon::parse($startOfWeek);
        $end = Carbon::parse($endOfWeek);
        $period = CarbonPeriod::create($start, $end);
        Carbon::setLocale('ar');

        $hasBuffalo = $meals->sum('buffalo_weight') > 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th rowspan="{{ $hasBuffalo ? 2 : 1 }}">اليوم</th>
                <th colspan="{{ $hasBuffalo ? 2 : 1 }}">الوجبة الصباحية</th>
                <th colspan="{{ $hasBuffalo ? 2 : 1 }}">الوجبة المسائية</th>
            </tr>
            @if($hasBuffalo)
            <tr>
                <th>بقري</th>
                <th>جاموسي</th>
                <th>بقري</th>
                <th>جاموسي</th>
            </tr>
            @endif
        </thead>

        <tbody>
            @foreach($period as $day)
            <tr>
                <td class="text-bold">
                    {{ (Config::get('app.locale')=='ar') ? $day->translatedFormat('l') : $day->format('l') }}
                </td>

                @php
                    $m0 = $meals->first(fn($m) => Carbon::parse($m->date)->toDateString() === $day->toDateString() && $m->type == 0);
                    $m1 = $meals->first(fn($m) => Carbon::parse($m->date)->toDateString() === $day->toDateString() && $m->type == 1);
                @endphp

                <td>{{ $m0->bovine_weight ?? '—' }}</td>
                @if($hasBuffalo)
                <td>{{ $m0->buffalo_weight ?? '—' }}</td>
                @endif

                <td>{{ $m1->bovine_weight ?? '—' }}</td>
                @if($hasBuffalo)
                <td>{{ $m1->buffalo_weight ?? '—' }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>البيان</th>
                <th>بقري</th>
                @if($hasBuffalo)
                <th>جاموسي</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-bold">إجمالي الأوزان</td>
                <td>{{ $meals->sum('bovine_weight') }} كيلو</td>
                @if($hasBuffalo)
                <td>{{ $meals->sum('buffalo_weight') }} كيلو</td>
                @endif
            </tr>
            <tr>
                <td class="text-bold">سعر الكيلو</td>
                <td>{{ $meals[0]->bovine_price ?? 0 }} ج.م</td>
                @if($hasBuffalo)
                <td>{{ $meals[0]->buffalo_price ?? 0 }} ج.م</td>
                @endif
            </tr>
        </tbody>
    </table>

    <table class="summary-table">
        <tbody>
            <tr>
                <td>{{ __('main.total_money') }}</td>
                <td>{{ number_format($totalMoney, 2) }} ج.م</td>
            </tr>
            <tr>
                <td>{{ __('main.before_balance') }}</td>
                <td>{{ number_format($beforeBalance + $weekPaid, 2) }} ج.م</td>
            </tr>
            <tr>
                <td>{{ __('main.required') }}</td>
                <td style="font-size: 13px;">{{ number_format($totalMoney + ($beforeBalance + $weekPaid), 2) }} ج.م</td>
            </tr>
            <tr>
                <td>{{ __('main.paid') }}</td>
                <td>{{ number_format($weekPaid, 2) }} ج.م</td>
            </tr>
            <tr style="background: #eee !important;">
                <td><strong>{{ __('main.remain') }}</strong></td>
                <td style="font-size: 13px;">{{ number_format($totalMoney + $beforeBalance, 2) }} ج.م</td>
            </tr>
        </tbody>
    </table>

    <div class="print-footer">
        مصنع أولاد بلال لمنتجات الألبان — تقرير حسابات داخلي
    </div>

</div>

<script>
    window.onload = function () {
        // تأخير بسيط لضمان استقرار التنسيق قبل الحوار
        setTimeout(() => {
            window.print();
            window.onafterprint = () => window.close();
        }, 300);
    };
</script>

</body>
</html>