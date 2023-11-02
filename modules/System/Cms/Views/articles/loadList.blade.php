<table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">
    <colgroup>
        <col width='3%' />
        <col width='10%' />
        <col width='45%' />
        <col width='15%' />
        <col width='15%' />
        <col width='12%' />
    </colgroup>
    <thead>
        <th align="center"><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></th>
        <th align="center">Ngày tạo</th>
        <th align="center">Tiêu đề</th>
        <th align="center">Chuyên mục</th>
        <th align="center">Tác giả</th>
        <th align="center">Tình trạng</th>
    </thead>
    <tbody id="data-list-articles">
        @if(isset($datas) && count($datas) > 0)
        @foreach($datas as $data)
        <tr>
            <td style="vertical-align: middle;" align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="{{$data->id}}"></td>
            <td style="vertical-align: middle;" align="center" onclick="{select_row(this);}">{{ isset($data->create_date) ? date('d/m/Y', strtotime($data->create_date)) : '' }}</td>
            <td style="vertical-align: middle;" onclick="{select_row(this);}">{{ $data->title }}</td>
            <td style="vertical-align: middle;" onclick="{select_row(this);}">{{ $data->categories->name ?? '' }}</td>
            <td style="vertical-align: middle;" onclick="{select_row(this);}">{{ $data->author }}</td>
            <td style="vertical-align: middle;" onclick="{select_row(this);}" align="center">{{ isset($data->status) && $data->status == 1 ? 'Hoạt động' : 'Ngưng hoạt động' }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
    <tfoot>
        @if(isset($datas) && count($datas) > 0)
        <tr><td colspan="10">{{ $datas->links('pagination.articles') }}</td></tr>
        @else
        <tr><td colspan="10" align="center">Không tìm thấy dữ liệu</td></tr>
        @endif
    </tfoot>
</table>