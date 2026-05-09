<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 9 , 'subSlag' => 92])
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
                            <span class="text-muted fw-light">{{__('main.accounting_department')}} /</span> {{__('main.stock_exchange_edit')}}
                        </h4>

                        <button type="button" class="btn btn-primary" id="createButton" style="height: 45px"
                                onclick="valdiateRequest()">
                            {{__('main.save_btn')}}  <span class="tf-icons bx bx-save"></span>&nbsp;
                        </button>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.stock_exchange_edit')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">
                            <form class="center" method="POST" action="{{ route('stock_exchange_update') }}"
                                  enctype="multipart/form-data" id="MealForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.docNumber') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="bill_number" id="bill_number"
                                                   class="form-control @error('bill_number') is-invalid @enderror"
                                                   placeholder="{{ __('main.docNumber') }}" autofocus required readonly
                                            value="{{$doc -> bill_number}}"/>
                                            @error('bill_number')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          <input type="hidden" id="id" name="id" value="{{$doc -> id}}" />
                                        </div>
                                    </div>
                                    
                                        <div class="col-md-3 col-lg-3 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.message_code') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="message_code" id="message_code"
                                                   class="form-control @error('message_code') is-invalid @enderror"
                                                   placeholder="{{ __('main.message_code') }}" autofocus required readonly
                                            value="{{$doc -> message_code}}"/>
                                            @error('message_code')
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
                                                   autofocus required value="{{$doc -> date}}"/>

                                            <input type="hidden" id="setDate" value="{{$doc -> date}}" >
                                            @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.from_store') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="from_store" id="from_store" @if(Config::get('app.locale')=='ar' )
                                                dir="rtl" @endif
                                                     class="form-control search @error('from_store') is-invalid @enderror"  required>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($stores as $store)
                                                    <option value="{{$store -> id}}" @if($store -> id == $doc -> from_store) selected @endif> {{$store -> name  }} </option>

                                                @endforeach

                                            </select>

                                            @error('from_store')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.to_store') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="to_store" id="to_store" @if(Config::get('app.locale')=='ar' )
                                                dir="rtl" @endif
                                                     class="form-control search @error('to_store') is-invalid @enderror"  required>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($stores as $store)
                                                    <option value="{{$store -> id}}" @if($store -> id == $doc -> to_store) selected @endif> {{$store -> name  }} </option>

                                                @endforeach

                                            </select>

                                            @error('to_store')
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
                                                      placeholder="{{__('main.notes')}}" autofocus rows="2"> {{$doc -> notes}} </textarea>
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
                                                <th class="text-center"> {{__('main.quantity_to_post')}}</th>
                                                <th class="text-center"> {{__('main.weight')}}</th>.
                                                <th class="text-center"> {{__('main.actions')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="bond-details">
                                            @foreach($details as  $item)
                                            <tr data-item-index="{{$loop -> index}}">
                                                <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="details_id[]" value="{{$item -> details_id}}" /> </td>
                                                <td class="text-center" hidden="hidden">

                                                    <input type="hidden" class="form-control" name="item_id[]" value="{{$item -> id}}" />
                                                    <input type="hidden" class="form-control" name="item_store_id[]" value="{{$item -> store_id}}" />

                                                </td>
                                                <td class="text-center">{{$item -> code}} -- {{$item -> name}}</td>

                                                <td class="text-center" hidden="hidden"> <input type="number" step="any" class="form-control" name="available_quantity[]"   value="{{$item -> available_quantity}}" /> </td>


                                                <td class="text-center"> <input type="number" step="any" class="form-control quantity" name="quantity[]" id="{{"quantity_" . $loop -> index}}" min="1"  value="{{$item -> quantity}}" /> </td>
                                                <td class="text-center"> <input type="number" step="any" class="form-control weight" name="weight[]" id="{{"weight_" . $loop -> index}}" value="{{$item -> weight}}" /> </td>
                                                <td class="text-center"> <i class="bx bxs-trash text-danger deleteBtn" style="font-size: 25px; cursor: pointer" data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}" data-id="' + i + '"></i> </td>
                                            </tr>
                                            @endforeach


                                            </tbody>
                                        </table>
                                    </div>

                                </div>



                            </form>

                        </div>

                    </div>
                    <!--/ Responsive Table -->
                </div>
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

@include('admin.StockTransaction.alertModal')
@include('admin.StockTransaction.deleteModal')
@include('admin.Sales.deleteItemModal')
@include('layouts.footer')

<script type="text/javascript">
    var items = [] ;
    var deleted_index = 0 ;
    $(document).ready(function() {
        const datePicker = flatpickr(".date", {
            dateFormat: "d-m-Y",
            defaultDate: new Date() // shows today's date initially
        });

        var dateStr = $('#setDate').val();
        var dateOnly = dateStr.split(" ")[0];

        console.log(dateOnly);
        var parts = dateOnly.split('-');    // ["12", "07", "2025"]

        console.log(parts);

// Rearranged to "2025-07-12"
        var formatted = `${parts[2]}-${parts[1]}-${parts[0]}`;

        console.log(formatted);

        datePicker.setDate(formatted);

        items = @json($details);
        console.log(items);
        $(document).on('click', '#itemsBtn', function(event) {

            let from_store = $('#from_store').val();
            let to_store = $('#to_store').val();

            console.log(from_store);
            console.log(to_store);
            if(from_store != "" && to_store != "") {
                if(from_store != to_store){

                    $.ajax({
                        url: `/get_store_items/${from_store}`, // adjust the base path if needed
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
                                    $('#mstore_id').val(from_store);

                                    $('#items-body').empty();

                                    response.forEach(function(item, index) {
                                        let row = `
    <tr>
        <td class="text-center" hidden>${index + 1}</td>
        <td class="text-center">${item.code || ''}</td>
        <td class="text-center">${item.name || ''}</td>
        <td class="text-center">${item.balance ?? 0}</td>
        <td class="text-center">
            <button
                class="btn btn-sm btn-primary selectBtn"
                data-id="${item.id}"
                data-store="${from_store}">
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

                } else {
                    var translatedText1 = "{{ __('main.select_different_stores') }}";
                    toastr.warning(translatedText1);
                }


            } else {
                var translatedText = "{{ __('main.select_stores_first') }}";
                toastr.warning(translatedText);
            }
        });

        $(document).on('click', '.agree_btn', function(event) {
            $('#confirmModal').modal("hide");
        });

        $('#itemsModal').on('hidden.bs.modal', function () {
            var id =   $(".modal-body #id").val();
            var store =   $(".modal-body #selected_store_id").val();
            if(id > 0){

                if(items.filter(c=> c.item_id == id).length >  0){
                    showalert( "هذا المنتج تم إضافته من قبل إلي الفاتورة يمكنك زيادة كميته إذا أردت" , 1);
                    $(".modal-body #id").val(0);
                    $(".modal-body #selected_store_id").val(0);

                    return ;

                }
                // Ensure all existing items have the same cheese_meal_id as the selected meal
                var allSameMeal = items.every(c => c.item_store_id == store);
                if (!allSameMeal) {
                    showalert(  " جميع الأصناف يجب أن تكون من نفس المخزن",1 );
                    $(".modal-body #id").val(0);
                    $(".modal-body #selected_store_id").val(0);
                    return;
                }

                addItemToTable(id);


            } else {
                console.log('no item selected');
            }

            $(".modal-body #id").val(0);
            $(".modal-body #selected_store_id").val(0);

        });


        $(document).on('click' , '.selectBtn' , function (event){
            const id = $(this).data('id');
            const store = $(this).data('store');
            $(".modal-body #id").val(id);
            $(".modal-body #selected_store_id").val(store);

            $('#itemsModal').modal("hide");
        } );




    });




    function addItemToTable(id){
        $.ajax({
            type:'get',
            url:'/item-select' + '/' + id + '/' + $('#from_store').val() ,
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
                    'item_store_id':response['item_store_id']
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
        for (let i = 0; i < items.length; i++) {
            index = i + 1;
            html += '<tr data-item-index="' + i + '">\
                <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="details_id[]" value="' + items[i]['details_id'] + '" /> </td>\
                      <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="item_id[]" value="' + items[i]['item_id'] + '" /> \
                 <input type="hidden" class="form-control" name="item_store_id[]" value="' + items[i]['item_store_id'] + '" />  \
                </td>\
                <td class="text-center">' + items[i]['code'] + '--' + items[i]['name'] + '</td>\
                <td class="text-center" hidden="hidden"> <input type="number" step="any" readonly class="form-control" name="available_quantity[]" value="' + items[i]['available_quantity'] + '" /> </td>\
                <td class="text-center"> <input type="number" step="any" class="form-control quantity" name="quantity[]" id="quantity_' + i + '" min="1" max="' + items[i]['available_quantity'] + '" value="' + items[i]['quantity'] + '" /> </td>\
                <td class="text-center"> <input type="number" step="any" class="form-control weight" name="weight[]" id="weight_' + i + '" value="' + items[i]['weight'] + '" /> </td>\
                <td class="text-center"> <i class="bx bxs-trash text-danger deleteBtn" style="font-size: 25px; cursor: pointer" data-toggle="tooltip" data-placement="top" title="' + translatedText + '" data-id="' + i + '"></i> </td>\
            </tr>';
        }
       console.log(items);

        body.innerHTML = html ;


    }

    function valdiateRequest(){
        var msg = '' ;
        if($('#code').val() == "")
            msg =  'حقل الكود مطلوب' + "\n" ;
        if($('#from_store').val() == "")
            msg =  'حقل من مخزن مطلوب' + "\n" ;
        if($('#to_store').val() == "")
            msg =  'حقل إلي مخزن مطلوب' + "\n" ;
        if( items.length ==  0)
            msg =  'يجب إضافة أصناف إلي تفاصيل الصنف' + "\n" ;

        const allQuantitiesValid = items.every(item => item.quantity && parseFloat(item.quantity) > 0);
         console.log(allQuantitiesValid);
        if( !allQuantitiesValid)
            msg =  'يجب تحديد الكمية المرحلة من كل الأصناف ويجب ان تكون اكبر من 0' + "\n" ;

        if(msg == ''){
            $('#MealForm').submit();

        } else {
            showalert(msg);
            return ;
        }
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
        const index = $row.data('item-index'); // Make sure data-item-index is set in your <tr>

        // Validation: not more than available
        if (enteredValue > maxAvailable) {
            enteredValue = maxAvailable;
            $quantityInput.val(maxAvailable);
            showalert('عفوا لا يمكن ان تتخطي الكمية المرحلة الكمية المتاحة في المخزن');
        }

        // Validation: not less than 1
        if (enteredValue < 1) {
            enteredValue = 1;
            $quantityInput.val(1);
        }

        // ✅ Update the items array
        if (typeof items[index] !== 'undefined') {
            items[index]['quantity'] = enteredValue;
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
            console.log(`Item ${index} weight updated to:`, enteredValue);
        } else {
            console.warn('Invalid index for weight update:', index);
        }
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
