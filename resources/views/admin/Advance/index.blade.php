<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 18 , 'subSlag' => 183])
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
                                <span class="text-muted fw-light">{{__('main.employee_department')}} /</span> {{__('main.advances')}}
                            </h4>
                            <div class="mb-3" >
                                <select id="filterRecords" class="form-control" >
                                    <option value="all" @if($is_all == 1) selected @endif>{{ __('main.show_all_records') }}</option>
                                    <option value="week" @if($is_all == 0) selected @endif>{{ __('main.show_this_week_records') }}</option>
                                </select>
                            </div>
                        </div>
                        @can('page-access', [11, 'edit'])
                            <button type="button" class="btn btn-primary" id="createButton"  style="height: 45px">
                                {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                            </button>
                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.advances')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.date')}}</th>
                                    <th class="text-center"> {{__('main.employee')}}</th>
                                    <th class="text-center">{{__('main.safe')}}</th>
                                    <th class="text-center">{{__('main.amount')}}</th>
                                    <th class="text-center">{{__('main.paid_back')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($docs as $doc)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center"> {{\Carbon\Carbon::parse($doc -> date) -> format('Y-m-d') }}</td>

                                        <td class="text-center">{{$doc -> employee_name}}</td>
                                        <td class="text-center">{{$doc -> safe_name}}</td>
                                        <td class="text-center">{{$doc -> amount}}</td>
                                        <td class="text-center">
                                            @if($doc -> paid_back == 0)
                                                <span class="badge bg-warning">{{__('main.paid_back0')}}</span>
                                            @elseif($doc -> paid_back == 1)
                                                <span class="badge bg-success">{{__('main.paid_back1')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($doc -> paid_back == 0)
                                            <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                <i class='bx bxs-edit text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                   style="font-size: 25px ; cursor: pointer" data-id="{{$doc -> id}}">

                                                </i>

                                                <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                   data-id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer">

                                                </i>


                                            </div>
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


@include('admin.Advance.create')
@include('layouts.footer')
<script></script>
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
                $(".modal-body #amount").val("0");
                $(".modal-body #safe_id").val("0");
                $(".modal-body #employee_id").val("0");
                $(".modal-body #description").val("");

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

    $(document).on('click', '.editBtn', function (event) {
        console.log('clicked');
        let id = $(this).data('id');
        event.preventDefault();
        let href = $(this).attr('data-attr');

        $.ajax({
            type: 'get',
            url: '/getAdvance' + '/' + id,
            dataType: 'json',

            success: function (response) {
                $.ajax({
                    url: href,
                    beforeSend: function () {
                        $('#loader').show();
                    },
                    // return the result
                    success: function (result) {
                        $('#createModal').modal("show");
                        $(".modal-body #id").val(response.id);
                        $(".modal-body #amount").val(response.amount);
                        $(".modal-body #safe_id").val(response.safe_id);
                        $(".modal-body #employee_id").val(response.employee_id);
                        $(".modal-body #description").val(response.description);

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
            }


        });


    });

    $(document).on('click', '.deleteBtn', function(event) {
        let id = $(this).data('id');
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


    function confirmDelete(id){
        let url = "{{ route('deleteAdvance', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }



    $(document).on('change', '#filterRecords', function() {
        let filter = $(this).val();
        if (filter === 'week') {
            let url = "{{ route('advances', ':isAll') }}";
            url = url.replace(':isAll', 0);
            document.location.href=url;
        } else {
            let url = "{{ route('advances', ':isAll') }}";
            url = url.replace(':isAll', 1);
            document.location.href=url;
        }
    });
</script>
</body>
</html>
