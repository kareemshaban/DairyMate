<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 14 , 'subSlag' => 144])
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
                            <span class="text-muted fw-light">{{__('main.reports_department')}} /</span> {{__('main.supplierAccountSettlement')}}
                        </h4>

                            <button type="button" class="btn btn-primary"  id="settlementBtn" style="height: 45px"
                                    data-id="{{ $wmeal->id  }}" >
                                {{__('main.settlement')}}  <span class="tf-icons bx bx-money"></span>&nbsp;
                            </button>



                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.weakly_meals_view')}}
                            @if (Config::get('app.locale')=='en' )
                                {{$wmeal -> from_day_name_en}}
                            @else
                                {{$wmeal -> from_day_name_ar}}
                            @endif
                            <span style="color: grey">  {{\Carbon\Carbon::parse($wmeal -> start_date) -> format('Y-m-d') }}</span>

                            ---
                            @if (Config::get('app.locale')=='en' )
                                {{$wmeal -> to_day_name_en}}
                            @else
                                {{$wmeal -> to_day_name_ar}}
                            @endif
                            <span style="color: grey">  {{\Carbon\Carbon::parse($wmeal  -> end_date) -> format('Y-m-d') }}</span>
                        </h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover view_table">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center" rowspan="2">#</th>
                                    <th class="text-center" rowspan="2">{{ __('main.supplier') }}</th>
                                    <th class="text-center" colspan="2">{{ __('main.daily_meal') }}</th>
                                    <th class="text-center" colspan="2">{{ __('main.morning_meal') }}</th>
                                    <th class="text-center" colspan="2">{{ __('main.evening_meal') }}</th>
                                    <th class="text-center" colspan="2">{{ __('main.meal_total') }}</th>
                                    <th class="text-center" rowspan="2">{{ __('main.total_cash') }}</th>
                                </tr>
                                <tr class="text-nowrap">
                                    <th class="text-center" >{{ __('main.morning_meal') }}</th>
                                    <th class="text-center" >{{ __('main.evening_meal') }}</th>
                                    <th class="text-center text-primary">{{ __('main.buffalo_weight') }}</th>
                                    <th class="text-center text-success">{{ __('main.bovine_weight') }}</th>
                                    <th class="text-center text-primary">{{ __('main.buffalo_weight') }}</th>
                                    <th class="text-center text-success">{{ __('main.bovine_weight') }}</th>
                                    <th class="text-center text-primary">{{ __('main.total_buffalo_weight') }}</th>
                                    <th class="text-center text-success">{{ __('main.total_bovine_weight') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($meals as $index => $meal)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $meal->client_name }}</td>
                                        <td class="text-center">
                                         <span class="daily_meal_hl" style="cursor: pointer" data-id="{{$meal -> morning_meal}}"> {{$meal -> morning_meal}}</span>
                                        </td>
                                        <td class="text-center">
                                           <span class="daily_meal_hl" style="cursor: pointer" data-id="{{$meal -> evening_meal}}">{{$meal -> evening_meal}} </span>
                                        </td>

                                        <td class="text-center text-primary">{{ $meal->morning_buffalo_weight }}</td>
                                        <td class="text-center text-success">{{ $meal->morning_bovine_weight }}</td>
                                        <td class="text-center text-primary">{{ $meal->evening_buffalo_weight }}</td>
                                        <td class="text-center text-success">{{ $meal->evening_bovine_weight }}</td>
                                        <td class="text-center text-primary">
                                            {{ $meal->morning_buffalo_weight + $meal->evening_buffalo_weight }}
                                        </td>
                                        <td class="text-center text-success">
                                            {{ $meal->morning_bovine_weight + $meal->evening_bovine_weight }}
                                        </td>
                                        <td class="text-center text-success">
                                            {{ $meal->morning_total + $meal->evening_total }}

                                        </td>


                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                <tr style="background-color: lightyellow;">
                                    <td class="text-center" colspan="4">{{ __('main.total') }}</td>
                                    <td class="text-center text-primary">
                                        {{ $meals->sum('morning_buffalo_weight') }}
                                    </td>
                                    <td class="text-center text-success">
                                        {{ $meals->sum('morning_bovine_weight') }}
                                    </td>
                                    <td class="text-center text-primary">
                                        {{ $meals->sum('evening_buffalo_weight') }}
                                    </td>
                                    <td class="text-center text-success">
                                        {{ $meals->sum('evening_bovine_weight') }}
                                    </td>
                                    <td class="text-center text-primary">
                                        {{ $meals->sum('morning_buffalo_weight') + $meals->sum('evening_buffalo_weight') }}
                                    </td>
                                    <td class="text-center text-success">
                                        {{ $meals->sum('morning_bovine_weight') + $meals->sum('evening_bovine_weight') }}
                                    </td>
                                    <td class="text-center text-success">
                                        {{ $meals->sum('morning_total') + $meals->sum('evening_total') }}
                                    </td>
                                </tr>
                                </tfoot>

                            </table>

                        </div>
                    </div>
                    <!--/ Responsive Table -->
                </div>
                <!-- / Content -->

                <input  type="hidden" id="msupplier_id" name="msupplier_id" value="{{$supplier_id}}"/>
                <input  type="hidden" id="mamount" name="mamount" value="{{$amount}}"/>
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

