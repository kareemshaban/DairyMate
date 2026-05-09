<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 6 , 'subSlag' => 61])
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
                            <span class="text-muted fw-light">{{__('main.milk_department')}} /</span> {{__('main.daily_meals')}}
                        </h4>
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.daily_meals_list')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.code')}}</th>
                                    <th class="text-center"> {{__('main.weakly_meal')}}</th>
                                    <th class="text-center">{{__('main.daily_meal_type')}}</th>
                                    <th class="text-center">{{__('main.date_time')}}</th>
                                    <th class="text-center">{{__('main.supplier')}}</th>
                                    <th class="text-center">{{__('main.buffalo_weight')}}</th>
                                    <th class="text-center">{{__('main.bovine_weight')}}</th>
                                    <th class="text-center">{{__('main.hasBonus')}}</th>
                                    <th class="text-center">{{__('main.bonus_value')}}</th>
                                    <th class="text-center">{{__('main.total_cash')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($meals as $meal)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$meal -> code}}</td>
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
                                            @if($meal -> hasBonus == 0)
                                                <span class="badge bg-danger">{{__('main.hasBonus0')}}</span>
                                            @elseif($meal -> hasBonus == 1)
                                                <span class="badge bg-success">{{__('main.hasBonus1')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{$meal -> bonus}}</td>
                                        <td class="text-center">{{$meal -> total}}</td>
                                        <td class="text-center">

                                            <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                <i class='bx bxs-edit-alt text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                   id="{{$meal -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
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

@include('admin.DailyMeals.create')
@include('admin.DailyMeals.deleteModal')
@include('admin.DailyMeals.alertModal')
@include('layouts.footer')
<script></script>
<script type="text/javascript">
    var id = 0 ;
    $(document).on('click', '#createButton', function (event) {
        console.log('clicked');
        id = 0;
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function () {
                $('#loader').show();
            },
            // return the result
            success: function (result) {
                var date = new Date();
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
                $('#createModal').modal("show");
                $(".modal-body #id").val(0);
                $(".modal-body #date").val(start_date);
                $(".modal-body #weakly_meal_id").val("");
                $(".modal-body #code").val("");
                $(".modal-body #type").val("0");
                $(".modal-body #supplier_id").val("");
                $(".modal-body #buffalo_weight").val("0");
                $(".modal-body #bovine_weight").val("0");
                $(".modal-body #hasBonus").val("0");
                $(".modal-body #hasBonusS").val("0");
                $(".modal-body #buffalo_min_limit").val("0");
                $(".modal-body #buffalo_max_limit").val("0");
                $(".modal-body #bovine_min_limit").val("0");
                $(".modal-body #bovine_max_limit").val("0");
                $(".modal-body #bonus").val("0");
                $(".modal-body #total").val("0");
                $(".modal-body #buffalo_price").val("0");
                $(".modal-body #bovine_price").val("0");
                $(".modal-body #weakly_meal_id_hidden").val( "0" );
                $(".modal-body #weak_meal").hide();
                $(".modal-body #weakly_meal_id").show();


                checkForBouns();
                $(".modal-body #notes").val("");
                var translatedText = "{{ __('main.newData') }}";
                $(".modelTitle").html(translatedText);




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
    });
    $(document).on('click', '.editBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/daily_meals-show' + '/' + id,
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
                            $('#createModal').modal("show");
                            $(".modal-body #code").val( response.code );
                            $(".modal-body #date").val(start_date );
                            $(".modal-body #weakly_meal_id").val( response.weakly_meal_id );
                            $(".modal-body #weakly_meal_id_hidden").val( response.weakly_meal_id );
                             $(".modal-body #weak_meal").val( response.weak_meal );
                            $(".modal-body #type").val( response.type );
                            $(".modal-body #supplier_id").val( response.supplier_id );
                            $(".modal-body #buffalo_weight").val( response.buffalo_weight );
                            $(".modal-body #bovine_weight").val( response.bovine_weight );
                            $(".modal-body #hasBonus").val( response.hasBonus );
                            $(".modal-body #notes").val( response.notes );
                            $(".modal-body #bonus").val(response.bonus);
                            $(".modal-body #total").val(response.total);
                            $(".modal-body #buffalo_price").val(response.buffalo_price);
                            $(".modal-body #bovine_price").val(response.bovine_price);
                            $(".modal-body #weak_meal").show();
                            $(".modal-body #weakly_meal_id").hide();

                            $(".modal-body #id").val(response.id);
                            var translatedText = "{{ __('main.editData') }}";
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
    $(document).on('click', '.deleteBtn', function(event) {
        id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {
                $('#deleteModal').modal("show");
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

        confirmDelete(id);
    });
    $(document).on('click', '.cancel-modal', function(event) {
        $('#deleteModal').modal("hide");
        console.log()
        id = 0 ;
    });

    function confirmDelete(id){
        let url = "{{ route('daily_meals-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

    $(document).on('change', '.modal-body #weakly_meal_id', function () {
        const meal_id = $(this).val();
        getDailyMealCode(meal_id);
    });
    $(document).on('change', '.modal-body #type', function () {
        const meal_id = $('.modal-body #weakly_meal_id').val();
        getDailyMealCode(meal_id);
        checkForBouns();
    });

    $(document).on('change', '.modal-body #supplier_id', function () {
        const id = $(this).val();
        getSupplier(id);
    });

    $(document).on('change', '.modal-body #date', function () {
        const date = $(this).val();
        console.log(date);
        checkForBouns();
    });

    function getDailyMealCode(meal_id){
        $.ajax({
            type:'get',
            url:'/daily_meals-code' + '/' + meal_id,
            dataType: 'json',

            success:function(code){
                let fCode = "" ;
                if($(".modal-body #type").val() == 0){
                    fCode = 'DMM' + code ;
                } else {
                    fCode = 'DME' + code ;
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
