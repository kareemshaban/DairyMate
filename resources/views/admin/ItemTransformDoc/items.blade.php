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

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label> {{__('main.from_item')}} </label>
                                <select id="fitem" name="fitem" class="form-control">
                                     <option value="0"> {{__('main.select')}} </option>


                                </select>

                            </div>

                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label> {{__('main.to_item')}} </label>
                                <select id="titem" name="titem" class="form-control">
                                    <option value="0"> {{__('main.select')}} </option>
                                    @foreach($allItems as $item)
                                        <option value="{{$item -> id}}">  {{$item -> name}} </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>
                    </div>



                    <div class="row" style="margin-top: 40px">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary selectBtn">{{ __('main.select_btn') }}</button>

                        </div>

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
