<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> </label>


                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>


            </div>
            <div class="modal-body" id="smallBody">

                <div class="container-fluid">


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
                                               class="form-control @error('date') is-invalid @enderror"
                                               placeholder="0" autofocus required readonly/>
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
                                    <label>{{ __('main.recipit_typel') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="recipit_type" id="recipit_type"
                                             class="form-control @error('recipit_type') is-invalid @enderror"
                                             autofocus  required disabled>
                                        <option value=""> {{__('main.select')}}</option>
                                        @foreach($types as $type)
                                            <option value="{{$type -> id}}"> {{$type -> name}} </option>
                                        @endforeach

                                    </select>
                                    @error('recipit_type')
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
                                             autofocus  required disabled>
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
                                           placeholder="0" autofocus required readonly/>
                                    @error('amount')
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
                                    <textarea type="text" name="notes" id="notes" readonly
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



                </div>
            </div>
        </div>
    </div>
</div>
