<form id="frmAprovalArticles" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="PK_ARTICLES" id="PK_ARTICLES" value="<?= isset($datas->id) ? $datas->id : '' ?>">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">XEM BÀI VIẾT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh;overflow-y: scroll;">
                <div><i class="fas fa-calendar"></i> {{$datas->create_date ?? ''}}</div>
                <div class="mt-3"><strong>{{ $datas->title }}</strong></div>
                <div class="mt-3" align="center"><img src="{{ $datas->feature_img ?? '' }}" alt="{{ $datas->note_feature_img ?? '' }}"></div>
                <div><i class="text-primary">{{ $datas->note_feature_img }}</i></div>
                <div>{!! $datas->content ?? '' !!}</div>
                <div>
                    @if(isset($files))
                    @foreach($files as $key => $file)
                    <div><a target="_blank" href="{{ $file['url'] }}"><i class="fas fa-link"></i> {{ $file['file_name'] }}</a></div>
                    @endforeach
                    @endif
                </div>
                <hr style="margin-bottom: 10px !important; border-top: 1px solid #00000033 !important;">
                <div align="right">
                    <p class="mb-0" style="font-size:12px"><b>Tác giả bài viết: </b> {{$datas->author}}</p>
                    <p class="mb-0" style="font-size:12px"><b>Nguồn tin:</b> {{$datas->source}}</p>
                </div>
                <div id="fb-root"></div>
            </div>
            <div class="modal-footer">
                @if($action=='duyet')
                <button onclick="JS_Articles.update_approval($('form#frmAprovalArticles'))" class="btn btn-primary " type="button">Duyệt</button>
                <button onclick="JS_Articles.refuse($('form#frmAprovalArticles'))" class="btn btn-danger " type="button">Từ chối</button>
                @endif
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close" id="close_modal"><?= Lang::get('System::Common.close') ?></button>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">

</script>
<style type="text/css">
    #frmAddArticles .row {
        margin-top: 10px;
    }

    h1 {
        text-align: center;
        font-size: 32px;
        font-weight: bold;
        color: #b53310;
        text-transform: uppercase;
        margin-top: 0px;
        margin-bottom: 0px;
    }

    img {
        /* width: 80%; */
        text-align: center;
    }

    section {
        margin-bottom: 20px;
    }
</style>