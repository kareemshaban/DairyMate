<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar"
>
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0" id="desktop-menu-toggle">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
               <img  src="{{asset('assets/img/logo.png')}}" style="height: 80px" />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center @if(Config::get('app.locale')=='ar' ) me-auto @else ms-auto @endif ">
            <!-- Place this tag where you want the button to render. -->

            <li class="nav-item" style="@if(Config::get('app.locale')=='en' )  padding-right: 30px  ; @else padding-left: 30px ;  @endif">
                @if(Config::get('app.locale')=='en' )
                    <a el="alternate" hreflang="ar"
                       href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" class="d-flex align-items-center gap-2 dropdown-item">
                       <img src="{{asset('assets/img/arab.png')}}" style="width: 40px; cursor: pointer; ">
                    </a>
                    @else
                    <a el="alternate" hreflang="en"
                       href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="d-flex align-items-center gap-2 dropdown-item">
                       <img src="{{asset('assets/img/english.png')}}" style="width: 40px; cursor: pointer; ">
                    </a>
                @endif

            </li>
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{asset('assets/img/avatar.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu @if(Config::get('app.locale')=='ar' ) dropdown-menu-start  @else  dropdown-menu-end @endif "
                @if(Config::get('app.locale')=='ar' ) style="direction: rtl" @endif>
                    <li>
                        <a class="dropdown-item" href="#" >
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{asset('assets/img/avatar.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{auth()->user()->name }}</span>
                                    <small class="text-muted">

                                            @if(auth()->user() -> type == 0)
                                                <span class="badge bg-success">{{__('main.user0')}}</span>
                                            @elseif(auth()->user() -> type == 1)
                                                <span class="badge bg-info">{{__('main.user1')}}</span>
                                            @elseif(auth()->user() -> type == 2)
                                                <span class="badge bg-primary">{{__('main.user2')}}</span>
                                            @endif


                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" @if(Config::get('app.locale')=='ar' ) style="text-align: right" @endif href="{{route('getUserProfile' , auth() -> user() -> id)}}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">{{__('main.profile' )}}</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" onclick="openLogOutModal()"
                           @if(Config::get('app.locale')=='ar' ) style="text-align: right" @endif href="#">
                            <i class="bx bx-power-off me-2 text-danger"></i>
                            <span class="align-middle text-danger">{{__('main.logOut')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
@include('layouts.logOutModal')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function openLogOutModal(){
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {
                $('#alertModal').modal("show");
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function(jqXHR, testStatus, error) {
                console.log(error);
                alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            },
            timeout: 8000
        })
    }

    $(document).on('click', '.logout_btn', function(event) {
        console.log('clicked');
        $('#alertModal').modal("hide");
        document.getElementById('logout-form').submit();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('desktop-menu-toggle');
        const htmlTag = document.documentElement;

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // إضافة أو حذف الكلاس من الـ html
                htmlTag.classList.toggle('layout-menu-collapsed');

                // تغيير شكل الأيقونة (السهم)

                // إرسال تنبيه للمتصفح لإعادة حساب الأبعاد
                window.dispatchEvent(new Event('resize'));
            });
        }
    });
</script>
