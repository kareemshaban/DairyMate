<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 17 , 'subSlag' => 171])
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
                                <span class="text-muted fw-light">{{__('main.cheese_department')}} /</span> {{__('main.item_transform_docs')}}
                            </h4>
                            <div class="mb-3" >
                                <select id="filterRecords" class="form-control" >
                                    <option value="all" @if($is_all == 1) selected @endif>{{ __('main.show_all_records') }}</option>
                                    <option value="week" @if($is_all == 0) selected @endif>{{ __('main.show_this_week_records') }}</option>
                                </select>
                            </div>
                        </div>
                        @can('page-access', [13, 'edit'])
                        <a href="{{route('item_transform_docs_create')}}">
                            <button type="button" class="btn btn-primary"  style="height: 45px">
                                {{__('main.add_new')}}  <span class="tf-icons bx bx-plus"></span>&nbsp;
                            </button>
                        </a>

                        @endcan

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.item_transform_docs')}}</h5>
                        @include('flash-message')
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">#</th>
                                    <th class="text-center"> {{__('main.docNumber')}}</th>
                                    <th class="text-center"> {{__('main.date')}}</th>
                                    <th class="text-center"> {{__('main.from_store')}}</th>
                                    <th class="text-center">{{__('main.to_store')}}</th>
                                    <th class="text-center">{{__('main.meal_state')}}</th>
                                    <th class="text-center">{{__('main.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($docs as $doc)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop -> index +1}}</th>
                                        <td class="text-center">{{$doc -> bill_number}}</td>
                                        <td class="text-center"> {{\Carbon\Carbon::parse($doc -> date) -> format('Y-m-d') }}</td>

                                        <td class="text-center">{{$doc -> from_store_name}}</td>
                                        <td class="text-center">{{$doc -> to_store_name}}</td>
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
                                                    @can('page-access', [23, 'edit'])
                                                    <a href="{{route('item_transform_docs_edit' , $doc -> id)}}">
                                                        <i class='bx bxs-edit text-success' data-toggle="tooltip" data-placement="top" title="{{__('main.edit_action')}}"
                                                           style="font-size: 25px ; cursor: pointer">

                                                        </i>
                                                    </a>
                                                    <i class='bx bxs-trash text-danger deleteBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.delete_action')}}"
                                                       id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer">

                                                    </i>
                                                    <i class='bx bxs-cloud-upload text-primary postBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.post_action')}}"
                                                       id="{{$doc -> id}}"  style="font-size: 25px ; cursor: pointer">

                                                    </i>
                                                    @endcan

                                                @else
                                                    <a href="{{route('item_transform_docs_view' , $doc -> id)}}">
                                                        <i class='bx bxs-show text-primary viewBtn' data-toggle="tooltip" data-placement="top" title="{{__('main.view_action')}}"
                                                           id="{{$doc -> id}}" style="font-size: 25px ; cursor: pointer">

                                                        </i>
                                                    </a>

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


@include('admin.ItemTransformDoc.alertModal')
@include('admin.ItemTransformDoc.deleteModal')
@include('layouts.footer')
<script></script>
<script type="text/javascript">
    var id = 0 ;

    $(document).on('click', '.postBtn', function(event) {
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
                $('#confirmModal').modal("show");
                var translatedText = "{{ __('main.confirm_post') }}";

                $(" #msg").html( translatedText.replace(/\n/g, "<br>") );
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

    $(document).on('click', '.agree_btn', function(event) {
        $('#confirmModal').modal("hide");
        console.log(id);
        confirmPost(id);
    });


    function confirmDelete(id){
        let url = "{{ route('item_transform_docs_delete', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

    function confirmPost(id){
        console.log(id);
        let url = "{{ route('item_transform_docs_post', ':id') }}";
        url = url.replace(':id', id);
        document.location.href=url;
    }

    $(document).on('change', '#filterRecords', function() {
        let filter = $(this).val();
        if (filter === 'week') {
            let url = "{{ route('item_transform_docs', ':isAll') }}";
            url = url.replace(':isAll', 0);
            document.location.href=url;
        } else {
            let url = "{{ route('item_transform_docs', ':isAll') }}";
            url = url.replace(':isAll', 1);
            document.location.href=url;
        }
    });
    //
</script>
</body>
</html>
