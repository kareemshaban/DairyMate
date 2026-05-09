<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 4 , 'subSlag' => 46])
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
                            <span class="text-muted fw-light">{{__('main.product_department')}} /</span> {{__('main.items_qnt')}}
                        </h4>
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px">
                            {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                        </button>
                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.items_qnt')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.item')}}</th>
                                    <th class="text-center">{{__('main.store')}}</th>
                                    <th class="text-center">{{__('main.opening_balance')}}</th>
                                    <th class="text-center">{{__('main.quantity_in')}}</th>
                                    <th class="text-center">{{__('main.quantity_out')}}</th>
                                    <th class="text-center">{{__('main.balance')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$item -> item_name}}</td>
                                        <td class="text-center">{{$item -> store_name}}</td>
                                        <td class="text-center">{{$item -> opening_quantity}}</td>
                                        <td class="text-center">{{$item -> quantity_in}}</td>
                                        <td class="text-center">{{$item -> quantity_out}}</td>
                                        <td class="text-center">{{$item -> balance + $item -> opening_quantity }}</td>
                                        <td class="text-center">

                                                <div style="display: flex ; gap: 10px ; justify-content: center ">
                                                    <i class='bx bx-show text-success editBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                       id="{{$item -> item_id}}"
                                                       data-quantity="{{ $item -> store_quantity_id  }}"   style="font-size: 25px ; cursor: pointer"></i>

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

@include('admin.items-quantity.balance')
@include('layouts.footer')
<script type="text/javascript">
    var id = 0 ;
    $(document).on('click', '#createButton', function (event) {
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
                        $('#balanceModal').modal("show");
                        $(".modal-body #store_id").val( 0 );
                        $(".modal-body #item_id").val( 0);
                        $(".modal-body #opening_quantity").val(0 );
                        $(".modal-body #quantity_in").val( 0 );
                        $(".modal-body #quantity_out").val( 0);
                        $(".modal-body #balance").val(0  );
                        $(".modal-body #id").val(0);

                        $(".modal-body #store_id").prop("disabled", false);
                        $(".modal-body #material_id").prop("disabled", false);

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
        let id = $(this).data('quantity') ;
        console.log(id);
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            type:'get',
            url:'/items-quantity-show' + '/' + id,
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

                            const store_id = response.store_id;
                            const storeSelect = choicesInstances['store_id'];
                            storeSelect.setChoiceByValue(store_id.toString());

                            const item_id = response.item_id;
                            const materialSelect = choicesInstances['item_id'];
                            materialSelect.setChoiceByValue(item_id.toString());

                            $(".modal-body #opening_quantity").val( response.opening_quantity );
                            $(".modal-body #quantity_in").val( response.quantity_in );
                            $(".modal-body #quantity_out").val( response.quantity_out );
                            $(".modal-body #balance").val(Number(response.balance) + Number(response.opening_quantity)  );
                            $(".modal-body #id").val(response.id);
                            $(".modal-body #m_store_id").val(response.store_id);
                            $(".modal-body #m_item_id").val(response.item_id);

                            $(".modal-body #store_id").prop("disabled", true);
                            $(".modal-body #material_id").prop("disabled", true);

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



</script>
</body>
</html>
