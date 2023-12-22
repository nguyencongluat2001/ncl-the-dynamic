<style>
    .unit-edit span {
        font-size: 19px;
    }
</style>
{{-- @php
use Modules\System\Recordtype\Helpers\WorkflowHelper;
@endphp --}}
<div class="table-responsive pmd-card pmd-z-depth ">
    <table  id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <thead>
            <tr>
                <td align="center"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <td align="center"><b>STT</b></td>
                <td align="center"><b>Thông tin người dùng</b></td>
                <td align="center"><b>Ảnh đại diện</b></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $key => $data)
                <tr>
                    <td style="width:5%;padding-top: 75px" align="center"><input type="checkbox" name="chk_item_id"
                            value="{{ $data['id'] }}"></td>
                    <td style="width:5%;padding-top: 75px" align="center">{{ $key + 1 }}
                    <td style="width:45%;height:150px;padding-left:30px" >
                       <div>
                           <div>Tên người dùng : {{ $data['name'] }}</div>
                           <div>Số điện thoại : {{ $data['phone'] }}</div>
                           <div>Địa chỉ Email : {{ $data['email'] }}</div>
                           <div>Địa chỉ : {{ $data['address'] }}</div>
                           <div>Ngày sinh : {{ $data['dateBirth'] }}</div>
                           <div>Quyền quản trị : <span class="animate-charcter">Quản trị hệ thống</span></div>
                       </div>
                        
                    </td>
                    <td style="width:45%;" align="center"><img  src="{{url('/file-image/avatar/')}}/{{$data['avatar']}}" alt="Image" style="height: 150px;width: 150px;object-fit: cover;"></td>
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
