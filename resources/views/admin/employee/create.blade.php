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

                    <form class="center" method="POST" action="{{ route('store-employee') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 ">
                                <div class="form-group">
                                    <label>{{ __('main.name') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="{{ __('main.name') }}" autofocus required/>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                                <input type="hidden" id="id" name="id">
                            </div>

                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.phone') }} </label>
                                    <input type="text" step="any" name="phone" id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="+201XXXXXXXXX" autofocus  style="direction: ltr"/>
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.weekly_salary') }} </label>
                                    <input type="number" step="any" name="weekly_salary" id="weekly_salary"
                                           class="form-control @error('weekly_salary') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('weekly_salary')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('main.address') }} </label>
                                    <textarea type="text" name="address" id="address"
                                              class="form-control @error('address') is-invalid @enderror"
                                              placeholder="{{__('main.address')}}" autofocus rows="2"> </textarea>
                                    @error('address')
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
