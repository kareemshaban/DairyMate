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

                    <form class="center" method="POST" action="{{ route('supplier-balance-update') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.opening_balance_debit') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="number" name="opening_balance_debit" id="opening_balance_debit"
                                           class="form-control @error('opening_balance_debit') is-invalid @enderror"
                                           placeholder="{{ __('main.opening_balance_debit') }}" autofocus required/>
                                    @error('opening_balance_debit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                                <input type="hidden" id="id" name="id">
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.opening_balance_credit') }} <span style="font-size: 14px ; color: red">*</span></label>
                                    <input type="number" name="opening_balance_credit" id="opening_balance_credit"
                                           class="form-control @error('opening_balance_credit') is-invalid @enderror"
                                           placeholder="{{ __('main.opening_balance_credit') }}" autofocus required/>
                                    @error('opening_balance_credit')
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
                                    <label>{{ __('main.debit') }} </label>
                                    <input type="number" name="debit" id="debit"
                                           class="form-control @error('debit') is-invalid @enderror"
                                           placeholder="{{ __('main.debit') }}" autofocus  readonly/>
                                    @error('debit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.credit') }} </label>
                                    <input type="number" name="credit" id="credit"
                                           class="form-control @error('credit') is-invalid @enderror"
                                           placeholder="{{ __('main.credit') }}" autofocus readonly/>
                                    @error('credit')
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
