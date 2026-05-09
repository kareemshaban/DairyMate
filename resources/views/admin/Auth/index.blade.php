<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 15 , 'subSlag' => 153])
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
                            <span class="text-muted fw-light">{{__('main.users_list')}} /</span> {{__('main.auth')}}
                        </h4>
                        @can('page-access', [22, 'edit'])
                       <a href="{{route('authCreate')}}">
                           <button type="button" class="btn btn-primary"   style="height: 45px">
                               {{__('main.update_auth')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                           </button>
                       </a>
                        @endcan


                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.auth')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.role')}}</th>
                                    <th class="text-center">{{__('main.form')}}</th>
                                    <th class="text-center">{{__('main.access_level')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $auth)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">

                                                {{$auth -> role}}

                                        </td>
                                        <td class="text-center">
                                                {{$auth -> form}}

                                        </td>

                                        <td class="text-center">
                                            @if($auth -> access_level == 0)
                                                <span class="badge bg-danger">{{__('main.access_level0')}}</span>
                                            @elseif($auth -> access_level == 1)
                                                <span class="badge bg-success">{{__('main.access_level1')}}</span>
                                            @elseif($auth -> access_level == 2)
                                                <span class="badge bg-warning">{{__('main.access_level2')}}</span>
                                            @endif
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

@include('admin.Roles.create')
@include('admin.Roles.deleteModal')
@include('layouts.footer')
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
        let url = "{{ route('deleteRole', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

</script>
</body>
</html>
