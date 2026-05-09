<!DOCTYPE html>

@include('layouts.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 14 , 'subSlag' => 141])
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('layouts.nav')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <form class="center" method="POST" action="{{ route('clientAccountReport') }}"
                      enctype="multipart/form-data" id="MealForm">
                    @csrf
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div style="display: flex ; justify-content: space-between ; align-items: center">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">{{__('main.reports_department')}} /</span> {{__('main.client_account_report')}}
                        </h4>

                        <button type="submit" class="btn btn-primary" id="createButton" style="height: 45px"
                               >
                            {{__('main.search_btn')}}  <span class="tf-icons bx bx-search"></span>&nbsp;
                        </button>

                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.client_account_report')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">


                                <div class="row">


                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.from_date') }} </label>
                                            <div style="display: flex ; gap: 10px">
                                                <input type="checkbox" id="isDateFrom" name="isDateFrom" class="form-check"  >

                                                <input type="text"  name="from_date" id="from_date"
                                                   class="form-control date @error('from_date') is-invalid @enderror"
                                                   autofocus  />

                                            </div>
                                            @error('from_date')
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
                                                <input type="checkbox" id="isDateTo" name="isDateTo" class="form-check"  >

                                            <input type="text"  name="to_date" id="to_date"
                                                   class="form-control date @error('to_date') is-invalid @enderror"
                                                   autofocus  />
                                            </div>
                                            @error('to_date')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>



                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.client') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="client_id" id="client_id"  @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                                     class="form-control search @error('client_id') is-invalid @enderror"  required>
                                                <option value=""> {{__('main.select')}} </option>
                                                @foreach($clients as $client)
                                                    <option value="{{$client -> id}}"> {{$client -> name  }} </option>

                                                @endforeach

                                            </select>

                                            @error('client_id')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 10px">
                                        <div class="form-group">
                                            <label>{{ __('main.report_type') }} <span style="font-size: 14px ; color: red">*</span></label>
                                            <select  name="report_type" id="report_type"
                                                     class="form-control @error('report_type') is-invalid @enderror"  required>
                                                <option value="0"> {{__('main.report_type0')}} </option>
                                                <option value="1"> {{__('main.report_type1')}} </option>

                                            </select>

                                            @error('to_store')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>



                                </div>




                            </form>

                        </div>

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


@include('layouts.footer')



</body>
</html>
