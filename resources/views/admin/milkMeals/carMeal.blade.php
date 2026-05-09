<!DOCTYPE html>

@include('layouts.head')

<style>
    /* 1. إجبار الجدول على العرض الثابت */
    .view_table {
        table-layout: fixed !important;
        width: 100% !important;
        font-size: 11px !important;
    }

    /* 2. تصغير الحشوات تماماً */
    .view_table td,
    .view_table th {
        padding: 2px !important;
        overflow: hidden;
    }


    /* 3. تصغير حقول الإدخال */
    .view_table input.form-control {
        padding: 1px 2px !important;
        height: 24px !important;
        font-size: 11px !important;
        text-align: center;
        border-radius: 2px;
    }

    /* 4. تحديد عرض الأعمدة (سيعمل الآن بسبب fixed layout) */
    .col-id {
        width: 30px !important;
    }

    .supplier {
        width: 110px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* وضع نقاط في حال كان الاسم طويلاً */

    }

    .inp {
        width: 50px !important;
    }

    /* خلايا إدخال الأرقام */
    .col-action {
        width: 70px !important;
    }

    .view_table th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* وضع نقاط في حال كان الاسم طويلاً */
        font-size: 9px !important;
    }
</style>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 3 , 'subSlag' => 31])
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('layouts.nav')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div style="display: flex ; justify-content: space-between ; align-items: center">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">{{__('main.milk_department')}} /</span> {{__('main.weakly_meals_view')}}
                        </h4>
                        <div style="display: flex ; gap: 10px">
                            <button type="button" class="btn btn-primary"  id="printBtn" style="height: 45px"
                                    data-id="{{ $meal->id  }}" >
                                {{__('main.print')}}  <span class="tf-icons bx bx-printer"></span>&nbsp;
                            </button>

                            <button type="button" class="btn btn-secondary"  id="postBtn" style="height: 45px"
                                    data-id="{{ $meal->id  }}" hidden="hidden">
                                {{__('main.details_report')}}  <span class="tf-icons bx bx-repost"></span>&nbsp;
                            </button>
                        </div>



                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.weakly_meals_view')}}
                            (
                            @if (Config::get('app.locale')=='en' )
                                {{$dayName}}
                            @else
                                {{$dayName_ar}}
                            @endif
                            <span style="color: grey">  {{\Carbon\Carbon::parse($startOfWeek) -> format('Y-m-d') }}</span>

                            ---
                            @if (Config::get('app.locale')=='en' )
                                {{$end_dayName}}
                            @else
                                {{$end_dayName_ar}}
                            @endif
                            <span style="color: grey">  {{\Carbon\Carbon::parse($endOfWeek) -> format('Y-m-d') }}</span>
                            )
                        </h5>

                        @include('flash-message')

                        <div class="table-responsive  text-nowrap">

                            <table class="table table-striped table-hover  table-bordered view_table">
                                <thead>
                                @php
                                    use Carbon\Carbon;
                                    use Carbon\CarbonPeriod;

                                    $start = Carbon::parse($startOfWeek);
                                    $end = Carbon::parse($endOfWeek);
                                    $period = CarbonPeriod::create($start, $end);
                                    Carbon::setLocale('ar');
                                @endphp

                                <tr>
                                    <th class="text-center" rowspan="3" style="width: 120px">{{__('main.supplier')}}</th>
                                    @foreach ($period as $date)
                                        <th colspan="2" class="text-center">
                                            @if (Config::get('app.locale')=='ar' )
                                                {{ $date->translatedFormat('l') }}
                                            @else
                                                {{ $date->format('l') }}

                                            @endif

                                        </th>

                                    @endforeach
                                    <th class="text-center cell"  rowspan="2">{{__('main.total')}}</th>
                                    <th class="text-center cell" colspan="1" rowspan="2">{{__('main.price')}}</th>
                                    <th class="text-center cell" colspan="1" rowspan="3">{{__('main.total_cash')}}</th>

                                </tr>
                                <tr>
                                    @foreach ($period as $date)
                                        <th class="text-center text-primary" >{{ __('main.morning_meal') }}</th>
                                        <th class="text-center text-success" >{{ __('main.evening_meal') }}</th>
                                    @endforeach
                                </tr>

                                <tr>
                                    @foreach ($period as $date)
                                        <th class="text-center cell"> وزن اللبن</th>
                                        <th class="text-center cell">وزن اللبن</th>
                                    @endforeach
                                    <th class="text-center cell text-primary" >إجمالي الوزن </th>
                                    <th class="text-center cell text-success" >سعر اللبن </th>

                                </tr>
                                </thead>
                                <tbody>

                                @foreach($suppliers as $supplier)
                                    <tr class="main-row">
                                       <td class="text-center supplier">
                                                <div class="supplier-cell-content">
                                                    <span class="supplier-name"
                                                        title="{{$supplier->name}}">{{$supplier->name}}</span>
                                                </div>
                                            </td>
                                        @foreach ($period as $date)
                                            <td class="text-center" style="padding: 5px">
                                                <input type="number" step="any" name="mbovine_weight[]" data-type="0" data-field="0"
                                                       class="form-control"
                                                       data-date="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}" data-supplier="{{ $supplier->id }}"
                                                       @if(optional($meal)->state === 1) disabled @endif value="0" />
                                            </td>

                                            <td class="text-center" style="padding: 5px">
                                                <input type="number" step="any" name="ebovine_weight[]" data-type="1" data-field="0"
                                                       class="form-control" data-date="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}" data-supplier="{{ $supplier -> id }}"
                                                       @if(optional($meal)->state === 1) disabled @endif value="0"/>
                                            </td>

                                        @endforeach

                                        <td class="text-center" style="padding: 5px">
                                            <input type="text" step="any" name="total_bovine_weight[]"
                                                   class="form-control" data-date="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}" data-supplier="{{ $supplier -> id }}" readonly
                                                   @if(optional($meal)->state === 1) disabled @endif/>
                                        </td>


                                        <td class="text-center" style="padding: 5px">
                                            <input type="number" step="any" name="bovine_price[]" data-filed ="2" data-type="3"
                                                   class="form-control" data-date="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}" data-supplier="{{ $supplier -> id }}"
                                                   @if(optional($meal)->state === 1) disabled @endif/>
                                        </td>


                                        <td class="text-center" style="padding: 5px">
                                            <input type="text" step="any" name="total_money[]"
                                                   class="form-control" data-date="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}" data-supplier="{{ $supplier -> id }}" readonly
                                                   @if(optional($meal)->state === 1) disabled @endif/>
                                        </td>



                                    </tr>


                                @endforeach



                                </tbody>




                            </table>

                        </div>

                        <h5 class="card-header">{{__('main.weakly_meals_view')}}</h5>
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover  table-bordered view_table">
                                <thead>
                                <tr>
                                    <th class="text-center">البيان</th> <!-- "Header" column -->
                                    <th class="text-center">القيمة</th>
                                    <!-- Add more columns for more suppliers -->
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th class="text-center">حساب ما قبل</th>
                                    <td class="text-center">{{$beforeBalance + $weekPaid}}</td>
                                </tr>
                                <tr>
                                    <th class="text-center">حساب الاسبوع</th>
                                    <td class="text-center">{{$weekBalance}}</td>
                                </tr>

                                <tr hidden="hidden">
                                    <th class="text-center">المسدد من الإسبوع</th>
                                    <td class="text-center">{{$weekPaid}}</td>
                                </tr>

                                <tr>
                                    <th class="text-center"> المستحق (إجمالي الحساب)  </th>
                                    <td class="text-center">{{$weekBalance - $weekPaid + $beforeBalance}}</td>

                                </tr>
                                <tr>
                                    <th class="text-center">المدفوع</th>
                                    <td class="text-center">


                                        <input type="number" step="any" name="amount" id="amount" class="form-control"
                                               style="width: 150px;display: block;margin: auto;text-align: center;"
                                               @if($weekBalance + $beforeBalance > 0) value="0"  @else value="{{$weekPaid}}"  disabled  @endif  />


                                    </td>
                                </tr>
                                <tr>

                                    <th class="text-center">الخزنة</th>
                                    <td class="text-center">
                                        <select  name="safe_id" id="safe_id" style="width: 150px ; display: block ; margin: auto"
                                                 class="form-control @error('safe_id') is-invalid @enderror"
                                                 autofocus  required>
                                            <option value=""> {{__('main.select')}}</option>
                                            @foreach($safes as $safe)
                                                <option value="{{$safe -> id}}" @if($safe -> isDefault ==1) selected @endif> {{$safe -> name}} </option>
                                            @endforeach

                                        </select>
                                    </td>

                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        @if($weekBalance + $beforeBalance  > 0)

                                            <button type="button" class="btn btn-primary"  id="payBtn" style="height: 45px"
                                                    data-id="{{ $meal->id  }}" >
                                                {{__('main.pay_btn')}}  <span class="tf-icons bx bx-money"></span>&nbsp;
                                            </button>
                                        @else
                                            <h2 class="text-center text-danger" style="font-size: 25px"> وجبة مسددة </h2>

                                        @endif

                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                    <!--/ Responsive Table -->
                </div>
                <!-- / Content -->

                <!-- Footer -->
                @include('layouts.footer_design')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>

