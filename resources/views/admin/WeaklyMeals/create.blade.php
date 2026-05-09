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

                    <form class="center" method="POST" action="{{ route('weakly_meals-store') }}"
                          enctype="multipart/form-data">
                        @csrf
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.mealState') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <select type="text" name="stateS" id="stateS"
                                           class="form-control @error('type') is-invalid @enderror"  required disabled>
                                        <option value="0"> {{__('main.mealState0')}} </option>
                                        <option value="1"> {{__('main.mealState1')}} </option>
                                    </select>
                                    <input type="hidden" name="state" id="state">
                                    @error('type')
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
                                    <label>{{ __('main.start_date') }} <span style="font-size: 14px ; color: red">*</span></label>

                                    <input type="text"  name="start_date" id="start_date"
                                           class="form-control date @error('start_date') is-invalid @enderror"
                                         autofocus required/>
                                    @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.end_date') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="text"  name="end_date" id="end_date"
                                           class="form-control date @error('end_date') is-invalid @enderror"
                                           autofocus required/>
                                    @error('end_date')
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
                                    <label>{{ __('main.price_buffalo') }} </label>
                                    <input type="number" step="any" name="price_buffalo" id="price_buffalo"
                                           class="form-control @error('price_buffalo') is-invalid @enderror"
                                           placeholder="0" autofocus />
                                    @error('price_buffalo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.price_bovine') }} </label>
                                    <input type="number" step="any" name="price_bovine" id="price_bovine"
                                           class="form-control @error('price_bovine') is-invalid @enderror"
                                           placeholder="0" autofocus />
                                    @error('price_bovine')
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
                                    <label>{{ __('main.total_money') }} </label>
                                    <input type="text" name="total_money" id="total_money"
                                           class="form-control @error('total_money') is-invalid @enderror"
                                           placeholder="{{__('main.total_money')}}" autofocus readonly />
                                    @error('total_money')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.number_of_daily_meals') }} </label>
                                    <input type="text" name="number_of_daily_meals" id="number_of_daily_meals"
                                           class="form-control @error('number_of_daily_meals') is-invalid @enderror"
                                           placeholder="{{__('main.total_money')}}" autofocus readonly />
                                    @error('number_of_daily_meals')
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
