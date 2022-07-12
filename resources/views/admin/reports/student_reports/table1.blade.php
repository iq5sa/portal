@extends('admin.layouts.app')
@section('styles')
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
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
  <small>جدول رقم 1 صباحي</small>
  <button onclick="exportTableToExcel('table1', 'جدول رقم 1 صباحي')">
تصدير اكسل
  </button>

</div>
<div class="col-md-12">

</div>
<table class="tg text-center" id="table1">
  <tr>
    <th class="tg-0pky text-center" rowspan="3">ت</th>
    <th class="tg-0pky text-center" rowspan="3">الكلية</th>
    <th class="tg-0pky text-center" rowspan="3">القسم</th>
    <th class="tg-0pky text-center" rowspan="3">الفرع</th>
    <th class="tg-0pky text-center" colspan="{{count($generals)*3+3}}">الطلبة المقبولون في الصف الاول</th>
    <th class="tg-0lax text-center" colspan="3">الطلبة المقبولون في الصف الثاني</th>
  </tr>
  <tr>
    <?php foreach ($generals as $key => $general): ?>
      <td class="tg-0pky text-center" colspan="3">{{$general->general}}</td>
    <?php endforeach; ?>

    <td class="tg-0lax text-center" colspan="3">المجموع</td>
    <td class="tg-0lax text-center" colspan="3">المجموع</td>
  </tr>

  <tr>
    <?php foreach ($generals as $key => $general): ?>
      <td class="tg-0pky text-center">ذكور</td>
      <td class="tg-0pky text-center">اناث</td>
      <td class="tg-0pky text-center">مج</td>
    <?php endforeach; ?>


    <td class="tg-0lax text-center">ذكور</td>
    <td class="tg-0lax text-center">اناث</td>
    <td class="tg-0lax text-center">مج</td>

    <td class="tg-0lax text-center">ذكور</td>
    <td class="tg-0lax text-center">اناث</td>
    <td class="tg-0lax text-center">مج</td>
  </tr>

<?php foreach ($arrgen1 as $key1 => $value): ?>
  <tr>
    <td class="tg-0pky text-center">{{$key1+1}}</td>
    <td class="tg-0pky text-center">{{$value[0]}}</td>
    <td class="tg-0pky text-center">{{$value[1]}}</td>
    <td class="tg-0pky text-center"></td>
    <?php foreach ($value[2] as $key1 => $value1): ?>
      <td class="tg-0pky text-center">{{$value1[1]}}</td>
      <td class="tg-0lax text-center">{{$value1[2]}}</td>
      <td class="tg-0lax text-center">{{$value1[3]}}</td>
    <?php endforeach; ?>


    <td class="tg-0pky text-center">{{$value[3]}}</td>
    <td class="tg-0lax text-center">{{$value[4]}}</td>
    <td class="tg-0lax text-center">{{$value[3]+$value[4]}}</td>

    <td class="tg-0lax text-center">{{$value[5]}}</td>
    <td class="tg-0lax text-center">{{$value[6]}}</td>
    <td class="tg-0lax text-center">{{$value[5]+$value[6]}}</td>
  </tr>
  <?php endforeach; ?>
</table>
<br>
<div class="col-md-12">
  <small>جدول رقم 1 مسائي</small>
<button onclick="exportTableToExcel('table2', 'جدول رقم 1 مسائي')">
تصدير اكسل
</button>
</div>
<div class="col-md-12">

</div>
<table class="tg text-center" id="table2">
  <tr>
    <th class="tg-0pky text-center" rowspan="3">ت</th>
    <th class="tg-0pky text-center" rowspan="3">الكلية</th>
    <th class="tg-0pky text-center" rowspan="3">القسم</th>
    <th class="tg-0pky text-center" rowspan="3">الفرع</th>
    <th class="tg-0pky text-center" colspan="{{count($generals)*3+3}}">الطلبة المقبولون في الصف الاول</th>
    <th class="tg-0lax text-center" colspan="3">الطلبة المقبولون في الصف الثاني</th>
  </tr>
  <tr>
    <?php foreach ($generals as $key => $general): ?>
      <td class="tg-0pky text-center" colspan="3">{{$general->general}}</td>
    <?php endforeach; ?>

    <td class="tg-0lax text-center" colspan="3">المجموع</td>
    <td class="tg-0lax text-center" colspan="3">المجموع</td>
  </tr>

  <tr>
    <?php foreach ($generals as $key => $general): ?>
      <td class="tg-0pky text-center">ذكور</td>
      <td class="tg-0pky text-center">اناث</td>
      <td class="tg-0pky text-center">مج</td>
    <?php endforeach; ?>


    <td class="tg-0lax text-center">ذكور</td>
    <td class="tg-0lax text-center">اناث</td>
    <td class="tg-0lax text-center">مج</td>

    <td class="tg-0lax text-center">ذكور</td>
    <td class="tg-0lax text-center">اناث</td>
    <td class="tg-0lax text-center">مج</td>
  </tr>

<?php foreach ($arrgen2 as $key1 => $value): ?>
  <tr>
    <td class="tg-0pky text-center">{{$key1+1}}</td>
    <td class="tg-0pky text-center">{{$value[0]}}</td>
    <td class="tg-0pky text-center">{{$value[1]}}</td>
    <td class="tg-0pky text-center"></td>
    <?php foreach ($value[2] as $key1 => $value1): ?>
      <td class="tg-0pky text-center">{{$value1[1]}}</td>
      <td class="tg-0lax text-center">{{$value1[2]}}</td>
      <td class="tg-0lax text-center">{{$value1[3]}}</td>
    <?php endforeach; ?>


    <td class="tg-0pky text-center">{{$value[3]}}</td>
    <td class="tg-0lax text-center">{{$value[4]}}</td>
    <td class="tg-0lax text-center">{{$value[3]+$value[4]}}</td>

    <td class="tg-0lax text-center">{{$value[5]}}</td>
    <td class="tg-0lax text-center">{{$value[6]}}</td>
    <td class="tg-0lax text-center">{{$value[5]+$value[6]}}</td>
  </tr>
  <?php endforeach; ?>
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
