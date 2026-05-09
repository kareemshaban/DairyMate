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
                            <input type="hidden" name="reportType" value="1"> <!-- تفصيلي -->
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
                            @if($type == 0)
                                <span class="badge bg-warning">{{__('main.report_type0')}}</span>
                            @elseif($type == 1)
                                <span class="badge bg-success">{{__('main.report_type1')}}</span>
                            @endif
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
                                @php
                                    $totalCredit = 0;
                                    $totalDebit  = 0;
                                    $finalBalance = 0;

                                    // الرصيد الافتتاحي
                                    if ($account) {
                                        $totalCredit += $account->opening_balance_credit;
                                        $totalDebit  += $account->opening_balance_debit;
                                    }

                                    // رصيد ما قبل الفترة
                                    foreach ($brfors as $b) {
                                        $totalCredit += $b['credit'];
                                        $totalDebit  += $b['debit'];
                                    }

                                    // الحركات
                                    foreach ($data as $doc) {

                                        // مدين
                                        if (in_array($doc->type, [0,3,5])) {
                                            $totalDebit += $doc->amount;
                                        } else {
                                            $totalCredit += $doc->amount;
                                        }
                                    }

                                    $finalBalance = $totalDebit - $totalCredit;
                                @endphp


                                <table class="table table-striped table-hover view_table">
                                <thead>
                                <tr>
                                <th class="text-center"> # </th>
                                <th class="text-center"> {{ __('main.docType') }} </th>
                                <th class="text-center"> {{ __('main.client') }} </th>
                                <th class="text-center"> {{ __('main.date') }} </th>
                                <th class="text-center"> {{ __('main.docNumber') }} </th>
                                <th class="text-center"> {{ __('main.credit') }} </th>
                                <th class="text-center"> {{ __('main.debit') }} </th>

                                <th class="text-center"> {{ __('main.balance') }} </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($account)
                                    <tr>
                                        <td class="text-center">  ----- </td>
                                        <td class="text-center">  رصيد إفتتاحي </td>
                                        <td class="text-center"> {{ $account -> client	 }} </td>
                                        <td class="text-center">  ----- </td>
                                        <td class="text-center">  ----- </td>
                                        <td class="text-center text-danger" style="font-size: 18px ; font-weight: bold ;"> {{number_format($account -> opening_balance_credit  , 2)}} </td>
                                        <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{number_format($account -> opening_balance_debit , 2)}}   </td>
                                        <td  style="font-size: 18px ; font-weight: bold ;" class="text-center @if($account -> opening_balance_credit  - $account -> opening_balance_debit  < 0 )  text-success @else text-danger @endif"> {{  number_format($account -> opening_balance_credit   - $account -> opening_balance_debit  , 2)}} </td>
                                    </tr>
                                @endif
                                @foreach ( $brfors as $brfore )
                                    <tr>
                                        <td class="text-center"> -- </td>
                                        <td class="text-center">  رصيد ما قبل الفترة </td>
                                        <td class="text-center"> {{ $brfore['client_name']	 }} </td>
                                        <td class="text-center"> ---- </td>
                                        <td class="text-center"> ----</td>
                                        <td class="text-center text-danger" style="font-size: 15px ; font-weight: bold ;"> {{number_format($brfore['credit'] , 2)}} </td>
                                        <td class="text-center text-success" style="font-size: 15px ; font-weight: bold ;"> {{number_format(  $brfore['debit']  , 2)}} </td>

                                        <td style="font-size: 15px ; font-weight: bold ;" class="text-center @if( $brfore['debit']  - $brfore['credit'] > 0 )  text-success @else text-danger @endif"> {{  number_format($brfore['debit'] - $brfore['credit'] , 2) }} </td>
                                    </tr>
                                @endforeach

                                @foreach ( $data as $doc )
                                    <tr >
                                        <td class="text-center"> {{ $loop -> index + 1}} </td>
                                        <td class="text-center">
                                                <span @if ($doc -> type == 0 || $doc -> type == 3 || $doc -> type == 5) class="badge bg-success"
                                                      @else class="badge bg-danger" @endif>
                                                     @if($doc -> type == 0)
                                                        {{__('main.doc0')}}
                                                    @elseif($doc -> type == 1)
                                                        {{__('main.doc1')}}
                                                    @elseif($doc -> type == 2)
                                                        {{__('main.doc2')}}
                                                    @elseif($doc -> type == 3)
                                                        {{__('main.doc3')}}
                                                    @elseif($doc -> type == 4)
                                                        {{__('main.doc4')}}
                                                    @elseif($doc -> type == 5)
                                                        {{__('main.doc5')}}
                                                    @endif
                                                </span>
                                        </td>
                                        <td class="text-center"> {{ $doc -> client	 }} </td>
                                        <td class="text-center">
                                            @if ($doc->type == 4)
                                                {{ \Carbon\Carbon::parse($doc->docDate)->format('Y-m-d') }} -- {{ \Carbon\Carbon::parse($doc->docDate)->addDays(6)->format('Y-m-d') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($doc->docDate)->format('Y-m-d') }}
                                            @endif
                                        </td>                                        <td class="text-center"> {{ $doc -> docNumber	 }} </td>
                                        <td class="text-center text-danger" style="font-size: 18px ; font-weight: bold ;"> {{  ($doc -> type == 0 || $doc -> type == 3 || $doc -> type == 5 )  ? number_format(0 , 2) : number_format($doc -> amount , 2)  }} </td>
                                        <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{  ($doc -> type == 0 || $doc -> type == 3 || $doc -> type == 5)  ?  number_format($doc -> amount , 2)  : number_format(0 , 2)}} </td>

                                        <td  style="font-size: 18px ; font-weight: bold ;" class="text-center @if($doc -> type == 0 || $doc -> type == 3 || $doc -> type == 5)  text-success @else text-danger @endif"> {{  ($doc -> type == 2 || $doc -> type == 4 || $doc -> type == 12)  ? number_format($doc -> amount , 2 ) : number_format(-1 * $doc -> amount , 2) }} </td>
                                    </tr>



                                @endforeach



                                </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-center" style="font-size:18px;font-weight:bold">
                                            اجمالي الرصيد
                                        </th>

                                        <th class="text-center text-danger" style="font-size:18px;font-weight:bold">
                                            {{ number_format($totalCredit, 2) }}
                                        </th>

                                        <th class="text-center text-success" style="font-size:18px;font-weight:bold">
                                            {{ number_format($totalDebit, 2) }}
                                        </th>

                                        <th class="text-center"
                                            style="font-size:18px;font-weight:bold"
                                            class="{{ $finalBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($finalBalance, 2) }}
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
