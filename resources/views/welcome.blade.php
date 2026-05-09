
<!doctype html>
<html lang="en">
  <head>
  	<title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="../../assets/css/login.css">

	</head>
	<body class="img js-fullheight" style="background-image: url(../assets/img/login.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">{{__('main.login_title')}}</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-6 login_card" >
					<div class="login-wrap p-0">
		      	<h3 class="mb-4 text-center">{{__('main.lofin_title2')}}</h3>
		      	<form class="login100-form validate-form" method="POST" action="{{ route('login') }}">

                        @csrf
		
					
					<div class="form-group" data-validate = "Username is required">
						<input type="email"   id="email" placeholder="user name"
                        class="input100 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
					
                        @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
					
					
					<div class="form-group" data-validate="Password is required">
						<input  placeholder="Password" type="password"  id="password" type="password" class="input100 form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">


                        @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>

					<div class="form-group">
						<button class="form-control btn btn-primary submit px-3" type="submit">
							Sign in
						</button>
					</div>
			
				</form>



	          <p class="w-100 text-center">&mdash; {{__('main.or_create')}} &mdash;</p>
	          <div class="social d-flex text-center">
	          	<a href="{{route('register')}}" class="px-2 py-2 mr-md-1 rounded"> {{__('main.register')}}</a>
	          </div>
		      </div>
				</div>
			</div>
		</div>
	</section>

	<script src="../../assets/js/jquery.min.js"></script>
  <script src="../../assets/js/popper.js"></script>
  <script src="../../assets/js/bootstrap.min.js"></script>
  <script src="../../assets/js/login.js"></script>

	</body>
</html>




