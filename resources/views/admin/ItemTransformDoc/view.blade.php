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
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">{{__('main.cheese_department')}} /</span> {{__('main.item_transform_doc_view')}}
                        </h4>


                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.item_transform_doc_view')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">


                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.docNumber') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" name="bill_number" id="bill_number"
                                                   class="form-control @error('bill_number') is-invalid @enderror"
                                                   placeholder="{{ __('main.docNumber') }}" autofocus required readonly value="{{$doc -> bill_number}}"/>
                                            @error('bill_number')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <input type="hidden" id="id" name="id" value="{{$doc -> id}}">

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.date') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text"  name="date" id="date"
                                                   class="form-control @error('date') is-invalid @enderror" readonly
                                                   autofocus required value="{{ \Carbon\Carbon::parse($doc -> date) -> format('d-m-Y') }}"/>
                                            @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.from_store') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" id="from_store" name="from_store" class="form-control"
                                                   value="{{$doc -> from_store_name}}" readonly>



                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.to_store') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <input type="text" id="to_store" name="to_store" class="form-control"
                                                   value="{{$doc -> to_store_name}}" readonly>



                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.notes') }} </label>
                                            <textarea type="text" name="notes" id="notes"
                                                      class="form-control @error('notes') is-invalid @enderror"
                                                      placeholder="{{__('main.notes')}}" autofocus rows="2" readonly> {{$doc -> notes}} </textarea>
                                            @error('notes')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror

                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div style="    display: flex; justify-content: space-between;align-items: center;border-top: solid 3px #eee; margin: 15px auto;border-bottom: solid 3px #eee;">
                                        <h5 class="card-header">{{__('main.items')}}</h5>

                                    </div>

                                    <div class="table-responsive  text-nowrap">
                                        <table class="table table-striped table-hover view_table">
                                            <thead>
                                            <tr class="text-nowrap">
                                                <th class="text-center" hidden="hidden">Details_id</th>
                                                <th class="text-center" hidden="hidden">Item_id</th>
                                                <th class="text-center"> {{__('main.from_item')}}</th>
                                                <th class="text-center"> {{__('main.to_item')}}</th>
                                                <th class="text-center"> {{__('main.quantity_to_transform')}}</th>
                                                <th class="text-center"> {{__('main.weight')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="bond-details">
                                            @foreach($details as $detail)
                                                <tr data-item-index="{{$loop -> index}}">
                                                    <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="details_id[]" value="{{$detail -> id}}" /> </td>
                                                    <td class="text-center" hidden="hidden"> <input type="hidden" class="form-control" name="from_item_id[]" value="{{$detail ->from_item_id }}" />
                                                        <input type="hidden" class="form-control" name="to_item_id[]"   value="{{$detail ->to_item_id }}"/>
                                                    </td>
                                                    <td class="text-center">{{$detail -> from_item_code}} -- {{$detail -> from_item_name}}</td>
                                                    <td class="text-center">{{$detail -> to_item_code}} -- {{$detail -> to_item_name}}</td>
                                                    <td class="text-center"> <input type="number"  readonly step="any" class="form-control quantity" name="quantity[]" id="quantity_ . {{$loop -> index}}" min="1"  value="{{$detail -> quantity}}" /> </td>
                                                    <td class="text-center"> <input type="number" readonly step="any" class="form-control weight" name="weight[]" id="weight_ . {{$loop -> index}}" value="{{$detail -> weight}}" /> </td>
                                                </tr>


                                            @endforeach




                                            </tbody>
                                        </table>
                                    </div>

                                </div>


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

@include('layouts.footer')


</body>
</html>
