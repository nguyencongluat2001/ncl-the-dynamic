<form id="frmAddListType" role="form" action="" method="POST">
    @csrf
    <input type="hidden" name="listtype" value="{{ $listtype_id }}">
    <input type="hidden" name="idlist" value="{{ $idlist }}">
    <input type="hidden" name="oldorder" value="{{ $oldorder }}">

    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ Lang::get('System::Listtype.add') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! $strHTML !!}
            </div>
            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary btn-flat" type="button">
                    {{ Lang::get('System::Common.submit') }}
                </button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
                    {{ Lang::get('System::Common.close') }}
                </button>
            </div>
        </div>
    </div>
</form>
