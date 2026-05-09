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
                <form class="center" method="POST" action="{{ route('store-auth') }}"
                      enctype="multipart/form-data">
                    @csrf
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div style="display: flex ; justify-content: space-between ; align-items: center">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">{{__('main.users_list')}} /</span> {{__('main.auth')}}
                        </h4>

                            <button type="submit" class="btn btn-primary"   style="height: 45px">
                                {{__('main.save_btn')}}  <span class="tf-icons bx bx-save"></span>
                            </button>



                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> {{__('main.role')}}  <span style="font-size: 14px ; color: red"> * </span> </label>
                                        <select id="role_id" name="role_id" class="form-control" required >
                                            <option value=""> {{__('main.choose')}} </option>
                                            @foreach($roles as $role)
                                                <option value="{{$role -> id}}">

                                                        {{$role -> name}}

                                                </option>
                                            @endforeach

                                        </select>

                                        <input type="hidden" id="lang" name="lang"  value="{{Config::get('app.locale')}}"/>

                                    </div>

                                </div>

                            </div>
                        </div>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover view_table" id="authTable">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center" hidden="hidden"></th>
                                    <th class="text-center">{{__('main.role')}}</th>
                                    <th class="text-center">{{__('main.form')}}</th>
                                    <th class="text-center">{{__('main.access_level')}}</th>
                                </tr>
                                </thead>
                                <tbody id="auth-details">


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--/ Responsive Table -->
                </div>
                <!-- / Content -->

                </form>

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
    $(document).ready(function() {
        $('#role_id').val("");



    });
    $('#role_id').on('change', function () {
        var roleId = $(this).val();

        $.ajax({
            type:'get',
            url:'/getRoleAuthForms' + '/' + roleId,
            dataType: 'json',

            success:function(response){
                console.log(response);
                $('#auth-details').html("");
                var html = "" ;

                var lang = $('#lang').val();
                if(response){
                    for (let i = 0 ; i < response.length ; i ++){

                        html += '<tr data-item-index="' + i + '" data-row-id="' + response[i].auth_id + '">';
                        html += `<td class="text-center"> ${i + 1} </td>`;
                        html += `<td class="text-center" hidden="hidden">
                          <input id="id[]" name="id[]" value="${response[i].auth_id}" />
                          <input id="role_id[]" name="role_id[]" value="${response[i].role_id}" />
                          <input id="form_id[]" name="form_id[]" value="${response[i].form_id}" />
                          </td>`;
                        html += `<td class="text-center"> ${response[i].role } </td>`;

                        html += `<td class="text-center"> ${ response[i].form  } </td>`;

                        let access_level0 = "{{ __('main.access_level0') }}";
                        let access_level1 = "{{ __('main.access_level1') }}";
                        let access_level2 = "{{ __('main.access_level2') }}";


                        html += `<td class="text-center">
                                 <select id="access_level[]"  name="access_level[]"  class="form-control access_level" required >
                                    <option value="0" data-color="red" style="color: red;" ${response[i].access_level == 0 ? 'selected' : ''}> ${access_level0}  </option>
                                    <option value="1" data-color="green" style="color: green;" ${response[i].access_level == 1 ? 'selected' : ''}> ${access_level1} </option>
                                    <option value="2" data-color="blue" style="color: blue;" ${response[i].access_level == 2 ? 'selected' : ''}> ${access_level2}  </option>
                                  <select/>
                             </td>`;





                        html += '</tr>';

                    }
                    $('#auth-details').html(html);



                } else {

                }

                if ($.fn.DataTable.isDataTable('#authTable')) {
                    $('#authTable').DataTable().destroy();
                }
                $('#authTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy' , 'excel',  'print'
                    ],
                    responsive: true,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'

                    }
                });

            }
        });

    });

</script>
</body>
</html>
