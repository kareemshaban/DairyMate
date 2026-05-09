<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 3 , 'subSlag' => 32])
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
                            <span class="text-muted fw-light">{{__('main.milk_department')}} /</span> {{__('main.car_meal_check')}}
                        </h4>


                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.cars_meals')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.code')}}</th>
                                    <th class="text-center"> {{__('main.car')}}</th>
                                    <th class="text-center"> {{__('main.weakly_meal')}}</th>
                                    <th class="text-center">{{__('main.daily_meal_type')}}</th>
                                    <th class="text-center">{{__('main.date_time')}}</th>
                                    <th class="text-center">{{__('main.supplier')}}</th>
                                    <th class="text-center">{{__('main.buffalo_weight')}}</th>
                                    <th class="text-center">{{__('main.bovine_weight')}}</th>
                                    <th class="text-center">{{__('main.meal_state')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($meals as $meal)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$meal -> code}}</td>
                                        <td class="text-center"> {{$meal -> car_code}} - <span class="badge bg-success"> {{$meal -> car_number}} </span>
                                            <br>
                                            <span style="color: grey">  {{$meal -> driver_name}}  </span>

                                        </td>
                                        <td class="text-center"> {{$meal -> weakly_meal_code}}
                                            <br>
                                            <span style="color: grey">  {{\Carbon\Carbon::parse($meal -> start_date) -> format('Y-m-d') }} -- {{\Carbon\Carbon::parse($meal -> start_date) -> format('Y-m-d') }}  </span>

                                        </td>
                                        <td class="text-center">
                                            @if($meal -> type == 0)
                                                <span class="badge bg-primary">{{__('main.daily_meal_type0')}}</span>
                                            @elseif($meal -> type == 1)
                                                <span class="badge bg-info">{{__('main.daily_meal_type1')}}</span>
                                            @endif

                                        </td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($meal -> date) -> format('Y-m-d H:i')}}</td>
                                        <td class="text-center">{{$meal -> client_name}}</td>
                                        <td class="text-center">{{$meal -> buffalo_weight}}</td>
                                        <td class="text-center">{{$meal -> bovine_weight}}</td>
                                        <td class="text-center">
                                            @if($meal -> state == 0)
                                                <span class="badge bg-warning">{{__('main.car_meal_state0')}}</span>
                                            @elseif($meal -> state == 1)
                                                <span class="badge bg-success">{{__('main.car_meal_state1')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">

                                            <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                <i class='bx bxs-show text-success viewBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.view_action')}}"
                                                   id="{{$meal -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                <i class='bx bxs-cloud-upload text-primary postBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.car_post_action')}}"
                                                   id="{{$meal -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
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


