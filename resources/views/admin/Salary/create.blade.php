<!DOCTYPE html>

@include('layouts.head')


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
                            <span class="text-muted fw-light">{{__('main.employee_department')}} /</span> {{__('main.salaries')}}
                        </h4>
                        @if(!$isSalaryCreated )

                            <button type="button" class="btn btn-warning"  id="salaryButton" style="height: 45px">
                                {{__('main.confirm_salary')}}  <span class="tf-icons bx bx-money"></span>&nbsp;
                            </button>

                            @else
                            <h4 class="text-danger"> تم صرف رواتب هذا الأسبوع </h4>
                        @endif

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        @php
                            use Carbon\Carbon;
                        @endphp

                        <h5 class="card-header">
                            {{ __('main.salaries') }} -
                            {{ Carbon::parse($startOfWeek)->translatedFormat('l d-m-Y') }}
                            --
                            {{ Carbon::parse($endOfWeek)->translatedFormat('l d-m-Y') }}
                        </h5>
                        @include('flash-message')

                        <div class="table-responsive  text-nowrap">

                            <table class="table table-striped table-hover  table-bordered view_table">
                                <thead>

                                <tr>
                                    <th class="text-center" rowspan="3">#</th>
                                    <th class="text-center" rowspan="3">{{__('main.employee')}}</th>
                                    <th class="text-center" rowspan="3">{{__('main.shift_count')}}</th>
                                    <th class="text-center" rowspan="3">{{__('main.day_count')}}</th>
                                    <th class="text-center" rowspan="3">{{__('main.weekly_salary')}}</th>
                                    <th class="text-center" rowspan="3">{{__('main.salary')}}</th>
                                    <th class="text-center" rowspan="3">{{__('main.advances')}}</th>
                                    <th class="text-center" rowspan="3">{{__('main.net')}}</th>
                                </tr>

                                </thead>
                                <tbody id="details">


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
        let startOfWeek = @json($startOfWeek);
        let endOfWeek = @json($endOfWeek);
        $.ajax({
            url: "{{ route('getSalaries') }}", // using route name
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Laravel CSRF token
                start_date: startOfWeek,
                end_date: endOfWeek
            },
            success: function(response) {
                console.log('Success:', response);
                let tbody = $('#details');
                tbody.empty(); // clear previous rows if any

                response.forEach(function(doc, index) {
                    // Example calculations
                    let totalShifts = parseInt(doc.total_morning) + parseInt(doc.total_evening);
                    let dayCount = totalShifts / 2; // assuming 2 shifts per day
                    let dailySalary = doc.weekly_salary; // replace with your logic
                    let salary = Math.ceil((dailySalary / 7) * dayCount); // simple example
                    let advances = doc.advances; // replace with your logic
                    let net = salary - advances;

                    let row = `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">${doc.employee_name}</td>
                    <td class="text-center">${totalShifts}</td>
                    <td class="text-center">${dayCount}</td>
                    <td class="text-center">${dailySalary}</td>
                    <td class="text-center">${salary}</td>
                    <td class="text-center">${advances}</td>
                    <td class="text-center">${net}</td>
                </tr>`;

                    tbody.append(row);
                });

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);


            }
        });
    });

    $('#salaryButton').on('click', function() {
        // الكود اللي عايز تنفذه عند الضغط على الزر
        Swal.fire({
            title: 'إعتماد رواتب الأسبوع',
            text: 'هل انت متأكد من أنك تريد إعتماد و صرف رواتب  الإسبوع من الخزينة الإفتراضية ؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم , متأكد',
            cancelButtonText: 'لا , تراجع'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion or any other logic
                paySalary();
            }
        });
    });


    function paySalary(){
        let startOfWeek = @json($startOfWeek);
        let endOfWeek = @json($endOfWeek);

        $.ajax({
            url: "{{ route('store-salary') }}", // using route name
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Laravel CSRF token
                start_date: startOfWeek,
                end_date: endOfWeek
            },
            success: function(response) {
                console.log('Success:', response);
                // You can handle UI updates here

                $('#salaryButton').hide();
                $('#salaryPaid').show();

                toastr.success("تم إعتماد و صرف رواتب هذا الأسبوع بنجاح");
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);

                toastr.error("حدث خطأ في عملية الصرف");
            }
        });
    }


</script>


</body>
</html>
