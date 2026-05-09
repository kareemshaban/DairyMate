<!DOCTYPE html>

@include('layouts.head')


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('layouts.sidebar' , ['slag' => 13 , 'subSlag' => 131])
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
                                <span class="text-muted fw-light">{{__('main.accounting_department')}} /</span>
                                {{__('main.balance_transactions')}}
                                <div class="mb-3" >
                                    <select id="filterRecords" class="form-control" >
                                        <option value="all" @if($is_all == 1) selected @endif>{{ __('main.show_all_records') }}</option>
                                        <option value="week" @if($is_all == 0) selected @endif>{{ __('main.show_this_week_records') }}</option>
                                    </select>
                                </div>
                            </div>
                            @can('page-access', [2, 'edit'])
                            <button type="button" class="btn btn-primary" id="createButton" style="height: 45px">
                                {{__('main.add_new')}} <span class="tf-icons bx bx-plus"></span>&nbsp;
                            </button>
                            @endcan

                        </div>



                        <!-- Responsive Table -->
                        <div class="card">
                            <h5 class="card-header">{{__('main.balance_transactions')}}</h5>
                            @include('flash-message')
                            <div class="table-responsive  text-nowrap">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="text-nowrap">
                                            <th class="text-center">#</th>
                                            <th class="text-center"> {{__('main.docNumber')}}</th>
                                            <th class="text-center">{{__('main.date')}}</th>
                                            <th class="text-center">{{__('main.from_balance')}}</th>
                                            <th class="text-center">{{__('main.to_balance')}}</th>
                                            <th class="text-center">{{__('main.amount')}}</th>
                                            <th class="text-center">{{__('main.actions')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($docs as $doc)
                                        <tr>
                                            <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                            <td class="text-center">{{\Carbon\Carbon::parse($doc -> date) ->
                                                format('d-m-Y') }}</td>
                                            <td class="text-center">{{$doc -> bill_number}}</td>
                                            <td class="text-center">{{$doc -> source_name}}</td>
                                            <td class="text-center">{{$doc -> destiantion_name}}</td>
                                            <td class="text-center">{{$doc -> balance}}</td>

                                            <td class="text-center">
                                                @can('page-access', [2, 'edit'])
                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <i class='bx bxs-edit-alt text-success editBtn'
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{__('main.edit_action')}}" id="{{$doc -> id}}"
                                                        style="font-size: 25px ; cursor: pointer"></i>

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

    @include('admin.Safe.create2')
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
            url:'/get_transaction_code',
            dataType: 'json',
            success:function(code){
                console.log(code);
        $.ajax({
            url: href,
            beforeSend: function () {
                $('#loader').show();
            },
            // return the result
            success: function (result) {
                $('#createModal').modal("show");
                $(".modal-body #id").val(0);
                $(".modal-body #bill_number").val(code);
                $(".modal-body #from_safe_id").val("0");
                $(".modal-body #to_safe_id").val("0");
                $(".modal-body #balance").val("0");
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
        });

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
            url:'/balance_transactions_show' + '/' + id,
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


                            const from_safe_id = response.from_safe_id;
                            const from_safe_Select = choicesInstances['from_safe_id'];
                            from_safe_Select.setChoiceByValue(from_safe_id.toString());

                            const to_safe_id = response.to_safe_id;
                            const to_safe_select = choicesInstances['to_safe_id'];
                            to_safe_select.setChoiceByValue(to_safe_id.toString());

                            $(".modal-body #id").val(response.id);
                            $(".modal-body #balance").val(response.balance);
                            $(".modal-body #notes").val(response.notes);
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
    $(document).on('click', '.addBalance', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/balance-show' + '/' + id,
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
                            $('#balanceModal').modal("show");
                            $(".modal-body #safe").val( response.name );
                            $(".modal-body #safe_id").val( response.id );
                            $(".modal-body #opening_balance").val( response.opening_balance );

                            var translatedText = "{{ __('main.editBalance') }}";
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
        let url = "{{ route('safes-delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }
        $(document).on('change', '#filterRecords', function() {
            let filter = $(this).val();
            if (filter === 'week') {
                let url = "{{ route('balance_transactions', ':isAll') }}";
                url = url.replace(':isAll', 0);
                document.location.href=url;
            } else {
                let url = "{{ route('balance_transactions', ':isAll') }}";
                url = url.replace(':isAll', 1);
                document.location.href=url;
            }
        });

    </script>
</body>

</html>
