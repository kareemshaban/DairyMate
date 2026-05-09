<!DOCTYPE html>
@include('layouts.head')

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        @include('layouts.sidebar', ['slag' => 14, 'subSlag' => 143])
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            @include('layouts.nav')
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <form class="center" method="POST" action="{{ route('safeMovementReport') }}"
                      enctype="multipart/form-data" id="MealForm">
                    @csrf
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <h4 class="fw-bold py-3 mb-4">
                                <span class="text-muted fw-light">{{__('main.reports_department')}} /</span> {{__('main.safe_movement_report')}}
                            </h4>

                            <button type="submit" class="btn btn-primary" id="createButton" style="height: 45px">
                                {{__('main.search_btn')}}  <span class="tf-icons bx bx-search"></span>&nbsp;
                            </button>
                        </div>

                        <!-- Responsive Table -->
                        <div class="card">
                            <h5 class="card-header">{{__('main.safe_movement_report')}}</h5>
                            @include('flash-message')
                            <div class="card-content" style="padding-right: 20px; padding-left: 20px; padding-bottom: 20px">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.from_date') }} </label>
                                            <div style="display: flex ; gap: 10px">
                                                <input type="checkbox" id="isFromDate" name="isFromDate" class="form-check"  >

                                                <input type="text"  name="fromDate" id="fromDate"
                                                       class="form-control date @error('fromDate') is-invalid @enderror"
                                                       autofocus  />

                                            </div>
                                            @error('fromDate')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.to_date') }} </label>
                                            <div style="display: flex ; gap: 10px">
                                                <input type="checkbox" id="isToDate" name="isToDate" class="form-check"  >

                                                <input type="text"  name="toDate" id="toDate"
                                                       class="form-control date @error('toDate') is-invalid @enderror"
                                                       autofocus  />
                                            </div>
                                            @error('toDate')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.safe') }}  <span style="color:red"> * </span> </label>
                                            <select name="safe_id" id="safe_id"  @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                                    class="form-control search @error('safe_id') is-invalid @enderror" required >
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($safes as $safe)
                                                    <option value="{{$safe->id}}"> {{$safe->name}} </option>
                                                @endforeach
                                            </select>

                                            @error('safe_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.reportType') }} <span style="font-size: 14px; color: red">*</span></label>
                                            <select name="reportType" id="reportType"
                                                    class="form-control @error('reportType') is-invalid @enderror" required>
                                                <option value="0"> {{__('main.reportType0')}} </option>
                                                <option value="1"> {{__('main.reportType1')}} </option>
                                            </select>

                                            @error('reportType') <!-- Changed from 'to_store' to 'item_id' -->
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / Responsive Table -->
                    </div>
                </form>
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

@include('layouts.footer')
</body>
</html>
