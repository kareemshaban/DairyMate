<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #F8F8F8 ; border-radius: 8px">
                <label class="modelTitle"> {{__('main.delete_title')}}</label>
                <i class='bx bxs-x-square text-danger modal-close' data-bs-dismiss="modal" style="font-size: 25px ; cursor: pointer"></i>

            </div>
            <div class="modal-body" id="smallBody">
                <div style="display: flex ; align-items: center">
                    <img src="{{ asset('assets/img/warning.png') }}" class="alertImage">
                    <label class="alertTitle">{{__('main.delete_alert')}}</label>
                </div>


                <br> <label  class="alertSubTitle" id="modal_table_bill"></label>
                <div class="row">
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-labeled btn-success btnConfirmDelete" >
                            <span class="btn-label" ><i class="bx bx-check"></i></span>{{__('main.confirm_btn')}}</button>
                    </div>
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-labeled btn-warning cancel-modal"  >
                            <span class="btn-label" ><i class="bx bx-x"></i></span>{{__('main.cancel_btn')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
