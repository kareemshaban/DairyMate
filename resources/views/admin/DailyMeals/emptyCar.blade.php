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
@foreach($members as $member)
<tr data-supplier="{{$member -> id}}" class="main-row">
    <td class="text-center" hidden="hidden">{{$loop -> index + 1}}</td>
    <td class="text-center supplier">
        <div class="supplier-cell-content">
            <span class="supplier-name" title="{{$member->name}}">{{$member->name}}</span>
            <i class='bx bx-plus-circle text-success add-sub-row' title="إضافة سطر جاموسي"
                data-supplier-name="{{$member->name}}"></i>

        </div>
        <input type="hidden" value="{{$member->supplier_id}}" name="supplier_id[]" />
    </td>

    @foreach ($period as $date)
    <td class="text-center inp">
        <input type="number" step="any" name="mbovine_weight[]" data-type="0" data-field="0" class="form-control"
            style="color: #6610f2" data-date="{{ $date }}"
            data-supplier="{{ $member->id }}" @if(optional($meal)->state === 1) disabled @endif />
    </td>

    <td class="text-center inp">
        <input type="number" step="any" name="ebovine_weight[]" data-type="1" data-field="0" class="form-control"
            data-date="{{ $date }}" data-supplier="{{ $member -> id }}"
            @if(optional($meal)->state === 1) disabled @endif style="color: #71dd37"/>
    </td>

    @endforeach

    <td class="text-center inp">
        <input type="text" step="any" name="total_bovine_weight[]" class="form-control"
            data-date="{{ $date }}" data-supplier="{{ $member -> id }}"
            readonly @if(optional($meal)->state === 1) disabled @endif/>
    </td>

    <td class="text-center inp">
        <input type="number" step="any" name="bovine_price[]" data-field="2" data-type="3" class="form-control"
            data-date="{{ $date }}" data-supplier="{{ $member -> id }}"
            value="{{$member -> bovine_price}}"  data-buffalo-price="{{$member -> buffalo_price}}"  @if(optional($meal)->state === 1) disabled @endif/>
    </td>


    <td class="text-center col-action">
        <input type="text" step="any" name="total_money[]" class="form-control"
            data-date="{{ $date }}" data-supplier="{{ $member -> id }}"
            readonly @if(optional($meal)->state === 1) disabled @endif/>
    </td>
    <td class="text-center">
        @if($member -> car_id == 0)

        <i class='bx bxs-cloud-upload text-primary postBtn' data-toggle="tooltip" data-placement="top"
            title="{{__('main.post_action')}}" data-supplier="{{$member -> id}}"
            data-supplier_name="{{$member -> name}}" style="font-size: 25px ; cursor: pointer"></i>

        <i class='bx bx-show text-primary viewBtn' data-toggle="tooltip" data-placement="top"
            title="{{__('main.view_action')}}" data-supplier="{{ $member -> id }}"
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



    {{-- total ebovine --}}
    <td class="text-center">
        <input type="text" class="form-control total-ebovine"
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


    <td class="text-center">
        <input type="text" class="form-control avg-price" readonly style="color:#0d6efd;font-weight:bold;">


    </td>

    <td class="text-center">
        <input type="text" class="form-control total-money" readonly>
    </td>

    <td></td>
</tr>
{{-- TOTAL CAR --}}
<tr style="background:#f1f1f1; font-weight:bold;">
    <td class="text-center" style="font-size: 10px ; color: red">إجمالي السيارة</td>

    @foreach ($period as $date)
    {{-- total mbovine --}}
    <td class="text-center">
        <input type="text" class="form-control car-total-mbovine text-primary" data-date="{{ $date}}" readonly>
    </td>

    {{-- total mbuffalo --}}
    <td class="text-center" hidden>
        <input type="text" class="form-control car-total-mbuffalo" data-date="{{ $date}}" readonly>
    </td>

    {{-- total ebovine --}}
    <td class="text-center">
        <input type="text" class="form-control car-total-ebovine" data-date="{{ $date}}" readonly>
    </td>

    {{-- total ebuffalo --}}
    <td class="text-center" hidden>
        <input type="text" class="form-control car-total-ebuffalo" data-date="{{ $date}}" readonly>
    </td>
    @endforeach

    {{-- Total bovine weight --}}
    <td class="text-center">

        <input type="text" class="form-control car-total-bovine-weight" readonly>
    </td>

    {{-- Total buffalo weight --}}
    <td class="text-center" hidden>
        <input type="text" class="form-control car-total-buffalo-weight" readonly>
    </td>

    {{-- Total money --}}


    <td class="text-center">

    </td>

    <td class="text-center">
        <input type="text" class="form-control car-total-money" readonly>
    </td>

    <td></td>
