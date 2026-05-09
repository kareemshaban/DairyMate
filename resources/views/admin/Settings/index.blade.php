<!DOCTYPE html >

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 16 , 'subSlag' => 161])
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('layouts.nav')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div style="display: flex ; justify-content: space-between ; align-items: center">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">{{__('main.settings')}} /</span> {{__('main.settings_edit')}}
                        </h4>
                        @can('page-access', [23, 'edit'])
                        <button type="button" class="btn btn-primary"  id="createButton" style="height: 45px" onclick="valdiateForm()">
                            {{__('main.save_btn')}}  <span class="tf-icons bx bx-save"></span>&nbsp;
                        </button>
                        @endif

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.settings')}}</h5>
                        @include('flash-message')

                        <form class="center" method="POST" action="{{ route('store-settings') }}"
                              enctype="multipart/form-data" id="product-form">
                            @csrf
                            <div class="container-fluid" style="padding-bottom: 30px;">
                                <div class="row">
                                    <input type="hidden" id="id" name="id" value="{{$setting->id ?? 0}}">
                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;" >
                                        <div class="form-group">
                                            <label>{{ __('main.buffalo_milk_price') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="number" step="any" class="form-control @error('buffalo_milk_price') is-invalid @enderror"
                                                   id="buffalo_milk_price" name="buffalo_milk_price" required
                                                   value="{{ (float) ($setting->buffalo_milk_price ?? 0) }}">
                                            @error('buffalo_milk_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;">
                                        <div class="form-group">
                                            <label>{{ __('main.bovine_milk_price') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="number" step="any" class="form-control @error('bovine_milk_price') is-invalid @enderror"
                                                   id="bovine_milk_price" name="bovine_milk_price" required
                                                   value="{{ (float) ($setting->bovine_milk_price ?? 0) }}" >
                                            @error('bovine_milk_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;">
                                        <div class="form-group">
                                            <label>{{ __('main.protein_price') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="number" step="any" class="form-control @error('protein_price') is-invalid @enderror"
                                                   id="protein_price" name="protein_price" required
                                                   value="{{ (float) ($setting->protein_price ?? 0) }}" >
                                            @error('protein_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;">
                                        <div class="form-group">
                                            <label>{{ __('main.cream_price') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="number" step="any" class="form-control @error('cream_price') is-invalid @enderror"
                                                   id="cream_price" name="cream_price" required
                                                   value="{{ (float) ($setting->cream_price ?? 0) }}" >
                                            @error('cream_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;">
                                        <div class="form-group">
                                            <label>{{ __('main.cheese_price') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="number" step="any" class="form-control @error('cheese_price') is-invalid @enderror"
                                                   id="cheese_price" name="cheese_price" required
                                                   value="{{ (float) ($setting->cheese_price ?? 0) }}" >
                                            @error('cheese_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>


                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;">
                                        <div class="form-group">
                                            <label>{{ __('main.white_cheese_price') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="number" step="any" class="form-control @error('white_cheese_price') is-invalid @enderror"
                                                   id="white_cheese_price" name="white_cheese_price" required
                                                   value="{{ (float) ($setting->white_cheese_price ?? 0) }}" >
                                            @error('white_cheese_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>


                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;" hidden="hidden">
                                        <div class="form-group">
                                            <label>{{ __('main.morning_bonus_time') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="text"  class="form-control morning_bonus_time  @error('morning_bonus_time') is-invalid @enderror"
                                                   id="morning_bonus_time" name="morning_bonus_time" required
                                                   value="{{ optional($setting)->morning_bonus_time ? \Carbon\Carbon::parse($setting->morning_bonus_time)->format('H:i') : '00:00' }}">
                                            @error('morning_bonus_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;" hidden="hidden">
                                        <div class="form-group">
                                            <label>{{ __('main.evening_bonus_time') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="text"  class="form-control evening_bonus_time @error('evening_bonus_time') is-invalid @enderror"
                                                   id="evening_bonus_time" name="evening_bonus_time" required
                                                   value="{{ optional($setting)->evening_bonus_time ? \Carbon\Carbon::parse($setting->evening_bonus_time)->format('H:i') : '00:00' }}">
                                            @error('evening_bonus_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 10px;" hidden="hidden">
                                        <div class="form-group">
                                            <label>{{ __('main.bonus_value') }} <span style="color: red ; font-size: 14px" > * </span> </label>
                                            <input type="number" step="any" class="form-control @error('bonus_value') is-invalid @enderror"
                                                   id="bonus_value" name="bonus_value" required value="{{ (float) ($setting->bonus_value ?? 0) }}">
                                            @error('bonus_value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror


                                        </div>
                                    </div>
                                </div>

                            </div>

                        </form>

                    </div>
                    <!--/ Responsive Table -->
                </div>
                <!-- / Content -->

                <!-- Footer -->
                @include('layouts.footer_design')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>

@include('admin.Store.create')
@include('admin.Store.deleteModal')
@include('layouts.footer')
<script type="text/javascript">
    var id = 0 ;


    function valdiateForm(){
        $('#product-form').submit();
    }

    flatpickr(".evening_bonus_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i", // 24-hour format
        time_24hr: true,
        defaultDate: document.getElementById('evening_bonus_time').value
    });
    flatpickr(".morning_bonus_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i", // 24-hour format
        time_24hr: true,
        defaultDate: document.getElementById('morning_bonus_time').value
    })

</script>
</body>
</html>
