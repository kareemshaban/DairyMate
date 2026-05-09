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
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">{{__('main.accounting_department')}} /</span> {{__('main.loans')}}
                        </h4>
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.loans_list')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.docNumber')}}</th>
                                    <th class="text-center">{{__('main.date')}}</th>
                                    <th class="text-center">{{__('main.supplier')}}</th>
                                    <th class="text-center">{{__('main.safe')}}</th>
                                    <th class="text-center">{{__('main.amount')}}</th>
                                    <th class="text-center">{{__('main.installment_amount')}}</th>
                                    <th class="text-center">{{__('main.installment_count')}}</th>
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
                                        <td class="text-center">{{$doc -> installment_amount}}</td>
                                        <td class="text-center">{{$doc -> installment_count}}</td>
                                        <td class="text-center">

                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <i class='bx bxs-edit-alt text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer"></i>
                                                    <i class='bx bx-money text-primary payBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer"></i>
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

@include('admin.Loans.create')
@include('admin.Loans.deleteModal')
@include('admin.Loans.pay')
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
            url:'/loan-getCode',
            dataType: 'json',
            success:function(code){
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
                        $(".modal-body #supplier_id").val("");
                        $(".modal-body #safe_id").val("");
                        $(".modal-body #amount").val("0");
                        $(".modal-body #installment_amount").val("0");
                        $(".modal-body #installment_count").val("0");
                        $(".modal-body #remaining_installments").val("0");
                        $(".modal-body #paid_installments").val("0");

                        $(".modal-body #supplier_id").prop('disabled', false);
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
            url:'/geLoan' + '/' + id,
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
                            $(".modal-body #supplier_id").val( response.supplier_id );
                            $(".modal-body #safe_id").val( response.safe_id );
                            $(".modal-body #amount").val( response.amount );
                            $(".modal-body #installment_amount").val( response.installment_amount );
                            $(".modal-body #installment_count").val( response.installment_count );
                            $(".modal-body #remaining_installments").val( response.remaining_installments );
                            $(".modal-body #paid_installments").val( response.paid_installments );
                            $(".modal-body #notes").val( response.notes );
                            $(".modal-body #id").val(response.id);
                            var translatedText = "{{ __('main.editData') }}";
                            $(".modelTitle").html(translatedText);

                            $(".modal-body #supplier_id").prop('disabled', true);

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
    $(document).on('click', '.payBtn', function(event) {
        let id = event.currentTarget.id ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');

        $.ajax({
            type:'get',
            url:'/catches-getCode',
            dataType: 'json',
            success:function(code){
                $.ajax({
                    type:'get',
                    url:'/geLoan' + '/' + id,
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


                                    $('#payModal').modal("show");
                                    $(".modal-body #bill_number").val(code );
                                    $(".modal-body #client_id").val( response.supplier_id );
                                    $(".modal-body #safe_id").val("");
                                    $(".modal-body #amount").val( response.installment_amount );
                                    $(".modal-body #payment_method").val("");
                                    $(".modal-body #notes").val("");
                                    $(".modal-body #id").val(response.id);
                                    $(".modal-body #loan_id").val(response.id);
                                    var translatedText = "{{ __('main.payInstalment') }}";
                                    $(".modelTitle").html(translatedText);

                                    $(".modal-body #supplier_id").prop('disabled', true);

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
            }
        })

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
        let url = "{{ route('deleteLoan', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }


    $(document).on('keyup change', '.modal-body #amount, .modal-body #installment_amount', function () {
           let amount = 0 ;
           let installment_amount = 0 ;
           let installment_count = 0 ;
        const $input = $(this);
        // Check which field is being updated
        if ($input.attr('id') === 'amount') {

            amount = parseFloat($input.val()) || 0;
            installment_amount = $('.modal-body #installment_amount').val();
            installment_count = amount / installment_amount ;
        } else if ($input.attr('id') === 'installment_amount') {

            installment_amount = parseFloat($input.val()) || 0;
            amount = $('.modal-body #amount').val();
            installment_count = amount / installment_amount ;
        }


        $('.modal-body #installment_count').val(installment_count);
    });

</script>
</body>
</html>