<input type="hidden" value="{{$meal -> id ?? 0}}" id="wid" name="wid">
<input type="hidden" value="{{$suppliers[0] -> id ?? 0}}" id="supplier_id" name="supplier_id">
<input type="hidden" value="{{$start}}" id="start" name="start">


<div id="loading-overlay">
    <div class="loader"></div>
    <div class="loading-text">{{__('main.loading')}}</div>
</div>

@include('layouts.footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let wid = $('#wid').val();
        let supplier_id = $('#supplier_id').val();
        console.log(supplier_id);
        loadMilkMealData(wid , supplier_id);


        $('#payBtn').on('click', function () {
            // Your logic here
            makePayment();

        });

        $('#printBtn').on('click', function () {
            // Your logic here
            let url = "{{ route('weakCarMealDetailsPrint', [':id', ':supplier_id' , ':start']) }}";

            let wid = $('#wid').val();
            let supplier_id = $('#supplier_id').val();
            let start = $('#start').val();

            url = url.replace(':id', wid);
            url = url.replace(':supplier_id', supplier_id);
            url = url.replace(':start', start);

            //  document.location.href=url;
            window.open(url, '_blank');
        });


    });

    function loadMilkMealData(weaklyMealId , supplier) {

        const overlay = document.getElementById('loading-overlay');

        // Show the overlay
        overlay.style.display = 'flex';

        // Create a timer that ensures the loading stays for at least 10 seconds
        const minDuration = 3000; // 5 seconds
        const startTime = Date.now();
        fetch(`/weakCarMealsView/${weaklyMealId}/${supplier}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(record => {
                    const date = record.date.split(' ')[0];
                    const type = record.type;
                    const supplierId = record.member_id;
                    const state = record.state ;

                     if (parseFloat(record.weight_b) > 0) {
                            injectBuffaloRow(supplierId);
                        }



                    const bovineSelector = `input[data-date="${date}"][data-type="${type}"][data-field="0"][data-supplier="${supplierId}"]`;
                    const bovineInput = document.querySelector(bovineSelector);
                    const bovineSelector0 = `input[data-date="${date}"][data-type="0"][data-field="0"][data-supplier="${supplierId}"]`;
                    const bovineSelector1 = `input[data-date="${date}"][data-type="1"][data-field="0"][data-supplier="${supplierId}"]`;
                    const bovineInput0 = document.querySelector(bovineSelector0);
                    const bovineInput1 = document.querySelector(bovineSelector1);
                    if (bovineInput) {
                        bovineInput.value = formatNumberSmart(record.weight);
                    }

                    setTimeout(() => {

                         const buffaloSelector = `input[data-date="${date}"][data-type="${type}"][data-field="1"][data-supplier="${supplierId}"]`;
                            const $buffaloInput = $(buffaloSelector);


                                if ($buffaloInput.length) {
                                    $buffaloInput.val(record.weight_b).trigger('change');
                                }

                    const bovineTotalSelector = `input[data-date="${date}"][data-type="1"][data-field="1"][data-supplier="${supplierId}"][name="total_bovine_weight[]"]`;

                    const bovineTotalInput = document.querySelector(bovineTotalSelector);
                    if (bovineTotalInput) {
                        bovineTotalInput.value = formatNumberSmart(record.weight);
                    }

                     const priceBSelector = `input[name="buffalo_price[]"]`;
                    const priceBInput = document.querySelector(priceBSelector);

                    if (priceBInput) {
                        priceBInput.value = formatNumberSmart(record.price_b);
                    }



                    const priceSelector = `input[name="bovine_price[]"]`;
                    const priceInput = document.querySelector(priceSelector);

                    if (priceInput) {
                        priceInput.value = formatNumberSmart(record.price);
                    }

                    const supplierInputs = document.querySelectorAll(`input[data-supplier="${supplierId}"]`);
                    supplierInputs.forEach(input => {
                        input.disabled = (state === 1); // disable if state == 1, otherwise enable
                    });

                    calculateRowTotals();


                     }, 200);
                });

            })
            .catch(err => {
                console.error('Error loading milk meal data:', err);
            })
            .finally(() => {
                const elapsed = Date.now() - startTime;
                const remaining = minDuration - elapsed;

                setTimeout(() => {
                    overlay.style.display = 'none';
                }, Math.max(remaining, 0));
            });
    }

        function injectBuffaloRow(supplierId) {
        console.log('supplierId' + supplierId);
    // 1. تحديد السطر الأصلي
    const $originalRow = $(`tr.main-row`);



    // منع التكرار
    if ($originalRow.next().hasClass('buffalo-row') || $originalRow.length === 0) {
        return;
    }

    // 2. استنساخ السطر الأصلي (نفس منطق الزرار اليدوي)
    const $newRow = $originalRow.clone();


    $newRow.addClass('buffalo-row').removeAttr('data-car');

    // 3. ضبط الـ Rowspan للخلايا المشتركة في السطر الأصلي
    $originalRow.find('td:has(input[name="total_money[]"]), td:last-child')
                .attr('rowspan', '2')
                .css('vertical-align', 'middle');

    // 4. حذف الخلايا الزائدة من السطر الجديد
    $newRow.find('td:has(input[name="total_money[]"]), td:last-child').remove();

    // 5. تحويل الحقول لـ Buffalo وتصفيرها
    const isRTL = $('html').attr('dir') === 'rtl' || $('body').css('direction') === 'rtl';
    const subIcon = isRTL ? 'bx-subdirectory-left' : 'bx-subdirectory-right';
    const marginClass = isRTL ? 'margin-right:15px;' : 'margin-left:15px;';

    $newRow.find('input').each(function() {
        let name = $(this).attr('name');
        if (name) {
            $(this).attr('name', name.replace('bovine', 'buffalo'));
        }
        if (name && name.includes('price')) {
        // لو الحقل ده بتاع سعر، نخليه 3
        $(this).attr('data-field', "3");
    } else if ($(this).attr('data-field') === "0") {
        // لو حقل وزن (كان 0 في البقري) نخليه 1 في الجاموسي
        $(this).attr('data-field', "1");
    }
        $(this).val(''); // بنصفره عشان loadMilkMealData هي اللي هتملاه
    });

    // 6. شكل خلية الاسم (أيقونة الجاموسي)
    const $nameCell = $newRow.find('.supplier-cell-content');
    $nameCell.html(`
        <span class="supplier-name" style="${marginClass} color:#6610f2; display: inline-flex; align-items: center; gap: 5px;">
            <i class='bx ${subIcon}'></i>
            <span>جاموسي</span>
        </span>
        <i class='bx bx-minus-circle text-danger remove-sub-row' style="cursor:pointer; display:none"  title="حذف"></i>
    `);



    // 7. حقن السطر في الجدول
    $newRow.insertAfter($originalRow);


}

    function calculateRowTotals() {
        const rows = document.querySelectorAll('table tbody tr'); // Adjust the selector if needed

        rows.forEach(row => {
            const inputs = row.querySelectorAll('input');

            let totalBovine = 0;
            let totalBuffalo = 0;

            inputs.forEach(input => {
                const field = input.getAttribute('data-field');
                const value = parseFloat(input.value);
                if (isNaN(value)) return;

                if (field === "0") {
                    totalBovine += value;
                } else if (field === "1") {
                    totalBuffalo += value;
                }
            });


            const totalBovineInput = row.querySelector('input[name="total_bovine_weight[]"]');
            const totalBuffaloInput = row.querySelector('input[name="total_buffalo_weight[]"]');


            const priceBovineInput = row.querySelector('input[name="bovine_price[]"]');
            const priceBuffaloInput = row.querySelector('input[name="buffalo_price[]"]');

            const moneyTotalInput = row.querySelector('input[name="total_money[]"]');

            let bovinePrice = 0 ;
            let buffaloPrice = 0 ;

            if (totalBovineInput) totalBovineInput.value = totalBovine.toFixed(2);
            if (totalBuffaloInput) totalBuffaloInput.value = totalBuffalo.toFixed(2);

            if(priceBovineInput)  bovinePrice = priceBovineInput.value ;
            if(priceBuffaloInput)  buffaloPrice = priceBuffaloInput.value ;

            if(!row.classList.contains('buffalo-row')){

                if (moneyTotalInput){
                        moneyTotalInput.value = (((totalBuffalo * buffaloPrice) + (totalBovine * bovinePrice))).toFixed(2);

                    }
                    } else {
                                const prevRow = row.previousElementSibling;
                                const prevMoneyInput = prevRow.querySelector('input[name="total_money[]"]');
                                const prevMoneyValue = prevMoneyInput ? parseFloat(prevMoneyInput.value) || 0 : 0;
                                const currentBuffaloTotal = totalBuffalo * buffaloPrice;
                                prevMoneyInput.value = (prevMoneyValue + currentBuffaloTotal).toFixed(2);
                    }
        });
    }


    function makePayment(){
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const safeId = $('#safe_id').val();
        const amount = parseFloat($('#amount').val());

        // Validation checks
        if (!safeId) {
            toastr.warning('يرجى اختيار الخزنة');
            return;
        }

        if (isNaN(amount) || amount <= 0) {
            toastr.warning('يجب أن يكون المبلغ أكبر من صفر');
            return;
        }

        fetch("{{ route('makePayment') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                //  date: new Date(),
                date: $('#start').val(),
                bill_number: "",
                supplier_id: $('#supplier_id').val(),
                recipit_type: 0,
                amount: amount,
                safe_id: safeId,
                notes: "",
                wid:  $('#wid').val(),
                car: 1
            })
        })
            .then(response => {
                console.log(response);
                return response.json();  // <-- Important: return here!
            })
            .then(data => {
                if (data.status === 'warning') {
                    toastr.warning(data.message);
                } else if (data.status === 'success') {
                    toastr.success(data.message);

                    setTimeout(() => {


                        $('#printBtn').click();
                        let url = "{{ route('milk_meals') }}";
                        document.location.href=url;
                    } , 1000);


                } else {
                    toastr.error(data.message);
                    console.log(data.debug);
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
                toastr.error('Failed to save value.', 'Error');
            });

    }

    function formatNumberSmart(val) {
        let num = parseFloat(val);
        if (isNaN(num)) return '';
        // إذا كان رقم صحيح ارجعه بدون أصفار، إذا كان كسر ارجعه بخانتين
        return Number.isInteger(num) ? num : num.toFixed(2);
    }
</script>
</body>
</html>
