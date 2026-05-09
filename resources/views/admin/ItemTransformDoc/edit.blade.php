<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 17 , 'subSlag' => 171])
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
                            <span class="text-muted fw-light">{{__('main.cheese_department')}} /</span> {{__('main.item_transform_doc_edit')}}
                        </h4>

                        <button type="button" class="btn btn-primary" id="createButton" style="height: 45px"
                                onclick="valdiateRequest()">
                            {{__('main.save_btn')}}  <span class="tf-icons bx bx-save"></span>&nbsp;
                        </button>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.item_transform_doc_edit')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">
                            <form class="center" method="POST" action="{{ route('item_transform_docs_update') }}"
                                  enctype="multipart/form-data" id="MealForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.docNumber') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="bill_number" id="bill_number"
                                                   class="form-control @error('bill_number') is-invalid @enderror"
                                                   placeholder="{{ __('main.docNumber') }}" autofocus required readonly value="{{$doc -> bill_number}}"/>
                                            @error('bill_number')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <input type="hidden" id="id" name="id" value="{{$doc -> id}}">

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.date') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text"  name="date" id="date"
                                                   class="form-control date @error('date') is-invalid @enderror"
                                                   autofocus required value="{{ \Carbon\Carbon::parse($doc -> date) -> format('d-m-Y') }}"/>
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
                                                <th class="text-center"> {{__('main.from_item')}}</th>
                                                <th class="text-center"> {{__('main.to_item')}}</th>
                                                <th class="text-center" > {{__('main.availableQnt')}}</th>
                                                <th class="text-center"> {{__('main.quantity_to_transform')}}</th>
                                                <th class="text-center"> {{__('main.weight')}}</th>
                                                <th class="text-center"> {{__('main.actions')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="bond-details">
                                            @foreach($details as $detail)
                                                <tr data-item-index="{{$loop -> index}}">
                                                    <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="details_id[]" value="{{$detail -> id}}" /> </td>
                                                    <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="from_item_id[]" value="{{$detail ->from_item_id }}" />
                                                        <input type="hidden" class="form-control" name="to_item_id[]"   value="{{$detail ->to_item_id }}"/>
                                                        <input type="hidden" class="form-control" name="item_store_id[]" value="{{$detail -> item_store_id}}" />
                                                    </td>
                                                    <td class="text-center">{{$detail -> from_item_code}} -- {{$detail -> from_item_name}}</td>
                                                    <td class="text-center">{{$detail -> to_item_code}} -- {{$detail -> to_item_name}}</td>
                                                    <td class="text-center"> <input type="number" step="any" readonly class="form-control" name="available_quantity[]" value="{{$detail -> available_quantity}}" /> </td>
                                                    <td class="text-center"> <input type="number" step="any" class="form-control quantity" name="quantity[]" id="quantity_ . {{$loop -> index}}" min="1" max="{{$detail -> available_quantity}}" value="{{$detail -> quantity}}" /> </td>
                                                    <td class="text-center"> <input type="number" step="any" class="form-control weight" name="weight[]" id="weight_ . {{$loop -> index}}" value="{{$detail -> weight}}" /> </td>
                                                    <td class="text-center"> <i class="bx bxs-trash text-danger deleteBtn" style="font-size: 25px; cursor: pointer" data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}" data-id="{{$loop -> index}}"></i> </td>
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

@include('admin.ItemTransformDoc.alertModal')
@include('admin.ItemTransformDoc.deleteModal')
@include('layouts.footer')
@include('admin.ItemTransformDoc.items')

<script type="text/javascript">
    var items = [] ;


    var deleted_index = 0 ;
    $(document).ready(function() {
        items = @json($details);

        const datePicker = flatpickr(".date", {
            dateFormat: "d-m-Y",
            defaultDate: new Date() // shows today's date initially
        });

        var dateStr = @json($doc -> date);
        var dateOnly = dateStr.split(" ")[0];

        console.log(dateOnly);
        var parts = dateOnly.split('-');    // ["12", "07", "2025"]

        console.log(parts);

// Rearranged to "2025-07-12"
        var formatted = `${parts[2]}-${parts[1]}-${parts[0]}`;

        console.log(formatted);

        datePicker.setDate(formatted);

        $(document).on('click', '#itemsBtn', function(event) {

            let from_store = $('#from_store').val();
            let to_store = $('#to_store').val();
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

                                    const $select1 = $('#fitem');
                                    $select1.empty();

                                    var translatedText0 = "{{ __('main.select') }}";
                                    $select1.append('<option value="0">' + translatedText0 + '</option>');
                                    response.forEach(function(item, index) {

                                        const option = `<option value="${item.id}">${item.name}</option>`;
                                        $select1.append(option);

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



        $(document).on('click' , '.selectBtn' , function (event){


            $('#itemsModal').modal("hide");
        } );


        $('#itemsModal').on('hidden.bs.modal', function () {
            var fItem =   $(".modal-body #fitem").val();
            var tItem =   $(".modal-body #titem").val();
            if(fItem > 0 && tItem > 0){

                if(fItem == tItem){
                    toastr.warning('عفوا لا يمكن تحويل الصنف الي نفسه يجب اختيار صنفين مختلفين ');
                    return;
                }

                if(items.filter(c=> c.from_item_id == fItem && c.to_item_id == tItem).length >  0){
                    toastr.warning( "هذا المنتج تم إضافته من قبل إلي تفاصيل المستند يمكنك" , 1);


                    return ;

                }
                // Ensure all existing items have the same cheese_meal_id as the selected meal
                var allSameMeal = items.every(c => c.item_store_id == store);
                if (!allSameMeal) {
                    toastr.warning(  " جميع الأصناف يجب أن تكون من نفس المخزن",1 );

                    return;
                }



                addItemToTable(fItem , tItem);


            } else {
                console.log('no item selected');
            }


        });


    });

    function addItemToTable(fItem , tItem){
        $.ajax({
            type:'get',
            url:'item-pair-select' + '/' + fItem + '/'  + tItem + '/'  + $('#from_store').val(),
            dataType: 'json',
            success:function(response){
                if (response.status === 'success') {
                    var item = {
                        'id': 0 ,
                        'from_item_id': response.from_item_object['id'],
                        'from_item_code': response.from_item_object['code'],
                        'from_item_name': response.from_item_object['name'],
                        'available_quantity': response.from_item_object['available_quantity'],
                        'to_item_id': response.to_item_object['id'],
                        'to_item_code': response.to_item_object['code'],
                        'to_item_name': response.to_item_object['name'],
                        'quantity': 0 ,
                        'weight': 0 ,
                        'item_store_id':response.from_item_object['item_store_id']
                    }
                    items.push(item);

                    setItems();
                } else {
                    toastr.error(response.message || 'Unknown error occurred');

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
                <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="details_id[]" value="' + items[i]['id'] + '" /> </td>\
                     <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="from_item_id[]" value="' + items[i]['from_item_id'] + '" /> \
                     <input type="hidden" class="form-control" name="to_item_id[]" value="' + items[i]['to_item_id'] + '" /> \
                 <input type="hidden" class="form-control" name="item_store_id[]" value="' + items[i]['item_store_id'] + '" />  \
                </td>\
                <td class="text-center">' + items[i]['from_item_code'] + '--' + items[i]['from_item_name'] + '</td>\
                <td class="text-center">' + items[i]['to_item_code'] + '--' + items[i]['to_item_name'] + '</td>\
                <td class="text-center"> <input type="number" step="any" readonly class="form-control" name="available_quantity[]" value="' + items[i]['available_quantity'] + '" /> </td>\
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
        let hasError = false;
        if($('#bill_number').val() == ""){
            toastr.warning('يجب إدخال رقم المستند');
            $('#bill_number').css('border', '2px solid red');
            hasError = true;
        } else {
            $('#bill_number').css('border', '');
        }

        if($('#from_store').val() == ""){
            toastr.warning('يجب إختيار المخزن المصدر');
            $('#from_store').css('border', '2px solid red');
            hasError = true;
        } else {
            $('#from_store').css('border', '');
        }

        if($('#to_store').val() == ""){
            toastr.warning('يجب إختيار المخزن الواجهة');
            $('#to_store').css('border', '2px solid red');
            hasError = true;
        } else {
            $('#to_store').css('border', '');
        }

        if( items.length ==  0){
            toastr.warning('يجب إضافة أصناف إلي تفاصيل الصنف');
            hasError = true;
        }


        const allQuantitiesValid = items.every(item => item.quantity && parseFloat(item.quantity) > 0);
        console.log(allQuantitiesValid);
        if( !allQuantitiesValid){
            toastr.warning( 'يجب تحديد الكمية المحولة من كل الأصناف ويجب ان تكون اكبر من 0' )  ;
            hasError = true;
        }


        if(!hasError){
            $('#MealForm').submit();

        } else {
            return ;
        }
    }
    function showalert(msg , agree = 0 ){

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
                if(agree == 1){
                    $(".modal-body #post").hide();
                    $(".modal-body #agree_btn").show();
                } else {
                    $(".modal-body #post").show();
                    $(".modal-body #agree_btn").hide();
                }

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

    $(document).on('click', '.agree_btn', function(event) {
        $('#confirmModal').modal("hide");
    });

    $(document).on('click', '.post', function(event) {
        $('#confirmModal').modal("hide");
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
            showalert('عفوا لا يمكن ان تتخطي الكمية المرحلة الكمية المتاحة في المخزن'  , 1);
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
