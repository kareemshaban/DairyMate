<div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> </label>


                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>


            </div>
            <div class="modal-body" id="smallBody">

                <div class="container-fluid">

                    <form class="center" method="POST" action="{{ route('items-quantity-store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.store') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <select  name="store_id" id="store_id" @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                           class="form-control search @error('store_id') is-invalid @enderror" autofocus required>
                                        <option value="0"> {{__('main.choose')}} </option>
                                        @foreach($stores as $store)
                                            <option value="{{$store -> id}}" > {{$store -> name}} </option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                                <input type="hidden" id="id" name="id">
                                <input type="hidden" id="m_store_id" name="m_store_id">
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.item') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <select  name="item_id" id="item_id" @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                             class="form-control search @error('item_id') is-invalid @enderror" autofocus required>
                                        <option value="0"> {{__('main.choose')}} </option>
                                        @foreach($items as $item)
                                            <option value="{{$item -> id}}" > {{$item -> name}} </option>
                                        @endforeach
                                    </select>
                                    @error('item_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input type="hidden" id="m_item_id" name="m_item_id">

                                </div>

                            </div>


                        </div>

                        <div class="row" >
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.quantity_in') }} </label>
                                    <input type="number" name="quantity_in" id="quantity_in"
                                           class="form-control @error('quantity_in') is-invalid @enderror"
                                           placeholder="{{ __('main.quantity_in') }}" autofocus  readonly/>
                                    @error('quantity_in')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.quantity_out') }} </label>
                                    <input type="number" name="quantity_out" id="quantity_out"
                                           class="form-control @error('quantity_out') is-invalid @enderror"
                                           placeholder="{{ __('main.quantity_out') }}" autofocus readonly/>
                                    @error('quantity_out')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                            </div>
                        </div>


                        <div class="row" style="margin-top: 10px">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.opening_balance') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="number" step="any" name="opening_quantity" id="opening_quantity"
                                           class="form-control @error('opening_quantity') is-invalid @enderror"
                                           placeholder="{{ __('main.opening_balance') }}" autofocus required/>
                                    @error('opening_quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.balance') }} </label>
                                    <input type="number" name="balance" id="balance"
                                           class="form-control @error('balance') is-invalid @enderror"
                                           placeholder="{{ __('main.balance') }}" autofocus readonly/>
                                    @error('balance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>


                        <div class="row" style="margin-top: 40px">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">{{ __('main.save_btn') }}</button>

                            </div>

                        </div>




                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
