<div id="style-1" class="table-responsive pmd-card pmd-z-depth ">
    <table id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <thead>
            <tr style="background: black;">
              @foreach ($datas[0] as $key => $value)
                  <td align="center"><b style="color: #02ffb8;font-size: 15px;">{{$key}}</b></td>
              @endforeach
            </tr>
        </thead>
        <tbody>
          @php $i = 0; @endphp
          @foreach ($datas as $key => $data)
            <tr style="background: black;">
                @foreach ($datas[$i] as $key => $value)
                    <td  align="center"><span style="color: #ffd629;font-size:12px">{{$value}}</span></td>
                @endforeach
            </tr>
            @php $i++; @endphp
           @endforeach
        </tbody>
    </table>
</div>
<!-- <div class="pmd-card pmd-z-depth ">
    <textarea name="" id="myTextarea" cols="30" rows="10"></textarea>
</div>
<script>
    var data = <?php echo json_encode($datas) ?>;
    var myData = data;

    var textedJson = JSON.stringify(myData, undefined, 4);
    $('#myTextarea').text(textedJson);
</script> -->