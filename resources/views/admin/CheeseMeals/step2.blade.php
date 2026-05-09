<!DOCTYPE html>

@include('layouts.head')

<style>
    td{
        padding-left: 2px !important;
        padding-right: 2px !important;
        padding-bottom: 5px !important;
        padding-top: 5px !important;
        min-width: 100px !important;
    }
    td input {
        text-align: center !important;
    }


</style>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 7 , 'subSlag' => 72])
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
                            <span class="text-muted fw-light">{{__('main.cheese_department')}} /</span> {{__('main.cheese_meals_create')}}
                        </h4>



                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header" style="display: flex ; justify-content: space-between">
                            <span >{{__('main.cheese_meals_create')}}</span>
                            <span class="text-primary"> {{__('main.milk_meals')}} ( {{\Carbon\Carbon::parse($meal -> start_date) -> format('d-m-Y')  }} : {{\Carbon\Carbon::parse($meal -> end_date) -> format('d-m-Y')  }} ) </span>
                        </h5>
                        <input type="hidden" id="wid" name="wid" value="{{$meal -> id}}">
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">


                            <div class="row" style="display: none">
                                <div class="col-md-3 col-lg-3 col-sm-6" style="margin-top: 10px">
                                    <div class="form-group">
                                        <label>{{ __('main.cheese_price') }} <span style="font-size: 14px ; color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" id="cheese_price" name="cheese_price"
                                               value="{{$setting['cheese_price'] ?? 0}}">

                                        @error('cheese_price')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>

                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-6" style="margin-top: 10px">
                                    <div class="form-group">
                                        <label>{{ __('main.white_cheese_price') }} <span style="font-size: 14px ; color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" id="white_cheese_price" name="white_cheese_price"
                                               value="{{$setting['white_cheese_price'] ?? 0}}">

                                        @error('white_cheese_price')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>

                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-6" style="margin-top: 10px">
                                    <div class="form-group">
                                        <label>{{ __('main.cream_price') }} <span style="font-size: 14px ; color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" id="cream_price" name="cream_price"
                                               value="{{$setting['cream_price'] ?? 0}}">

                                        @error('cream_price')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>

                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-6" style="margin-top: 10px">
                                    <div class="form-group">
                                        <label>{{ __('main.protein_price') }} <span style="font-size: 14px ; color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" id="protein_price" name="protein_price"
                                               value="{{$setting['protein_price'] ?? 0}}">

                                        @error('protein_price')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                            <h2 style="font-size: 11px ; color: red ; margin-right: 10px ; margin-left: 10px ; margin-top: 15px ">{{__('main.cheese_meal_note')}}</h2>


                            <div class="table-responsive  text-nowrap" style="margin-top: 25px">


                                <table class="table table-striped table-hover  table-bordered view_table">

                                    <thead>
                                    <tr>
                                        <th class="text-center">  {{__('main.day')}} </th>
                                        <th class="text-center" >  إسم الوجبة </th>
                                        <th class="text-center">  {{__('main.milk_weight')}} </th>
                                        <th class="text-center">  {{__('main.price')}} </th>
                                        <th class="text-center">  {{__('main.item')}} </th>
                                        <th class="text-center">  {{__('main.cheese_qnt')}} </th>
                                        <th class="text-center">  {{__('main.cheese_weight')}} </th>
                                        <th class="text-center">  {{__('main.disk_weight')}} </th>
                                        <th class="text-center">  {{__('main.disk_cost')}} </th>
                                        <th class="text-center">  {{__('main.productivity')}} </th>
                                        <th class="text-center">  {{__('main.cost_per_cheese_kilo')}} </th>
                                        <th class="text-center">  {{__('main.keshta_weight')}} </th>
                                        <th class="text-center">  {{__('main.cream_of_kilo_milk')}} </th>
                                        <th class="text-center">  {{__('main.proten_weight')}} </th>
                                        <th class="text-center">  {{__('main.protein_of_kilo_milk')}} </th>
                                        <th class="text-center">  {{__('main.net_value')}} </th>
                                        <th class="text-center">  {{__('main.actions')}} </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $meal)
                                        <tr>

                                            <td class="text-center">
                                                <input type="hidden" id="date[]" name="date[]" value="{{$meal['date']}}">
                                                <input type="hidden" id="cheese_meal_id[]" name="cheese_meal_id[]"
                                                       value="{{$meal['cheese_meal_id']}}">
                                                <input type="hidden" id="type" name="type" value="{{$meal['type']}}">
                                                @php
                                                    $locale = app()->getLocale(); // 'en' or 'ar'
                                                    $dayName = \Carbon\Carbon::parse($meal['date'])->locale($locale)->isoFormat('dddd');
                                                @endphp


                                                {{ $dayName }}
                                                @if($meal['type'] == 0)
                                                    <span class="badge bg-primary">{{__('main.daily_meal_type0')}}</span>
                                                @elseif($meal['type'] == 1)
                                                    <span class="badge bg-info">{{__('main.daily_meal_type1')}}</span>
                                                @endif
                                                <br>
                                                <span class="text-gray"> {{\Carbon\Carbon::parse($meal['date']) -> format('Y-m-d')}}  </span>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" name="symbol[]"   class="form-control"  value="{{$meal['symbol']}}" @if($meal['state'] == 1) readonly @endif>
                                            </td>
                                            <td class="text-center"> <input type="text" name="milk_weight[]"  readonly class="form-control" step="any" value="{{$meal['milk_weight']}}" > </td>
                                            <td class="text-center"> <input type="text" name="bovine_price[]" readonly  class="form-control" step="any" value="{{ number_format($meal['bovine_price'], 2) }}" > </td>
                                            <td class="text-center" style="min-width: 140px !important;">
                                                <select name="item_id[]" class="form-control" @if($meal['state'] == 1) disabled @endif>
                                                    @foreach($items as $item)
                                                        <option value="{{$item -> id}}"  @if($item -> id == $meal['item_id']) selected @endif > {{$item -> name}} </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="number" name="quantity[]" class="form-control quantity" step="any" value="{{$meal['quantity']}}" @if($meal['state'] == 1) readonly @endif > </td>
                                            <td class="text-center" style="min-width: 120px !important;">

                                                <input type="number" name="weight[]" class="form-control weight"
                                                       @if($meal['item_id'] == 6) readonly  @endif  step="any" value="{{$meal['weight']}}"  @if($meal['state'] == 1) readonly @endif>

                                            </td>
                                            <td class="text-center" > <input type="text" name="disk_weight[]" class="form-control" readonly  value="{{$meal['disk_weight']}}" > </td>
                                            <td class="text-center" > <input type="text" name="disk_cost[]" class="form-control" readonly value="{{$meal['disk_cost']}}" > </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="text" name="productivity[]" class="form-control" readonly value="{{$meal['productivity']}}" > </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="text" name="cost_per_cheese_kilo[]" class="form-control" readonly value="{{$meal['cost_per_cheese_kilo']}}" > </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="number" name="cream_weight[]" class="form-control cream_weight" step="any" value="{{$meal['cream_weight']}}" @if($meal['state'] == 1) readonly @endif > </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="text" name="cream_of_kilo_milk[]" class="form-control" readonly value="{{$meal['cream_of_kilo_milk']}}" > </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="number" name="protein_weight[]" class="form-control protein_weight" step="any" value="{{$meal['protein_weight']}}"  @if($meal['state'] == 1) readonly @endif> </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="text" name="protein_of_kilo_milk[]" class="form-control" readonly value="{{$meal['protein_of_kilo_milk']}}" > </td>
                                            <td class="text-center" style="min-width: 120px !important;"> <input type="text" name="net[]" class="form-control" readonly value="{{$meal['net']}}" > </td>
                                            <td class="text-center">
                                                @if($meal['state'] == 0)

                                                    <i class='bx bxs-cloud-upload text-primary postBtn' data-toggle="tooltip" data-placement="top"
                                                       title="{{__('main.post_action')}}"style="font-size: 25px ; cursor: pointer"></i>

                                                    <i class='bx bx-save text-primary saveBtn' data-toggle="tooltip" data-placement="top"
                                                       title="{{__('main.save_action')}}" style="font-size: 25px ; cursor: pointer"></i>

                                                @else
                                                    <span class="badge bg-danger">{{__('main.mealState1')}}</span>

                                                @endif
                                            </td>

                                        </tr>


                                    @endforeach
                                    </tbody>


                                </table>


                            </div>


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


