<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 15 , 'subSlag' => 151])
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
                            <span class="text-muted fw-light">{{__('main.users_list')}} /</span> {{__('main.users')}}
                        </h4>
                        @can('page-access', [20, 'edit'])
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>
                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.users')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.name')}}</th>
                                    <th class="text-center">{{__('main.role')}}</th>
                                    <th class="text-center">{{__('main.email')}}</th>
                                    <th class="text-center">{{__('main.type')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$user -> name}}</td>
                                        <td class="text-center">
                                                {{$user -> role ?? ''}}
                                        </td>
                                        <td class="text-center">{{$user -> email}}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{__('main.user0')}}</span>
                                        </td>


                                        <td class="text-center">
                                            @can('page-access', [23, 'edit'])
                                            <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                <i class='bx bxs-edit-alt text-success editBtn'  data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                   id="{{$user -> id}}" style="font-size: 25px ; cursor: pointer"></i>

                                                <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                   id="{{$user -> id}}" style="font-size: 25px ; cursor: pointer"></i>

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

@include('admin.Users.create')
@include('admin.Users.deleteModal')
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
                $('#createAccountModal').modal("show");
                $(".modal-body #id").val(0);
                $(".modal-body #supplier_id").val(0);
                $(".modal-body #type").val(0);
                $(".modal-body #name").val("");
                $(".modal-body #email").val("");
                $(".modal-body #password").val("");
                $(".modal-body #password2").val("");
                $(".modal-body #role_id").val("");
                $(".modal-body #roles").show();
                $(".modal-body #role_id").attr('required', true);
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
            url:'/getUser' + '/' + id,
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
                            $('#createAccountModal').modal("show");

                            $(".modal-body #id").val(response.id);
                            $(".modal-body #supplier_id").val(response.supplier_id);
                            $(".modal-body #type").val(response.type);
                            $(".modal-body #name").val(response.name);
                            $(".modal-body #email").val(response.email);
                            $(".modal-body #password").val("*******");
                            $(".modal-body #password2").val("*******");
                            $(".modal-body #role_id").val(response.role_id);
                            $(".modal-body #roles").show();
                            $(".modal-body #role_id").attr('required', true);

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
        let url = "{{ route('deleteUser', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

</script>
</body>
</html>