@include('admin.CarsMeals.view')
@include('admin.CarsMeals.post')
@include('admin.CarsMeals.alertModal')
@include('layouts.footer')
<script></script>
<script type="text/javascript">
    var id = 0 ;

    $(document).on('click', '.viewBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/car_meals-show' + '/' + id,
            dataType: 'json',

            success:function(response){
                console.log(response);
                var date = new Date(response.date);
                var day = date.getDate(),
                    month = date.getMonth() + 1,
                    year = date.getFullYear(),
                    hour = date.getHours() ,
                    mins = date.getMinutes();
                month = (month < 10 ? "0" : "") + month;
                day = (day < 10 ? "0" : "") + day;
                hour = (hour < 10 ? "0" : "") + hour ;
                mins = (mins < 10 ? "0" : "") + mins ;
                var start_date = year + "-" + month + "-" + day + " " + hour + ":" + mins ;

                if(response){
                    let href = $(this).attr('data-attr');
                    $.ajax({
                        url: href,
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        // return the result
                        success: function(result) {
                            $('#viewModal').modal("show");
                            $(".modal-body #code").val( response.code );
                            $(".modal-body #date").val(start_date );
                            $(".modal-body #weakly_meal_id").val( response.weakly_meal_id );
                            $(".modal-body #weakly_meal_id_hidden").val( response.weakly_meal_id );
                             $(".modal-body #weak_meal").val( response.weak_meal );
                            $(".modal-body #type").val( response.type );
                            $(".modal-body #supplier_id").val( response.supplier_id );
                            $(".modal-body #car_id").val( response.supplier_id );
                            $(".modal-body #buffalo_weight").val( response.buffalo_weight );
                            $(".modal-body #bovine_weight").val( response.bovine_weight );
                            $(".modal-body #notes").val( response.notes );
                            $(".modal-body #weak_meal").show();
                            $(".modal-body #weakly_meal_id").hide();

                            $(".modal-body #id").val(response.id);
                            var translatedText = "{{ __('main.viewData') }}";
                            $(".modelTitle").html(translatedText);
                            getSupplier(response.supplier_id );
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
                } else {

                }
            }
        });
    });
    $(document).on('click', '.postBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/car_meals-show' + '/' + id,
            dataType: 'json',

            success:function(response){
                console.log(response);
                var date = new Date(response.date);
                var day = date.getDate(),
                    month = date.getMonth() + 1,
                    year = date.getFullYear(),
                    hour = date.getHours() ,
                    mins = date.getMinutes();
                month = (month < 10 ? "0" : "") + month;
                day = (day < 10 ? "0" : "") + day;
                hour = (hour < 10 ? "0" : "") + hour ;
                mins = (mins < 10 ? "0" : "") + mins ;
                var start_date = year + "-" + month + "-" + day + " " + hour + ":" + mins ;

                if(response){
                    let href = $(this).attr('data-attr');
                    $.ajax({
                        url: href,
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        // return the result
                        success: function(result) {
                            $('#postModal').modal("show");
                            $(".modal-body #code").val( response.code );
                            $(".modal-body #date").val(start_date );
                            $(".modal-body #weakly_meal_id").val( response.weakly_meal_id );
                            $(".modal-body #weakly_meal_id_hidden").val( response.weakly_meal_id );
                            $(".modal-body #weak_meal").val( response.weak_meal );
                            $(".modal-body #type").val( response.type );
                            $(".modal-body #supplier_id").val( response.supplier_id );
                            $(".modal-body #car_id").val( response.supplier_id );
                            $(".modal-body #buffalo_weight").val( response.buffalo_weight );
                            $(".modal-body #bovine_weight").val( response.bovine_weight );
                            $(".modal-body #actual_bovine_weight").val( response.bovine_weight );
                            $(".modal-body #actual_buffalo_weight").val( response.buffalo_weight );
                            $(".modal-body #notes").val( response.notes );
                            $(".modal-body #weak_meal").show();
                            $(".modal-body #weakly_meal_id").hide();

                            $(".modal-body #id").val(response.id);
                            var translatedText = "{{ __('main.postData') }}";
                            $(".modelTitle").html(translatedText);
                            getSupplier(response.supplier_id );
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
                } else {

                }
            }
        });
    });

    $(document).on('change', '.modal-body #weakly_meal_id', function () {
        const meal_id = $(this).val();
        getDailyMealCode(meal_id);
    });
    $(document).on('change', '.modal-body #type', function () {
        const meal_id = $('.modal-body #weakly_meal_id').val();
        getDailyMealCode(meal_id);
     //   checkForBouns();
    });

    $(document).on('change', '.modal-body #supplier_id', function () {
        const id = $(this).val();
        getSupplier(id);
    });

    $(document).on('change', '.modal-body #date', function () {
        const date = $(this).val();
        console.log(date);
       // checkForBouns();
    });

    function getDailyMealCode(meal_id){
        $.ajax({
            type:'get',
            url:'/car_meals-code' + '/' + meal_id,
            dataType: 'json',

            success:function(code){
                let fCode = "" ;
                if($(".modal-body #type").val() == 0){
                    fCode = 'DMM-C' + code ;
                } else {
                    fCode = 'DME-C' + code ;
                }
                $(".modal-body #code").val(fCode);
            }
        });
    }

    function checkForBouns(){
        $.ajax({
            type:'get',
            url:'/bouns-check' + '/' + $('.modal-body #type').val() + '/' + $('.modal-body #date').val(),
            dataType: 'json',

            success:function(bouns){

                $(".modal-body #hasBonus").val(bouns);
                $(".modal-body #hasBonusS").val(bouns);
            }
        });
    }

    function getSupplier(id){
        $.ajax({
            type:'get',
            url:'/suppliers-show' + '/' + id,
            dataType: 'json',

            success:function(client){
                 console.log(client);
                $(".modal-body #buffalo_min_limit").val(client.buffalo_min_limit);
                $(".modal-body #buffalo_max_limit").val(client.buffalo_max_limit);
                $(".modal-body #bovine_min_limit").val(client.bovine_min_limit);
                $(".modal-body #bovine_max_limit").val(client.bovine_max_limit);
            }
        });
    }

    function  valdiateRequest(){
        let msg = '' ;
        let buffalo_min_limit =  parseFloat($(".modal-body #buffalo_min_limit").val());
        let buffalo_max_limit = parseFloat($(".modal-body #buffalo_max_limit").val());
        let bovine_min_limit = parseFloat($(".modal-body #bovine_min_limit").val());
        let bovine_max_limit = parseFloat($(".modal-body #bovine_max_limit").val());

        let buffalo_weight = parseFloat($(".modal-body #buffalo_weight").val());
        let bovine_weight = parseFloat($(".modal-body #bovine_weight").val());
        if(buffalo_min_limit > 0 && buffalo_max_limit > 0){
            if(!isNaN(buffalo_min_limit) &&  !isNaN(buffalo_max_limit) ) {
                if (buffalo_weight > buffalo_min_limit && buffalo_weight < buffalo_max_limit) {

                } else {
                    msg =  'برجاء العلم ان وزن اللبن الجاموسي خارج حدود سجلات المورد ' + "\n" ;
                }

            } else {

            }
        }

        if(bovine_min_limit > 0 && bovine_max_limit > 0) {
            if (!isNaN(bovine_min_limit) && !isNaN(bovine_max_limit)) {
                if (bovine_weight > bovine_min_limit && bovine_weight < bovine_max_limit) {

                } else {
                    msg += 'برجاء العلم ان وزن اللبن البقري خارج حدود سجلات المورد ' + "\n";
                }

            } else {

            }
        }
        if(msg == ''){
           $('#MealForm').submit();

        } else {
            $('#createModal').modal("hide");
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

        $('#MealForm').submit();
    });
    $(document).on('click', '.back-btn', function(event) {
        $('#confirmtModal').modal("hide");
        $('#createModal').modal("show");

    });
    //
</script>
</body>
</html>
