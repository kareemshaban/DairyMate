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
</style>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 18 , 'subSlag' => 182])
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
                            <span class="text-muted fw-light">{{__('main.employee_department')}} /</span> {{__('main.attendances')}}
                        </h4>
                        @if($weekState == 0)
                        <button type="button" class="btn btn-primary"  id="postButton" style="height: 45px">
                            {{__('main.post_close_btn')}}  <span class="tf-icons bx bx-cloud"></span>&nbsp;
                        </button>

                            @else

                            <button type="button" class="btn btn-info"  id="salaryButton" style="height: 45px">
                                {{__('main.salary_btn')}}  <span class="tf-icons bx bx-money"></span>&nbsp;
                            </button>
                        @endif

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.attendances')}}</h5>
                        @include('flash-message')

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
                                    <th class="text-center" rowspan="3">{{__('main.employee')}}</th>
                                    @foreach ($period as $date)
                                        <th colspan="2" class="text-center">
                                            @if (Config::get('app.locale')=='ar' )
                                                {{ $date->translatedFormat('l') }}
                                            @else
                                                {{ $date->format('l') }}

                                            @endif
                                            <br>
                                            <span> {{ Carbon::parse($date) -> format('d-m-Y') }} </span>

                                        </th>

                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($period as $date)
                                        <th class="text-center text-primary"  >{{ __('main.morning_shift') }}</th>
                                        <th class="text-center text-success" >{{ __('main.evening_shift') }}</th>
                                    @endforeach
                                </tr>


                                </thead>
                                <tbody>
                                   @foreach($result as $item)
                                       <tr>
                                           <td class="text-center"> {{$loop -> index + 1}} </td>
                                           <td class="text-center"> {{$item['employee_name']}} </td>
                                           @foreach($item['attends'] as $attend)
                                               <td class="text-center">
                                                   <input type="checkbox" class="form-check" name="morning_present[]"
                                                          style="display: block;margin: auto;width: 40px;"
                                                          @if($attend['morning_present'] == 1) checked @endif
                                                          @if($weekState == 1) disabled @endif
                                                          data-employee="{{$item['employee_id']}}"
                                                          data-date="{{$attend['date']}}" data-type="0">
                                               </td>
                                               <td class="text-center">
                                                   <input type="checkbox" class="form-check" name="evening_present[]"
                                                          style="display: block;margin: auto;width: 40px;"
                                                          @if($attend['evening_present'] == 1) checked @endif
                                                          @if( $weekState == 1) disabled @endif
                                                          data-employee="{{$item['employee_id']}}"
                                                          data-date="{{$attend['date']}}" data-type="1">
                                               </td>
                                           @endforeach
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

<div id="loading-overlay">
    <div class="loader"></div>
    <div class="loading-text">{{__('main.month_loading')}}</div>
</div>

@include('layouts.footer')
<script>
    $(document).ready(function() {
        $('#postButton').on('click', function() {
            // الكود اللي عايز تنفذه عند الضغط على الزر
            Swal.fire({
                title: 'إقفال دوام الأسبوع',
                text: 'هل انت متأكد من أنك تريد إقفال دوام الإسبوع و ترحيل البيانات لصرف الرواتب ؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم , متأكد',
                cancelButtonText: 'لا , تراجع'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with deletion or any other logic
                    postAttends();
                }
            });
        });

        $('#salaryButton').on('click', function() {
            let startOfWeek = @json($startOfWeek);
            let endOfWeek = @json($endOfWeek);
            let url = "{{ route('showSalaries', ['start' => ':start', 'end' => ':end']) }}";
            url = url.replace(':start', startOfWeek).replace(':end', endOfWeek);
            document.location.href=url;

        });




    });
    $(document).on('change', '.form-check', function() {
        const employeeId = $(this).data('employee');
        const date = $(this).data('date');
        const type = $(this).data('type'); // 0=morning, 1=evening
        const present = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('store-attendance') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                employee_id: employeeId,
                date: date,
                type: type,
                present: present
            },
            success: function(response) {
              //  console.log('Attendance updated', response);
                toastr.success("تم حفظ البيانات بنجاح");
            },
            error: function(xhr) {
                alert('Error updating attendance');
                console.log(xhr.responseText);
                toastr.error("حدث خطأ في عملية الحفظ");
            }
        });
    });



    function postAttends(){
        let startOfWeek = @json($startOfWeek);
        let endOfWeek = @json($endOfWeek);
        $.ajax({
        url: "{{ route('post-attendance') }}", // using route name
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}', // Laravel CSRF token
            start_date: startOfWeek,
            end_date: endOfWeek
        },
        success: function(response) {
            console.log('Success:', response);
            // You can handle UI updates here

            $('.form-check').each(function() {
                $(this).prop('disabled', true);
            });

            $('#postButton').hide();

            // Show the salaryButton
            $('#salaryButton').show();


            toastr.success("تم إقفال هذا الأسبوع بنجاح");
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);

            toastr.error("حدث خطأ في عملية الإقفال");
        }
    });
    }
</script>


</body>
</html>
