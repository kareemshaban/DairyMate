<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 9 , 'subSlag' => 93])
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


                        <div style="display: flex; gap: 10px ; align-items: baseline">
                            <h4 class="fw-bold py-3 mb-4">
                                <span class="text-muted fw-light">{{__('main.accounting_department')}} /</span> {{__('main.stock_in_list')}}
                            </h4>
                            <div class="mb-3" >
                                <select id="filterRecords" class="form-control" >
                                    <option value="all" @if($is_all == 1) selected @endif>{{ __('main.show_all_records') }}</option>
                                    <option value="week" @if($is_all == 0) selected @endif>{{ __('main.show_this_week_records') }}</option>
                                </select>
                            </div>
                        </div>


                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.stock_in_list')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.docNumber')}}</th>
                                    <th class="text-center"> {{__('main.date')}}</th>
                                    <th class="text-center"> {{__('main.cheese_meal')}}</th>
                                    <th class="text-center">{{__('main.store')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($docs as $doc)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$doc -> bill_number}}</td>
                                        <td class="text-center"> {{\Carbon\Carbon::parse($doc -> date) -> format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <span class="daily_meal_hl" style="cursor: pointer" data-id="{{$doc -> meal_id}}">
                                            {{$doc -> cheese_meal }} -- {{$doc -> symbol}}


                                        </span>
                                        </td>


                                        <td class="text-center">{{$doc -> store_name}}</td>

                                        <td class="text-center">

                                            <div style="display: flex ; gap: 10px ; justify-content: center ">
                                              <a href="{{route('stock_in_view' , $doc -> id)}}">
                                                  <i class='bx bxs-show text-primary' data-toggle="tooltip" data-placement="top" title="{{__('main.view_action')}}"
                                                      style="font-size: 25px ; cursor: pointer"></i>
                                              </a>

                                            </div>

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
    var id = 0 ;

    $(document).on('click', '.deleteBtn', function(event) {
        id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {
                $('#deleteModal').modal("show");
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function(jqXHR, testStatus, error) {
                console.log(error);
                alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            },
            timeout: 8000
        })
    });
    $(document).on('click', '.btnConfirmDelete', function(event) {

        confirmDelete(id);
    });
    $(document).on('click', '.cancel-modal', function(event) {
        $('#deleteModal').modal("hide");
        console.log()
        id = 0 ;
    });

    function confirmDelete(id){
        let url = "{{ route('cheese_meals-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

    $(document).on('change', '#filterRecords', function() {
        let filter = $(this).val();
        if (filter === 'week') {
            let url = "{{ route('stock_in', ':isAll') }}";
            url = url.replace(':isAll', 0);
            document.location.href=url;
        } else {
            let url = "{{ route('stock_in', ':isAll') }}";
            url = url.replace(':isAll', 1);
            document.location.href=url;
        }
    });
    //
</script>
</body>
</html>
