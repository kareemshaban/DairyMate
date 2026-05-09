<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shoonah | Activate Account</title>
    <!-- Password Reset 1 - Bootstrap Brain Component -->
    <link href="{{asset('assets/css/nav.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/reset.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />
    <style>
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



        .modelTitle {
            color: #B57E10 ;
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
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light  fixed-top">
    <div class="container-fluid">


        <div class="collapse navbar-collapse" id="navbarNav">


            <!-- Language Dropdown -->
            <ul class="navbar-nav" style="margin-left: 100px">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        {{__('main.lang')}}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" hel="alternate" hreflang="ar"
                               href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" >Arabic |  العربية</a></li>
                        <li><a class="dropdown-item" el="alternate" hreflang="en"
                               href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" >English | الإنجليزية</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="bg-light py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-11 col-lg-8 col-xl-7 col-xxl-6">
                <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                    <div class="row gy-3 mb-5">
                        <div class="col-12">
                            <div class="text-center">
                                <a href="#!">
                                    <img src="{{asset('assets/img/shoonah_trans.png')}}" alt="BootstrapBrain Logo" width="175" height="57">
                                </a>
                            </div>
                        </div>
                        <div class="col-12">
                            <h2 class="fs-6 fw-normal text-center text-secondary m-0 px-md-5">{{__('main.reset_password_to_verify')}}</h2>
                        </div>
                    </div>
                    <form class="center" method="POST" action="{{ route('verify-account') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-3 gy-md-4 overflow-hidden">
                            <div class="col-12">
                                <label for="email" class="form-label">{{__('main.email')}} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                      <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                          <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                                        </svg>
                                      </span>
                                    <input type="email" class="form-control" name="email" id="email" required value="{{$user -> email}}"
                                    readonly>
                                    <input type="hidden" id="id" name="id" value="{{$user -> id}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">{{__('main.new_password')}} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                      <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                          <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                                        </svg>
                                      </span>
                                    <input type="password" class="form-control" name="new_password" id="new_password" required >

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg" type="submit">{{__('main.verify_account_btn')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/nav.js')}}"></script>
</body>
</html>



