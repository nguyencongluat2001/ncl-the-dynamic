<style>
    .unit-edit span {
        font-size: 19px;
    }
</style>
@php
use Modules\System\Dashboard\Blog\Models\BlogImagesModel;

@endphp
<div class="table-responsive pmd-card pmd-z-depth ">
    <table  id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <thead>
            <tr>
                <td align="center"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <td align="center"><b>STT</b></td>
                <td align="center"><b>Ngày thêm</b></td>
                <td align="center"><b>Tên bài viết</b></td>
                <td align="center"><b>Ảnh</b></td>
                <td align="center"><b>Loại</b></td>
                <td align="center"><b>Trạng thái</b></td>
                <td align="center"><b>#</b></td>
            </tr>
        </thead>
        <tbody>
        @foreach ($datas as $key => $data)
                <tr>
                    <td style="width:5% ;vertical-align: middle;" align="center"><input type="checkbox" name="chk_item_id"
                            value="{{ $data['id'] }}"></td>
                    <td style="width:5% ;vertical-align: middle;" align="center">{{ $key + 1 }}</td>
                    <td style="width:5% ;vertical-align: middle;" align="center">{{ $data['created_at']}}</td>
                    <td style="width:20% ;white-space: inherit;vertical-align: middle;">{{ $data->detailBlog->title }}</td>
                    <td style="width:20%;vertical-align: middle;" align="center"><img  src="{{url('/file-image-client/blogs/')}}/{{ !empty($data->imageBlog[0]->name_image)?$data->imageBlog[0]->name_image:'' }}" alt="Image" style="height: 150px;width: 150px;object-fit: cover;"></td>
                    <td style="width:5% ;vertical-align: middle;" align="center">{{($data['type_blog'])}}</td>
                    <td style="width:5% ;vertical-align: middle;" align="center">
                        <label class="custom-control custom-checkbox p-0 m-0 pointer " style="cursor: pointer;">
                            <input type="checkbox" hidden class="custom-control-input toggle-status" id="status_{{ $data['id'] }}" {{ $data->status == 1 ? 'checked' : '' }}>
                            <span class="custom-control-indicator p-0 m-0" onclick="JS_Blogs.changeStatus('{{ $data['id'] }}')"></span>
                        </label>    
                    </td>
                    <td style="width:5% ;vertical-align: middle;" align="center">
                         <button onclick="JS_Blogs.infoBlog('{{ $data['id'] }}')" class="btn btn-light" type="button">
                              <i style="color:#00740a" class="far fa-eye"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="width:100%" class="row">
    <tfoot>
        <tr>
            <td colspan="10">
                {{$datas->links('pagination.phantrang')}}
            </td>
        </tr>
    </tfoot>
    </div>
</div>
