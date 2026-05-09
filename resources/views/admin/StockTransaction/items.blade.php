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
                <div class="container-fluid">

                    <div class="table-responsive  text-nowrap">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">#</th>
                                <th class="text-center" hidden="hidden"> {{__('main.meal')}}</th>
                                <th class="text-center"> {{__('main.code')}}</th>
                                <th class="text-center">{{__('main.name')}}</th>
                                <th class="text-center">{{__('main.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                    <td class="text-center">{{$item -> code}}</td>
                                    <td class="text-center">{{$item -> name}}</td>
                                    <td class="text-center">

                                        <div style="display: flex ; gap: 10px ; justify-content: center ">
                                            <i class='bx bxs-pointer text-primary selectBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.select_action')}}"
                                               data-id="{{ $item->id }}" style="font-size: 25px ; cursor: pointer"></i>

                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.selectBtn', function(event) {
            let id = $(this).data('id')
            console.log(id);
            $('.modal-body #id').val(id);
            $('#itemsModal').modal("hide");

        });
    </script>
</div>
