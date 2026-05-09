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

                    <form class="center" method="POST" action="{{ route('store-advance') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin-top: 10px">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.employee') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="employee_id" id="employee_id" @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                    class="form-control @error('employee_id') is-invalid @enderror"
                                             autofocus  required>
                                        <option value=""> {{__('main.select')}}</option>
                                        @foreach($employees as $employee)
                                            <option value="{{$employee -> id}}"> {{$employee -> name}} </option>
                                        @endforeach

                                    </select>
                                    @error('employee_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input type="hidden" id="id" name="id">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.safe') }} <span style="font-size: 14px ; color: red">*</span> </label>
                                    <select  name="safe_id" id="safe_id" @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                    class="form-control   @error('safe_id') is-invalid @enderror"
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

                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('main.details') }} </label>
                                    <textarea type="text" name="description" id="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="{{__('main.description')}}" autofocus rows="2"> </textarea>
                                    @error('description')
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
