    <!DOCTYPE html>

    @include('layouts.head')


    <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('layouts.sidebar' , ['slag' => 14 , 'subSlag' => 141])
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
                                <span class="text-muted fw-light">{{__('main.reports_department')}} /</span> {{__('main.client_account_report')}}
                            </h4>

                            <button type="button" class="btn btn-warning" style="width: 100px" onclick="printAction()" >{{ __('main.print_btn') }}</button>


                            <form id="detailedPrintForm" action="{{ route('clientAccountReportPrint') }}" method="POST" target="_blank" style="display: none;">
                                @csrf
                                <input type="hidden" name="reportType" value="0"> <!-- تفصيلي -->
                                <input type="hidden" name="client_id" value="{{ request('client_id', '') }}">
                                <input type="hidden" name="fromDate" value="{{ request('fromDate', '') }}">
                                <input type="hidden" name="toDate" value="{{ request('toDate', '') }}">
                                <input type="hidden" name="isFromDate" value="{{ request('isFromDate', '') }}">
                                <input type="hidden" name="isToDate" value="{{ request('isToDate', '') }}">

                                <!-- أي فلترات أخرى تحتاجها -->
                            </form>



                        </div>



                        <!-- Responsive Table -->
                        <div class="card">
                            <h5 class="card-header">{{__('main.client_account_report')}}

                                    <span class="badge bg-success">{{__('main.report_type0')}}</span>

                                <br>
                                <span style="font-size: 11px ; color: red">{{__('main.credit_debit_note')}}</span>
                            </h5>
                            @include('flash-message')
                            <div class="table-responsive  text-nowrap">
                                @if($from_date != "" && $to_date != "")
                                    <h3 class="text-center">
                                        {{__('main.period')}} :
                                        {{ \Carbon\Carbon::parse($from_date)->format('Y-m-d') }} -- {{ \Carbon\Carbon::parse($to_date)->format('Y-m-d') }}

                                    </h3>
                                @endif

                                <table class="table table-striped table-hover view_table">
                                    <thead>
                                    <th class="text-center"> {{ __('main.client') }} </th>
                                    <th class="text-center"> البيان </th>
                                    <th class="text-center"> {{ __('main.credit') }} </th>
                                    <th class="text-center"> {{ __('main.debit') }} </th>

                                    <th class="text-center"> {{ __('main.balance') }} </th>

                                    </thead>
                                    <tbody>
                                    @if($account)
                                        <tr>
                                            <td class="text-center"> {{ $account -> client	 }} </td>
                                            <td class="text-center">  رصيد إفتتاحي </td>
                                            <td class="text-center text-danger" style="font-size: 18px ; font-weight: bold ;"> {{number_format($account -> opening_balance_credit  , 2)}} </td>
                                            <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{number_format($account -> opening_balance_debit , 2)}}   </td>
                                            <td  style="font-size: 18px ; font-weight: bold ;" class="text-center @if($account -> opening_balance_credit  - $account -> opening_balance_debit  < 0 )  text-success @else text-danger @endif"> {{  number_format($account -> opening_balance_credit   - $account -> opening_balance_debit  , 2)}} </td>
                                        </tr>
                                    @endif

                                    <?php
                                    $credit = 0 ;
                                    $debit = 0  ;
                                    $balance = 0 ;
                                    ?>
                                    @foreach ( $totals as $doc )
                                        <tr >
                                            <td class="text-center"> {{ $doc  ['client']	 }} </td>
                                            <td class="text-center">  رصيد المعاملات </td>
                                            <td class="text-center text-danger" style="font-size: 18px ; font-weight: bold ;"> {{number_format($doc ['credit']  , 2)}} </td>
                                            <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{number_format($doc['debit'] , 2)}}   </td>
                                            <td  style="font-size: 18px ; font-weight: bold ;" class="text-center @if($doc ['credit']  - $doc ['debit']  < 0 )  text-success @else text-danger @endif"> {{  number_format($doc ['credit']  - $doc ['debit']  , 2)}} </td>
                                        </tr>
                                        <?php
                                            $credit += $doc ['credit'] ;
                                            $debit += $doc ['debit'] ;
                                            $balance += $doc ['credit'] - $doc ['debit'] ;

                                            ?>

                                    @endforeach
                                    <?php
                                    if($account){
                                        $credit += $account -> opening_balance_credit ;
                                        $debit += $account -> opening_balance_debit ;
                                        $balance +=  ($account -> opening_balance_credit  - $account -> opening_balance_debit);

                                    }

                                    ?>

                                    <tr>
                                        <td class="text-center" colspan="2" style="font-size: 20px ; font-weight: bold;"> إجمالي الرصيد </td>
                                        <td class="text-center text-danger" style="font-size: 20px ; font-weight: bold"> {{ number_format($credit , 2) }} </td>
                                        <td class="text-center text-success" style="font-size: 20px ; font-weight: bold"> {{ number_format($debit , 2) }} </td>
                                        <td class="text-center @if($balance < 0) text-success @else text-danger @endif" style="font-size: 20px ; font-weight: bold"> {{ number_format($balance , 2) }} </td>
                                    </tr>


                                    </tbody>


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
        function printAction() {
            var client_id = '{{ request("client_id", "") }}';
            var from_date = '{{ request("fromDate", "") }}';
            var to_date = '{{ request("toDate", "") }}';

            var isFromDate = '{{ request("isFromDate", "") }}';
            var isToDate = '{{ request("isToDate", "") }}';
            // إضافة القيم إلى الفورم
            $('#detailedPrintForm input[name="client_id"]').val(client_id);
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
