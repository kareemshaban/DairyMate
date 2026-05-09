<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 14 , 'subSlag' => 142])
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
                            <span class="text-muted fw-light">{{__('main.reports_department')}} /</span> {{__('main.stock_movement_report')}}
                            @if($type == 0)
                                <span class="badge bg-warning">{{__('main.report_type0')}}</span>
                            @elseif($type == 1)
                                <span class="badge bg-success">{{__('main.report_type1')}}</span>
                            @endif
                        </h4>

                        <button type="button" class="btn btn-warning" style="width: 100px" onclick="printAction()" >{{ __('main.print_btn') }}</button>


                        <form id="detailedPrintForm" action="{{ route('printStockMovementReport') }}" method="POST" target="_blank" style="display: none;">
                            @csrf
                            <input type="hidden" name="reportType" value="0"> <!-- تفصيلي -->
                            <input type="hidden" name="item_id" value="{{ request('item_id', '') }}">
                            <input type="hidden" name="store_id" value="{{ request('store_id', '') }}">
                            <!-- أي فلترات أخرى تحتاجها -->
                        </form>



                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.stock_movement_report')}}
                        </h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap ">
                            <table class="table table-striped table-hover view_table">
                                <thead>
                                <th class="text-center"> # </th>
                                <th class="text-center"> {{ __('main.store') }} </th>
                                <th class="text-center"> {{ __('main.item') }} </th>
                                <th class="text-center"> {{ __('main.opening_balance') }} </th>
                                <th class="text-center"> {{ __('main.quantity_in') }} </th>
                                <th class="text-center"> {{ __('main.quantity_out') }} </th>
                                <th class="text-center"> {{ __('main.balance') }} </th>

                                </thead>
                                <tbody>


                                @foreach ( $stock as $doc )
                                    <tr >
                                        <td class="text-center"> {{ $loop -> index + 1}} </td>
                                        <td class="text-center"> {{ $doc -> storeName	 }} </td>

                                        <td class="text-center"> {{ $doc -> itemName	 }} </td>
                                       <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{$doc -> opening_quantity}} </td>

                                        <td class="text-center text-success" style="font-size: 18px ; font-weight: bold ;"> {{$doc -> quantity_in}} </td>
                                        <td class="text-center text-danger" style="font-size: 18px ; font-weight: bold ;"> {{$doc -> quantity_out}} </td>

                                        <td  style="font-size: 18px ; font-weight: bold ;" class="text-center @if($doc -> balance > 0)  text-success @else text-danger @endif"> {{$doc -> balance + $doc -> opening_quantity}} </td>
                                    </tr>



                                @endforeach

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
        var item_id = '{{ request("item_id", "") }}';
        var store_id = '{{ request("store_id", "") }}';

        // إضافة القيم إلى الفورم

        $('#detailedPrintForm input[name="item_id"]').val(item_id);
        $('#detailedPrintForm input[name="store_id"]').val(store_id);
        // إرسال الفورم

        document.getElementById('detailedPrintForm').submit();
    }
</script>
</body>
</html>
