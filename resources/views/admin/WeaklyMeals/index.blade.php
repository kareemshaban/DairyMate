<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 3 , 'subSlag' => 31])
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
                            <span class="text-muted fw-light">{{__('main.milk_department')}} /</span> {{__('main.weakly_meals_list')}}
                        </h4>
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.weakly_meals_list')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.code')}}</th>
                                    <th class="text-center">{{__('main.start_date')}}</th>
                                    <th class="text-center">{{__('main.end_date')}}</th>
                                    <th class="text-center">{{__('main.mealState')}}</th>
                                    <th class="text-center">{{__('main.total_buffalo_weight')}}</th>
                                    <th class="text-center">{{__('main.total_bovine_weight')}}</th>
                                    <th class="text-center">{{__('main.total_money')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($meals as $meal)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$meal -> code}}</td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($meal -> start_date) -> format('Y-m-d') }}</td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($meal -> end_date) -> format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            @if($meal -> state == 0)
                                                <span class="badge bg-success">{{__('main.mealState0')}}</span>
                                            @elseif($meal -> state == 1)
                                                <span class="badge bg-danger">{{__('main.mealState1')}}</span>
                                            @endif

                                        </td>
                                        <td class="text-center">{{$meal -> total_buffalo_weight}}</td>
                                        <td class="text-center">{{$meal -> total_bovine_weight}}</td>
                                        <td class="text-center">{{$meal -> total_money}}</td>
                                        <td class="text-center">

                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    @if($meal -> state == 0)
                                                    <i class='bx bxs-edit-alt text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$meal -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       id="{{$meal -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    @endif

                                                    <a href="{{route('view_weakly_meal' , $meal -> id)}}">
                                                        <i class='bx bx-show text-primary' data-toggle="tooltip" data-placement="top" title="{{__('main.view_action')}}"
                                                           style="font-size: 25px ; cursor: pointer"></i>
                                                    </a>

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

@include('admin.WeaklyMeals.create')
@include('admin.WeaklyMeals.deleteModal')
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
                    year = date.getFullYear();
                var nextDay = date.getDate() + 7 ;
                month = (month < 10 ? "0" : "") + month;
                day = (day < 10 ? "0" : "") + day;
                var currentDate = year + "-" + month + "-" + day ;
                var nextDate = year + "-" + month + "-" + nextDay ;

                $.ajax({
                    type:'get',
                    url:'/weakly_meals-code',
                    dataType: 'json',

                    success:function(code){
                        console.log(nextDate);
                        $('#createModal').modal("show");
                        $(".modal-body #id").val(0);
                        $(".modal-body #start_date").val(currentDate);
                        $(".modal-body #end_date").val(nextDate);
                        $(".modal-body #code").val(code);
                        $(".modal-body #state").val("0");
                        $(".modal-body #stateS").val("0");
                        $(".modal-body #price_buffalo").val("0");
                        $(".modal-body #price_bovine").val("0");
                        $(".modal-body #total_buffalo_weight").val("0");
                        $(".modal-body #total_bovine_weight").val("0");
                        $(".modal-body #total_money").val("0");
                        $(".modal-body #number_of_daily_meals").val("0");
                        $(".modal-body #notes").val("");

                        var translatedText = "{{ __('main.newData') }}";
                        $(".modelTitle").html(translatedText);
                    }
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
    });
    $(document).on('click', '.editBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/weakly_meals-show' + '/' + id + '/' + '0',
            dataType: 'json',

            success:function(response){
                console.log(response);
                var date = new Date(response.start_date);
                var day = date.getDate(),
                    month = date.getMonth() + 1,
                    year = date.getFullYear();

                month = (month < 10 ? "0" : "") + month;
                day = (day < 10 ? "0" : "") + day;
                var start_date = year + "-" + month + "-" + day ;

                var date2 = new Date(response.end_date);
                var day2 = date2.getDate(),
                    month2 = date2.getMonth() + 1,
                    year2 = date2.getFullYear();
                month2 = (month2 < 10 ? "0" : "") + month2;
                day2 = (day2 < 10 ? "0" : "") + day2;
                var end_date = year2 + "-" + month2 + "-" + day2 ;

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
                            $(".modal-body #start_date").val(start_date );
                            $(".modal-body #end_date").val( end_date );
                            $(".modal-body #state").val( response.state );
                             $(".modal-body #stateS").val( response.state );
                            $(".modal-body #price_buffalo").val( response.price_buffalo );
                            $(".modal-body #price_bovine").val( response.price_bovine );
                            $(".modal-body #total_buffalo_weight").val( response.total_buffalo_weight );
                            $(".modal-body #total_bovine_weight").val( response.total_bovine_weight );
                            $(".modal-body #total_money").val( response.total_money );
                            $(".modal-body #number_of_daily_meals").val( response.number_of_daily_meals );
                            $(".modal-body #notes").val( response.notes );
                            $(".modal-body #id").val(response.id);
                            var translatedText = "{{ __('main.editData') }}";
                            $(".modelTitle").html(translatedText);

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
        let url = "{{ route('weakly_meals-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

</script>
</body>
</html>
