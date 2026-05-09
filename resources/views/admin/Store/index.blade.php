<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 1 , 'subSlag' => 11])
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
                            <span class="text-muted fw-light">{{__('main.basicData')}} /</span> {{__('main.stores')}}
                        </h4>
                        @can('page-access', [1, 'edit'])
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>
                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.stores')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.code')}}</th>
                                    <th class="text-center">{{__('main.name')}}</th>
                                    <th class="text-center">{{__('main.isDefault')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stores as $store)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$store -> code}}</td>
                                        <td class="text-center">{{$store -> name}}</td>
                                        <td class="text-center">
                                            @if($store -> isDefault == 0)
                                                <span class="badge bg-danger">{{__('main.isDefault0')}}</span>
                                            @elseif($store -> isDefault == 1)
                                                <span class="badge bg-success">{{__('main.isDefault1')}}</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @can('page-access', [1, 'edit'])
                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <i class='bx bxs-edit-alt text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$store -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       id="{{$store -> id}}" style="font-size: 25px ; cursor: pointer"></i>
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

@include('admin.Store.create')
@include('admin.Store.deleteModal')
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
                $(".modal-body #details").val("");
                $(".modal-body #isDefault").val("0");
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
            url:'/stores-show' + '/' + id,
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
                            $(".modal-body #id").val(response.id);
                            $(".modal-body #isDefault").val(response.isDefault);
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
        let url = "{{ route('stores-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

</script>
</body>
</html>
