@extends('admin.layouts.app')
@section('styles')
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
.tg .tg-0lax{text-align:left;vertical-align:top}
</style>
@endsection
@section('content')
<div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> جدول رقم 1 المقبولين العراقيين </h1>
            <p> الطلبة العراقيون المقبولون (المسجلون والمباشرون فعلاً) في الدراسات الاولية موزعين حسب القسم والفرع وفرع
                التخرج من الثانوية والجنس والعام الدراسي</p>
        </div>
    </div>
<div class="col-md-12">
  <small>جدول رقم 2 صباحي</small>
  <button onclick="exportTableToExcel('table1', 'جدول رقم 2 صباحي')">
تصدير اكسل
  </button>
  </div>
<br>
  <table class="tg" id="table1">
    <tr>
      <th class="tg-0pky" rowspan="2">محافظة السكن</th>
      <th class="tg-0pky" colspan="3">الطلبة المقبولين مرحلة اولى</th>
      <th class="tg-0pky" colspan="3">الطلبة المقبولين مرحلة ثانية</th>
    </tr>
    <tr>
      <td class="tg-0pky">ذكور</td>
      <td class="tg-0pky">اناث</td>
      <td class="tg-0pky">مجموع</td>
      <td class="tg-0pky">ذكور</td>
      <td class="tg-0pky">اناث</td>
      <td class="tg-0pky">مجموع</td>
    </tr>
    <?php foreach ($arr1[18][4] as $key => $value): ?>
      <tr>
        <td class="tg-0lax">{{$value[0]}}</td>
        <td class="tg-0lax">{{$value[1]}}</td>
        <td class="tg-0lax">{{$value[2]}}</td>
        <td class="tg-0lax">{{$value[1]+$value[2]}}</td>
        <td class="tg-0lax">{{$value[3]}}</td>
        <td class="tg-0lax">{{$value[4]}}</td>
        <td class="tg-0lax">{{$value[3]+$value[4]}}</td>
      </tr>
    <?php endforeach; ?>

    <tr>
      <td class="tg-0lax">الاجمالي</td>
      <td class="tg-0lax">{{$arr1[18][0]}}</td>
      <td class="tg-0lax">{{$arr1[18][1]}}</td>
      <td class="tg-0lax">{{$arr1[18][0]+$arr1[18][1]}}</td>
      <td class="tg-0lax">{{$arr1[18][2]}}</td>
      <td class="tg-0lax">{{$arr1[18][3]}}</td>
      <td class="tg-0lax">{{$arr1[18][2]+$arr1[18][3]}}</td>
    </tr>
  </table>




<br>
<div class="col-md-12">
  <small>جدول رقم 2 مسائي</small>
<button onclick="exportTableToExcel('table2', 'جدول رقم 2 مسائي')">
تصدير اكسل
</button>
</div>
<br>
<table class="tg" id="table2">
  <tr>
    <th class="tg-0pky" rowspan="2">محافظة السكن</th>
    <th class="tg-0pky" colspan="3">الطلبة المقبولين مرحلة اولى</th>
    <th class="tg-0pky" colspan="3">الطلبة المقبولين مرحلة ثانية</th>
  </tr>
  <tr>
    <td class="tg-0pky">ذكور</td>
    <td class="tg-0pky">اناث</td>
    <td class="tg-0pky">مجموع</td>
    <td class="tg-0pky">ذكور</td>
    <td class="tg-0pky">اناث</td>
    <td class="tg-0pky">مجموع</td>
  </tr>
  <?php foreach ($arr2[18][4] as $key => $value): ?>
    <tr>
      <td class="tg-0lax">{{$value[0]}}</td>
      <td class="tg-0lax">{{$value[1]}}</td>
      <td class="tg-0lax">{{$value[2]}}</td>
      <td class="tg-0lax">{{$value[1]+$value[2]}}</td>
      <td class="tg-0lax">{{$value[3]}}</td>
      <td class="tg-0lax">{{$value[4]}}</td>
      <td class="tg-0lax">{{$value[3]+$value[4]}}</td>
    </tr>
  <?php endforeach; ?>

  <tr>
    <td class="tg-0lax">الاجمالي</td>
    <td class="tg-0lax">{{$arr2[18][0]}}</td>
    <td class="tg-0lax">{{$arr2[18][1]}}</td>
    <td class="tg-0lax">{{$arr2[18][0]+$arr2[18][1]}}</td>
    <td class="tg-0lax">{{$arr2[18][2]}}</td>
    <td class="tg-0lax">{{$arr2[18][3]}}</td>
    <td class="tg-0lax">{{$arr2[18][2]+$arr2[18][3]}}</td>
  </tr>
</table>

@endsection
@section('scripts')
<script type="text/javascript">
function exportTableToExcel(tableID, filename = ''){

    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    console.log(tableSelect);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
    }
}

</script>
@endsection
