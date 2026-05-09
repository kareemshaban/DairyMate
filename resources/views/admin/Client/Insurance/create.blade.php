<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 2 , 'subSlag' => 23])
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
                            <span class="text-muted fw-light">{{__('main.factory_department')}} /</span> {{__('main.supplier_insurance_balance_create')}}
                        </h4>

                        <button type="button" class="btn btn-primary" id="createButton" style="height: 45px"
                                onclick="valdiateRequest()">
                            {{__('main.save_btn')}}  <span class="tf-icons bx bx-save"></span>&nbsp;
                        </button>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.supplier_insurance_balance_create')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">
                            <form class="center" method="POST" action="{{ route('insuranceBalances-store') }}"
                                  enctype="multipart/form-data" id="MealForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.supplier') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="supplier_id" id="supplier_id" @if(Config::get('app.locale')=='ar' )
                                                dir="rtl" @endif class="form-control search @error('supplier_id') is-invalid @enderror"  required>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{$supplier -> id}}"> {{$supplier -> name  }} </option>

                                                @endforeach

                                            </select>

                                            @error('supplier_id')
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
                                            <label>{{ __('main.insurance_balance') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="number" name="balance" id="balance"
                                                   class="form-control @error('balance') is-invalid @enderror"
                                                   placeholder="0" autofocus required/>
                                            @error('balance')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                         <div class="form-group">
                                            <label>{{ __('main.meal_state') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="state" id="state" @if(Config::get('app.locale')=='ar' )
                                                dir="rtl" @endif class="form-control @error('supplier_id') is-invalid @enderror"  required>
                                                <option value="0"> {{__('main.insurance_state0')}} </option>
                                                <option value="1"> {{__('main.insurance_state1')}} </option>

                                            </select>

                                            @error('state')
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
                                                <th class="text-center"> {{__('main.quantity')}}</th>
                                                <th class="text-center"> {{__('main.weight')}}</th>
                                                <th class="text-center"> {{__('main.actions')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="bond-details">




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
                  @include('admin.Client.Insurance.items')
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

@include('layouts.footer')

<script type="text/javascript">
    var items = [] ;
    var deleted_index = 0 ;
    $(document).ready(function() {

        $(document).on('click', '#itemsBtn', function(event) {
              event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {

                 $('#itemsModal').modal("show");
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



        $(document).on('click' , '.selectBtn' , function (event){
            const id = $(this).data('id');
            $(".modal-body #id").val(id);

            $('#itemsModal').modal("hide");
        } );



        $('#itemsModal').on('hidden.bs.modal', function () {
            var id =   $(".modal-body #id").val();
            if(id > 0){

                if(items.filter(c=> c.item_id == id).length >  0){
                    toastr.warning( "هذا المنتج تم إضافته من قبل إلي الفاتورة يمكنك زيادة كميته إذا أردت" );
                    $(".modal-body #id").val(0);

                    return ;

                }

                addItemToTable(id);


            } else {
                console.log('no item selected');
            }

            $(".modal-body #id").val(0);

        });


    });

    function addItemToTable(id){
        //console.log('item-select' + '/' + id + '/' + $('#store_id').val() + '/' + $('#meal_id').val());
        $.ajax({
            type:'get',
            url:'items-show' + '/' + id ,
            dataType: 'json',
            success:function(response){
             //   console.log(response);
                var item = {
                    'details_id': 0 ,
                    'item_id': response['id'],
                    'code': response['code'],
                    'name': response['name'],
                    'quantity': 0 ,
                    'weight': 0 ,
                }
                console.log(items);
                items.push(item);
                console.log(items);

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
                <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="details_id[]" value="' + items[i]['details_id'] + '" /> </td>\
                <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="item_id[]" value="' + items[i]['item_id'] + '" /> \
                </td>\
                <td class="text-center">' + items[i]['code'] + '--' + items[i]['name'] + '</td>\
                <td class="text-center"> <input type="number" step="any" class="form-control quantity" name="quantity[]" id="quantity_' + i + '" min="1" max="' + items[i]['available_quantity'] + '" value="' + items[i]['quantity'] + '" /> </td>\
                <td class="text-center"> <input type="number" step="any" class="form-control weight" name="weight[]" id="weight_' + i + '" value="' + items[i]['weight'] + '" /> </td>\
                <td class="text-center"> <i class="bx bxs-trash text-danger deleteBtn" style="font-size: 25px; cursor: pointer" data-toggle="tooltip" data-placement="top" title="' + translatedText + '" data-id="' + i + '"></i> </td>\
            </tr>';
        }

        body.innerHTML = html ;



    }

    function valdiateRequest(){
        var msg = '' ;
        if($('#supplier_id').val() == ""){
             msg =  'حقل المورد مطلوب' + "\n" ;
             toastr.warning(msg);
             return ;
        }

        if($('#balance').val() == ""){
            msg =  'حقل نقدية الأرضية مطلوب' + "\n" ;
            toastr.warning(msg);
             return ;

        }
        $('#MealForm').submit();

    }



    $(document).on('click','.deleteBtn',function () {
         let item_id = $(this).data('id');
             Swal.fire({
            title: 'حذف الصنف',
            text: 'هل انت متأكد من حذف الصنف من تفاصيل المستند ؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم , متأكد',
            cancelButtonText: 'لا , تراجع'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion or any other logic
                confirmDelete(item_id);
            }
        });


    });
    $(document).on('click', '.btnConfirmDelete', function(event) {
        confirmDelete();
    });
    $(document).on('click', '.cancel-modal', function(event) {
        $('#deleteItemModal').modal("hide");
        deleted_index = 0 ;
    });
    function confirmDelete(item_id){
        items.splice(item_id , 1);
        setItems();
    }

    $(document).on('click', '.agree_btn', function(event) {
        $('#confirmModal').modal("hide");
    });


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
