<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 7 , 'subSlag' => 73])
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
                            <span class="text-muted fw-light">{{__('main.cheese_department')}} /</span> {{__('main.posted_cheese_meals')}}
                        </h4>



                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.posted_cheese_meals')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.code')}}</th>
                                    <th class="text-center"> {{__('main.date')}}</th>
                                    <th class="text-center">{{__('main.milk_weight')}}</th>
                                    <th class="text-center">{{__('main.price')}}</th>
                                    <th class="text-center">{{__('main.cheese_qnt')}}</th>
                                    <th class="text-center">{{__('main.cheese_weight')}}</th>
                                    <th class="text-center">{{__('main.item')}}</th>
                                    <th class="text-center">{{__('main.disk_weight')}}</th>
                                    <th class="text-center">{{__('main.disk_cost')}}</th>
                                    <th class="text-center">{{__('main.productivity')}}</th>
                                    <th class="text-center">{{__('main.cost_per_cheese_kilo')}}</th>
                                    <th class="text-center">{{__('main.keshta_weight')}}</th>
                                    <th class="text-center">{{__('main.cream_of_kilo_milk')}}</th>
                                    <th class="text-center">{{__('main.proten_weight')}}</th>
                                    <th class="text-center">{{__('main.protein_of_kilo_milk')}}</th>
                                    <th class="text-center">{{__('main.net_value')}}</th>
                                    <th class="text-center">{{__('main.meal_state')}}</th>
                                    <th class="text-center" hidden="hidden">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($meals as $meal)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$meal -> code}}</td>
                                        <td class="text-center">
                                            @php
                                                $locale = app()->getLocale(); // 'en' or 'ar'
                                                $dayName = \Carbon\Carbon::parse($meal -> date)->locale($locale)->isoFormat('dddd');
                                            @endphp
                                            {{ $dayName }}
                                             <br>
                                            <small class="text-gray"> {{\Carbon\Carbon::parse($meal -> date) -> format('Y-m-d')}} </small>
                                        </td>
                                        <td class="text-center">{{$meal -> milk_weight}}</td>
                                        <td class="text-center">{{$meal -> bovine_price}}</td>
                                        <td class="text-center">{{$meal -> quantity}}</td>
                                        <td class="text-center">{{$meal -> weight}}</td>
                                        <td class="text-center">{{$meal -> item}}</td>
                                        <td class="text-center">{{$meal -> disk_weight}}</td>
                                        <td class="text-center">{{$meal -> disk_cost}}</td>
                                        <td class="text-center">{{$meal -> productivity}}</td>
                                        <td class="text-center">{{$meal -> cost_per_cheese_kilo}}</td>
                                        <td class="text-center">{{$meal -> cream_weight}}</td>
                                        <td class="text-center">{{$meal -> cream_of_kilo_milk}}</td>
                                        <td class="text-center">{{$meal -> protein_weight}}</td>
                                        <td class="text-center">{{$meal -> protein_of_kilo_milk}}</td>
                                        <td class="text-center">{{$meal -> net}}</td>
                                        <td class="text-center">
                                            @if($meal -> state == 0)
                                                <span class="badge bg-success">{{__('main.new_meal')}}</span>
                                                @else
                                                <span class="badge bg-danger">{{__('main.posted_meal')}}</span>
                                            @endif

                                        </td>
                                        <td class="text-center" hidden="hidden">
                                          @if($meal -> state == 0)
                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <a href="{{route('cheese_meal_edit' , $meal -> id)}}">
                                                        <i class='bx bxs-edit-alt text-success' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                           style="font-size: 25px ; cursor: pointer"></i>
                                                    </a>
                                                    <a href="{{route('stock_in_create' , $meal -> id)}}">
                                                        <i class='bx bxs-cloud-upload text-primary' data-toggle="tooltip" data-placement="top" title="{{__('main.post_action')}}"
                                                           style="font-size: 25px ; cursor: pointer"></i>
                                                    </a>

                                                    <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       id="{{$meal -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                </div>
                                              @else
                                                <a href="{{route('cheese_meal_view' , $meal -> id)}}">
                                                    <i class='bx bxs-show text-primary' data-toggle="tooltip" data-placement="top" title="{{__('main.view_action')}}"
                                                       style="font-size: 25px ; cursor: pointer"></i>
                                                </a>


                                            @endif


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

@include('admin.CheeseMeals.deleteModal')
@include('admin.DailyMeals.create')
@include('layouts.footer')
<script></script>
<script type="text/javascript">
    var id = 0 ;

    $(document).on('click', '.daily_meal_hl', function(event) {
        let code = $(this).data('id');

        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/get_daily_meal_by_code' + '/' + code,
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
                            $(".modal-body #action_row").hide();

                            var translatedText = "{{ __('main.viewData') }}";
                            $(".modelTitle").html(translatedText);

                            $(".modal-body #weakly_meal_id").prop("disabled", true);
                            $(".modal-body #supplier_id").prop("disabled", true);
                            $(".modal-body #type").prop("disabled", true);
                            $(".modal-body #date").prop("disabled", true);
                            $(".modal-body #buffalo_weight").prop("disabled", true);
                            $(".modal-body #bovine_weight").prop("disabled", true);
                            $(".modal-body #notes").prop("disabled", true);

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
        let url = "{{ route('cheese_meals-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }


    //
</script>
</body>
</html>
