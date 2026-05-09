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

                    <form class="center" method="POST" action="{{ route('store-loans') }}"
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
                                    <label>{{ __('main.supplier') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="supplier_id" id="supplier_id"
                                             class="form-control @error('supplier_id') is-invalid @enderror"
                                             autofocus  required>
                                        <option value=""> {{__('main.select')}}</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier -> id}}"> {{$supplier -> name}} </option>
                                        @endforeach

                                    </select>
                                    @error('supplier_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.safe') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="safe_id" id="safe_id"
                                             class="form-control @error('safe_id') is-invalid @enderror"
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
                                    <label>{{ __('main.amount') }} <span style="color: red ; font-size: 14px">*</span> </label>
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
                                    <label>{{ __('main.installment_amount') }} <span style="color: red ; font-size: 14px">*</span></label>
                                    <input type="number" step="any" name="installment_amount" id="installment_amount"
                                           class="form-control @error('installment_amount') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('installment_amount')
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
                                    <label>{{ __('main.installment_count') }} <span style="color: red ; font-size: 14px">*</span></label>
                                    <input type="number" step="any" name="installment_count" id="installment_count"
                                           class="form-control @error('installment_count') is-invalid @enderror"
                                           placeholder="0" autofocus required readonly/>
                                    @error('installment_count')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.start_date') }} </label>
                                    <input type="text"  name="start_date" id="start_date"
                                           class="form-control date @error('start_date') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('start_date')
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
                                    <label>{{ __('main.remaining_installments') }} </label>
                                    <input type="number" step="any" name="remaining_installments" id="remaining_installments"
                                           class="form-control @error('remaining_installments') is-invalid @enderror"
                                           placeholder="0" autofocus required readonly/>
                                    @error('remaining_installments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.paid_installments') }} </label>
                                    <input type="number" step="any" name="paid_installments" id="paid_installments"
                                           class="form-control @error('paid_installments') is-invalid @enderror"
                                           placeholder="0" autofocus required readonly/>
                                    @error('paid_installments')
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
                                <button type="submit" class="btn btn-primary">{{ __('main.save_btn') }}</button>

                            </div>

                        </div>




                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
