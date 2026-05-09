<!DOCTYPE html>

@include('layouts.head')

<style>
    .week-square {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 1rem;
        margin: 0.5rem;
        text-align: center;
        height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #f8f9fa;
        box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
        cursor: pointer;
    }
    .week-title {
        font-weight: 600;
        font-size: 1.1rem;
    }
    .week-subtitle {
        font-size: 0.9rem;
        color: #555;
        margin-top: 0.3rem;
    }
    .week-state {
        width: fit-content;
        display: block;
        margin: 10px auto;
        padding-left: 15px;
        padding-right: 15px;
    }
    .cell {
        min-width: 120px !important;
        max-width: 120px !important;
        white-space: break-spaces;
    }
    input {
        width: 100% !important;
        max-width: 100% !important;
    }
    td{
        padding: 5px !important
    }

    .table-responsive {
        max-height: 400px; /* or your desired height */
        overflow-y: auto;
        overflow-x: auto;
    }

    thead th {
        position: sticky !important;
        top: 0 !important;
        background: white !important;
        z-index: 100 !important;
    }
</style>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 5 , 'subSlag' => 52])
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
                            <span class="text-muted fw-light">{{__('main.cars_department')}} /</span> {{__('main.cars_meals')}}
                        </h4>
                      @if($meal)
                        @if( $meal -> state == 0)

                            <button type="button" class="btn btn-primary"  id="postBtn" style="height: 45px"
                                    data-id="{{ $meal->id  }}" >
                                {{__('main.save_btn')}}  <span class="tf-icons bx bx-save"></span>&nbsp;
                            </button>
                        @else
                            <h4 class="fw-bold py-3 mb-4 text-danger"> {{__('main.posted_meal')}} </h4>

                            @endif
                        @endif
                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.cars_meals')}}
                        (
                            @if (Config::get('app.locale')=='en' )
                                {{$dayName}}
                            @else
                                {{$dayName_ar}}
                            @endif
                            <span style="color: grey">  {{\Carbon\Carbon::parse($startOfWeek) -> format('Y-m-d') }}</span>

                            ---
                            @if (Config::get('app.locale')=='en' )
                                {{$end_dayName}}
                            @else
                                {{$end_dayName_ar}}
                            @endif
                            <span style="color: grey">  {{\Carbon\Carbon::parse($endOfWeek) -> format('Y-m-d') }}</span>
                            )
                        </h5>
                        <input type="hidden" id="start" name="start" value="{{$startOfWeek}}">
                        <input type="hidden" id="end" name="end" value="{{$endOfWeek}}">
                        <input type="hidden" id="wid" name="wid" value="{{$wid}}">

                        @include('flash-message')
                           <h2 style="font-size: 11px ; color: red ; margin-right: 10px ; margin-left: 10px">{{__('main.milk_meal_note')}}</h2>

                            <div class="table-responsive  text-nowrap">

                                <table class="table table-striped table-hover  table-bordered view_table">
                                        <thead>
                                        @php
                                            use Carbon\Carbon;
                                            use Carbon\CarbonPeriod;

                                            $start = Carbon::parse($startOfWeek);
                                            $end = Carbon::parse($endOfWeek);
                                            $period = CarbonPeriod::create($start, $end);
                                            Carbon::setLocale('ar');
                                        @endphp
                                        <tr>
                                            <th class="text-center" rowspan="3">#</th>
                                            <th class="text-center" rowspan="3">{{__('main.supplier')}}</th>
                                            @foreach ($period as $date)
                                                <th colspan="2" class="text-center">
                                                            @if (Config::get('app.locale')=='ar' )
                                                                    {{ $date->translatedFormat('l') }}
                                                            @else
                                                                    {{ $date->format('l') }}

                                                            @endif

                                                </th>

                                            @endforeach
                                            <th class="text-center cell"  rowspan="2">{{__('main.total')}}</th>
                                            <th class="text-center cell" colspan="1" rowspan="2">{{__('main.price')}}</th>
                                            <th class="text-center cell" colspan="1" rowspan="3">{{__('main.total_cash')}}</th>
                                            <th class="text-center cell" rowspan="3">{{ __('main.actions') }}</th>
                                        </tr>
                                        <tr>
                                            @foreach ($period as $date)
                                                <th class="text-center text-primary"  >{{ __('main.morning_meal') }}</th>
                                                <th class="text-center text-success" >{{ __('main.evening_meal') }}</th>
                                            @endforeach
                                        </tr>

                                            <tr>
                                                @foreach ($period as $date)
                                                <th class="text-center cell text-primary">{{__('main.bovine_weight')}}</th>
                                                <th class="text-center cell" hidden="hidden">{{__('main.buffalo_weight')}}</th>
                                                <th class="text-center cell text-success">{{__('main.bovine_weight')}}</th>
                                                <th class="text-center cell" hidden="hidden">{{__('main.buffalo_weight')}}</th>
                                                @endforeach
                                                    <th class="text-center cell" >{{ __('main.total_bovine_weight') }}</th>
                                                    <th class="text-center cell" hidden="hidden">{{ __('main.total_buffalo_weight') }}</th>
                                                    <th class="text-center cell" >{{ __('main.bovine_milk_price') }}</th>
                                                    <th class="text-center cell" hidden="hidden">{{ __('main.buffalo_milk_price') }}</th>

                                            </tr>
                                            </thead>
                                    <tbody>


                                        @include('admin.DailyMeals.empty')



                                    <tbody>




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

<script>
    $(document).ready(function() {
        $('#postBtn').on('click', function(e) {
            e.preventDefault(); // prevent default behavior if it's a <button> or <a>
            const mealId = $(this).data('id');
            Swal.fire({
                title: 'ترحيل و إقفال الأسبوع',
                text: 'هل انت متأكد من ترحيل و إقفال الأسبوع ؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم , متأكد',
                cancelButtonText: 'لا , تراجع'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with deletion or any other logic
                    postAndCloseWeakMeal(mealId);
                }
            });
        });
    });

    function postAndCloseWeakMeal(id){
        //mealCarryOver
        const overlay = document.getElementById('closing-overlay');

        // Show the overlay
        overlay.style.display = 'flex';

        // Create a timer that ensures the loading stays for at least 10 seconds
        const minDuration = 5000; // 5 seconds
        const startTime = Date.now();

        fetch(`/mealCarryOver/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'warning') {
                    // Display warning toast
                    toastr.warning(data.message);
                } else if(data.status == 'success') {
                    // continue normal behavior
                    Swal.fire({
                        title: 'تم الترحيل بنجاح',
                        text: 'يمكنك استعراض الوجبات بدون تعديل',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'نعم , افهم ذلك',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Proceed with deletion or any other logic
                            let url = "{{ route('milk_meals') }}";
                            document.location.href=url;
                        }
                    });


                } else {
                    toastr.error(data.message);
                    console.log(data.debug);
                }

            })
            .catch(err => {
                console.error('Error loading milk meal data:', err);
            })
            .finally(() => {
                const elapsed = Date.now() - startTime;
                const remaining = minDuration - elapsed;

                setTimeout(() => {
                    overlay.style.display = 'none';
                }, Math.max(remaining, 0));
            });
    }
</script>
</body>
</html>
