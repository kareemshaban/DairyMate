<div class="modal fade" id="confirmtModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
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

                    </p>
                </div>


                <br> <label  class="alertSubTitle" id="modal_table_bill"></label>
                <div class="row">

                    <div class="col-6 ">
                        <button type="button" class="btn btn-labeled btn-warning submit-btn"  >
                            <span class="btn-label" >{{__('main.iAgreeBtn')}}</span></button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
