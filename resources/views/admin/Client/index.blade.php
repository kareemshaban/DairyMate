<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 2 , 'subSlag' => $type == 0 ? 22 :  21])
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
                             {{ $type == 0 ? __('main.clients_list')  : __('main.suppliers_list')}}
                        </h4>
                        @can('page-access', [7, 'edit'])
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>
                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.suppliers_list')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.name')}}</th>
                                    <th class="text-center">{{__('main.client_type')}}</th>
                                    <th class="text-center">{{__('main.phone')}}</th>
                                    <th class="text-center">{{__('main.balance')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($suppliers as $supplier)
                                    <tr>
                                        <th scope="row" class="text-center">
                                            @if($supplier -> type == 1)
                                            {{$supplier -> sort}}
                                            @else
                                            {{$loop -> index + 1}}
                                            @endif
                                        </th>
                                        <td class="text-center">{{$supplier -> name}}</td>
                                        <td class="text-center">
                                            @if($supplier -> type == 0)
                                                <span class="badge bg-primary">{{__('main.client_type0')}}</span>
                                            @elseif($supplier -> type == 1)
                                                <span class="badge bg-warning">{{__('main.client_type1')}}</span>
                                            @elseif($supplier -> type == 2)
                                                <span class="badge bg-success">{{__('main.client_type2')}}</span>
                                            @endif

                                        </td>
                                        <td class="text-center">{{$supplier -> phone}}</td>
                                        @php
                                        if($supplier -> balance)
                                           $balance = ($supplier -> balance -> debit  + $supplier -> balance -> opening_balance_debit )
                                           - ($supplier -> balance -> credit + $supplier -> balance ->  opening_balance_credit);
                                        else
                                            $balance = 0 ;
                                        @endphp
                                        <td class="text-center @if($balance <= 0) text-danger @else text-success @endif">

                                              {{$balance }}
                                         </td>
                                        <td class="text-center">
                                            @can('page-access', [7, 'edit'])
                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <i class='bx bxs-edit-alt text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$supplier -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       id="{{$supplier -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    <i class='bx bx-money text-primary moneyBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       data-id="{{$supplier -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    @if($supplier -> car_id > 0)
                                                        <a href="{{route('carMembers' , $supplier -> id)}}">
                                                            <i class='bx bxs-user-plus  text-info membersBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.members_action')}}"
                                                               style="font-size: 25px ; cursor: pointer" data-id="{{$supplier -> id}}"></i>
                                                        </a>

                                                    @endif
                                                </div>
                                            @endcan

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

@include('admin.Client.create')
@include('admin.Client.deleteModal')
@include('admin.Client.balance')
@include('layouts.footer')
<script type="text/javascript">
    var id = 0 ;
    $(document).on('click', '#createButton', function (event) {
        $.ajax({
            type:'get',
            url:'/supplier-order',
            dataType: 'json',

            success:function(order){
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
                        $(".modal-body #sort").val(order);
                        $(".modal-body #type").val(@json($type));
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
                });

            }
        });

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
                            $(".modal-body #sort").val( response.sort );
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
        Swal.fire({
            title: 'حذف البيانات',
            text: 'هل انت متأكد من أنك تريد حذف البيانات ؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم , متأكد',
            cancelButtonText: 'لا , تراجع'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion or any other logic
                confirmDelete(id);
            }
        });
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
