<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> </label>


                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>


            </div>
            <div class="modal-body" id="smallBody">

                <div class="container-fluid">

                    <form class="center" method="POST" action="{{ route('weakly_meals-carryingOver') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <h2 style="font-size: 12px; color: grey">{{__('main.post_note')}}</h2>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.code') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="text" name="code" id="code"
                                           class="form-control @error('code') is-invalid @enderror"
                                           placeholder="{{ __('main.code') }}" autofocus required readonly/>
                                    @error('code')
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
                                    <label>{{ __('main.total_buffalo_weight') }} </label>
                                    <input type="text" name="total_buffalo_weight" id="total_buffalo_weight"
                                              class="form-control @error('total_buffalo_weight') is-invalid @enderror"
                                              placeholder="{{__('main.total_buffalo_weight')}}" autofocus readonly />
                                    @error('total_buffalo_weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.total_bovine_weight') }} </label>
                                    <input type="text" name="total_bovine_weight" id="total_bovine_weight"
                                           class="form-control @error('total_bovine_weight') is-invalid @enderror"
                                           placeholder="{{__('main.total_bovine_weight')}}" autofocus readonly />
                                    @error('total_bovine_weight')
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
                                    <label>{{ __('main.price_buffalo') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="number" step="any" name="price_buffalo" id="price_buffalo"
                                           class="form-control @error('price_buffalo') is-invalid @enderror"
                                           placeholder="0" autofocus required />
                                    @error('price_buffalo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.price_bovine') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="number" step="any" name="price_bovine" id="price_bovine"
                                           class="form-control @error('price_bovine') is-invalid @enderror"
                                           placeholder="0" autofocus required/>
                                    @error('price_bovine')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>

                        </div>


                        <div class="row" style="margin-top: 40px">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">{{ __('main.post_close_btn') }}</button>

                            </div>

                        </div>




                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
