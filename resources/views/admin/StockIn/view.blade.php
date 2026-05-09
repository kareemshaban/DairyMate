<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 9 , 'subSlag' => 93])
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
                            <span class="text-muted fw-light">{{__('main.accounting_department')}} /</span> {{__('main.stock_in_view')}}
                        </h4>


                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.stock_in_view')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">


                                <div class="row">
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.docNumber') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="bill_number" id="bill_number"
                                                   class="form-control @error('bill_number') is-invalid @enderror"
                                                   placeholder="{{ __('main.docNumber') }}" autofocus
                                                   required readonly value="{{$doc ->bill_number }}"/>
                                            @error('bill_number')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.cheese_meal') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="meal_id" id="meal_id"
                                                     class="form-control @error('meal_id') is-invalid @enderror"  required disabled>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($meals as $meal)
                                                    <option selected value="{{$meal -> id}}"> {{$meal -> code}}</option>
                                                @endforeach
                                            </select>

                                            @error('meal_id')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror

                                        </div>

                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.date') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text"  name="date" id="date"
                                                   class="form-control @error('date') is-invalid @enderror"
                                                   autofocus required value="{{\Carbon\Carbon::parse($doc -> date) -> format('d-m-Y')}}" disabled/>
                                            @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.store') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="store_id" id="store_id"
                                                     class="form-control @error('store_id') is-invalid @enderror"  required disabled>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($stores as $store)
                                                    <option @if($store -> id == $doc -> store_id) selected @endif value="{{$store -> id}}"> {{$store -> name  }} </option>

                                                @endforeach

                                            </select>

                                            @error('store_id')
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
                                                      placeholder="{{__('main.notes')}}" autofocus rows="2"  readonly> {{$doc -> notes}} </textarea>
                                            @error('notes')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror

                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <h5 class="card-header">{{__('main.items')}}</h5>
                                    <div class="table-responsive  text-nowrap">
                                        <table class="table table-striped table-hover view_table">
                                            <thead>
                                            <tr class="text-nowrap">
                                                <th class="text-center">#</th>
                                                <th class="text-center"> {{__('main.item')}}</th>
                                                <th class="text-center"> {{__('main.quantity')}}</th>
                                                <th class="text-center"> {{__('main.weight')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($docDetails as $item)

                                                <tr>
                                                    <th scope="row" class="text-center">{{$loop -> index + 1}}</th>
                                                    <td class="text-center">{{$item -> name}}</td>

                                                    <td class="text-center">{{$item -> quantity}}</td>
                                                    <td class="text-center">{{$item -> weight}}</td>

                                                </tr>
                                            @endforeach




                                            </tbody>
                                        </table>
                                    </div>

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

@include('admin.CheeseMeals.alertModal')
@include('layouts.footer')

<script type="text/javascript">
    $(document).ready(function() {
        $('#daily_milk_meal').val("");
        $('#milk_weight').val("0");
        $('#average_weight_per_milk_litter').val("0");
        $('#average_productivity_per_cheese_disk').val("0");
        $('#quantity').val("0");
        $('#weight').val("0");
        $('#productivity').val("0");
        $('#cost_per_cheese_kilo').val("0");

        $.ajax({
            type:'get',
            url:'/stock_in_code',
            dataType: 'json',

            success:function(code){

                $("#bill_number").val(code);
            }
        });

        $('#daily_milk_meal').change(function() {
            let id = $(this).val();
            $.ajax({
                type:'get',
                url:'/daily_meals-show' + '/' + id,
                dataType: 'json',

                success:function(response){
                    $('#milk_weight').val(Number(response.buffalo_weight) + Number(response.bovine_weight) );
                    $('#average_weight_per_milk_litter').val((Number(response.total) / (Number(response.buffalo_weight) + Number(response.bovine_weight) )).toFixed(2));
                    getAverageProductivity();
                    getProductivity();
                    getAverageCost();
                }
            });
        });



        $('#quantity').on('keyup change', function() {
            getAverageProductivity();
        });
        $('#weight').on('keyup change', function() {
            getProductivity();
            getAverageCost();
        });
        $('#average_weight_per_milk_litter').on('keyup change', function() {
            getAverageCost();
        });
    });

    function getAverageProductivity(){
        var milkWeight = $('#milk_weight').val() ;
        var quantity = $('#quantity').val() ;
        var val =  milkWeight / quantity;
        $('#average_productivity_per_cheese_disk').val(val.toFixed(2));

    }
    function getProductivity(){
        var milkWeight = $('#milk_weight').val() ;
        var weight = $('#weight').val() ;
        var val =  weight / milkWeight * 20;
        $('#productivity').val(val.toFixed(2));

    }

    function getAverageCost(){
        var milkWeight = $('#milk_weight').val() ;
        var price = $('#average_weight_per_milk_litter').val() ;
        var weight = $('#weight').val() ;
        var val =  (milkWeight *   price) / weight;
        $('#cost_per_cheese_kilo').val(val.toFixed(2));

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
                $('#confirmtModal').modal("show");
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
</script>
</body>
</html>
