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

    .buffalo-row {
        background-color: #fdf2e9 !important;
        /* لون برتقالي فاتح جداً أو أي لون تفضله */
    }

    /* تنسيق خلية المورد لجعل الأيقونة في طرف والاسم في طرف */
    .supplier-cell-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* يضع الاسم في جهة والأيقونة في الجهة الأخرى */
        width: 100%;
        direction: rtl;
        /* لضمان الترتيب الصحيح */
    }

    .supplier-name {
        flex-grow: 1;
        text-align: right;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .add-sub-row,
    .remove-sub-row {
        cursor: pointer;
        font-size: 18px;
        transition: transform 0.2s;
        margin-right: 8px;
        /* مسافة بسيطة من الاسم */
    }

    .add-sub-row:hover {
        transform: scale(1.2);
    }

    .remove-sub-row:hover {
        transform: scale(1.2);
    }

    /* لون السطر المضاف */
    .buffalo-row {
        background-color: #fff9f4 !important;
        /* لون هادئ جداً */
        border-right: 6px solid #ffab00;
        /* علامة جانبية تميز السطر */

    }

    .supplier-name-sub {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6610f2;
        font-weight: 600;
        /* إزاحة من البداية (يمين في العربي، يسار في الإنجليزي) */
        margin-inline-start: 15px;
    }

    .supplier-name-sub i {
        font-size: 1.2rem;
        color: #adb5bd;
        /* لضمان أن السهم يشير دائماً "للداخل" تجاه النص */
        vertical-align: middle;
    }

    /* تمييز السطر المضاف بصرياً */
    .buffalo-row {
        background-color: rgba(102, 16, 242, 0.02) !important;
    }

    /* ربط بصري عبر الحدود */
    td[rowspan] {
        border-inline-start: 4px solid #6610f2 !important;
        /* خط ملون في جهة بداية السطر */
        background-color: #fff !important;
    }
</style>
@foreach($suppliers as $supplier)
<tr data-car="{{$supplier -> car_id}}" class="main-row" data-supplier="{{$supplier -> id}}">
    <td class="text-center" hidden="hidden">{{$loop -> index + 1}}</td>
    <td class="text-center supplier">
        <div class="supplier-cell-content">
            @if($supplier->car_id == 0)
            <span class="supplier-name" title="{{$supplier->name}}">{{$supplier->name}}</span>
            <i class='bx bx-plus-circle text-success add-sub-row' title="إضافة سطر جاموسي"
                data-supplier-name="{{$supplier->name}}"></i>
            @else
            <a href="{{route('car_meals' , ['supplier_id' => $supplier->id , 'startDate' => $startDate])}}"
                target="_blank">
                <span class="text-info">{{$supplier->name}}</span>
            </a>
            @endif
        </div>
        <input type="hidden" value="{{$supplier->id}}" name="supplier_id[]" />
    </td>
    @foreach ($period as $date)
    <td class="text-center inp">
        <input type="number" step="any" name="mbovine_weight[]" data-type="0" data-field="0"
            data-car="{{$supplier -> car_id}}" class="form-control" style="color: #6610f2"
            data-date="{{ $date }}" data-supplier="{{ $supplier->id }}"
            @if(optional($meal)->state === 1) disabled @endif />
    </td>

    <td class="text-center inp">
        <input type="number" step="any" name="ebovine_weight[]" data-type="1" data-field="0"
            data-car="{{$supplier -> car_id}}" class="form-control"
            data-date="{{ $date }}" data-supplier="{{ $supplier -> id }}"
            @if(optional($meal)->state === 1) disabled @endif style="color:
        #71dd37"/>
    </td>

    @endforeach

    <td class="text-center col-action">
        <input type="text" step="any" name="total_bovine_weight[]" data-car="{{$supplier -> car_id}}"
            class="form-control" data-date="{{ $date }}"
            data-supplier="{{ $supplier -> id }}" readonly @if(optional($meal)->state === 1) disabled @endif/>
    </td>


    <td class="text-center inp">
        <input type="number" step="any" name="bovine_price[]" data-field="2" data-type="3"
            data-car="{{$supplier -> car_id}}" class="form-control"
            data-date="{{ $date }}" data-supplier="{{ $supplier -> id }}"
            value="{{$supplier -> bovine_price}}" data-buffalo-price="{{$supplier -> buffalo_price}}"  @if($supplier -> car_id > 0)
        readonly @endif
        @if(optional($meal)->state === 1) disabled @endif/>
    </td>


    <td class="text-center col-action">
        <input type="text" step="any" name="total_money[]" data-car="{{$supplier -> car_id}}" class="form-control"
            data-date="{{ $date }}" data-supplier="{{ $supplier -> id }}"
            readonly @if(optional($meal)->state === 1)
        disabled @endif/>
    </td>
    <td class="text-center">
        @if($supplier -> car_id == 0)

        <i class='bx bxs-cloud-upload text-primary postBtn' data-toggle="tooltip" data-placement="top"
            title="{{__('main.post_action')}}" data-supplier="{{$supplier -> id}}"
            data-supplier_name="{{$supplier -> name}}" style="font-size: 25px ; cursor: pointer"></i>

        <i class='bx bx-show text-primary viewBtn' data-toggle="tooltip" data-placement="top"
            title="{{__('main.view_action')}}" data-supplier="{{ $supplier -> id }}"
            style="font-size: 25px ; cursor: pointer"></i>
        @endif



    </td>


</tr>


@endforeach
{{-- TOTAL ROW --}}
<tr style="background:#f1f1f1; font-weight:bold;">
    <td class="text-center">الإجمالي</td>

    @foreach ($period as $date)
    {{-- total mbovine --}}
    <td class="text-center">
        <input type="text" class="form-control total-mbovine text-primary"
            data-date="{{ $date }}" readonly>
    </td>

    {{-- total mbuffalo --}}
    <td class="text-center" hidden>
        <input type="text" class="form-control total-mbuffalo"
            data-date="{{ $date }}" readonly>
    </td>

    {{-- total ebovine --}}
    <td class="text-center">
        <input type="text" class="form-control total-ebovine"
            data-date="{{ $date }}" readonly>
    </td>

    {{-- total ebuffalo --}}
    <td class="text-center" hidden>
        <input type="text" class="form-control total-ebuffalo"
            data-date="{{ $date }}" readonly>
    </td>
    @endforeach

    {{-- Total bovine weight --}}
    <td class="text-center">

        <input type="text" class="form-control total-bovine-weight" readonly>
    </td>

    {{-- Total buffalo weight --}}
    <td class="text-center" hidden>
        <input type="text" class="form-control total-buffalo-weight" readonly>
    </td>

    {{-- Total money --}}


    <td>

    </td>

    <td class="text-center">
        <input type="text" class="form-control total-money" readonly>
    </td>

    <td></td>
</tr>



<div id="loading-overlay">
    <div class="loader"></div>
    <div class="loading-text">{{__('main.milk_loading')}}</div>
</div>

<div id="closing-overlay">
    <div class="loader"></div>
    <div class="loading-text">{{__('main.closing_loading')}}</div>
</div>

<script>
    const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    document.addEventListener('DOMContentLoaded', function () {
        
        

        // ========== ربط الأحداث الأولي ==========
        bindInputEvents();
        
        // ========== تحميل البيانات ==========
        loadMilkMealData($('#wid').val());
        
        // ========== أحداث الأزرار ==========
        $('.postBtn').on('click', function () {
            const row = $(this).closest('tr');
            const supplier_id = $(this).data('supplier');
            const supplier_name = $(this).data('supplier_name');
            
            Swal.fire({
                title: 'ترحيل و إقفال الأسبوع',
                text: `هل انت متأكد من ترحيل و إقفال وجبات الأسبوع الخاصة ب ${supplier_name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم , متأكد',
                cancelButtonText: 'لا , تراجع'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = Number($('#wid').val());
                    if (id > 0) {
                        supplierMealsCaryOver(supplier_id, row);
                    } else {
                        toastr.warning('عفوا لا يوجد وجبات لترحيلها');
                    }
                }
            });
        });
        
        $('.viewBtn').on('click', function () {
            const supplier_id = $(this).data('supplier');
            const wid = $('#wid').val();
            const start = $('#start').val();
            const $row = $(this).closest('tr');
            const $bovineInput = $row.find('input[name="bovine_price[]"]');
            
            if (wid > 0) {
                if ($bovineInput.prop('disabled')) {
                    let url = "{{ route('weakMealDetails', ['id' => '__id__', 'supplier_id' => '__supplier__', 'start' => '__start__']) }}";
                    url = url.replace('__id__', wid);
                    url = url.replace('__supplier__', supplier_id);
                    url = url.replace('__start__', start);
                    document.location.href = url;
                } else {
                    toastr.warning('يجب ترحيل البيانات أولا');
                }
            } else {
                toastr.warning('عفوا لا يوجد بيانات محفوظة');
            }
        });
        
        // ========== إضافة سطر جاموسي يدوياً ==========
        $(document).on('click', '.add-sub-row', function() {
            const $originalRow = $(this).closest('tr');
            const supplierName = $(this).data('supplier-name');
            
            if ($originalRow.next().hasClass('buffalo-row')) {
                toastr.warning('يوجد سطر إضافي بالفعل لهذا المورد');
                return;
            }
            
            const isRTL = $('html').attr('dir') === 'rtl' || $('body').css('direction') === 'rtl';
            const subIcon = isRTL ? 'bx-subdirectory-left' : 'bx-subdirectory-right';
            const marginClass = isRTL ? 'margin-right:15px;' : 'margin-left:15px;';
            
            Swal.fire({
                title: 'إضافة سطر جاموسي؟',
                text: `سيتم دمج الحساب المالي للمورد: ${supplierName}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، أضف',
                cancelButtonText: 'تراجع'
            }).then((result) => {
                if (result.isConfirmed) {
                    const $newRow = $originalRow.clone();
                    $newRow.addClass('buffalo-row').removeAttr('data-car');
                    
                    $originalRow.find('td:has(input[name="total_money[]"]), td:last-child')
                        .attr('rowspan', '2')
                        .css('vertical-align', 'middle');
                    
                    $newRow.find('td:has(input[name="total_money[]"]), td:last-child').remove();
                    
                    $newRow.find('input').each(function() {
                        let name = $(this).attr('name');
                        if (name) {
                            let newName = name.replace('bovine', 'buffalo');
                            $(this).attr('name', newName);
                        }
                        if (name && name.includes('price')) {
                            $(this).attr('data-field', "3");
                        } else if ($(this).attr('data-field') === "0") {
                            $(this).attr('data-field', "1");
                        }
                        if (name && !name.includes('price')) {
                            $(this).val('');
                        } else {
                            const $priceInput = $originalRow.find('input[name="bovine_price[]"]');
                            const buffaloPrice = $priceInput.data('buffalo-price');
                            $(this).val(buffaloPrice || '');
                        }
                        $(this).removeAttr('data-last-value');
                    });
                    
                    const $nameCell = $newRow.find('.supplier-cell-content');
                    $nameCell.html(`
                        <span class="supplier-name" style="${marginClass} color:#6610f2; display: inline-flex; align-items: center; gap: 5px;">
                            <i class='bx ${subIcon}'></i>
                            <span>جاموسي</span>
                        </span>
                        <i class='bx bx-minus-circle text-danger remove-sub-row' style="cursor:pointer; display:none" title="حذف السطر" data-parent-name="${supplierName}"></i>
                    `);
                    
                    $newRow.insertAfter($originalRow);
                    
                    // إعادة ربط الأحداث للسطر الجديد
                    bindInputEvents();
                    
                    calculateTotals();
                    calculateRowTotals();
                    
                    toastr.success('تم إضافة سطر الجاموسي ودمج الحساب');
                }
            });
        });
        
        // ========== حذف سطر جاموسي ==========
        $(document).on('click', '.remove-sub-row', function() {
            const $rowToRemove = $(this).closest('tr');
            const $originalRow = $rowToRemove.prev();
            
            Swal.fire({
                title: 'حذف السطر؟',
                text: 'سيتم حذف جميع وجبات الجاموسي المدخلة لهذا المورد',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذف'
            }).then((result) => {
                if (result.isConfirmed) {
                    $originalRow.find('td').attr('rowspan', '1');
                    $rowToRemove.remove();
                    calculateTotals();
                    calculateRowTotals();
                    toastr.success('تم حذف السطر بنجاح');
                }
            });
        });
    });
    
            // ========== دالة ربط الأحداث المركزية ==========
        function bindInputEvents() {
            // اختيار جميع حقول الإدخال (الأرقام والأسعار)
            const inputs = document.querySelectorAll('input[type="number"], input[name="bovine_price[]"], input[name="buffalo_price[]"]');
            
            // إزالة الأحداث القديمة لمنع التكرار
            inputs.forEach(input => {
                input.removeEventListener('keydown', handleKeyDown);
                input.removeEventListener('blur', handleBlur);
            });
            
            // إضافة الأحداث الجديدة
            inputs.forEach(input => {
                if (!isMobile) {
                    input.addEventListener('keydown', handleKeyDown);
                } else {
                    input.addEventListener('blur', handleBlur);
                }
            });
        }
        
        // ========== دالة معالجة Enter (للديسكتوب فقط) ==========
        function handleKeyDown(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const input = e.target;
                
                // تخطي إذا كان الحقل فارغاً أو معطلاً
                if (!input.value.trim() || input.disabled) {
                    return;
                }
                
                // تنسيق القيمة
                formatInputValue(input);
                
                // حفظ القيمة
                handleSave(input);
                
                // الانتقال إلى الحقل التالي
                moveToNextInput(input);
            }
        }
        
        // ========== دالة معالجة blur (للموبايل فقط) مع تحقق من تغيير القيمة ==========
        function handleBlur(e) {
            const input = e.target;
            
            // تخطي إذا كان الحقل فارغاً أو معطلاً
            if (!input.value.trim() || input.disabled) {
                return;
            }
            
            // تحقق من تغيير القيمة قبل الحفظ
            const lastValue = input.dataset.lastValue || '';
            const currentValue = input.value;
            
            if (lastValue !== currentValue) {
                formatInputValue(input);
                handleSave(input);
            }
        }
        
        // ========== دالة تنسيق القيمة ==========
        function formatInputValue(input) {
            let number = parseFloat(input.value);
            if (!isNaN(number)) {
                if (Number.isInteger(number)) {
                    input.value = number;
                } else {
                    input.value = number.toFixed(2);
                }
            }
        }
        
        // ========== دالة الانتقال إلى الحقل التالي ==========
        function moveToNextInput(currentInput) {
            const currentCell = currentInput.closest('td');
            if (!currentCell) return;
            
            const currentRow = currentCell.closest('tr');
            const cellIndex = Array.from(currentRow.children).indexOf(currentCell);
            const nextRow = currentRow.nextElementSibling;
            
            if (nextRow) {
                const nextCell = nextRow.children[cellIndex];
                if (nextCell) {
                    const nextInput = nextCell.querySelector('input[type="number"], input[name*="price"]');
                    if (nextInput && !nextInput.disabled) {
                        setTimeout(() => {
                            nextInput.focus();
                            nextInput.select();
                        }, 10);
                    }
                }
            }
        }
        
        // ========== دالة الحفظ الرئيسية ==========
