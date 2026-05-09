<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> {{__('main.alert_title')}}</label>
                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>

            </div>
            <div class="modal-body" id="smallBody">
                <div style="display: flex ; align-items: center">
                    <img src="{{ asset('assets/img/warning.png') }}" class="alertImage">
                    <p class="alertTitle" id="msg">
                       {{__('main.logout_alert')}}
                    </p>
                </div>


                <br> <label  class="alertSubTitle" id="modal_table_bill"></label>
                <div class="row">

                    <div class="col-12 text-start">
                        <button type="button" class="btn btn-labeled btn-warning logout_btn"  >
                            <span class="btn-label" >{{__('main.okbtn')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