</tr>
{{-- SHORTAGE CAR --}}
<tr style="background:#f1f1f1; font-weight:bold;">
    <td class="text-center" style="font-size: 10px ; color: red">العجز</td>

    @foreach ($period as $date)
    {{-- total mbovine --}}
    <td class="text-center">
        <input type="text" class="form-control car-shortage-mbovine text-primary"
            data-date="{{ $date }}" readonly>
    </td>



    {{-- total ebovine --}}
    <td class="text-center">
        <input type="text" class="form-control car-shortage-ebovine"
            data-date="{{ $date }}" readonly>
    </td>


    @endforeach

    {{-- Total bovine weight --}}
    <td class="text-center">

        <input type="text" class="form-control car-shortage-bovine-weight" readonly>
    </td>

    {{-- Total buffalo weight --}}
    <td class="text-center" hidden>
        <input type="text" class="form-control car-shortage-buffalo-weight" readonly>
    </td>

    {{-- Total money --}}


    <td>

    </td>

    <td class="text-center">
        <input type="text" class="form-control car-shortage-money" readonly>
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
    // ========== المتغيرات العامة ==========
    const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    let lastRequestCache = new Map(); // لتتبع آخر الطلبات ومنع التكرار

    // ========== دالة ربط الأحداث المركزية ==========
    function bindInputEvents() {
        // اختيار جميع حقول الإدخال (الأرقام والأسعار)
        const inputs = document.querySelectorAll('input[type="number"], input[name="bovine_price[]"], input[name="buffalo_price[]"]');
        
        // إزالة الأحداث القديمة لمنع التكرار
        inputs.forEach(input => {
            input.removeEventListener('keydown', handleKeyDown);
            input.removeEventListener('blur', handleBlur);
            input.removeEventListener('change', handleChange);
        });
        
        // إضافة الأحداث الجديدة
        inputs.forEach(input => {
            if (!isMobile) {
                input.addEventListener('keydown', handleKeyDown);
            } else {
                input.addEventListener('blur', handleBlur);
            }
            input.addEventListener('change', handleChange);
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
            
            // التحقق من منع التكرار قبل الحفظ
            if (checkDuplicateRequest(input)) {
                // حفظ القيمة
                handleSave(input);
            }
            
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
            if (checkDuplicateRequest(input)) {
                handleSave(input);
            }
        }
    }
    
    // ========== دالة معالجة change ==========
    function handleChange(e) {
        const input = e.target;
        if (input.value && !input.disabled) {
            input.dataset.lastValue = input.value;
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
    
    // ========== دالة التحقق من التكرار ==========
    function checkDuplicateRequest(input) {
        const key = `${input.dataset.date}_${input.dataset.supplier}_${input.dataset.type}_${input.value}`;
        const now = Date.now();
        const lastRequest = lastRequestCache.get(key);
        
        if (lastRequest && (now - lastRequest) < 2000) { // منع التكرار خلال 2 ثانية
            toastr.warning('تم حفظ هذه القيمة مسبقاً، يرجى الانتظار');
            input.value = input.dataset.lastValue || '';
            return false;
        }
        
        lastRequestCache.set(key, now);
        
        // تنظيف الكاش القديم (أكثر من 10 ثواني)
        setTimeout(() => {
            if (lastRequestCache.get(key) === now) {
                lastRequestCache.delete(key);
            }
        }, 10000);
        
        return true;
    }
    
    
    // ========== دالة الانتقال إلى الحقل التالي (معدلة للتخطي الصحيح) ==========
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
        
        if (!date || !supplier) {
            console.error('Missing required data attributes');
            return;
        }
        
        // الحصول على الأسعار (مع دعم السطور الجاموسية)
        let bovinePrice = 0, buffaloPrice = 0;
        const $row = $(input).closest('tr');
        
        if ($row.hasClass('buffalo-row')) {
            // سطر جاموسي - نأخذ السعر من السطر الأصلي
            const $originalRow = $row.prev('.main-row');
            const $bovinePriceInput = $originalRow.find('input[name="bovine_price[]"]');
            bovinePrice = parseFloat($bovinePriceInput.val()) || 0;
            buffaloPrice = parseFloat($bovinePriceInput.data('buffalo-price')) || 0;
            
            // إذا كان هناك سعر في حقل الجاموسي الخاص به
            const buffaloPriceFromRow = parseFloat($row.find('input[name="buffalo_price[]"]').val()) || 0;
            if (buffaloPriceFromRow > 0) {
                buffaloPrice = buffaloPriceFromRow;
            }
        } else {
            // سطر عادي
            const $bovinePriceInput = $row.find('input[name="bovine_price[]"]');
            bovinePrice = parseFloat($bovinePriceInput.val()) || 0;
            buffaloPrice = parseFloat($bovinePriceInput.data('buffalo-price')) || 0;
            
            // إذا كان هناك سطر جاموسي تابع، استخدم سعره (كبديل)
            const $nextRow = $row.next('.buffalo-row');
            if ($nextRow.length > 0) {
                const buffaloPriceFromRow = parseFloat($nextRow.find('input[name="buffalo_price[]"]').val()) || 0;
                if (buffaloPriceFromRow > 0) {
                    buffaloPrice = buffaloPriceFromRow;
                }
            }
        }
        
        postDailyValue(date, value, supplier, field, type, bovinePrice, buffaloPrice, input);
    }
    
    // ========== دالة postDailyValue ==========
    function postDailyValue(date, val, member, field, type, bovinePrice, buffaloPrice, inputEl) {
        // منع الإرسال إذا كانت القيمة غير صالحة
        if (val === null || val === undefined || val === '') {
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }
        
        const start = $('#start').val();
        const end = $('#end').val();
        const wid = $('#wid').val();
        const sId = $('#supplier_id').val();
        
        const body = {
            value: val,
            field: field,
            date: date,
            member: member,
            supplier: sId,
            start: start,
            end: end,
            type: type,
            bovinePrice: bovinePrice || 0,
            buffaloPrice: buffaloPrice || 0,
            wMeal: wid
        };
        
        const $input = $(inputEl);
        $input.css('background-color', '#fff9c4');
        
        fetch("{{ route('postCarMeal') }}", {
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
                inputEl.value = inputEl.dataset.lastValue || '';
                inputEl.dataset.lastValue = '';
            } else if (data.status === 'success') {
                toastr.success(data.message);
                $('#wid').val(data.wId);
                calculateRowTotals();
                calculateTotals();
                loadCarTotals();
            } else {
                toastr.error(data.message || 'حدث خطأ أثناء الحفظ');
                if (data.debug) console.log(data.debug);
                inputEl.value = inputEl.dataset.lastValue || '';
                inputEl.dataset.lastValue = '';
            }
        })
        .catch(error => {
            $input.css('background-color', '');
            console.error('AJAX error:', error);
            toastr.error('فشل حفظ البيانات، يرجى المحاولة مرة أخرى');
            inputEl.value = inputEl.dataset.lastValue || '';
            inputEl.dataset.lastValue = '';
        });
    }
    
    // ========== دالة loadCarTotals ==========
    function loadCarTotals() {
        const carTotals = @json($standars);
        console.log('carTotals', carTotals);
        let totalBovineWeight = 0;
        let totalMoney = 0;
        
        if (!carTotals || carTotals.length === 0) {
            return;
        }
        
        carTotals.forEach(item => {
            const date = item.date;
            const mWeight = parseFloat(item.sum_of_m_bovine_weight) || 0;
            const eWeight = parseFloat(item.sum_of_e_bovine_weight) || 0;
            const money = parseFloat(item.sum_of_total_price) || 0;
            
            const mInput = document.querySelector(`.car-total-mbovine[data-date="${date}"]`);
            const eInput = document.querySelector(`.car-total-ebovine[data-date="${date}"]`);
            
            if (mInput) mInput.value = mWeight.toFixed(2);
            if (eInput) eInput.value = eWeight.toFixed(2);
            
            totalBovineWeight += (mWeight + eWeight);
            totalMoney += money;
        });
        
        const totalWeightInput = document.querySelector('.car-total-bovine-weight');
        const totalMoneyInput = document.querySelector('.car-total-money');
        
        if (totalWeightInput) totalWeightInput.value = totalBovineWeight.toFixed(2);
        if (totalMoneyInput) totalMoneyInput.value = totalMoney.toFixed(2);
    }
    
    // ========== دالة calculateShortage ==========
    function calculateShortage() {
        let totalShortageWeight = 0;
        
        // حساب العجز لكل تاريخ (وزن)
        document.querySelectorAll('.total-mbovine').forEach(input => {
            const date = input.dataset.date;
            const totalM = parseFloat(input.value) || 0;
            const carM = parseFloat(document.querySelector(`.car-total-mbovine[data-date="${date}"]`)?.value) || 0;
            
            const totalE = parseFloat(document.querySelector(`.total-ebovine[data-date="${date}"]`)?.value) || 0;
            const carE = parseFloat(document.querySelector(`.car-total-ebovine[data-date="${date}"]`)?.value) || 0;
            
            const shortageM =  carM - totalM;
            const shortageE = carE - totalE ;
            
            const shortageMInput = document.querySelector(`.car-shortage-mbovine[data-date="${date}"]`);
            const shortageEInput = document.querySelector(`.car-shortage-ebovine[data-date="${date}"]`);
            
            if (shortageMInput) shortageMInput.value = shortageM.toFixed(2);
            if (shortageEInput) shortageEInput.value = shortageE.toFixed(2);
            
            totalShortageWeight += (shortageM + shortageE);
        });
        
        // تحديث إجمالي العجز في الوزن
        const totalWeightInput = document.querySelector('.car-shortage-bovine-weight');
        if (totalWeightInput) totalWeightInput.value = totalShortageWeight.toFixed(2);
        
        // حساب إجمالي الفلوس الفعلي من جدول الموردين (الإجمالي)
        let actualGrandTotalMoney = 0;
        document.querySelectorAll('input[name="total_money[]"]').forEach(inp => {
            actualGrandTotalMoney += parseFloat(inp.value) || 0;
        });
        
        // جلب إجمالي فلوس السيارة
        const carMoney = parseFloat(document.querySelector('.car-total-money')?.value) || 0;
        
        // المعادلة الصحيحة: (الإجمالي الكلي - إجمالي السيارات)
        const shortageMoney = actualGrandTotalMoney - carMoney;
        
        // وضع القيمة في حقل العجز
        const shortageMoneyInput = document.querySelector('.car-shortage-money');
        if (shortageMoneyInput) {
            shortageMoneyInput.value = shortageMoney.toFixed(2);
            shortageMoneyInput.style.color = shortageMoney > 0 ? "red" : "green";
        }
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
    
    // ========== دالة loadMilkMealData ==========
    function loadMilkMealData(weaklyMealId) {
        const sId = $('#supplier_id').val();
        fetch(`/weakCarMeals/${weaklyMealId}/${sId}`)
            .then(response => response.json())
            .then(data => {
                if (!data || data.length === 0) {
                    return;
                }
                
                data.forEach(record => {
                    const date = record.date;
                    const type = record.type;
                    const supplierId = record.member_id;
                    const state = record.state;
                    
                    // إضافة سطر الجاموسي تلقائياً إذا كان هناك وزن جاموسي
                    if (parseFloat(record.weight_b) > 0) {
                        injectBuffaloRow(supplierId);
                    }
                    
                    // تعبئة حقل البقري
                    const bovineSelector = `input[data-date="${date}"][data-type="${type}"][data-field="0"][data-supplier="${supplierId}"]`;
                    const bovineInput = document.querySelector(bovineSelector);
                    if (bovineInput) {
                        const val = formatNumberSmart(record.weight);
                        bovineInput.value = val;
                        bovineInput.dataset.lastValue = val;
                    }
                    
                    // تعبئة حقل الجاموسي
                    const buffaloSelector = `input[data-date="${date}"][data-type="${type}"][data-field="1"][data-supplier="${supplierId}"]`;
                    const buffaloInput = document.querySelector(buffaloSelector);
                    if (buffaloInput) {
                        const val = formatNumberSmart(record.weight_b);
                        buffaloInput.value = val;
                        buffaloInput.dataset.lastValue = val;
                    }
                    
                    // تعبئة إجمالي الوزن
                    const bovineTotalSelector = `input[data-date="${date}"][data-supplier="${supplierId}"][name="total_bovine_weight[]"]`;
                    const bovineTotalInput = document.querySelector(bovineTotalSelector);
                    if (bovineTotalInput) {
                        const val = formatNumberSmart(record.weight + record.weight_b);
                        bovineTotalInput.value = val;
                        bovineTotalInput.dataset.lastValue = val;
                    }
                    
                    // تعبئة سعر البقري
                    const bovinePriceSelector = `input[data-type="3"][data-field="2"][data-supplier="${supplierId}"][name="bovine_price[]"]`;
                    const bovinePriceInp = document.querySelector(bovinePriceSelector);
                    if (bovinePriceInp) {
                        const val = formatNumberSmart(record.price);
                        bovinePriceInp.value = val;
                        bovinePriceInp.dataset.lastValue = val;
                    }
                    
                    // تعبئة سعر الجاموسي
                    const buffloPriceSelector = `input[data-type="3"][data-field="3"][data-supplier="${supplierId}"][name="buffalo_price[]"]`;
                    const buffloPriceInp = document.querySelector(buffloPriceSelector);
                    if (buffloPriceInp) {
                        const val = formatNumberSmart(record.price_b);
                        buffloPriceInp.value = val;
                        buffloPriceInp.dataset.lastValue = val;
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
    
    // ========== دالة calculateRowTotals ==========
    function calculateRowTotals() {
        const rows = document.querySelectorAll('table tbody tr');
        
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
            
            let bovinePrice = 0;
            let buffaloPrice = 0;
            
            if (totalBovineInput) totalBovineInput.value = formatNumberSmart(totalBovine);
            if (totalBuffaloInput) totalBuffaloInput.value = formatNumberSmart(totalBuffalo);
            
            if (priceBovineInput) bovinePrice = parseFloat(priceBovineInput.value) || 0;
            if (priceBuffaloInput) buffaloPrice = parseFloat(priceBuffaloInput.value) || 0;
            
            if (!row.classList.contains('buffalo-row')) {
                if (moneyTotalInput) {
                    moneyTotalInput.value = formatNumberSmart((totalBuffalo * buffaloPrice) + (totalBovine * bovinePrice));
                }
            } else {
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
        
        fetch(`/supplierCarMealsCarryOver/${wid}/${supplier_id}`)
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
                                let url = "{{ route('weakCarMealDetails', ['id' => '__id__', 'supplier_id' => '__supplier__', 'start' => '__start__']) }}";
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
            $('.total-mbuffalo[data-date="'+date+'"]').val(formatNumberSmart(dateTotals[date].mbuffalo));
            $('.total-ebovine[data-date="'+date+'"]').val(formatNumberSmart(dateTotals[date].ebovine + dateTotals[date].ebuffalo));
            $('.total-ebuffalo[data-date="'+date+'"]').val(formatNumberSmart(dateTotals[date].ebuffalo));
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
        $('.total-buffalo-weight').val(formatNumberSmart(grandBuffalo));
        $('.total-money').val(formatNumberSmart(grandMoney));
        
        let avgPrice = 0;
        if ((grandBovine + grandBuffalo) > 0) {
            avgPrice = grandMoney / (grandBovine + grandBuffalo);
        }
        
        const avgPriceInput = document.querySelector('.avg-price');
        if (avgPriceInput) {
            avgPriceInput.value = formatNumberSmart(avgPrice);
        }
        
        calculateShortage();
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
    
    // ========== تشغيل التطبيق ==========
    document.addEventListener('DOMContentLoaded', function () {
        // ربط الأحداث الأولي
        bindInputEvents();
        
        // إضافة سطر جاموسي يدوياً
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
                        <i class='bx bx-minus-circle text-danger remove-sub-row'
                           style="cursor:pointer; display:none"
                           title="حذف السطر"
                           data-parent-name="${supplierName}"></i>
                    `);
                    
                    $newRow.insertAfter($originalRow);
                    bindInputEvents();
                    calculateTotals();
                    calculateRowTotals();
                    toastr.success('تم إضافة سطر الجاموسي ودمج الحساب');
                }
            });
        });
        
        // حذف سطر جاموسي
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
        
        // تحميل البيانات
        loadMilkMealData($('#wid').val());
        loadCarTotals();
        
        // أحداث الأزرار
        $('.postBtn').on('click', function () {
            const row = $(this).closest('tr');
            const supplier_id = $(this).data('supplier');
            const supplier_name = $(this).data('supplier_name');
            
            Swal.fire({
                title: 'ترحيل و إقفال الوجبة',
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
                    let url = "{{ route('weakCarMealDetails', ['id' => '__id__', 'supplier_id' => '__supplier__', 'start' => '__start__']) }}";
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
    });
</script>
