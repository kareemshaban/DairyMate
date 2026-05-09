<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>طباعة تقرير توريد - A5</title>

    <style>
        /* إعدادات الصفحة A5 */
        @page {
            size: A5 portrait;
            margin: 8mm;
        }

        body {
            font-family: "Tahoma", "Arial", sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: #000;
            background: #fff;
            -webkit-print-color-adjust: exact;
        }

        /* حاوية المحتوى الأساسية */
        .page-content {
            width: 100%;
            display: block;
        }

        /* الهيدر */
        .print-header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 8px;
            padding-bottom: 4px;
        }

        .print-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .print-header .meta {
            font-size: 10px;
            margin-top: 2px;
        }

        /* صفوف المعلومات */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 11px;
        }

        /* تنسيق الجداول */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
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

        /* منع انقسام الصفوف عند الطباعة */
        tr { page-break-inside: avoid; }

        /* جدول الحسابات النهائي */
        .summary-table {
            width: 70%;
            margin-right: auto; /* دفع الجدول لجهة اليسار في الاتجاه العربي */
            margin-left: 0;
        }

        .summary-table td:first-child {
            background-color: #f9f9f9 !important;
            text-align: right;
            padding-right: 8px;
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
            (
            @if (Config::get('app.locale')=='en')
                {{ $dayName }}
            @else
                {{ $dayName_ar }}
            @endif
            {{ \Carbon\Carbon::parse($startOfWeek)->format('Y-m-d') }}
            —
            @if (Config::get('app.locale')=='en')
                {{ $end_dayName }}
            @else
                {{ $end_dayName_ar }}
            @endif
            {{ \Carbon\Carbon::parse($endOfWeek)->format('Y-m-d') }}
            )
        </div>
    </div>

    @php
        use Carbon\Carbon;
        use Carbon\CarbonPeriod;

        $start = Carbon::parse($startOfWeek);
        $end = Carbon::parse($endOfWeek);
        $period = CarbonPeriod::create($start, $end);
        Carbon::setLocale('ar');

        $hasBuffalo = $meals->sum('weight_b') > 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th rowspan="{{ $hasBuffalo ? 2 : 1 }}"> {{ __('main.day') }}</th>
                <th colspan="{{ $hasBuffalo ? 2 : 1 }}">{{ __('main.morning_meal') }}</th>
                <th colspan="{{ $hasBuffalo ? 2 : 1 }}">{{ __('main.evening_meal') }}</th>
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
                    $meal0 = $meals->first(fn($m) => Carbon::parse($m->date)->toDateString() === $day->toDateString() && $m->type == 0);
                    $meal1 = $meals->first(fn($m) => Carbon::parse($m->date)->toDateString() === $day->toDateString() && $m->type == 1);
                @endphp

                <td>{{ $meal0->weight ?? '—' }}</td>
                @if($hasBuffalo)
                    <td>{{ $meal0->weight_b ?? '—' }}</td>
                @endif

                <td>{{ $meal1->weight ?? '—' }}</td>
                @if($hasBuffalo)
                    <td>{{ $meal1->weight_b ?? '—' }}</td>
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
                <td class="text-bold">إجمالي الوزن</td>
                <td>{{ $meals->sum('weight') }} كيلو</td>
                @if($hasBuffalo)
                    <td>{{ $meals->sum('weight_b') }} كيلو</td>
                @endif
            </tr>
            <tr>
                <td class="text-bold">{{ __('main.price') }}</td>
                <td>{{ $meals[0]->price ?? 0 }} ج.م</td>
                @if($hasBuffalo)
                    <td>{{ $meals[0]->price_b ?? 0 }} ج.م</td>
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
                <td>{{ number_format($beforeBalance +  $weekPaid , 2) }} ج.م</td>
            </tr>
            <tr>
                <td>{{ __('main.required') }}</td>
                <td><strong style="font-size: 12px;">{{ number_format($totalMoney + $beforeBalance + $weekPaid , 2) }} ج.م</strong></td>
            </tr>
            <tr>
                <td>{{ __('main.paid') }}</td>
                <td>{{ number_format($weekPaid, 2) }} ج.م</td>
            </tr>
            <tr style="background: #eee !important;">
                <td><strong>{{ __('main.remain') }}</strong></td>
                <td><strong style="font-size: 13px;">{{ number_format($totalMoney + $beforeBalance , 2) }} ج.م</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="print-footer">
        مصنع أولاد بلال لمنتجات الألبان — تقرير داخلي
    </div>

</div>

<script>
    window.onload = function () {
        setTimeout(() => {
            window.print();
            window.onafterprint = () => window.close();
        }, 300);
    };
</script>

</body>
</html>