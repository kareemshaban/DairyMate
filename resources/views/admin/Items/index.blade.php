<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 4 , 'subSlag' => 44])
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
                            <span class="text-muted fw-light">{{__('main.product_department')}} /</span> {{__('main.product_list')}}
                        </h4>
                        @can('page-access', [4, 'edit'])

                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>




                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.products')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.code')}}</th>
                                    <th class="text-center">{{__('main.name')}}</th>
                                    <th class="text-center">{{__('main.default_selling_price')}}</th>
                                    <th class="text-center" hidden="hidden">{{__('main.item_type')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$item -> code}}</td>
                                        <td class="text-center">{{$item -> name}}</td>
                                        <td class="text-center">{{$item -> default_selling_price}}</td>
                                        <td class="text-center" hidden="hidden">
                                            @if($item -> type == 0)
                                                <span class="badge bg-secondary">{{__('main.item_type0')}}</span>
                                            @elseif($item -> type == 1)
                                                <span class="badge bg-primary">{{__('main.item_type1')}}</span>
                                            @elseif($item -> type == 2)
                                                <span class="badge bg-dark">{{__('main.item_type2')}}</span>
                                            @elseif($item -> type == 3)
                                                <span class="badge bg-warning">{{__('main.item_type3')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @can('page-access', [4, 'edit'])
                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <i class='bx bxs-edit-alt text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$item -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       id="{{$item -> id}}" style="font-size: 25px ; cursor: pointer"></i>

{{--                                                     @if($item -> type == 0 || $item -> type == 1)--}}

{{--                                                       <i class='bx bxs-store text-primary storeBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.store_action')}}"--}}
{{--                                                       date-id="{{$item -> id}}" style="font-size: 25px ; cursor: pointer"></i>--}}
{{--                                                       @endif--}}
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

@include('admin.Items.create')
@include('admin.Items.deleteModal')
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
                $(".modal-body #code").val("");
                $(".modal-body #name").val("");
                $(".modal-body #default_selling_price").val("0");
                $(".modal-body #type").val("0");
                $(".modal-body #details").val("");
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
    $(document).on('click', '.editBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/items-show' + '/' + id,
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
                            $(".modal-body #code").val( response.code );
                            $(".modal-body #name").val( response.name );
                            $(".modal-body #details").val( response.details );
                            $(".modal-body #default_selling_price").val(response.default_selling_price);
                            $(".modal-body #type").val(response.type);
                            $(".modal-body #id").val(response.id);
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

     $(document).on('click', '.storeBtn', function(event) {
         var id = $(this).attr('date-id'); // use attr() for non-standard attributes
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/item-qnt' + '/' + id,
            dataType: 'json',

            success:function(response){
               console.log(response);

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
        let url = "{{ route('items-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

</script>
</body>
</html>
