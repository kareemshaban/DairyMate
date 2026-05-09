
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>RegistrationForm</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="../../assets/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../../assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../../assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../../assets/css/util.css">
		<!-- LINEARICONS -->
		<link rel="stylesheet" href="fonts/linearicons/style.css">
		<script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
		<!-- STYLE CSS -->
		<link rel="stylesheet" href="../../assets/css/register.css">
        
	</head>

	<body style="background-image: url(../../assets/img/login.jpg);">

		<div class="wrapper">
			<div class="inner">
			
			    <form method="POST" action="{{ route('register') }}">
                        @csrf
					<h3>{{__('main.register')}}</h3>
					<div class="form-holder">
						<span class="lnr lnr-user"></span>
	
				
                        <input id="name" type="text"  placeholder="{{__('main.ph_username')}}"  class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>
					<div class="form-holder">
						<span class="lnr lnr-phone-handset"></span>
                        <input id="phone" type="text" placeholder="{{__('main.ph_phone')}}"  class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="email">

                        @error('phone')
                                    <span class="invalid-feedback" role="alert" style="margin-top: 25px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
					<div class="form-holder">
						<span class="lnr lnr-envelope"></span>
                        <input id="email" type="email" placeholder="{{__('main.ph_email')}}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                                    <span class="invalid-feedback" role="alert" style="margin-top: 25px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
					<div class="form-holder">
						<span class="lnr lnr-lock"></span>
                        <input id="password" placeholder="{{__('main.ph_pass')}}"  type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

				
                        @error('password')
                                    <span class="invalid-feedback" role="alert" style="margin-top: 25px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                    </div>
                    <div class="form-holder">
						<span class="lnr lnr-lock"></span>
                        <input id="password-confirm" placeholder="{{__('main.ph_pass')}}" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                
                     <button type="submit" class=" btn-primary" >
                                    {{ __('Register') }}
                     </button>
                    <p class="w-100 text-center" style="text-align:center; ; display:block; margin:10px auto;">&mdash; {{__('main.or_login')}} &mdash; <br> 
                   <a href="{{route('login')}}">  <span style="text-decoration:underline ; color:#0D47A1;">{{__('main.lofin_title2')}}</span></a>
                   </p>

				</form>
			
			</div>
			
		</div>
		

        
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/main.js"></script>
        <script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>

     
	</body>
</html>


