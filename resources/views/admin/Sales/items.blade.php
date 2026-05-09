<div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> {{__('main.products')}} </label>


                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>


            </div>
            <div class="modal-body" id="smallBody">
                <input type="hidden" id="id" name="id"/>
                <input type="hidden" id="selected_store_id" name="selected_store_id"/>
                <input type="hidden" id="selected_meal_id" name="selected_meal_id"/>
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{__('main.store')}}</label>
                                <select class="form-control" id="mstore_id" name="mstore_id" disabled>
                                    @foreach($stores as $store)
                                        <option  value="{{$store -> id}}"  > {{$store -> name}} </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                      

                    </div>

                    <div class="table-responsive  text-nowrap">
                        <table class="table table-striped table-hover view_table">
                            <thead>
                            <tr class="text-nowrap">
                                <th class="text-center" hidden="hidden">#</th>
                                <th class="text-center" hidden="hidden"> {{__('main.meal')}}</th>
                                <th class="text-center" > {{__('main.code')}}</th>
                                <th class="text-center">{{__('main.name')}}</th>
                                <th class="text-center">{{__('main.balance')}}</th>
                                <th class="text-center">{{__('main.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody id="items-body">


                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <script>
        function onMealChange() {
            let meal_id   = $('#meal_id').val();
            let from_store = $('#mstore_id').val(); // أو المتغير اللي عندك

            $.ajax({
                url: `/get_store_items/${from_store}`,
                method: 'GET',
                dataType: 'json',
                data: {
                    meal_id: meal_id !== '' ? meal_id : null   // 👈 الفلتر
                },
                beforeSend: function () {
                    $('#loader').show();
                },
                success: function (response) {

                    $('#itemsModal').modal("show");
                    $('#mstore_id').val(from_store);
                    $('#items-body').empty();

                    response.forEach(function (item, index) {
                        let row = `
                    <tr>
                        <td class="text-center" hidden>${index + 1}</td>
                        <td class="text-center">${item.code || ''}</td>
                        <td class="text-center">${item.cheese_meal_code || ''} -- ${item.symbol || ''}</td>
                        <td class="text-center">${item.name || ''}</td>
                        <td class="text-center">${item.balance ?? 0}</td>
                        <td class="text-center">
                            <button
                                class="btn btn-sm btn-primary selectBtn"
                                data-id="${item.id}"
                                data-meal="${item.meal_id}"
                                data-store="${from_store}">
                                إختيار
                            </button>
                        </td>
                    </tr>
                `;
                        $('#items-body').append(row);
                    });
                },
                complete: function () {
                    $('#loader').hide();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                    toastr.error('فشل في جلب العناصر');
                    $('#loader').hide();
                }
            });
        }

    </script>
</div>
