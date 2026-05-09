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
                            <span class="text-muted fw-light">{{__('main.milk_department')}} /</span> {{__('main.milk_meals_posted')}}
                        </h4>


                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.milk_meals_posted')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.code')}}</th>
                                    <th class="text-center">{{__('main.start_date')}}</th>
                                    <th class="text-center">{{__('main.end_date')}}</th>
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
                                        <td class="text-center">{{$meal -> total_bovine_weight}}</td>
                                        <td class="text-center">{{$meal -> total_money}}</td>
                                        <td class="text-center">


                                    <i class='bx bx-show text-primary viewBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.view_action')}}"
                                       style="font-size: 25px ; cursor: pointer" data-date="{{$meal -> start_date}}" data-id="{{$meal -> id}}"></i>



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


@include('layouts.footer')
<script></script>
<script type="text/javascript">
    $(document).on('click', '.viewBtn', function() {
        const dateStr = $(this).data('date');
        const id = $(this).data('id');

        const date = new Date(dateStr);

        const day = date.getDate(); // 30
        const month = date.getMonth() + 1; // 5 (Months are 0-based, so +1)
        const year = date.getFullYear();

        let url = "{{ route('getWeaklyMeal', [':id', ':month', ':year' , ':day']) }}";

        url = url.replace(':id', id);
        url = url.replace(':month', month);
        url = url.replace(':year', year);
        url = url.replace(':day', day);
        document.location.href=url;



    });

</script>
</body>
</html>
