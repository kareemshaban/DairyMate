
<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 8 , 'subSlag' => 82])
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('layouts.nav')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <form class="center" method="POST" action="{{ route('sales_store') }}"
                      enctype="multipart/form-data" id="MealForm">
                    @csrf

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div style="display: flex ; justify-content: space-between ; align-items: center">
                            <h4 class="fw-bold py-3 mb-4">
                                <span class="text-muted fw-light">{{__('main.accounting_department')}} /</span> {{__('main.add_sales')}}
                            </h4>

                            <div style="display: flex ; gap: 10px; align-items: end; ">
                                <div class="form-group" style="display: flex ; flex-direction: column; justify-content: center; align-items: center;">
                                    <label>{{ __('main.isPost') }}</label>
                                    <input type="checkbox" id="isPost" name="isPost" class="form-check" style="width: 35px ; height: 35px;"/>

                                </div>

                                <button type="button" class="btn btn-primary" id="createButton" style="height: 45px"
                                        onclick="valdiateRequest()">
                                    {{__('main.save_btn')}}  <span class="tf-icons bx bx-save"></span>&nbsp;
                                </button>

                            </div>



                        </div>



                        <!-- Responsive Table -->
                        <div class="card">
                            <h5 class="card-header">{{__('main.add_sales')}}</h5>
                            @include('flash-message')
                            <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">


                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.docNumber') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="bill_number" id="bill_number"
                                                   class="form-control @error('bill_number') is-invalid @enderror"
                                                   placeholder="{{ __('main.docNumber') }}" autofocus required readonly/>
                                            @error('bill_number')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.date') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text"  name="date" id="date"
                                                   class="form-control date @error('date') is-invalid @enderror"
                                                   autofocus required/>
                                            @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.store') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="store_id" id="store_id" @if(Config::get('app.locale')=='ar' )
                                                dir="rtl" @endif
                                                     class="form-control search @error('store_id') is-invalid @enderror"  required>
                                                <option value="" > {{__('main.select')}} </option>
                                                @foreach($stores as $store)
                                                    <option value="{{$store -> id}}" @if($store -> isDefault == 1) selected @endif> {{$store -> name  }} </option>

                                                @endforeach

                                            </select>

                                            @error('store_id')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.client') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="client_id" id="client_id" @if(Config::get('app.locale')=='ar' )
                                                dir="rtl" @endif class="form-control search @error('client_id') is-invalid @enderror"  required>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($clients as $client)
                                                    <option value="{{$client -> id}}"> {{$client -> name  }} </option>

                                                @endforeach

                                            </select>

                                            @error('client_id')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.notes') }} </label>
                                            <textarea type="text" name="notes" id="notes"
                                                      class="form-control @error('notes') is-invalid @enderror"
                                                      placeholder="{{__('main.notes')}}" autofocus rows="2"> </textarea>
                                            @error('notes')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror

                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div style="    display: flex; justify-content: space-between;align-items: center;border-top: solid 3px #eee; margin: 15px auto;border-bottom: solid 3px #eee;">
                                        <h5 class="card-header">{{__('main.items')}}</h5>
                                        <i class='bx bxs-plus-circle text-primary' id="itemsBtn" data-toggle="tooltip" data-placement="top" title="{{__('main.add_item_action')}}"
                                           style="font-size: 30px ; cursor: pointer">

                                        </i>
                                    </div>

                                    <div class="table-responsive  text-nowrap">
                                        <table class="table table-striped table-hover view_table">
                                            <thead>
                                            <tr class="text-nowrap">
                                                <th class="text-center" hidden="hidden">Details_id</th>
                                                <th class="text-center" hidden="hidden">Item_id</th>
                                                <th class="text-center"> {{__('main.item')}}</th>
                                                <th class="text-center" hidden="hidden"> {{__('main.availableQnt')}}</th>
                                                <th class="text-center"> {{__('main.quantity')}}</th>
                                                <th class="text-center"> {{__('main.weight')}}</th>
                                                <th class="text-center"> {{__('main.price')}}</th>
                                                <th class="text-center"> {{__('main.total')}}</th>
                                                <th class="text-center"> {{__('main.actions')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="bond-details">




                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div style="  border-top: solid 3px #eee; margin: 15px auto;border-bottom: solid 3px #eee;">
                                    <h5 class="card-header">{{__('main.totals')}}</h5>

                                </div>
                                <div class="row" >
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('main.total') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="billTotal" id="billTotal"
                                                   class="form-control @error('billTotal') is-invalid @enderror"
                                                   placeholder="{{ __('main.total') }}" autofocus required readonly/>
                                            @error('billTotal')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('main.discount') }} </label>
                                            <input type="number" step="any" name="discount" id="discount"
                                                   class="form-control @error('discount') is-invalid @enderror"
                                                   placeholder="{{ __('main.discount') }}" autofocus />
                                            @error('discount')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('main.net') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="net" id="net"
                                                   class="form-control @error('net') is-invalid @enderror"
                                                   placeholder="{{ __('main.net') }}" autofocus required readonly/>
                                            @error('net')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                </div>




                            </div>

                        </div>
                        <!--/ Responsive Table -->
                    </div>

                </form>
                <!-- / Content -->
                @include('admin.Sales.items')
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

