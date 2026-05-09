<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تسجيل الدخول | DairyMate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 (RTL Version) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Google Font (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favico.png')}}" />

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
        }

        .login-card {
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.2);
            border: none;
            background: #fff;
        }

        .login-image {
            background: url("{{ asset('assets/auth/images/dairy_factory_bg.jpg') }}") center / cover no-repeat;
            position: relative;
        }

        .login-image::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(30, 60, 114, 0.5);
        }

        /* ضبط الحقول لتناسب الاتجاه العربي */
        .form-control {
            height: 50px;
            border-radius: 10px !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 10px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            color: #2a5298;
            min-width: 45px;
            justify-content: center;
        }

        .btn-login {
            height: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 10px;
            background-color: #2a5298;
            border: none;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #1e3c72;
            transform: translateY(-2px);
        }

        .brand-footer {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 25px;
        }

        /* تحسين مظهر التشيك بوكس في الـ RTL */
        .form-check-input {
            float: right;
            margin-left: 0.5rem;
            margin-right: 0;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-10 col-lg-11">
            <div class="card login-card shadow-lg">
                <div class="row g-0">

                    <!-- الجانب الأيمن: النموذج -->
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold text-primary mb-1">DairyMate</h2>
                                <h6 class="text-secondary mb-3">نظام إدارة إنتاج الألبان الذكي</h6>
                            </div>

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <!-- البريد الإلكتروني -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">البريد الإلكتروني</label>
                                    <div class="input-group">
                                        <!-- الأيقونة أولاً في الكود لتظهر يمين الحقل في RTL -->
                                        <span class="input-group-text">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                        <input type="email" name="email" id="email" required
                                               class="form-control" placeholder="admin@automation-home.com">
                                    </div>
                                </div>

                                <!-- كلمة المرور -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">كلمة المرور</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="password" required
                                               class="form-control" placeholder="********">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check p-0">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            تذكر بيانات الدخول
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-login">
                                    دخول للنظام <i class="fa fa-sign-in-alt ms-2"></i>
                                </button>
                            </form>

                            <div class="text-center brand-footer">
                                <p>بواسطة <strong>AutomationHome</strong> &copy; 2026</p>
                            </div>
                        </div>
                    </div>

                    <!-- الجانب الأيسر: الصورة -->
                    <div class="col-lg-6 d-none d-lg-block login-image order-1 order-lg-2">
                        <div class="h-100 d-flex flex-column justify-content-center align-items-center text-white position-relative" style="z-index: 2;">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="DairyMate Logo" class="mb-4" width="300">
                            <h3 class="fw-bold">Smart Dairy Solution</h3>
                            <p class="px-5 text-center small opacity-75">حلول أتمتة متكاملة لمصانع الألبان والجبنة لضمان أعلى جودة في الإنتاج.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    @if ($errors->any())
        toastr.options = { "positionClass": "toast-top-left", "rtl": true };
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
</body>
</html>
