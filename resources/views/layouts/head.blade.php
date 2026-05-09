<html
    lang="en"
    class="light-style layout-menu-fixed"
    @if(Config::get('app.locale')=='ar' ) dir="rtl" @else dir="ltr"   @endif
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard | DairyMate</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favico.png')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('assets/js/config.js')}}"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <link rel="stylesheet" href="{{asset('assets/css/flatpickr.min.css')}}">
    <script src="{{asset('assets/js/flatpickr.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/choices.min.css')}}" />
    <!-- JS -->
    <script src="{{asset('assets/js/choices.min.js')}}"></script>
        <link rel="stylesheet" href="{{asset('assets/css/datatables/dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/datatables/buttons.dataTables.min.css')}}">
    <link href="{{asset('assets/css/toastr.min.css')}}" rel="stylesheet"/>

</head>
<style>

    .choices__list--dropdown {
        bottom: auto !important;
        top: 100% !important;
    }
    .dataTables_filter {
        margin-left: 10px !important;
        margin-right: 10px !important;;
    }
    .dt-buttons {
        margin-left: 10px !important;;
        margin-right: 10px !important;;
    }
    .dataTables_info {
        margin-left: 10px !important;;
        margin-right: 10px !important;;
    }
    @font-face {
        font-family: 'icomoon';
        src: url("{{asset('assets/fonts/ArbFONTS-The-Sans-Plain.otf')}}");
        src: url("{{asset('assets/fonts/ArbFONTS-The-Sans-Plain.otf')}}");
        font-weight: normal;
        font-style: normal;
    }

    * {
        font-family: 'icomoon' !important;
    }

   .date {
       background: white !important;
   }
    .date_time {
        background: white !important;
    }
    .choices__inner::after {
        display: none !important;
    }

    .modelTitle {
        color: #D268CA ;
        font-size: 20px;
    }

    .modal-close:hover {
        transform: scale(1.5);
    }

    .alertImage {
        width: 40px;
        height: 40px;
        margin: 5px;
    }

    .dt-paging {
        direction: ltr !important;
    }

    .dt-length select {
        width: 200px !important;
        -webkit-appearance: auto !important;
        -moz-appearance: auto !important;
        appearance: auto !important;
        direction: ltr !important;
    }

    .dt-search {
        margin-bottom: 10px;
    }

    .dt-search input {
        width: 200px !important;
    }

    .dt-length {
        display: flex;
        direction: rtl;
        align-items: center;
        float: left;

    }

    .dt-length label {
        margin-left: 5px;
        margin-right: 5px
    }

    .dt-search {
        display: flex;
        direction: ltr;
        align-items: center;
        float: right;
    }

    .dt-search label {
        margin-left: 5px;
        margin-right: 5px
    }

    .dt-empty {
        text-align: center !important;
    }
    .imgDiv {
        width: 100px;
        height: 100px;
        border: solid 1px #CBCBCB;
        border-radius: 10px;
        display: flex;
        margin: auto;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    .pickerImg {
        width: 40px;
        height: 40px;
    }
</style>


@if(Config::get('app.locale')=='en' )
<style>
    .dt-paging nav {
        float: right !important;
    }

    @media (min-width: 1200px) {
        .layout-menu-fixed .layout-menu,
        .layout-menu-fixed-offcanvas .layout-menu {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .layout-menu-fixed:not(.layout-menu-collapsed) .layout-page,
        .layout-menu-fixed-offcanvas:not(.layout-menu-collapsed) .layout-page {
            padding-left: 16.25rem !important;
            padding-right: 0 !important;
        }
    }
    @media (max-width: 1199.98px) {
        .layout-menu-fixed .layout-wrapper.layout-navbar-full .layout-menu,
        .layout-menu-fixed-offcanvas .layout-wrapper.layout-navbar-full .layout-menu {
            top: 0 !important;
        }

        html:not(.layout-navbar-fixed) .layout-navbar-full .layout-page {
            padding-top: 0 !important;
        }
    }
    @media (min-width: 1200px) {
        .layout-menu-fixed .layout-navbar-full .layout-navbar,
        .layout-menu-fixed-offcanvas .layout-navbar-full .layout-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
        }

        .layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar,
        .layout-menu-fixed.layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar,
        .layout-menu-fixed-offcanvas.layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar {
            left: 16.25rem;
            right: 0;
        }
    }
    .menu-icon {
        margin-left: 0 ;
        margin-right: 0.5rem;
    }
    .layout-wrapper:not(.layout-horizontal) .bg-menu-theme .menu-inner > .menu-item.active:before {
        right: 0;
        left: unset;
    }
    .menu-vertical .menu-item .menu-toggle::after {
        right: 1rem !important;
    }
    .menu-toggle::after {
        transform: translateY(-50%) rotate(45deg);

    }
    .menu-vertical .menu-sub .menu-link {
        padding-left: 3rem;
    }

    .bg-menu-theme .menu-sub > .menu-item > .menu-link::before {
        left: 1.4375rem;
    }
    .menu-vertical .menu-item .menu-toggle {
        padding-right: calc(1rem + 1.26em);
    }
    .bg-menu-theme .menu-header:before {
        left: 0;
    }
</style>

@endif



@if(Config::get('app.locale')=='ar' )
<style>
    .dt-length select {
        width: 200px !important;
        -webkit-appearance: auto !important;
        -moz-appearance: auto !important;
        appearance: auto !important;
        direction: rtl !important;
    }

    .dt-length {
        display: flex;
        direction: ltr;
        align-items: center;
        float: right;

    }

    .dt-search {
        display: flex;
        direction: ltr;
        align-items: center;
        float: left;
    }


    @media (min-width: 1200px) {
        .layout-menu-fixed .layout-menu,
        .layout-menu-fixed-offcanvas .layout-menu {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .layout-menu-fixed:not(.layout-menu-collapsed) .layout-page,
        .layout-menu-fixed-offcanvas:not(.layout-menu-collapsed) .layout-page {
            padding-right: 16.25rem !important;
            padding-left: 0 !important;
        }
    }

    @media (max-width: 1199.98px) {
        .layout-menu-fixed .layout-wrapper.layout-navbar-full .layout-menu,
        .layout-menu-fixed-offcanvas .layout-wrapper.layout-navbar-full .layout-menu {
            top: 0 !important;
        }

        html:not(.layout-navbar-fixed) .layout-navbar-full .layout-page {
            padding-top: 0 !important;
        }
    }
    @media (min-width: 1200px) {
        .layout-menu-fixed .layout-navbar-full .layout-navbar,
        .layout-menu-fixed-offcanvas .layout-navbar-full .layout-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
        }

        .layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar,
        .layout-menu-fixed.layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar,
        .layout-menu-fixed-offcanvas.layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar {
            right: 16.25rem !important;
            left: 0 !important;
        }
    }


    .menu-icon {
        margin-right: 0 ;
        margin-left: 0.5rem;
    }
    .layout-wrapper:not(.layout-horizontal) .bg-menu-theme .menu-inner > .menu-item.active:before {
        right: unset;
        left: 0;
    }
    .menu-vertical .menu-item .menu-toggle::after {
        left: 1rem !important;
    }

    .menu-toggle::after {
        transform: translateY(-50%) rotate(225deg);

    }
    .menu-vertical .menu-sub .menu-link {
        padding-right: 3rem;
    }
    .bg-menu-theme .menu-sub > .menu-item > .menu-link::before {
        right: 1.4375rem;
    }
    .menu-vertical .menu-item .menu-toggle {
        padding-left: calc(1rem + 1.26em);
    }
    .bg-menu-theme .menu-header:before {
        right: 0;
    }

</style>


@endif

<style>
    #loading-overlay  {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.85);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }
    #closing-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.85);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }

    .loader {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #007bff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    .loading-text {
        margin-top: 15px;
        font-size: 18px;
        font-weight: 500;
        color: #333;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>
