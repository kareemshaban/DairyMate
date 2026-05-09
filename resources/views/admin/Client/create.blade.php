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

                    <form class="center" method="POST" action="{{ route('suppliers-store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="@if($type == 0) col-12 @else col-6 @endif ">
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
                            @if($type == 1)
                                <div class="col-6" >
                                    <div class="form-group">
                                        <label>{{ __('main.order') }} <span style="font-size: 14px ; color: red">*</span></label>
                                        <input type="number" class="form-control @error('sort') is-invalid @enderror" id="sort"
                                               name="sort" required placeholder="0" >

                                        @error('sort')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            @endif
                            <div class="col-6" hidden="hidden">
                                <div class="form-group">
                                    <label>{{ __('main.client_type') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <select type="text" name="type" id="type"
                                           class="form-control @error('type') is-invalid @enderror"  required>
                                        <option value="0"> {{__('main.client_type0')}} </option>
                                        <option value="1"> {{__('main.client_type1')}} </option>
                                        <option value="2"> {{__('main.client_type2')}} </option>
                                    </select>
                                    <input type="hidden" name="type" id="type">
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px" hidden="hidden">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.buffalo_min_limit') }} </label>
                                    <input type="number" step="any" name="buffalo_min_limit" id="buffalo_min_limit"
                                           class="form-control @error('buffalo_min_limit') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('buffalo_min_limit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.buffalo_max_limit') }} </label>
                                    <input type="number" step="any" name="buffalo_max_limit" id="buffalo_max_limit"
                                           class="form-control @error('buffalo_max_limit') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('buffalo_max_limit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>

                        </div>
                        <div class="row" style="margin-top: 10px" hidden="hidden">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.bovine_min_limit') }} </label>
                                    <input type="number" step="any" name="bovine_min_limit" id="bovine_min_limit"
                                           class="form-control @error('bovine_min_limit') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('bovine_min_limit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.bovine_max_limit') }} </label>
                                    <input type="number" step="any" name="bovine_max_limit" id="bovine_max_limit"
                                           class="form-control @error('bovine_max_limit') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('bovine_max_limit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>

                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="@if($type == 0) col-12 @else col-6 @endif">
                                <div class="form-group">
                                    <label>{{ __('main.phone') }} </label>
                                    <input type="text" name="phone" id="phone"
                                              class="form-control @error('phone') is-invalid @enderror"
                                              placeholder="{{__('main.phone')}}" autofocus rows="2" />
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            @if($type == 1)
                            <div class="col-6" >
                                <div class="form-group">
                                    <label> {{__('main.car')}} </label>
                                    <select class="form-control" id="car_id" name="car_id" >
                                        <option value="0"> {{__('main.select')}} </option>
                                        @foreach($cars as $car)
                                            <option value="{{$car -> id}}"> {{$car -> car_number}} -- {{$car -> driver_name}}</option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            @endif
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
