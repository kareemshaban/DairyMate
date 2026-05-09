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
                        <table class="table table-striped table-hover view_table">
                            <thead>
                            <tr class="text-nowrap">
                                <th class="text-center" hidden="hidden">#</th>
                                <th class="text-center"> {{__('main.code')}}</th>
                                <th class="text-center">{{__('main.name')}}</th>
                                <th class="text-center">{{__('main.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody id="items-body">
                                @foreach ($items as $item )
                                  <tr>
                                    <td class="text-center" hidden> </td>
                                    <td class="text-center" > {{$item -> code}} </td>
                                    <td class="text-center" > {{$item -> name}} </td>
                                    <td class="text-center" >
                                        <button
                                            class="btn btn-sm btn-primary selectBtn"
                                            data-id="{{$item -> id}}">
                                            إختيار
                                        </button>


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

    </script>
</div>