@include('admin.Sales.alertModal')
@include('admin.Sales.deleteItemModal')
@include('layouts.footer')

<script type="text/javascript">
    var items = [] ;
    var deleted_index = 0 ;
    $(document).ready(function() {

        $(document).on('click', '#itemsBtn', function(event) {


            let store = $('#store_id').val();

            if(store != "") {

                $.ajax({
                    url: `/get_store_items/${store}`, // adjust the base path if needed
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {


                        event.preventDefault();
                        let href = $(this).attr('data-attr');
                        $.ajax({
                            url: href,
                            beforeSend: function () {
                                $('#loader').show();
                            },
                            // return the result
                            success: function (result) {
                                $('#itemsModal').modal("show");
                                $('#mstore_id').val(store);
                                $('#items-body').empty();
                                response.forEach(function(item, index) {
                                    let row = `
    <tr>
       <td class="text-center" hidden="hidden">${item.cheese_meal_code  || ''} -- ${item.symbol}</td>
        <td class="text-center">${item.code || ''}</td>

        <td class="text-center">${item.name || ''}</td>
        <td class="text-center">${item.balance ?? 0}</td>
        <td class="text-center">
            <button
                class="btn btn-sm btn-primary selectBtn"
                data-id="${item.id}"
                data-store="${store}"
                data-meal="${item.meal_id}"
>
                إختيار
            </button>
        </td>
    </tr>
`;
                                    $('#items-body').append(row);
                                });


                            },
                            complete: function () {
                                $('#loader').hide();
                            },
                            error: function (jqXHR, testStatus, error) {
                                console.log(error);
                                alert("Page " + href + " cannot open. Error:" + error);
                                $('#loader').hide();
                            },
                            timeout: 8000
                        })


                    },
                    error: function(xhr, status, error) {
                        // ❌ Handle errors
                        console.error('AJAX error:', error);
                        toastr.error('فشل في جلب العناصر');
                    }
                })


            }
            else {
                var translatedText = "{{ __('main.select_store_first') }}";
                console.log(translatedText );
                toastr.warning(translatedText);
            }
        });



        $(document).on('click' , '.selectBtn' , function (event){
            const id = $(this).data('id');
            const store = $(this).data('store');
            const meal = $(this).data('meal');
            $(".modal-body #id").val(id);
            $(".modal-body #selected_store_id").val(store);
            $(".modal-body #selected_meal_id").val(meal);

            $('#itemsModal').modal("hide");
        } );

        $('#itemsModal').on('hidden.bs.modal', function () {
            var id =   $(".modal-body #id").val();
            var store =   $(".modal-body #selected_store_id").val();
            var meal =   $(".modal-body #selected_meal_id").val();
            if(id > 0){

                if(items.filter(c=> c.item_id == id && c.item_store_id == store).length >  0){
                    showalert( "هذا المنتج تم إضافته من قبل إلي الفاتورة يمكنك زيادة كميته إذا أردت" , 1);
                    $(".modal-body #id").val(0);
                    $(".modal-body #selected_store_id").val(0);
                    $(".modal-body #selected_meal_id").val(0);
                    $(".modal-body #selected_meal_id").val(0);
                    return ;

                }
                // Ensure all existing items have the same cheese_meal_id as the selected meal
                // var allSameMeal = items.every(c => c.item_store_id == store);
                // if (!allSameMeal) {
                //     showalert(  " جميع الأصناف يجب أن تكون من نفس المخزن",1 );
                //     $(".modal-body #id").val(0);
                //     $(".modal-body #selected_store_id").val(0);
                //     return;
                // }

                addItemToTable(id , meal , store);


            } else {
                console.log('no item selected');
            }
            $(".modal-body #id").val(0);
            $(".modal-body #selected_store_id").val(0);
            $(".modal-body #selected_meal_id").val(0);

        });


        $.ajax({
            type:'get',
            url:'/sales_code',
            dataType: 'json',

            success:function(code){

                $("#bill_number").val(code);
            }
        });

    });

    function addItemToTable(id , meal , store) {
        $.ajax({
            type:'get',
            url:'item-select-W-M' + '/' + id + '/' + store ,
            dataType: 'json',
            success:function(response){
                console.log(response);
                var item = {
                    'details_id': 0 ,
                    'item_id': response['id'],
                    'code': response['code'],
                    'name': response['name'],
                    'available_quantity': response['available_quantity'],
                    'quantity': 0 ,
                    'weight': 0 ,
                    'price': response['default_selling_price'] ,
                    'total': 0,
                    'item_store_id':response['item_store_id'],
                    'meal_id':response['item_meal_id'],
                    'store_name':response['store_name'],
                }
                items.push(item);

                if(response){
                    setItems();
                }

            }
        });
    }

    function setItems(){

        var body = document.getElementById('bond-details');
        var html = '' ;
        var translatedText = "{{ __('main.delete_action') }}";
        let index = 0 ;
        let total = 0 ;

        for (let i = 0; i < items.length; i++) {

            index = i + 1;

            html += '<tr data-item-index="' + i + '">\
            <td class="text-center" hidden="hidden"> \
                <input type="hidden" class="form-control" name="details_id[]" value="' + items[i]['details_id'] + '" /> \
            </td>\
            <td class="text-center" hidden="hidden"> \
                <input type="hidden" class="form-control" name="item_id[]" value="' + items[i]['item_id'] + '" /> \
                <input type="hidden" class="form-control" name="item_store_id[]" value="' + items[i]['item_store_id'] + '" /> \
                <input type="hidden" class="form-control" name="meal_id[]" value="' + items[i]['meal_id'] + '" /> \
            </td>\
            <td class="text-center"> \
                <div class="fw-bold">' + items[i]['code'] + ' -- ' + items[i]['name'] + '</div> \
                <small class="text-muted">' + items[i]['store_name'] + '</small> \
            </td>\
            <td class="text-center" hidden="hidden"> \
                <input type="number" step="any" readonly class="form-control" name="available_quantity[]" value="' + items[i]['available_quantity'] + '" /> \
            </td>\
            <td class="text-center"> \
                <input type="number" step="any" class="form-control quantity" \
                    name="quantity[]" \
                    id="quantity_' + i + '" \
                    min="1" \
                    max="' + items[i]['available_quantity'] + '" \
                    value="' + items[i]['quantity'] + '" /> \
            </td>\
            <td class="text-center"> \
                <input type="number" step="any" class="form-control weight" \
                    name="weight[]" \
                    id="weight_' + i + '" \
                    value="' + items[i]['weight'] + '" /> \
            </td>\
            <td class="text-center"> \
                <input type="number" step="any" class="form-control price" \
                    name="price[]" \
                    id="price_' + i + '" \
                    value="' + items[i]['price'] + '" /> \
            </td>\
            <td class="text-center"> \
                <input type="number" step="any" class="form-control total" \
                    readonly \
                    name="total[]" \
                    id="total_' + i + '" \
                    value="' + items[i]['total'] + '" /> \
            </td>\
            <td class="text-center"> \
                <i class="bx bxs-trash text-danger deleteBtn" \
                    style="font-size: 25px; cursor: pointer" \
                    data-toggle="tooltip" \
                    data-placement="top" \
                    title="' + translatedText + '" \
                    data-id="' + i + '"></i> \
            </td>\
        </tr>';

            total += Number(items[i]['total']);
        }

        body.innerHTML = html;

        $('#billTotal').val(total);
        $('#discount').val(0);
        $('#net').val(total);
    }

    function valdiateRequest(){
        var msg = '' ;
        if($('#bill_number').val() == ""){
            msg =  'حقل رقم الفاتورة مطلوب' ;
            toastr.warning(msg);
            return;
        }

        if($('#store_id').val() == "") {
            msg = 'حقل المخزن مطلوب ';
            toastr.warning(msg);
            return;
        }
        if($('#client_id').val() == "") {
            msg = 'حقل العميل مطلوب' ;
            toastr.warning(msg);
            return;
        }
        if( items.length ==  0){
            msg =  'يجب إضافة أصناف إلي تفاصيل الصنف'  ;
            toastr.warning(msg);
            return;
        }


        const allQuantitiesValid = items.every(item => item.quantity && parseFloat(item.quantity) > 0);

        if( !allQuantitiesValid){
            msg =  'يجب تحديد الكمية المباعة من كل الأصناف ويجب ان تكون اكبر من 0';
            toastr.warning(msg);
            return;
        }

        $('#MealForm').submit();

    }
    function showalert(msg){

        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {

                $('#confirmModal').modal("show");
                $(" #msg").html( msg.replace(/\n/g, "<br>") );
                $(".modal-body #post").hide();
                $(".modal-body #agree_btn").show();
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function(jqXHR, testStatus, error) {
                console.log(error);
                alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            },
            timeout: 8000
        })

    }
    $(document).on('click', '.submit-btn', function(event) {
        $('#confirmtModal').modal("hide");
    });

    $(document).on('keyup change', 'input[name="quantity[]"]', function () {
        const $quantityInput = $(this);
        let enteredValue = parseFloat($quantityInput.val()) || 0;

        // Get the corresponding table row
        const $row = $quantityInput.closest('tr');
        const maxAvailable = parseFloat($row.find('input[name="available_quantity[]"]').val());
        const index = $row.data('item-index'); // Ensure <tr data-item-index="...">

        // Validation: not more than available
        if (enteredValue > maxAvailable) {
            enteredValue = maxAvailable;
            $quantityInput.val(maxAvailable);
            showalert('عفوا لا يمكن ان تتخطي الكمية المباعة الكمية المتاحة في المخزن');
        }

        // Validation: not less than 1
        if (enteredValue < 1) {
            enteredValue = 1;
            $quantityInput.val(1);
        }

        // ✅ Update the items array
        if (typeof items[index] !== 'undefined') {
            items[index]['quantity'] = enteredValue;

            // Get the unit price


            console.log(`Item ${index} quantity updated to:`, enteredValue);

        } else {
            console.warn('Invalid index for quantity update:', index);
        }


    });



    $(document).on('keyup change', 'input[name="weight[]"]', function () {
        const $quantityInput = $(this);
        let enteredValue = parseFloat($quantityInput.val()) || 0;

        // Get the corresponding table row
        const $row = $quantityInput.closest('tr');

        const index = $row.data('item-index'); // Make sure data-item-index is set in your <tr>

        // ✅ Update the items array
        if (typeof items[index] !== 'undefined') {
            items[index]['weight'] = enteredValue;

            const price = parseFloat($row.find('input[name="price[]"]').val()) || 0;

            // Calculate total
            const total = enteredValue * price;
            items[index]['total'] = total;

            // Set total value in the input or span
            $row.find('input[name="total[]"]').val(total.toFixed(2)); // or use .text() if it's not an input


            console.log(`Item ${index} weight updated to:`, enteredValue);
        } else {
            console.warn('Invalid index for weight update:', index);
        }
        calcTotals();
    });

    $(document).on('keyup change', 'input[name="price[]"]', function () {
        const $quantityInput = $(this);
        let enteredValue = parseFloat($quantityInput.val()) || 0;

        // Get the corresponding table row
        const $row = $quantityInput.closest('tr');

        const index = $row.data('item-index'); // Make sure data-item-index is set in your <tr>

        // ✅ Update the items array
        if (typeof items[index] !== 'undefined') {
            items[index]['price'] = enteredValue;
            console.log(`Item ${index} price updated to:`, enteredValue);

            const weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;

            const total = enteredValue * weight;
            items[index]['total'] = total;
            $row.find('input[name="total[]"]').val(total.toFixed(2));



        } else {
            console.warn('Invalid index for price update:', index);
        }

        calcTotals();
    });


    $(document).on('click','.deleteBtn',function () {
        deleted_index = $(this).data('id')
        console.log(deleted_index);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {
                $('#deleteItemModal').modal("show");
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function(jqXHR, testStatus, error) {
                console.log(error);
                alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            },
            timeout: 8000
        })

    });
    $(document).on('click', '.btnConfirmDelete', function(event) {
        confirmDelete();
    });
    $(document).on('click', '.cancel-modal', function(event) {
        $('#deleteItemModal').modal("hide");
        deleted_index = 0 ;
    });
    function confirmDelete(){
        console.log('clicked' , deleted_index);
        items.splice(deleted_index , 1);
        $('#deleteItemModal').modal("hide");
        setItems();
    }

    $(document).on('click', '.agree_btn', function(event) {
        $('#confirmModal').modal("hide");
    });

    function calcTotals(){
        let totalSum = 0;

        $('input[name="total[]"]').each(function () {
            const value = parseFloat($(this).val()) || 0;
            totalSum += value;
        });

        $('#billTotal').val(totalSum);
        var discount = Number($('#discount').val());

        $('#net').val(totalSum - discount);
    }

    $(document).on('change keyup', '#discount', function () {
        // Get bill total and discount
        let billTotal = parseFloat($('#billTotal').val()) || 0;
        let discount = parseFloat($(this).val()) || 0;

        // Calculate net
        let net = billTotal - discount;
        if (net < 0) net = 0; // Optional: prevent negative net

        // Set net value
        $('#net').val(net.toFixed(2)); // If it's an input
        // Or use: $('#netTotal').text(net.toFixed(2)); // if it's a <span>
    });


    function getMealItems(meal_id){
        let store_id = $('#store_id').val();
        $.ajax({
            type:'get',
            url:'/getMealItems' + '/' + meal_id + '/' + store_id,
            dataType: 'json',

            success:function(items){
                var body = document.getElementById('items-body');
                var html = '' ;

                for (let i = 0; i < items.length; i++) {
                    index = i + 1;
                    html += '<tr data-item-index="' + i + '">\
                <td class="text-center" hidden="hidden">\
                 <input type="hidden" class="form-control" name="item_id[]" value="' + items[i]['id'] + '" /> \
                 <input type="number" step="any" readonly class="form-control" name="balance[]" value="' + items[i]['balance'] + '" /> \
                 <input type="number" step="any" readonly class="form-control" name="item_store_id[]" value="' + items[i]['item_store_id'] + '" />\
                 <input type="number" step="any" readonly class="form-control" name="cheese_meal_id[]" value="' + items[i]['cheese_meal_id'] + '" />\
                </td>\
                <td class="text-center">' + items[i]['code'] + '</td>\
                <td class="text-center">' + items[i]['name'] + '</td>\
                <td class="text-center">' + items[i]['balance'] + '</td>\
                <td class="text-center"> <i class="bx bxs-pointer text-primary selectBtn" style="font-size: 25px; cursor: pointer" data-toggle="tooltip" data-placement="top" title="" data-id="' + items[i]['id'] + '"  data-meal="' + items[i]['cheese_meal_id'] + '"></i> </td>\
            </tr>';

                }
                body.innerHTML = html ;
            }
        });

    }


    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('.view_table');

        table.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                const inputs = table.querySelectorAll('input:not([type="hidden"]):not([readonly])');
                const currentIndex = Array.from(inputs).indexOf(e.target);

                if (currentIndex > -1 && currentIndex < inputs.length - 1) {
                    e.preventDefault(); // prevent form submit
                    inputs[currentIndex + 1].focus();
                }
            }
        });
    });
</script>
</body>
</html>