@include('admin.Recipits.create')
@include('layouts.footer')

<script type="text/javascript">
    var id = 0 ;

    $(document).ready(function() {
        $(document).on('click', '#settlementBtn', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'get',
                url: '/recipits-getCode', // This one is still needed to get the code
                dataType: 'json',
                success: function (code) {
                    // ✅ Now we just open and populate the modal directly, no second AJAX call
                    $('#createModal').modal("show");

                    $(".modal-body #id").val(0);
                    $(".modal-body #bill_number").val(code);
                    $(".modal-body #supplier_id").val($('#msupplier_id').val());
                    $(".modal-body #safe_id").val("");
                    $(".modal-body #amount").val($('#mamount').val());
                    $(".modal-body #payment_method").val("0");
                    $(".modal-body #notes").val("");

                    $(".modal-body #supplier_id")
                        .css("pointer-events", "none") // disable mouse interaction
                        .attr("tabindex", "-1");

                    $(".modal-body #amount").attr("readonly", true);

                    var translatedText = "{{ __('main.supplierAccountSettlement') }}";
                    $(".modelTitle").html(translatedText);
                },
                beforeSend: function () {
                    $('#loader').show();
                },
                complete: function () {
                    $('#loader').hide();
                },
                error: function (jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Error fetching receipt code: " + error);
                    $('#loader').hide();
                },
                timeout: 8000
            });
        });


    });


</script>

<script>
    $(document).ready(function() {
        $('.view_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy',
                'excel',
                {
                    extend: 'print',
                    text: 'طباعة',
                    exportOptions: {
                        columns: ':visible',
                        footer: true // Include footer
                    },
                    customize: function (win) {
                        var body = $(win.document.body);
                        body.find('h1, .page-title, .header-title').hide();
                        body.prepend('<h2 style="text-align:center; margin-bottom:20px;">تقرير تسوية حساب مورد</h2>');
                        // Apply RTL direction and Arabic styling
                        $(win.document.body).css('direction', 'rtl');

                        var table = $(win.document.body).find('table');

                        // Clone original footer and append to print view
                        var originalFooter = $('.view_table').find('tfoot').clone();
                        table.append(originalFooter);

                        // Apply styles
                        table
                            .addClass('display')
                            .css('direction', 'rtl')
                            .css('text-align', 'right');

                        table.find('thead th, tfoot th')
                            .css('text-align', 'center')
                            .css('font-weight', 'bold');
                    }
                }
            ],

            responsive: true,
            paging: false,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'

            },

            footerCallback: function (row, data, start, end, display) {
                var api = this.api();

                var intVal = function (i) {
                    return typeof i === 'string'
                        ? parseFloat(i.replace(/[^0-9.-]+/g, '')) || 0
                        : typeof i === 'number'
                            ? i
                            : 0;
                };

                // Calculate filtered totals:
                var morning_buffalo_weight = api.column(4, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var morning_bovine_weight = api.column(5, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var evening_buffalo_weight = api.column(6, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var evening_bovine_weight = api.column(7, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var total_buffalo_weight = api.column(8, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var total_bovine_weight = api.column(9, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var total = api.column(10, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);

                // Update footer (use .footer() without filter option)
                $(api.column(4).footer()).html(morning_buffalo_weight.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $(api.column(5).footer()).html(morning_bovine_weight.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $(api.column(6).footer()).html(evening_buffalo_weight.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $(api.column(7).footer()).html(evening_bovine_weight.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $(api.column(8).footer()).html(total_buffalo_weight.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $(api.column(9).footer()).html(total_bovine_weight.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $(api.column(10).footer()).html(total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            }

        });

    });
</script>
</body>
</html>
