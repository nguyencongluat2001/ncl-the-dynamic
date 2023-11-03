
<form id="frmAddArticles" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{ isset($datas['id']) ? $datas['id'] : ''}}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT TIN BÀI</label>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Chuyên mục</label></div>
                <div class="col-md-10 jstree jstree-1 jstree-default jstree-checkbox-selection" id="TreeCategories">
                    {!!$shtmlTree!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Ngày đăng</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md datepicker" type="text" id="create_date" name="create_date" value="{{ isset($datas['create_date']) ? date('d/m/Y', strtotime($datas['create_date'])) : date('d/m/Y') }}">
                </div>
                <div class="col-md-2"><label class="control-label required">Tác giả</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="author" name="author" value="{{ $datas['author'] ?? ''}}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Nguồn tin</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="source" name="source"  value="{{ $datas['source'] ?? '' }}"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Tiêu đề</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="title" name="title"  value="{{ $datas['title'] ?? '' }}"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Trích dẫn</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="subject" name="subject" value="{{ $datas['subject'] ?? ''}}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Đường dẫn</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="slug" name="slug" value="{{ $datas['slug'] ?? ''}}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Ảnh bài viết</label></div>
                <div class="col-md-2">
                    <label for="choose_img" class="btn btn-default mb-0 ms-0">Chọn ảnh</label>
                    <input class="form-control input-md" hidden style="width: 95px;"  type="file" id="choose_img" onchange="JS_Articles.PreviewFeatureImage()" name="choose_img" value="">
                </div>
                <div class="col-md-1" style="display:flex; align-items: center;">
                    <span> Hoặc </span>
                </div>
                <div class="col-md-7">
                    <input class="form-control input-md" type="text" id="feature_img" name="feature_img" value="{{ $datas['feature_img'] ?? ''}}" placeholder="Link ảnh bài viết">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8" id="PreviewFeatureImage">
                    <div class='col-md-3 text-center' id='file_image'>
                        @if (isset($feature_img_base) && $feature_img_base != null && $feature_img_base != '')
                            @if (strpos('Ncl' . $feature_img_base, 'http') > 0)
                                <img class='img-responsive' src="{{ $feature_img_base}}" width="200px">
                                <a href="javascript:;" onclick="JS_Articles.deletefile_FeatureImage('xoa')"><i class="fas fa-trash"></i> Xóa</a>
                            @else
                                <img class='img-responsive' src="{{ $datas->feature_img}}" width="200px">
                                <a href="javascript:;" onclick="JS_Articles.deletefile_FeatureImage('xoa')"><i class="fas fa-trash"></i> Xóa</a>
                            @endif
                        @endif
                        

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label ">Chú thích ảnh bài biết</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="note_feature_img" name="note_feature_img" value="{{ $datas['note_feature_img'] ?? '' }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"><label class="control-label required">Nội dung bài viết</label></div>
                <div class="col-md-10">
                    <textarea class="form-control input-md" type="text" id="content" name="content"  xml_data="false" column_name="content">{{ $datas['content'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Tiêu đề SEO</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="title_SEO" name="title_SEO" value="{{ $datas['title_SEO'] ?? '' }}" xml_data="false" column_name="title_SEO">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Miêu tả SEO</label></div>
                <div class="col-md-10">
                    <textarea class="form-control"  id="description_SEO" name="description_SEO">{{ $datas['description_SEO'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Tùy chọn</label></div>
                <div class="col-md-2">
                    <label><input type="checkbox" {{ isset($checked) || isset($datas) && $datas->status == '1' ? 'checked' : '' }} id="status" name="status"> Hoạt động</label>
                </div>
                <div class="col-md-2">
                    <label><input type="checkbox"  {{ isset($is_comment) || isset($datas) && $datas->is_comment == '1' ? 'checked' : '' }}  id="is_comment" name="is_comment"> Hiển thị bình luận</label>
                </div>
                <div class="col-md-2">
                    <label><input type="checkbox" {{ isset($is_hide_relate_articles) || isset($datas) && $datas->is_hide_relate_articles == '1' ? 'checked' : '' }} id="is_hide_relate_articles" name="is_hide_relate_articles"> Ẩn tin liên quan</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Loại tin bài</label></div>
                @if(isset($arrLoaiTinBai) && count($arrLoaiTinBai) > 0)
                @foreach ($arrLoaiTinBai as $key => $value)
                    <div class="col-md-2">
                        <label><input type="radio"  
                                {{ (isset($datas->articles_type) && $datas->articles_type == $value['code'])
                                    || (!isset($datas->articles_type) && $value['code'] == 'TIN_HIEN_THI_CO_DINH') ? 'checked' : '' }} 
                                value="{{ $value['code'] }}" name="articles_type"> {{ $value['name'] }}</label>
                    </div>
                @endforeach
                @endif
            </div>

            <div class="row" id="trangthaitinbai">
                <div class="col-md-2"><label class="control-label required">Trạng thái tin bài</label></div>
                @if(isset($arrTrangThaiTinBai) && count($arrTrangThaiTinBai) > 0)
                @foreach ($arrTrangThaiTinBai as $key => $value)
                    <div class="col-md-2" id="{{ $value['code']}}">
                        <label><input type="radio" 
                            {{ (isset($datas->status_articles) && $datas->status_articles == $value['code'])
                                || (!isset($datas->status_articles) && $value['code'] == 'CHO_DUYET')
                                ? 'checked'
                                : '' }}
                            id="{{ $value['code'] . '_1' }}" value="{{ $value['code'] }}"  name="status_articles"> <span>{{ $value['name'] }}</span>
                        </label>
                    </div>
                @endforeach
                @endif
            </div>

            <div class="row">
                <div class="col-md-2"><label class="control-label required">Thứ tự hiển thị</label></div>
                <div class="col-md-1">
                    <input class="form-control input-md" type="text" id="order" name="order" value="{{ isset($datas['order']) ? $datas['order'] : '' }}" xml_data="false" column_name="order">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">File đính kèm</label></div>
                <div class="col-md-10">
                    <label for="images" class="btn btn-default mb-0 ms-0">Chọn File</label>
                    <input type="file" hidden class="form-control" id="images" style="width: 95px;" name="images[]" onchange="JS_Articles.preview_images();" multiple/>
                </div>
            </div>
            <div class="row" id="image_preview">
                <!-- 2023_10_24_1709081883!~!370941f39057a9e178adf0975330b6e5.jpg -->
                @if(isset($arrAttachments))
                @foreach($arrAttachments as $key => $value)
                @php $arrFileName = explode('!~!', $value['file_name']); @endphp 
                    <div data-id="{{ isset($datas['id']) ? $datas['id'] : '' }}" id="file-preview-{{$key}}" class='col-md-3'>
                        <i class='fa fa-file' aria-hidden='true'></i> 
                        <a target='_blank' href="{{ $value['file_url'] }}"> {{ $arrFileName[1] ?? ($value['file_name'] ?? '') }}</a> 
                        <i class='fas fa-trash' onclick="JS_Articles.deletefileInSerVer('{{ $value }}', '{{ $key }}')"></i>
                    </div>
                @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center">

                    <button onclick="JS_Articles.update($('form#frmAddArticles'))" class="btn btn-primary " type="button">{{ Lang::get('System::Common.submit') }}</button>

                    <button class="btn btn-danger " id="close_modal">{{ Lang::get('System::Common.close') }}</button>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">
    $('.datepicker').datepicker({
        dateFormat: 'dd/mm/yy',
    });

    <?php
    $filebrowserBrowseUrl = url('filemanager/dialog.php?type=2&editor=ckeditor&fldr=');
    $filebrowserUploadUrl = url('filemanager/dialog.php?type=2&editor=ckeditor&fldr=');
    $filebrowserImageBrowseUrl = url('filemanager/dialog.php?type=1&editor=ckeditor&fldr=');
    ?>
    var states = <?php echo json_encode($arrAuthorReturn); ?>;
    $('#author').autocomplete({
        source: [states]
    });
    var filebrowserBrowseUrl = '{{ $filebrowserBrowseUrl }}';
    var filebrowserUploadUrl = '{{ $filebrowserUploadUrl }}';
    var filebrowserImageBrowseUrl = '{{ $filebrowserImageBrowseUrl }}';
    CKEDITOR.replace('content', {
        filebrowserBrowseUrl: filebrowserBrowseUrl,
        filebrowserUploadUrl: filebrowserUploadUrl,
        filebrowserImageBrowseUrl: filebrowserImageBrowseUrl,
        filebrowserUploadMethod: 'form',
    });
    $('#title').change(function () {
        var date = new Date();
        var value = $(this).val();
        value = convertTitleToUrl(value);
        $('#slug').val(value + '-' + date.getTime() + '.html');
    });
    function convertTitleToUrl(str){
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        // Some system encode vietnamese combining accent as individual utf-8 characters
        str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, ""); // Huyền sắc hỏi ngã nặng 
        str = str.replace(/\u02C6|\u0306|\u031B/g, ""); // Â, Ê, Ă, Ơ, Ư
        str = str.replace(/ /g, "-");
        str = str.replace(/[`~!@#$%^&*()_+=\[\]{};:'"\|<>,.\/\\?]/g, "");
        str = str.replaceAll(/--/g, "-");
        if(str.indexOf('--') != -1){
            return convertTitleToUrl(str);
        }else{
            return str;
        }
    }
    $("#TreeCategories").jstree({
        "core": {"expand_selected_onload": false, multiple: false},
        "plugins": ["themes", "html_data", "checkbox", "search"],
        "search": {
            "case_sensitive": false,
            "show_only_matches": true
        }
    });
    var role = "{{ $role }}";

    if (role == 'USER') {

        $('#TreeCategories').on('changed.jstree', function (e, data) {

            var category = data.selected[0];
            var id = $('#id').val();
            var trangthaitinbai = "{{ $trangthaitinbai }}";
            JS_Articles.check_duyet(category, trangthaitinbai, id);
        });
    }

</script>
<style type="text/css">
    #frmAddArticles .row{
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
    section{
        margin-bottom: 20px;
    }
</style>
<script>
    $("#feature_img").on('change', function(){
        $("#choose_img").val('');
        $('#PreviewFeatureImage').html("<div class='col-md-3 text-center' id='file_image'><img class='img-responsive' width='200px' src='" + $("#feature_img").val() + "'><a href='javascript:;' style='margin-top:10px' onclick=\"JS_Articles.deletefile_FeatureImage(\'\')\"><i class='fas fa-trash'></i> Xóa</a></div>");
    });
</script>
