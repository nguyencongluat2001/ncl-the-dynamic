<table class="table table-striped table-bordered" id="table-data">
    <colgroup>
        <col width="5%">
        <col width="5%">
        <col width="35%">
        <col width="25%">
        <col width="20%">
        <col width="10%">
    </colgroup>
    <thead style="text-align: center;">
        <th><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></th>
        <th>STT</th>
        <th>Tên người dùng</th>
        <th>Tên đăng nhập</th>
        <th>Trạng thái</th>
        <th>#</th>
    </thead>
    <tbody>
        @if(isset($datas) && count($datas) > 0)
        @foreach ($datas as $key => $value)
            <tr>
                <td style="vertical-align: middle;" align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="{{$value->id}}"></td>
                <td style="vertical-align: middle;" align="center">{{$key + 1}}</td>
                <td style="vertical-align: middle;">{{$value['name']}}</td>
                <td style="vertical-align: middle;">{{$value['username']}}</td>
                <td style="vertical-align: middle;" align="center">{{ isset($value['status']) && $value['status'] == 1 ? 'Hoạt động' : 'Ngưng hoạt động' }}</td>
                <td style="vertical-align: middle;" align="center">
                    <button id="btn-view" type="button" class="btn btn-primary btn-sm shadow-sm"><i class="fas fa-eye"></i></button>
                </td>
            </tr>
        @endforeach
        @endif
    </tbody>
</table>