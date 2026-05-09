<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> {{__('main.reset_password')}} </label>


                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>


            </div>
            <div class="modal-body" id="smallBody">

                <div class="container-fluid">

                    <form class="center" method="POST" action="{{ route('reset-password') }}"
                          enctype="multipart/form-data" id="myForm">
                        @csrf
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.new_password') }}  </label>
                                    <input type="password" name="new_password" id="new_password"
                                           class="form-control @error('new_password') is-invalid @enderror"
                                           placeholder="{{ __('main.new_password') }}" autofocus  required/>
                                    @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input  name="id" id="id" type="hidden"/>

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.re_enter_password') }}</label>
                                    <input type="password" name="password2" id="password2"
                                           class="form-control @error('password2') is-invalid @enderror"
                                           placeholder="{{ __('main.re_enter_password') }}" autofocus />
                                    @error('password2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>



                        <div class="row" style="margin-top: 40px">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-warning" onclick="valdiateForm()">{{ __('main.save_btn') }}</button>

                            </div>

                        </div>




                    </form>
                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                    <script>
                        function  valdiateForm(){
                            var pass1 = $('#new_password').val().toString();
                            var pass2 = $('#password2').val().toString();
                            if(pass1 == pass2){
                                document.getElementById('myForm').submit();
                            } else {
                                var translatedText = "{{ __('main.password_missmatch') }}";
                                alert(translatedText);
                            }
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
