<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 14 , 'subSlag' => 143])
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
                            <span class="text-muted fw-light">{{__('main.reports_department')}} /</span> {{__('main.safe_movement_report')}}
                        </h4>

                        <button type="button" class="btn btn-warning" style="width: 100px" onclick="printAction()" >{{ __('main.print_btn') }}</button>


                        <form id="detailedPrintForm" action="{{ route('safeMovementReportPrint') }}" method="POST" target="_blank" style="display: none;">
                            @csrf
                            <input type="hidden" name="reportType" value="1"> <!-- تفصيلي -->
                            <input type="hidden" name="safe_id" value="{{ request('safe_id', '') }}">
                            <input type="hidden" name="fromDate" value="{{ request('fromDate', '') }}">
                            <input type="hidden" name="toDate" value="{{ request('toDate', '') }}">
                            <input type="hidden" name="isFromDate" value="{{ request('isFromDate', '') }}">
                            <input type="hidden" name="isToDate" value="{{ request('isToDate', '') }}">

                            <!-- أي فلترات أخرى تحتاجها -->
                        </form>




                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.safe_movement_report')}}
                        </h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover view_table">
                                <thead>
                                <tr>
                                    <th class="text-center"> # </th>
                                    <th class="text-center"> {{ __('main.safe') }} </th>
                                    <th class="text-center"> {{ __('main.docType') }} </th>
                                    <th class="text-center"> {{ __('main.from_to') }} </th>
                                    <th class="text-center"> {{ __('main.date') }} </th>
                                    <th class="text-center"> {{ __('main.docNumber') }} </th>
                                    <th class="text-center"> {{ __('main.debit') }} </th>
                                    <th class="text-center"> {{ __('main.credit') }} </th>
                                    <th class="text-center"> {{ __('main.balance') }} </th>
                                </tr>

                                </thead>
                                <tbody>
                                    
                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                    
                                        $openingBalance = (float) ($safeBalance->opening_balance ?? 0);
                                    
                                        // تحديد اتجاه الرصيد الافتتاحي
                                        $openingDebit = $openingBalance < 0 ? abs($openingBalance) : 0;
                                        $openingCredit = $openingBalance >= 0 ? $openingBalance : 0;
                                    
                                        $totalDebit += $openingDebit;
                                        $totalCredit += $openingCredit;
                                    
                                        $runningBalance = $openingBalance;
                                    @endphp
                                    
                                    
                                    <tr style="background:#f8f9fa;font-weight:bold;">
                                        <td class="text-center">1</td>
                                        <td class="text-center">{{ $data->first()->safe ?? '' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                رصيد افتتاحي
                                            </span>
                                        </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                    
                                        <td class="text-center fw-bold text-danger">
                                            {{ $openingDebit ? number_format($openingDebit,2) : '0.00' }}
                                        </td>
                                    
                                        <td class="text-center fw-bold text-success">
                                            {{ $openingCredit ? number_format($openingCredit,2) : '0.00' }}
                                        </td>
                                    
                                        <td class="text-center fw-bold {{ $runningBalance < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($runningBalance,2) }}
                                        </td>
                                    </tr>


                                @foreach ( $data as $doc )

                                    @php
                                        $isDebit = in_array($doc->type, [0,3,4,7,9,10]);
                                
                                        $debit = $isDebit ? $doc->amount : 0;
                                        $credit = $isDebit ? 0 : $doc->amount;
                                
                                        $totalDebit += $debit;
                                        $totalCredit += $credit;
                                
                                        $runningBalance += ($credit - $debit);
                                    @endphp
                                    
                                
                                    <tr>
                                        <td class="text-center">{{ $loop->index + 2 }}</td>
                                        <td class="text-center">{{ $doc->safe }}</td>
                                
                                        <td class="text-center">
                                            <span class="badge {{ $isDebit ? 'bg-danger' : 'bg-success' }}">
                                                @if($doc->type == 0) {{__('main.doc0')}}
                                                @elseif($doc->type == 1) {{__('main.doc1')}}
                                                @elseif($doc->type == 2) {{__('main.doc2')}}
                                                @elseif($doc->type == 3) {{__('main.doc3')}}
                                                @elseif($doc->type == 4) {{__('main.doc6')}}
                                                @elseif($doc->type == 7) {{__('main.doc7')}}
                                                @elseif($doc->type == 8) {{__('main.doc8')}}
                                                @elseif($doc->type == 9) {{__('main.doc9')}}
                                                @elseif($doc->type == 10) {{__('main.doc10')}}
                                                @elseif($doc->type == 11) {{__('main.doc11')}}
                                                @endif
                                            </span>
                                        </td>
                                
                                        <td class="text-center">{{ $doc->client }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($doc->docDate)->format('Y-m-d') }}</td>
                                        <td class="text-center">{{ $doc->docNumber }}</td>
                                
                                        <td class="text-center text-danger fw-bold">
                                            {{ number_format($debit, 2) }}
                                        </td>
                                
                                        <td class="text-center text-success fw-bold">
                                            {{ number_format($credit, 2) }}
                                        </td>
                                
                                        <td class="text-center fw-bold {{ $runningBalance < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($runningBalance, 2) }}
                                        </td>
                                    </tr>
                                
                                @endforeach



                                </tbody>
                                <tfoot>
                                        <tr>
                                            <th class="text-center fw-bold" colspan="6" style="font-size:15px">
                                                {{ __('main.total') }}
                                            </th>
                                        
                                            <th class="text-center text-danger fw-bold" style="font-size:15px">
                                                {{ number_format($totalDebit, 2) }}
                                            </th>
                                        
                                            <th class="text-center text-success fw-bold" style="font-size:15px">
                                                {{ number_format($totalCredit, 2) }}
                                            </th>
                                        
                                            <th class="text-center fw-bold {{ ($totalCredit - $totalDebit) < 0 ? 'text-danger' : 'text-success' }}" style="font-size:15px">
                                               {{ number_format($runningBalance, 2) }}

                                            </th>
                                        </tr>
                                    </tfoot>


                            </table>
                        </div>
                    </div>
                    <!--/ Responsive Table -->


                </div>
                <!-- / Content -->

                @include('layouts.footer')
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
<script>
    // $(document).ready(function() {
    //     $('.view_table').DataTable({
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'copy',
    //             'excel',
    //             {
    //                 extend: 'print',
    //                 text: 'طباعة',
    //                 exportOptions: {
    //                     columns: ':visible',
    //                     footer: true // Include footer
    //                 },
    //                 customize: function (win) {
    //                     var body = $(win.document.body);
    //                     body.find('h1, .page-title, .header-title').hide();
    //                     body.prepend('<h2 style="text-align:center; margin-bottom:20px;">تقرير كشف حركة خزنة</h2>');
    //                     // Apply RTL direction and Arabic styling
    //                     $(win.document.body).css('direction', 'rtl');
    //
    //                     var table = $(win.document.body).find('table');
    //
    //                     // Clone original footer and append to print view
    //                     var originalFooter = $('.view_table').find('tfoot').clone();
    //                     table.append(originalFooter);
    //
    //                     // Apply styles
    //                     table
    //                         .addClass('display')
    //                         .css('direction', 'rtl')
    //                         .css('text-align', 'right');
    //
    //                     table.find('thead th, tfoot th')
    //                         .css('text-align', 'center')
    //                         .css('font-weight', 'bold');
    //                 }
    //             }
    //         ],
    //
    //         responsive: true,
    //         paging: false,
    //         language: {
    //             url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
    //
    //         },
    //
    //         footerCallback: function (row, data, start, end, display) {
    //             var api = this.api();
    //
    //             var intVal = function (i) {
    //                 return typeof i === 'string'
    //                     ? parseFloat(i.replace(/[^0-9.-]+/g, '')) || 0
    //                     : typeof i === 'number'
    //                         ? i
    //                         : 0;
    //             };
    //
    //             // Calculate filtered totals:
    //             var debit = api.column(6, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
    //             var credit = api.column(7, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
    //             var balance = api.column(8, { filter: 'applied' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
    //
    //             // Update footer (use .footer() without filter option)
    //             $(api.column(6).footer()).html(debit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    //             $(api.column(7).footer()).html(credit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    //
    //             var formattedBalance = balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    //             var balanceFooter = $(api.column(8).footer());
    //             balanceFooter.html(formattedBalance);
    //
    //             balanceFooter
    //                 .removeClass('text-danger text-success')
    //                 .addClass(balance < 0 ? 'text-danger' : 'text-success');
    //         }
    //
    //     });
    //
    // });

    function printAction() {
        var safe_id = '{{ request("safe_id", "") }}';
        var from_date = '{{ request("fromDate", "") }}';
        var to_date = '{{ request("toDate", "") }}';

        var isFromDate = '{{ request("isFromDate", "") }}';
        var isToDate = '{{ request("isToDate", "") }}';
        // إضافة القيم إلى الفورم
        $('#detailedPrintForm input[name="safe_id"]').val(safe_id);
        $('#detailedPrintForm input[name="fromDate"]').val(from_date);
        $('#detailedPrintForm input[name="toDate"]').val(to_date);
        $('#detailedPrintForm input[name="isFromDate"]').val(isFromDate);
        $('#detailedPrintForm input[name="isToDate"]').val(isToDate);
        // إرسال الفورم
        document.getElementById('detailedPrintForm').submit();
    }
</script>

</body>
</html>
