<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 2 , 'subSlag' => 23])
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
                            <span class="text-muted fw-light">{{__('main.factory_department')}} /</span>
                             {{ __('main.supplier_insurance_balance') }}
                        </h4>
                        @can('page-access', [7, 'edit'])
                        <a href="{{route('insuranceBalances-create')}}">

                        <button type="button" class="btn btn-primary"  style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>
                        </a>
                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.supplier_insurance_balance')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.supplier')}}</th>
                                    <th class="text-center">{{__('main.date')}}</th>
                                    <th class="text-center">{{__('main.insurance_balance')}}</th>
                                    <th class="text-center">{{__('main.meal_state')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($balances as $balance)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$balance -> supplier_name}}</td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($balance -> date) -> format('d-m-Y')  }}</td>
                                        <td class="text-center">{{$balance -> balance}}</td>
                                        <td class="text-center">
                                            @if($balance -> state == 0)
                                                <span class="badge bg-success">{{__('main.insurance_state0')}}</span>
                                            @elseif($balance -> state == 1)
                                                <span class="badge bg-danger">{{__('main.insurance_state1')}}</span>
                                            @endif

                                        </td>

                                        <td class="text-center">

                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <a href="{{ route('insuranceBalances-edit' , $balance -> id) }}">
                                                        <i class='bx bxs-edit-alt text-success' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$balance -> id}}" style="font-size: 25px ; cursor: pointer"></i>

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
<script type="text/javascript">
    var id = 0 ;
    $(document).on('click', '#createButton', function (event) {
        console.log('clicked');
        id = 0;
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function () {
                $('#loader').show();
            },
            // return the result
            success: function (result) {
                $('#createModal').modal("show");
                $(".modal-body #id").val(0);
                $(".modal-body #name").val("");
                $(".modal-body #phone").val("");
                $(".modal-body #buffalo_min_limit").val("0");
                $(".modal-body #buffalo_max_limit").val("0");
                $(".modal-body #bovine_min_limit").val("0");
                $(".modal-body #bovine_max_limit").val("0");
                $(".modal-body #car_id").val("0");
                $(".modal-body #address").val("");

                var translatedText = "{{ __('main.newData') }}";
                $(".modelTitle").html(translatedText);


            },
            complete: function () {
                $('#loader').hide();
            },
            error: function (jqXHR, testStatus, error) {
                console.log(error);
                alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            },
            timeout: 8000
        })
    });
    $(document).on('click', '.moneyBtn', function(event) {
        let id = $(this).data('id');
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/supplier-account-show' + '/' + id,
            dataType: 'json',

            success:function(response){
                console.log(response);

                let href = $(this).attr('data-attr');
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#balanceModal').modal("show");
                        $(".modal-body #opening_balance_credit").val( response ? response.opening_balance_credit : 0 );
                        $(".modal-body #opening_balance_debit").val( response ? response.opening_balance_debit : 0 );
                        $(".modal-body #debit").val( response ? response.debit : 0 );
                        $(".modal-body #credit").val(response ?  response.credit : 0 );
                        $(".modal-body #balance").val(response ?
                            (Number(response.debit)  + Number(response.opening_balance_debit)  - Number(response.credit)  - Number(response.opening_balance_credit) ) : 0 );
                        $(".modal-body #id").val(id);
                        var translatedText = "{{ __('main.editData') }}";

                        $("#balanceModal .modelTitle").html(translatedText);

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

            }
        });
    });
    $(document).on('click', '.editBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/suppliers-show' + '/' + id,
            dataType: 'json',

            success:function(response){
                console.log(response);
                if(response){
                    let href = $(this).attr('data-attr');
                    $.ajax({
                        url: href,
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        // return the result
                        success: function(result) {
                            $('#createModal').modal("show");
                            $(".modal-body #type").val( response.type );
                            $(".modal-body #name").val( response.name );
                            $(".modal-body #phone").val( response.phone );
                            $(".modal-body #buffalo_min_limit").val( response.buffalo_min_limit );
                            $(".modal-body #buffalo_max_limit").val( response.buffalo_max_limit );
                            $(".modal-body #bovine_min_limit").val( response.bovine_min_limit );
                            $(".modal-body #bovine_max_limit").val( response.bovine_max_limit );
                            $(".modal-body #address").val( response.address );
                            $(".modal-body #id").val(response.id);
                            $(".modal-body #car_id").val(response.car_id);
                            var translatedText = "{{ __('main.editData') }}";
                            $(".modelTitle").html(translatedText);

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
                } else {

                }
            }
        });
    });
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
        let url = "{{ route('suppliers-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

</script>
</body>
</html>