<div id="closing-overlay">
    <div class="loader"></div>
    <div class="loading-text">{{__('main.closing_loading')}}</div>
</div>

@include('admin.CheeseMeals.alertModal')
@include('layouts.footer')

<script type="text/javascript">
    $(document).ready(function() {

        $('table').on('keydown', 'input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // prevent form submission or line break

                let inputs = $('table').find('input:visible:enabled'); // all inputs in order
                let index = inputs.index(this);

                if (index > -1 && index < inputs.length - 1) {
                    inputs.eq(index + 1).focus().select(); // move to next input
                }
            }
        });

        $('.quantity').on('keyup change', function() {
            let $row = $(this).closest('tr');
            let quantity = $(this).val();

            let $weight = $row.find('.weight');
            let weight = $weight.val();
            let milkWeight = $row.find('input[name="milk_weight[]"]').val();

            let $diskWeight = $row.find('input[name="disk_weight[]"]');
            let $diskCost = $row.find('input[name="disk_cost[]"]');

            let item_id = $row.find('select[name="item_id[]"]').val();

            if (quantity && !isNaN(quantity) && Number(quantity) !== 0) {
                if (item_id == 6) {
                    $weight.val(quantity);
                    $weight.trigger('change');
                }

                let disk_cost = (milkWeight / quantity).toFixed(2);
                let disk_weight = (weight / quantity).toFixed(2);

                $diskWeight.val(disk_weight);
                $diskCost.val(disk_cost);
            } else {
                // quantity is empty or invalid
                $diskWeight.val(0);
                $diskCost.val(0);
            }
        });

        $('.weight').on('keyup change', function () {
            let $row = $(this).closest('tr');
            let weight = parseFloat($(this).val()) || 0;
            let quantity = parseFloat($row.find('.quantity').val()) || 0;
            let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
            let price = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;
            let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
            let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;
            let item_id = $row.find('select[name="item_id[]"]').val();

            let cheese_price = item_id == 1
                ? parseFloat($('#cheese_price').val()) || 0
                : parseFloat($('#white_cheese_price').val()) || 0;
            let cream_price = parseFloat($('#cream_price').val()) || 0;
            let protein_price = parseFloat($('#protein_price').val()) || 0;

            let productivity = (milkWeight > 0 && weight > 0)
                ? ((weight / milkWeight) * 20).toFixed(2)
                : 0;

            let disk_weight = (quantity > 0 && weight > 0)
                ? (weight / quantity).toFixed(2)
                : 0;

            let kilo_cost = (weight > 0 && milkWeight > 0 && price > 0)
                ? ((milkWeight * price) / weight).toFixed(2)
                : 0;

            let net = ((cheese_price * weight) + (cream_price * cream_weight) + (protein_price * protein_weight)) - (price * milkWeight);

            $row.find('input[name="productivity[]"]').val(productivity);
            $row.find('input[name="disk_weight[]"]').val(disk_weight);
            $row.find('input[name="cost_per_cheese_kilo[]"]').val(kilo_cost);
            $row.find('input[name="net[]"]').val(net.toFixed(2));
        });

        $('.cream_weight').on('keyup change', function () {
            let $row = $(this).closest('tr');
            let cream_weight = $(this).val();
            let cream_price = parseFloat($('#cream_price').val()) || 0;
            let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
            let weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
            let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;
            let price = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;
            let item_id = $row.find('select[name="item_id[]"]').val();

            let $creamOfKiloMilk = $row.find('input[name="cream_of_kilo_milk[]"]');
            let $net = $row.find('input[name="net[]"]');

            if (cream_weight && !isNaN(cream_weight) && Number(cream_weight) !== 0)

                cream_weight = parseFloat(cream_weight);
            else
                cream_weight = 0 ;

            let val = milkWeight !== 0
                ? ((cream_weight * cream_price) / milkWeight).toFixed(2)
                : 0;

            $creamOfKiloMilk.val(val);

            let cheese_price = item_id == 1
                ? parseFloat($('#cheese_price').val()) || 0
                : parseFloat($('#white_cheese_price').val()) || 0;

            let protein_price = parseFloat($('#protein_price').val()) || 0;

            let net = ((cheese_price * weight) + (cream_price * cream_weight) + (protein_price * protein_weight)) - (price * milkWeight);
            $net.val(net.toFixed(2));
            // } else {
            //     // Input is empty or invalid — reset
            //     $creamOfKiloMilk.val(0);
            //     $net.val(0);
            // }
        });

        $('.protein_weight').on('keyup change', function () {
            let $row = $(this).closest('tr');

            // Safely parse all inputs
            let protein_weight = parseFloat($(this).val()) || 0;
            let protein_price = parseFloat($('#protein_price').val()) || 0;
            let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
            let weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
            let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
            let price = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;
            let item_id = $row.find('select[name="item_id[]"]').val();

            let cheese_price = item_id == 1
                ? parseFloat($('#cheese_price').val()) || 0
                : parseFloat($('#white_cheese_price').val()) || 0;
            let cream_price = parseFloat($('#cream_price').val()) || 0;

            // Calculate protein per kilo milk safely
            let proteinOfKiloMilk = (milkWeight > 0)
                ? ((protein_weight * protein_price) / milkWeight).toFixed(2)
                : 0;

            // Calculate net value
            let net = ((cheese_price * weight) +
                    (cream_price * cream_weight) +
                    (protein_price * protein_weight)) -
                (price * milkWeight);

            // Update inputs
            $row.find('input[name="protein_of_kilo_milk[]"]').val(proteinOfKiloMilk);
            $row.find('input[name="net[]"]').val(net.toFixed(2));
        });



        $('select[name="item_id[]"]').on('change', function () {
            const value = $(this).val();

            console.log(value);

            // Find the closest row (adjust selector as needed)
            const row = $(this).closest('tr'); // or '.form-row', '.item-line', etc.

            // Find the weight[] input in the same row
            const weightInput = row.find('input[name="weight[]"]');

            const qnt = row.find('input[name="quantity[]"]').val();
            if (value == 1) {
                weightInput.prop('readonly', false).val(0);
            } else if (value == 6) {
                weightInput.prop('readonly', true).val(qnt); // Optional: clear value
            }

            weightInput.trigger('change');
        });

        $('.saveBtn').on('click', function(e) {
            e.preventDefault(); // prevent default behavior (useful if inside a form)
            let $row = $(this).closest('tr');
            if (!valdiateSaveRequest($row)) {
                return; // Stop the save function
            }



            // Get all input values in the row
            let data = {};

            $row.find('input, select').each(function() {
                let name = $(this).attr('name');
                let value = $(this).val();

                // Handle name as array, e.g. "quantity[]"
                if (name) {
                    // Remove [] from name if it exists
                    let key = name.replace(/\[\]$/, '');
                    data[key] = value;
                }
            });

            data['cheese_price'] = $('#cheese_price').val();
            data['white_cheese_price'] = $('#white_cheese_price').val();
            data['cream_price'] = $('#cream_price').val();
            data['protein_price'] = $('#protein_price').val();
            data['wid'] = $('#wid').val();

            //ajax post request to create cheese meal
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('cheese_meals_post') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {

                    if (data.status === 'debug') {
                        // Display warning toast
                        console.log(data.request);
                    }
                    if (data.status === 'warning') {
                        // Display warning toast
                        toastr.warning(data.message);
                    }
                    else if(data.status == 'success') {
                        // continue normal behavior
                        toastr.success(data.message);
                        $row.find('input[name="cheese_meal_id[]"]').val(data.cheese_meal_id);

                    }
                    else {
                        toastr.error(data.message);
                        console.log(data.debug);
                        console.log(data.request);
                    }

                })
                .catch(error => {
                    console.error('AJAX error:', error);

                    // ❌ Show error toast
                    toastr.error('Failed to save value.', 'Error');
                });


        });

        $('.postBtn').on('click' , function (e) {
            e.preventDefault();

            let $row = $(this).closest('tr');
            let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;
            if(cheese_meal_id > 0){

                Swal.fire({
                    title: 'ترحيل و إقفال الوجبة',
                    text:  `هل انت متأكد من ترحيل و إقفال الوجبة` ,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم , متأكد',
                    cancelButtonText: 'لا , تراجع'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with deletion or any other logic
                        postMealAction(cheese_meal_id , $row);

                    }
                });



            } else {
                // Display warning toast
                toastr.warning('برجاء ادخال بيانات الوجبة و حفظها أولا حتي تتمكن من ترحيلها');
            }

        });


        const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

        if(!isMobile) {

            $('#cheese_price').on('keydown', function (e) {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices

                //update net
                if (e.key === 'Enter') {
                    let cheese_price = $(this).val();
                    let cream_price = $('#cream_price').val();
                    let protein_price = $('#protein_price').val();

                    $('table tr').each(function () {
                        let $row = $(this);

                        let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;
                        let item_id = parseInt($row.find('select[name="item_id[]"]').val());
                        let quantity = parseFloat($row.find('input[name="quantity[]"]').val()) || 0;

                        if( quantity > 0 && item_id == 1) {

                            // Get inputs from the current row
                            let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                            let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                            let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                            let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                            let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;


                            // Calculate net value
                            let net = (cheese_weight * cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                            // Set the result into net[]
                            $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals

                        }
                    });

                    updateCheeseMealPrices();
                }

            });

            $('#white_cheese_price').on('keydown', function (e) {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices

                //update net
                if (e.key === 'Enter') {
                    let white_cheese_price = $(this).val();
                    let cream_price = $('#cream_price').val();
                    let protein_price = $('#protein_price').val();

                    $('table tr').each(function () {
                        let $row = $(this);

                        let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;
                        let item_id = parseInt($row.find('select[name="item_id[]"]').val());
                        let quantity = parseFloat($row.find('input[name="quantity[]"]').val()) || 0;


                        if(quantity > 0 && item_id == 6) {

                            // Get inputs from the current row
                            let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                            let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                            let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                            let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                            let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;


                            // Calculate net value
                            let net = (cheese_weight * white_cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                            // Set the result into net[]
                            $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals

                        }
                    });

                    updateCheeseMealPrices();
                }

            });



            $('#cream_price').on('keydown', function (e) {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices

                if (e.key === 'Enter') {
                    let cream_price = $(this).val();
                    let cheese_price = $('#cheese_price').val();
                    let white_cheese_price = $('#white_cheese_price').val();
                    let protein_price = $('#protein_price').val();

                    $('table tr').each(function () {
                        let $row = $(this);

                        let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;

                        let item_id = parseInt($row.find('select[name="item_id[]"]').val());

                        let quantity = parseInt($row.find('input[name="quantity[]"]').val()) || 0;

                        if(cheese_meal_id > 0 || quantity > 0 ) {
                            // Get inputs from the current row

                            let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                            let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                            let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                            let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                            let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;

                            let net = 0 ;
                            if(item_id == 1){
                                net = (cheese_weight * cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                            } else if(item_id == 6){
                                net = (cheese_weight * white_cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                            }
                            // Calculate net value

                            let cream_of_kilo_milk = ((cream_weight * cream_price) / milkWeight).toFixed(2);
                            // Set the result into net[]
                            $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals
                            $row.find('input[name="cream_of_kilo_milk[]"]').val(cream_of_kilo_milk);
                        }
                    });
                    updateCheeseMealPrices();
                }
            });

            $('#protein_price').on('keydown', function (e) {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices

                if (e.key === 'Enter') {
                    let protein_price = $(this).val();
                    let cheese_price = $('#cheese_price').val();
                    let cream_price = $('#cream_price').val();
                    let white_cheese_price = $('#white_cheese_price').val();


                    $('table tr').each(function () {
                        let $row = $(this);
                        let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;
                        let quantity = parseInt($row.find('input[name="quantity[]"]').val()) || 0;
                        let item_id = parseInt($row.find('select[name="item_id[]"]').val());


                        if(cheese_meal_id > 0 || quantity > 0) {

                            // Get inputs from the current row
                            let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                            let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                            let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                            let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                            let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;


                            let net = 0 ;
                            // Calculate net value
                            if(item_id == 1) {
                                net = (cheese_weight * cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);
                            } else if(item_id == 6){
                                net = (cheese_weight * white_cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                            }

                            let protein_of_kilo_milk = ((protein_weight * protein_price) / milkWeight).toFixed(2);

                            // Set the result into net[]
                            $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals
                            $row.find('input[name="protein_of_kilo_milk[]"]').val(protein_of_kilo_milk);
                        }
                    });
                    updateCheeseMealPrices();
                }

            });

        }
        else {

            $('#cheese_price').on('blur', function () {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices
                let cheese_price = $(this).val();
                let cream_price = $('#cream_price').val();
                let protein_price = $('#protein_price').val();

                $('table tr').each(function () {
                    let $row = $(this);

                    let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;
                    let item_id = parseInt($row.find('select[name="item_id[]"]').val());

                    if(cheese_meal_id > 0 && item_id == 1) {

                        // Get inputs from the current row
                        let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                        let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                        let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                        let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                        let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;


                        // Calculate net value
                        let net = (cheese_weight * cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                        // Set the result into net[]
                        $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals

                    }
                });

                updateCheeseMealPrices();

            });

            $('#white_cheese_price').on('blur', function () {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices
                let white_cheese_price = $(this).val();
                let cream_price = $('#cream_price').val();
                let protein_price = $('#protein_price').val();

                $('table tr').each(function () {
                    let $row = $(this);

                    let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;
                    let item_id = parseInt($row.find('select[name="item_id[]"]').val());

                    if(cheese_meal_id > 0 && item_id == 6) {

                        // Get inputs from the current row
                        let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                        let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                        let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                        let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                        let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;


                        // Calculate net value
                        let net = (cheese_weight * white_cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                        // Set the result into net[]
                        $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals

                    }
                });

                updateCheeseMealPrices();

            });

            $('#cream_price').on('blur', function () {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices
                let cream_price = $(this).val();
                let cheese_price = $('#cheese_price').val();
                let protein_price = $('#protein_price').val();

                $('table tr').each(function () {
                    let $row = $(this);

                    let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;

                    if(cheese_meal_id > 0) {
                        // Get inputs from the current row
                        let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                        let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                        let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                        let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                        let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;


                        // Calculate net value
                        let net = (cheese_weight * cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                        let cream_of_kilo_milk = ((cream_weight * cream_price) / milkWeight).toFixed(2);
                        // Set the result into net[]
                        $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals
                        $row.find('input[name="cream_of_kilo_milk[]"]').val(cream_of_kilo_milk);
                    }
                });
                updateCheeseMealPrices();

            });

            $('#protein_price').on('blur', function () {
                // update the table as needed
                // call ajax request to update the cheese meals with the new prices
                let protein_price = $(this).val();
                let cheese_price = $('#cheese_price').val();
                let cream_price = $('#cream_price').val();

                $('table tr').each(function () {
                    let $row = $(this);
                    let cheese_meal_id = parseInt($row.find('input[name="cheese_meal_id[]"]').val()) || 0;

                    if(cheese_meal_id > 0) {

                        // Get inputs from the current row
                        let milkWeight = parseFloat($row.find('input[name="milk_weight[]"]').val()) || 0;
                        let bovinePrice = parseFloat($row.find('input[name="bovine_price[]"]').val()) || 0;

                        let cheese_weight = parseFloat($row.find('input[name="weight[]"]').val()) || 0;
                        let cream_weight = parseFloat($row.find('input[name="cream_weight[]"]').val()) || 0;
                        let protein_weight = parseFloat($row.find('input[name="protein_weight[]"]').val()) || 0;


                        // Calculate net value
                        let net = (cheese_weight * cheese_price) + (protein_weight * protein_price) + (cream_weight * cream_price) - (milkWeight * bovinePrice);

                        let protein_of_kilo_milk = ((protein_weight * protein_price) / milkWeight).toFixed(2);

                        // Set the result into net[]
                        $row.find('input[name="net[]"]').val(net.toFixed(2)); // Rounded to 2 decimals
                        $row.find('input[name="protein_of_kilo_milk[]"]').val(protein_of_kilo_milk);
                    }
                });
                updateCheeseMealPrices();


            });
        }

    });

    function valdiateSaveRequest(row){
        let hasError = false;

        const cheesePrice = $('#cheese_price').val().trim();
        const white_cheese_price = $('#white_cheese_price').val().trim();
        const cream_price = $('#cream_price').val().trim();
        const protein_price = $('#protein_price').val().trim();

        if (!cheesePrice || parseFloat(cheesePrice) === 0) {
            toastr.warning('يجب إدخال سعر الجبنة');
            $('#cheese_price').css('border', '2px solid red');
            hasError = true;
        } else {
            $('#cheese_price').css('border', '');
        }

        if (!white_cheese_price || parseFloat(white_cheese_price) === 0) {
            toastr.warning('يجب إدخال سعر الجبنة البراميلي');
            $('#white_cheese_price').css('border', '2px solid red');
            hasError = true;
        } else {
            $('#white_cheese_price').css('border', '');
        }


        if (!cream_price || parseFloat(cream_price) === 0) {
            // Value is either empty or 0
            toastr.warning('يجب إدخال سعر القشطة');
            $('#cream_price').css('border', '2px solid red');
            hasError = true;
        }
        else {
            $('#cream_price').css('border', '');
        }
        if (!protein_price || parseFloat(protein_price) === 0) {
            // Value is either empty or 0
            toastr.warning('يجب إدخال سعر البروتين');
            $('#protein_price').css('border', '2px solid red');
            hasError = true;
        } else {
            $('#protein_price').css('border', '');
        }

        const milk_weight = row.find('input[name="milk_weight[]"]');
        const bovine_price = row.find('input[name="bovine_price[]"]');
        const item_id = row.find('select[name="item_id[]"]');
        const quantity = row.find('input[name="quantity[]"]');
        const weight = row.find('input[name="weight[]"]');
        const cheese_meal_id = row.find('input[name="cheese_meal_id[]"]');
        const symbol = row.find('input[name="symbol[]"]');

        const milk_weight_Val = milk_weight.val().trim();
        const bovine_price_val = bovine_price.val().trim();
        const item_id_val = item_id.val().trim();
        const quantity_val = quantity.val().trim();
        const weight_val = weight.val().trim();
        const cheese_meal_id_val = cheese_meal_id.val().trim();
        const symbol_val = symbol.val().trim();



        if (!milk_weight_Val || parseFloat(milk_weight_Val) === 0) {
            toastr.warning('وزن اللبن يجب ان يكون اكبر من الصفر حتي تبدأ تصنيع الوجية');
            milk_weight.css('border', '2px solid red');
            hasError = true;
        } else {
            milk_weight.css('border', '');
        }

        if (!bovine_price_val || parseFloat(bovine_price_val) === 0) {
            toastr.warning('سعر اللبن لا يمكن ان يكون صفر');
            bovine_price.css('border', '2px solid red');
            hasError = true;
        } else {
            bovine_price.css('border', '');
        }

        if (!item_id_val || parseFloat(item_id_val) === 0) {
            toastr.warning('يجب اختيار صنف الجبنة المراد تصنيعه من هذه الوجبة');
            item_id.css('border', '2px solid red');
            hasError = true;
        } else {
            item_id.css('border', '');
        }

        if (!quantity_val || parseFloat(quantity_val) === 0) {
            toastr.warning('يجب تحديد كمية الجبنة الناتجة من عملية التصنيع');
            quantity.css('border', '2px solid red');
            hasError = true;
        } else {
            quantity.css('border', '');
        }

        if (!weight_val || parseFloat(weight_val) === 0) {
            toastr.warning('يجب تحديد وزن الجبنة الناتجة من عملية التصنيع');
            weight.css('border', '2px solid red');
            hasError = true;
        } else {
            weight.css('border', '');
        }
        // 🚫 Stop save if any errors found
        return !hasError;
    }

    function postMealAction(cheese_meal_id , $row){

        //cheese_meals_carry_over

        const overlay = document.getElementById('closing-overlay');

        // Show the overlay
        overlay.style.display = 'flex';

        // Ensure overlay stays for at least 3 seconds
        const minDuration = 3000;
        const startTime = Date.now();

        fetch(`/cheese_meals_carry_over/${cheese_meal_id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'warning') {
                    // Display warning toast
                    toastr.warning(data.message);
                } else if (data.status === 'success') {
                    // Display success toast
                    toastr.success(data.message);

                    // 1. Make all non-readonly inputs inside the row readonly
                    $row.find('input:not([readonly])').attr('readonly', true);
                    $row.find('select').prop('disabled', true);


                    // 2. Find the last <td>, clear its content, and add the span
                    const badgeHTML = `<span class="badge bg-danger">{{__('main.mealState1')}}</span>`;
                    $row.find('td:last').html(badgeHTML);
                } else {
                    toastr.error(data.message);
                    console.log(data.debug);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            })
            .finally(() => {
                const elapsed = Date.now() - startTime;
                const remaining = minDuration - elapsed;

                setTimeout(() => {
                    // Hide the overlay
                    overlay.style.display = 'none';

                }, Math.max(remaining, 0));
            });



    }


    function updateCheeseMealPrices(){

        let hasDangerBadge = false;

        $('table tbody tr').each(function () {
            let lastTd = $(this).find('td:last');
            if (lastTd.find('span.badge.bg-danger').length > 0) {
                hasDangerBadge = true;
                return false; // break loop early
            }
        });

        if (hasDangerBadge) {
            toastr.error('عفوا لا يمكن تعديل الأسعار بسبب ترحيل بعض او كل الوجبات');

            setTimeout(function () {
                location.reload();
            }, 1500); // 1.5 seconds delay

            return;

        }


        let protein_price = $('#protein_price').val();
        let cheese_price  = $('#cheese_price').val();
        let cream_price   = $('#cream_price').val();
        let white_cheese_price = $('#white_cheese_price').val();
        let wid = $('#wid').val();
        console.log(wid);

        const body = {
            wid: wid,
            cheese_price: cheese_price ,
            white_cheese_price: white_cheese_price ,
            cream_price: cream_price,
            protein_price: protein_price
        };


        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('cheese_meals_prices_update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(body)
        })
            .then(response => response.json())
            .then(data => {


                if (data.status === 'warning') {
                    // Display warning toast
                    toastr.warning(data.message);
                    $('#protein_price').val(data.settings.protein_price);
                    $('#cheese_price').val(data.settings.cheese_price);
                    $('#cream_price').val(data.settings.cream_price);
                    $('#white_cheese_price').val(data.settings.white_cheese_price);



                }
                else if(data.status == 'success') {
                    // continue normal behavior
                    console.log(data.dailyMeals);
                    toastr.success(data.message);

                }
                else {
                    toastr.error(data.message);
                    console.log(data.debug);
                }

            })
            .catch(error => {
                console.error('AJAX error:', error);

                // ❌ Show error toast
                toastr.error('Failed to save value.', 'Error');
            });


    }


    function valdiateRequest(){
        var msg = '' ;
        if($('#code').val() == "")
            msg =  'حقل الكود مطلوب' + "\n" ;
        if($('#daily_milk_meal').val() == "")
            msg +=  'حقل وجبة اللبن مطلوب' + "\n" ;
        if($('#item_id').val() == "")
            msg +=  'حقل الصنف مطلوب' + "\n" ;
        if($('#milk_weight').val() == "")
            msg +=  'حقل وزن اللبن مطلوب' + "\n" ;
        if($('#quantity').val() == "")
            msg +=  'حقل عدد أقراص الجبنة مطلوب' + "\n" ;
        if($('#weight').val() == "")
            msg +=  'حقل عدد وزن الجبنة مطلوب' + "\n" ;
        if($('#average_weight_per_milk_litter').val() == "")
            msg +=  'حقل متوسط سعر كيلو اللبن مطلوب' + "\n" ;
        if($('#average_productivity_per_cheese_disk').val() == "")
            msg +=  'حقل متوسط انتاجية القرص مطلوب' + "\n" ;
        if($('#productivity').val() == "")
            msg +=  'حقل الانتاجية القرص مطلوب' + "\n" ;
        if($('#cost_per_cheese_kilo').val() == "")
            msg +=  'حقل متوسط تكلفة كيلو الجبنة مطلوب' + "\n" ;

        if(msg == ''){
            $('#MealForm').submit();

        } else {
            showalert(msg);
            return ;
        }
    }

</script>
</body>
</html>
