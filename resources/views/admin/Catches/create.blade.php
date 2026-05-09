<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> </label>


                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>


            </div>
            <div class="modal-body" id="smallBody">

                <div class="container-fluid">

                    <form class="center" method="POST" action="{{ route('store-catches') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.docNumber') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="text" name="bill_number" id="bill_number"
                                           class="form-control @error('bill_number') is-invalid @enderror"
                                           placeholder="{{ __('main.docNumber') }}" autofocus required readonly/>
                                    @error('bill_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                                <input type="hidden" id="id" name="id">
                            </div>

                            <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ __('main.date') }} </label>
                                        <input type="text"  name="date" id="date"
                                               class="form-control date @error('date') is-invalid @enderror"
                                               placeholder="0" autofocus required/>
                                        @error('date')
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
                                    <label>{{ __('main.client') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="client_id" id="client_id" @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                             class="form-control search @error('client_id') is-invalid @enderror"
                                             autofocus  required>
                                        <option value=""> {{__('main.select')}}</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client -> id}}"> {{$client -> name}} </option>
                                        @endforeach

                                    </select>
                                    @error('client_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.safe') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="safe_id" id="safe_id" @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                             class="form-control search  @error('safe_id') is-invalid @enderror"
                                             autofocus  required>
                                        <option value=""> {{__('main.select')}}</option>
                                        @foreach($safes as $safe)
                                            <option value="{{$safe -> id}}"> {{$safe -> name}} </option>
                                        @endforeach

                                    </select>
                                    @error('safe_id')
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
                                    <label>{{ __('main.amount') }} </label>
                                    <input type="number" step="any" name="amount" id="amount"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.payment_method') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="payment_method" id="payment_method"
                                             class="form-control @error('payment_method') is-invalid @enderror"
                                             autofocus  required>

                                        <option value="0"> {{__('main.payment_method0')}}</option>
                                        <option value="1"> {{__('main.payment_method1')}}</option>

                                    </select>
                                    @error('safe_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>

                        </div>
                        <div class="row" style="margin-top: 10px">

                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('main.notes') }} </label>
                                    <textarea type="text" name="notes" id="notes"
                                              class="form-control @error('notes') is-invalid @enderror"
                                              placeholder="{{__('main.notes')}}" autofocus rows="2"> </textarea>
                                    @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>


                      <div class="row" style="margin-top: 40px">
                            <div class="col-12 text-center">
                                <div style="display: flex ; gap: 10px; align-items: end; justify-content: center; ">
                                    <div class="form-group" style="display: flex ; flex-direction: column; justify-content: center; align-items: center;">
                                        <label>{{ __('main.isPost') }}</label>
                                        <input type="checkbox" id="isPost" name="isPost" class="form-check" style="width: 25px ; height: 25px;"/>

                                    </div>

                                   <button type="submit" class="btn btn-primary">{{ __('main.save_btn') }}</button>

                                </div>


                            </div>

                        </div>




                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