function handleSave(input) {
    // حفظ القيمة الحالية للمقارنة المستقبلية
    input.dataset.lastValue = input.value;
    
    const value = input.value;
    const date = input.dataset.date;
    const supplier = input.dataset.supplier;
    const type = input.dataset.type;
    const field = input.dataset.field;
    
    // الحصول على الأسعار (مع دعم السطور الجاموسية)
    let bovinePrice = 0, buffaloPrice = 0;
    const $row = $(input).closest('tr');
    
    if ($row.hasClass('buffalo-row')) {
        // سطر جاموسي - نأخذ السعر من السطر الأصلي
        const $originalRow = $row.prev('.main-row');
        bovinePrice = parseFloat($originalRow.find('input[name="bovine_price[]"]').val()) || 0;
        buffaloPrice = parseFloat($row.find('input[name="buffalo_price[]"]').val()) || 0;
    } else {
        // سطر عادي
        const $bovinePriceInput = $row.find('input[name="bovine_price[]"]');
        bovinePrice = parseFloat($bovinePriceInput.val()) || 0;
        
        // ✅ الطريقة الصحيحة: جلب data-buffalo-price من نفس الحقل في الصف الحالي
        const buffaloPriceText = $bovinePriceInput.data('buffalo-price');
        buffaloPrice = parseFloat(buffaloPriceText) || 0;
        
        // إذا كان هناك سطر جاموسي تابع، استخدم سعره (كبديل)
        const $nextRow = $row.next('.buffalo-row');
        if($nextRow.length > 0) {
            const buffaloPriceFromRow = parseFloat($nextRow.find('input[name="buffalo_price[]"]').val()) || 0;
            if (buffaloPriceFromRow > 0) {
                buffaloPrice = buffaloPriceFromRow;
            }
        }
    }
    
    // للتصحيح: تأكد من القيم
    console.log('Bovine Price:', bovinePrice);
    console.log('Buffalo Price:', buffaloPrice);
    console.log('From data attribute:', $row.find('input[name="bovine_price[]"]').data('buffalo-price'));
    
    postDailyValue(date, value, supplier, field, type, bovinePrice, buffaloPrice, input);
}
    
    // ========== دالة postDailyValue ==========
    function postDailyValue(date, val, supplier, field, type, bovinePrice, buffaloPrice, inputEl) {
        // منع الإرسال إذا كانت القيمة غير صالحة
        if (val === null || val === undefined || val === '') {
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const start = $('#start').val();
        const end = $('#end').val();
        
        const body = {
            value: val,
            field: field,
            date: date,
            supplier: supplier,
            start: start,
            end: end,
            type: type,
            bovinePrice: bovinePrice,
            buffaloPrice: buffaloPrice
        };
        
        const $input = $(inputEl);
        $input.css('background-color', '#fff9c4');
        
        fetch("{{ route('postMeal') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(body)
        })
        .then(response => response.json())
        .then(data => {
            $input.css('background-color', '');
            
            if (data.status === 'warning') {
                toastr.warning(data.message);
                inputEl.value = '';
                inputEl.dataset.lastValue = '';
            } else if (data.status == 'success') {
                toastr.success(data.message);
                $('#wid').val(data.wId);
                calculateRowTotals();
                calculateTotals();
            } else {
                toastr.error(data.message);
                if (data.debug) console.log(data.debug);
                inputEl.value = '';
                inputEl.dataset.lastValue = '';
            }
        })
        .catch(error => {
            $input.css('background-color', '');
            console.error('AJAX error:', error);
            toastr.error('Failed to save value.', 'Error');
        });
    }
    
    // ========== دالة loadMilkMealData ==========
    function loadMilkMealData(weaklyMealId) {
        fetch(`/weakMeals/${weaklyMealId}`)
            .then(response => response.json())
            .then(data => {
                var meals = data.meals;
                var prices = data.prices;
                var totals = data.totals;
                console.log(meals);
                
                meals.forEach(record => {
                    const date = record.date;
                    const type = record.type;
                    const supplierId = record.supplier_id;
                    const state = record.state;
                    const car_id = record.car_id;
                    
                    // إضافة سطر الجاموسي تلقائياً إذا كان هناك وزن جاموسي
                    if (parseFloat(record.buffalo_weight) > 0) {
                        injectBuffaloRow(supplierId);
                    }
                    
                    // تعبئة حقل البقري
                    const bovineSelector = `input[data-date="${date}"][data-type="${type}"][data-field="0"][data-supplier="${supplierId}"]`;
                    const bovineInput = document.querySelector(bovineSelector);
                    if (bovineInput) {
                        const val = formatNumberSmart(record.bovine_weight);
                        bovineInput.value = val;
                        bovineInput.dataset.lastValue = val;
                        
                        if (record.isManufactured == 1) {
                            bovineInput.readOnly = true;
                        } else {
                            bovineInput.readOnly = false;
                        }
                    }
                    
                    // تعبئة حقل الجاموسي
                    const buffaloSelector = `input[data-date="${date}"][data-type="${type}"][data-field="1"][data-supplier="${supplierId}"]`;
                    const buffaloInput = document.querySelector(buffaloSelector);
                    if (buffaloInput) {
                        const val = formatNumberSmart(record.buffalo_weight);
                        buffaloInput.value = val;
                        buffaloInput.dataset.lastValue = val;
                    }
                    
                    // تعبئة سعر البقري
                    const bovinePriceSelector = `input[data-type="3"][data-field="2"][data-supplier="${supplierId}"][name="bovine_price[]"]`;
                    const bovinePriceInp = document.querySelector(bovinePriceSelector);
                    if (bovinePriceInp) {
                        let priceVal = (car_id == 0) ? formatNumberSmart(record.bovine_price) : formatNumberSmart(prices[supplierId]);
                        bovinePriceInp.value = priceVal;
                        bovinePriceInp.dataset.lastValue = priceVal;
                    }
                    
                    // تعبئة سعر الجاموسي
                    const buffaloPriceSelector = `input[data-type="3"][data-field="3"][data-supplier="${supplierId}"][name="buffalo_price[]"]`;
                    const buffaloPriceInp = document.querySelector(buffaloPriceSelector);
                    if (buffaloPriceInp && car_id == 0) {
                        buffaloPriceInp.value = formatNumberSmart(record.buffalo_price);
                        buffaloPriceInp.dataset.lastValue = formatNumberSmart(record.buffalo_price);
                    }
                    
                    // تعبئة إجمالي المبلغ
                    const moneyTotalInputSelector = `input[data-supplier="${supplierId}"][name="total_money[]"]`;
                    const moneyTotalInput = document.querySelector(moneyTotalInputSelector);
                    if (moneyTotalInput && car_id != 0) {
                        moneyTotalInput.value = formatNumberSmart(totals[supplierId]);
                        moneyTotalInput.dataset.lastValue = formatNumberSmart(totals[supplierId]);
                    }
                    
                    // تعطيل الحقول إذا كان الحالة = 1
                    const supplierInputs = document.querySelectorAll(`input[data-supplier="${supplierId}"]`);
                    supplierInputs.forEach(input => {
                        input.disabled = (state === 1);
                    });
                    
                    const postBtn = document.querySelector(`.postBtn[data-supplier="${supplierId}"]`);
                    if (postBtn) {
                        postBtn.style.display = (state === 1) ? 'none' : '';
                    }
                });
                
                bindInputEvents();
                
                calculateRowTotals();
                calculateTotals();
            })
            .catch(err => console.error('Error loading milk meal data:', err));
    }
    
    // ========== دالة injectBuffaloRow لإضافة سطر جاموسي تلقائياً ==========
    function injectBuffaloRow(supplierId) {
        const $originalRow = $(`tr.main-row[data-supplier="${supplierId}"]`);
        
        if ($originalRow.next().hasClass('buffalo-row') || $originalRow.length === 0) {
            return;
        }
        
        const isRTL = $('html').attr('dir') === 'rtl' || $('body').css('direction') === 'rtl';
        const subIcon = isRTL ? 'bx-subdirectory-left' : 'bx-subdirectory-right';
        const marginClass = isRTL ? 'margin-right:15px;' : 'margin-left:15px;';
        
        const $newRow = $originalRow.clone();
        $newRow.addClass('buffalo-row').removeAttr('data-car');
        
        $originalRow.find('td:has(input[name="total_money[]"]), td:last-child')
            .attr('rowspan', '2')
            .css('vertical-align', 'middle');
        
        $newRow.find('td:has(input[name="total_money[]"]), td:last-child').remove();
        
        $newRow.find('input').each(function() {
            let name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace('bovine', 'buffalo'));
            }
            if (name && name.includes('price')) {
                $(this).attr('data-field', "3");
            } else if ($(this).attr('data-field') === "0") {
                $(this).attr('data-field', "1");
            }
            $(this).val('');
            $(this).removeAttr('data-last-value');
        });
        
        const $nameCell = $newRow.find('.supplier-cell-content');
        $nameCell.html(`
            <span class="supplier-name" style="${marginClass} color:#6610f2; display: inline-flex; align-items: center; gap: 5px;">
                <i class='bx ${subIcon}'></i>
                <span>جاموسي</span>
            </span>
            <i class='bx bx-minus-circle text-danger remove-sub-row' style="cursor:pointer; display:none" title="حذف"></i>
        `);
        
        $newRow.insertAfter($originalRow);
        bindInputEvents();

    }
    
    // ========== دالة calculateRowTotals ==========
    function calculateRowTotals() {
        const rows = document.querySelectorAll('table tbody tr');
        
        rows.forEach(row => {
            const inputs = row.querySelectorAll('input');
            const carId = row.getAttribute('data-car');
            
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
            
            let bovinePrice = 0;
            let buffaloPrice = 0;
            
            if (totalBovineInput) totalBovineInput.value = formatNumberSmart(totalBovine);
            if (totalBuffaloInput) totalBuffaloInput.value = formatNumberSmart(totalBuffalo);
            
            if (priceBovineInput) bovinePrice = parseFloat(priceBovineInput.value) || 0;
            if (priceBuffaloInput) buffaloPrice = parseFloat(priceBuffaloInput.value) || 0;
            
            if (carId === "0") {
                if (moneyTotalInput) {
                    moneyTotalInput.value = formatNumberSmart((totalBuffalo * buffaloPrice) + (totalBovine * bovinePrice));
                }
            }
            
            // معالجة سطر الجاموسي
            if (row.classList && row.classList.contains('buffalo-row')) {
                const prevRow = row.previousElementSibling;
                if (prevRow) {
                    const prevMoneyInput = prevRow.querySelector('input[name="total_money[]"]');
                    if (prevMoneyInput) {
                        const currentBuffaloTotal = totalBuffalo * buffaloPrice;
                        const prevMoneyValue = parseFloat(prevMoneyInput.value) || 0;
                        prevMoneyInput.value = formatNumberSmart(prevMoneyValue + currentBuffaloTotal);
                    }
                }
            }
        });
    }
    
    // ========== دالة supplierMealsCaryOver ==========
    function supplierMealsCaryOver(supplier_id, row) {
        const overlay = document.getElementById('closing-overlay');
        overlay.style.display = 'flex';
        
        const minDuration = 3000;
        const startTime = Date.now();
        const wid = $('#wid').val();
        let responseData = null;
        
        fetch(`/supplierMealsCarryOver/${wid}/${supplier_id}`)
            .then(response => response.json())
            .then(data => {
                responseData = data;
                if (data.status === 'warning') {
                    toastr.warning(data.message);
                } else if (data.status !== 'success') {
                    toastr.error(data.message);
                    if (data.debug) console.log(data.debug);
                }
            })
            .catch(err => {
                console.error('Error:', err);
            })
            .finally(() => {
                const elapsed = Date.now() - startTime;
                const remaining = minDuration - elapsed;
                
                setTimeout(() => {
                    overlay.style.display = 'none';
                    const inputs = row.find('input');
                    inputs.prop('disabled', true);
                    
                    const $nextRow = row.next();
                    if ($nextRow.length > 0 && $nextRow.hasClass('buffalo-row')) {
                        $nextRow.find('input').prop('disabled', true);
                    }
                    
                    const postButton = row.find('.postBtn');
                    if (postButton) {
                        postButton.hide();
                    }
                    
                    if (responseData?.status === 'success') {
                        Swal.fire({
                            title: 'تم الترحيل بنجاح',
                            text: 'يمكنك استعراض الوجبات بدون تعديل',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'نعم , افهم ذلك',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const wid = $('#wid').val();
                                const start = $('#start').val();
                                let url = "{{ route('weakMealDetails', ['id' => '__id__', 'supplier_id' => '__supplier__', 'start' => '__start__']) }}";
                                url = url.replace('__id__', wid);
                                url = url.replace('__supplier__', supplier_id);
                                url = url.replace('__start__', start);
                                document.location.href = url;
                            }
                        });
                    }
                }, Math.max(remaining, 0));
            });
    }
    
    // ========== دالة calculateTotals ==========
    function calculateTotals() {
        let dateTotals = {};
        let grandBovine = 0;
        let grandBuffalo = 0;
        let grandMoney = 0;
        
        $('input[data-date]').each(function () {
            let date = $(this).data('date');
            let name = this.name;
            let val = parseFloat(this.value) || 0;
            
            if (!dateTotals[date]) {
                dateTotals[date] = {
                    mbovine: 0,
                    mbuffalo: 0,
                    ebovine: 0,
                    ebuffalo: 0
                };
            }
            
            if (name === "mbovine_weight[]") dateTotals[date].mbovine += val;
            if (name === "mbuffalo_weight[]") dateTotals[date].mbuffalo += val;
            if (name === "ebovine_weight[]") dateTotals[date].ebovine += val;
            if (name === "ebuffalo_weight[]") dateTotals[date].ebuffalo += val;
        });
        
        for (let date in dateTotals) {
            $('.total-mbovine[data-date="'+date+'"]').val(formatNumberSmart(dateTotals[date].mbovine + dateTotals[date].mbuffalo));
            $('.total-ebovine[data-date="'+date+'"]').val(formatNumberSmart(dateTotals[date].ebovine + dateTotals[date].ebuffalo));
        }
        
        $('input[name="total_bovine_weight[]"]').each(function(){
            grandBovine += parseFloat(this.value) || 0;
        });
        $('input[name="total_buffalo_weight[]"]').each(function(){
            grandBuffalo += parseFloat(this.value) || 0;
        });
        $('input[name="total_money[]"]').each(function(){
            grandMoney += parseFloat(this.value) || 0;
        });
        
        $('.total-bovine-weight').val(formatNumberSmart(grandBovine + grandBuffalo));
        $('.total-money').val(formatNumberSmart(grandMoney));
    }
    
    // ========== دالة formatNumberSmart ==========
    function formatNumberSmart(val) {
        let num = parseFloat(val);
        if (isNaN(num)) return '';
        return Number.isInteger(num) ? num : num.toFixed(2);
    }
    
    // ========== تحديث الإجماليات عند الكتابة ==========
    $(document).on("input", "input", function () {
        calculateTotals();
        calculateRowTotals();
    });
</script>
