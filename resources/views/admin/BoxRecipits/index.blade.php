<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 12 , 'subSlag' => 121])
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
                            <span class="text-muted fw-light">{{__('main.accounting_department')}} /</span> {{__('main.box_recipit')}}

                            <div class="mb-3" >
                                <select id="filterRecords" class="form-control" >
                                    <option value="all" @if($is_all == 1) selected @endif>{{ __('main.show_all_records') }}</option>
                                    <option value="week" @if($is_all == 0) selected @endif>{{ __('main.show_this_week_records') }}</option>
                                </select>
                            </div>
                        </div>
                        @can('page-access', [17, 'edit'])
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>
                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.box_recipit_list')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.docNumber')}}</th>
                                    <th class="text-center">{{__('main.date')}}</th>
                                    <th class="text-center">{{__('main.recipit_typel')}}</th>
                                    <th class="text-center">{{__('main.safe')}}</th>
                                    <th class="text-center">{{__('main.amount')}}</th>
                                    <th class="text-center">{{__('main.doc_state')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($docs as $doc)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$doc -> bill_number}}</td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($doc -> date) -> format('d-m-Y')  }}</td>
                                        <td class="text-center">{{$doc -> name}}</td>
                                        <td class="text-center">{{$doc -> safe}}</td>
                                        <td class="text-center">{{$doc -> amount}}</td>
                                        <td class="text-center">

                                            @if($doc -> state == 0)
                                                <span class="badge bg-warning">{{__('main.doc_state0')}}</span>
                                            @elseif($doc -> state == 1)
                                                <span class="badge bg-success">{{__('main.doc_state1')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">

                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    @if($doc -> state == 0)
                                                        @can('page-access', [17, 'edit'])
                                                        <i class='bx bxs-edit-alt text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                           id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                        <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                           id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                        <i class='bx bxs-cloud-upload text-primary postBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.post_doc_action')}}"
                                                           data-id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer"></i>

                                                    @endcan
                                                        @else

                                                        <i class='bx bxs-show text-primary viewBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.view_action')}}"
                                                           data-id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    @endif


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

@include('admin.BoxRecipits.create')
@include('admin.BoxRecipits.view')
@include('admin.BoxRecipits.deleteModal')
@include('layouts.footer')
<script type="text/javascript">
    var id = 0 ;
    $(document).on('click', '#createButton', function (event) {
        console.log('clicked');
        id = 0;
        event.preventDefault();
        let href = $(this).attr('data-attr');

        $.ajax({
            type:'get',
            url:'/boxRecipits-getCode',
            dataType: 'json',
            success:function(response){
                $.ajax({
                    url: href,
                    beforeSend: function () {
                        $('#loader').show();
                    },
                    // return the result
                    success: function (result) {
                        $('#createModal').modal("show");
                        $(".modal-body #id").val(0);
                        $(".modal-body #bill_number").val(response.code);

                        const recipit_type = "";
                        const typeSelect = choicesInstances['recipit_type'];
                        typeSelect.setChoiceByValue(recipit_type.toString());


                        const safeId = response.safe.id;
                        const safeSelect = choicesInstances['safe_id'];
                        safeSelect.setChoiceByValue(safeId.toString());

                        $(".modal-body #amount").val("0");
                        $(".modal-body #payment_method").val("0");
                        $(".modal-body #notes").val("");

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
    $(document).on('click', '.editBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/getBoxRecipit' + '/' + id,
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

                            var date = new Date(response.date);
                            var day = date.getDate(),
                                month = date.getMonth() + 1,
                                year = date.getFullYear();

                            month = (month < 10 ? "0" : "") + month;
                            day = (day < 10 ? "0" : "") + day;
                            var start_date = year + "-" + month + "-" + day ;

                            $('#createModal').modal("show");
                            $(".modal-body #bill_number").val( response.bill_number );
                            $(".modal-body #date").val( start_date );
                           // $(".modal-body #recipit_type").val( response.recipit_type );
                            //$(".modal-body #safe_id").val( response.safe_id );

                            const recipit_type = response.recipit_type;
                            const typeSelect = choicesInstances['recipit_type'];
                            typeSelect.setChoiceByValue(recipit_type.toString());


                            const safeId = response.safe_id;
                            const safeSelect = choicesInstances['safe_id'];
                            safeSelect.setChoiceByValue(safeId.toString());

                            $(".modal-body #amount").val( response.amount );
                            $(".modal-body #payment_method").val( response.payment_method );
                            $(".modal-body #notes").val( response.notes );
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
    $(document).on('click', '.viewBtn', function(event) {
        let id = $(this).data('id') ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/getBoxRecipit' + '/' + id,
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

                            var date = new Date(response.date);
                            var day = date.getDate(),
                                month = date.getMonth() + 1,
                                year = date.getFullYear();

                            month = (month < 10 ? "0" : "") + month;
                            day = (day < 10 ? "0" : "") + day;
                            var start_date = year + "-" + month + "-" + day ;

                            $('#viewModal').modal("show");
                            $(".modal-body #bill_number").val( response.bill_number );
                            $(".modal-body #date").val( start_date );
                            $(".modal-body #recipit_type").val( response.recipit_type );
                            $(".modal-body #safe_id").val( response.safe_id );
                            $(".modal-body #amount").val( response.amount );
                            $(".modal-body #payment_method").val( response.payment_method );
                            $(".modal-body #notes").val( response.notes );
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
    $(document).on('click', '.postBtn', function(event) {
        let docId = $(this).data('id');
        Swal.fire({
            title: 'ترحيل و إقفال المستند',
            text: 'هل انت متأكد من ترحيل و إقفال المستند ؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم , متأكد',
            cancelButtonText: 'لا , تراجع'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion or any other logic
                postDoc(docId);
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
        let url = "{{ route('deleteBoxRecipit', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

    function postDoc(id){

        let url = "{{ route('box_recipit_post', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;

    }
    $(document).on('change', '#filterRecords', function() {
        let filter = $(this).val();
        if (filter === 'week') {
            let url = "{{ route('boxRecipits', ':isAll') }}";
            url = url.replace(':isAll', 0);
            document.location.href=url;
        } else {
            let url = "{{ route('boxRecipits', ':isAll') }}";
            url = url.replace(':isAll', 1);
            document.location.href=url;
        }
    });
</script>
</body>
</html>
