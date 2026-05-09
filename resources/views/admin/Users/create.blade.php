<div class="modal fade" id="createAccountModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> </label>


                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>


            </div>
            <div class="modal-body" id="smallBody">

                <div class="container-fluid">

                    <form class="center" method="POST" action="{{ route('store-user') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin-bottom: 10px" id="roles">
                             <div class="col-12">
                                 <div class="form-group">
                                     <label> {{__('main.role')}} <span style="color:red ; font-size: 14px"> * </span> </label>
                                       <select id="role_id" name="role_id" class="form-control" >
                                           <option value="0"> {{__('main.choose')}} </option>
                                           @foreach($roles as $role)
                                               <option value="{{$role -> id}}">

                                                       {{$role -> name}}

                                               </option>

                                           @endforeach
                                       </select>
                                 </div>

                             </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.name') }}  </label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="{{ __('main.name') }}" autofocus  required/>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input  name="id" id="id" type="hidden"/>
                                    <input  name="supplier_id" id="supplier_id" type="hidden"/>
                                    <input  name="type" id="type" type="hidden"/>

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.email') }}</label>
                                    <input type="text" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="{{ __('main.email') }}" autofocus />
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.password') }}  </label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="{{ __('main.password') }}" autofocus  required/>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ __('main.rePassword') }}</label>
                                    <input type="password" name="password2" id="password2"
                                           class="form-control @error('password2') is-invalid @enderror"
                                           placeholder="{{ __('main.rePassword') }}" autofocus />
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
                                <button type="submit" class="btn btn-warning">{{ __('main.save_btn') }}</button>

                            </div>

                        </div>




                    </form>
                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                    <script>
                        function readURL(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();

                                reader.onload = function (e) {
                                    $('#flag-img').attr('src', e.target.result);

                                }
                                reader.readAsDataURL(input.files[0]);

                            }
                        }
                        $("#flag").change(function () {
                            readURL(this);
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
