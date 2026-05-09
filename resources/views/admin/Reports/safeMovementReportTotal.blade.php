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
                            <input type="hidden" name="reportType" value="0"> <!-- تفصيلي -->
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
                                <th class="text-center"> {{ __('main.safe') }} </th>
                                <th class="text-center"> الرصيد الافتتاحي </th>
                                <th class="text-center"> {{ __('main.debit') }} </th>
                                <th class="text-center"> {{ __('main.credit') }} </th>
                                <th class="text-center"> {{ __('main.balance') }} </th>

                                </thead>
                                <tbody>

                                @foreach ( $totals as $doc )
                                    <tr >
                                    
                                        <td class="text-center"> {{ $doc  ['safe']	 }} </td>
                                        <td class="text-center @if($doc['opening_balance'] < 0 ) text-danger @else text-success @endif" style="font-size: 18px ; font-weight: bold ;"> {{number_format($doc['opening_balance'] , 2)}}   </td>
                                        <td class="text-center text-danger" style="font-size: 18px ; font-weight: bold ;"> {{number_format($doc['outMoney'] , 2)}}   </td>
                                        <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{number_format($doc ['inMoney']  , 2)}} </td>


                                        <td  style="font-size: 18px ; font-weight: bold ;" class="text-center @if($doc ['inMoney']  - $doc ['outMoney'] + $doc['opening_balance']  < 0 )  text-danger @else text-success @endif"> {{  number_format($doc ['inMoney']  - $doc ['outMoney'] + $doc['opening_balance']  , 2)}} </td>
                                    </tr>



                                @endforeach


                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center" style="font-size: 18px ; font-weight: bold" >{{__('main.total')}}</th>
                                    <th class="text-center text-danger" style="font-size: 18px ; font-weight: bold"> </th>
                                    <th class="text-center text-success" style="font-size: 18px ; font-weight: bold"> </th>
                                    <th class="text-center " style="font-size: 18px ; font-weight: bold"> </th>
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
