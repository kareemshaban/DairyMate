<!DOCTYPE html>

@include('layouts.head')


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
                            <span class="text-muted fw-light">{{__('main.cheese_department')}} /</span> {{__('main.cheese_meals_view')}}
                        </h4>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.cheese_meals_view')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">


                                <div class="row">
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.code') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="code" id="code"
                                                   class="form-control @error('code') is-invalid @enderror"
                                                   placeholder="{{ __('main.code') }}" autofocus required readonly
                                                   value="{{$meal -> code}}"/>
                                            <input type="hidden" id="id" name="id" value="{{$meal -> id}}">
                                            @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.daily_meal') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="daily_milk_meal" id="daily_milk_meal"
                                                     class="form-control @error('daily_milk_meal') is-invalid @enderror"  required disabled>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($dailyMeals as $dmeal)
                                                    <option @if($dmeal -> id == $meal -> daily_milk_meal) selected @endif value="{{$dmeal -> id}}"> {{$dmeal -> code}}
                                                        @if($dmeal -> type == 0)
                                                            <span class="badge bg-primary">{{__('main.daily_meal_type0')}}</span>
                                                        @elseif($dmeal -> type == 1)
                                                            <span class="badge bg-info">{{__('main.daily_meal_type1')}}</span>
                                                        @endif
                                                        <br>
                                                        <span style="color: grey">  {{\Carbon\Carbon::parse($dmeal -> date) -> format('Y-m-d') }}</span>
                                                        <br>
                                                        <span style="color: grey"> ({{$dmeal -> client_name}})</span>
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('daily_milk_meal')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror

                                        </div>

                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.item') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="item_id" id="item_id"
                                                     class="form-control @error('item_id') is-invalid @enderror"  required disabled>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($items as $item)
                                                    <option @if($item -> id == $meal -> item_id) selected @endif value="{{$item -> id}}"> {{$item -> name}}  </option>
                                                @endforeach
                                            </select>

                                            @error('item_id')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror

                                        </div>

                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.milk_weight') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="milk_weight" id="milk_weight"
                                                   class="form-control @error('milk_weight') is-invalid @enderror"
                                                   placeholder="{{ __('main.milk_weight') }}" autofocus required readonly
                                            value="{{$meal -> milk_weight}}"/>
                                            @error('milk_weight')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.cheese_qnt') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="number" step="any" name="quantity" id="quantity"
                                                   class="form-control @error('quantity') is-invalid @enderror"
                                                   placeholder="{{ __('main.cheese_qnt') }}" autofocus required
                                                   value="{{$meal -> quantity}}" readonly/>
                                            @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.cheese_weight') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="number" step="any" name="weight" id="weight"
                                                   class="form-control @error('weight') is-invalid @enderror"
                                                   placeholder="{{ __('main.cheese_weight') }}" autofocus required
                                                   value="{{$meal -> weight}}" readonly/>
                                            @error('weight')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.average_weight_per_milk_litter') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="number" step="any" name="average_weight_per_milk_litter" id="average_weight_per_milk_litter"
                                                   class="form-control @error('average_weight_per_milk_litter') is-invalid @enderror"
                                                   placeholder="{{ __('main.average_weight_per_milk_litter') }}" autofocus required
                                                   value="{{$meal -> average_weight_per_milk_litter}}" readonly/>
                                            @error('average_weight_per_milk_litter')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.average_productivity_per_cheese_disk') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="number" step="any" name="average_productivity_per_cheese_disk" id="average_productivity_per_cheese_disk"
                                                   class="form-control @error('average_productivity_per_cheese_disk') is-invalid @enderror"
                                                   placeholder="{{ __('main.average_productivity_per_cheese_disk') }}" autofocus
                                                   required readonly value="{{$meal -> average_productivity_per_cheese_disk}}" readonly/>
                                            @error('average_productivity_per_cheese_disk')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.productivity') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="number" step="any" name="productivity" id="productivity"
                                                   class="form-control @error('productivity') is-invalid @enderror"
                                                   placeholder="{{ __('main.productivity') }}" autofocus required readonly
                                                   value="{{$meal -> productivity}}"/>
                                            @error('productivity')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.cost_per_cheese_kilo') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="number" step="any" name="cost_per_cheese_kilo" id="cost_per_cheese_kilo"
                                                   class="form-control @error('cost_per_cheese_kilo') is-invalid @enderror"
                                                   placeholder="{{ __('main.cost_per_cheese_kilo') }}" autofocus required readonly
                                                   value="{{$meal -> cost_per_cheese_kilo}}"/>
                                            @error('cost_per_cheese_kilo')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.notes') }} </label>
                                            <textarea type="text" name="notes" id="notes" readonly
                                                      class="form-control @error('notes') is-invalid @enderror"
                                                      placeholder="{{__('main.notes')}}" autofocus rows="2"> {{$meal -> notes}}</textarea>
                                            @error('notes')
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
