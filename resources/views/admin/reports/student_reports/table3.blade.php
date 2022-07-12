@extends('admin.layouts.app')
@section('styles')
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-0lax{text-align:left;vertical-align:top}
</style>
@endsection
@section('content')
<div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> تابع جدول رقم 3 </h1>
            <p> الطلبة العراقيين االموجودين بحسب العمر والجنس للعام الدراسي  2019-2020</p>
        </div>
    </div>
<div class="col-md-12">
  <small>جدول رقم 3 صباحي</small>
  <button onclick="exportTableToExcel('table1', 'جدول رقم 3 صباحي')">
تصدير اكسل
  </button>

</div>

<table class="tg" id="table1">
  <tr>
    <th class="tg-c3ow" rowspan="2">دراسة صباحي او مسائية</th>
    <th class="tg-baqh" rowspan="2">الجامعة</th>
    <th class="tg-0lax" rowspan="2">الكلية</th>
    <th class="tg-0lax" rowspan="2">العمر</th>
    <th class="tg-c3ow" colspan="3">مرحلة اولى</th>
    <th class="tg-c3ow" colspan="3">مرحلة ثانية</th>
    <th class="tg-baqh" colspan="3">مرحلة ثالثة</th>
    <th class="tg-baqh" colspan="3">مرحلة رابعة</th>
  </tr>
  <tr>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
  </tr>
  <?php foreach ($arrs as $key => $arr): ?>
    <?php foreach ($arr[0][0]["age"] as $key1 => $value1): ?>
      <tr>
        <td class="tg-c3ow">{{$arr[0][0]["study"]}}</td>
        <td class="tg-baqh">جامعة البيان</td>
        <td class="tg-0lax">{{$arr[0][0]["college"]}}</td>
        <td class="tg-0lax">{{$key1}}</td>
        <td class="tg-c3ow">{{$arr[0][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[1][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[0][0]["age"][$key1]+$arr[1][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[0][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[1][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[0][1]["age"][$key1]+$arr[1][1]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[0][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[1][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[0][2]["age"][$key1]+$arr[1][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[0][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[1][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[0][3]["age"][$key1]+$arr[1][3]["age"][$key1]}}</td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td class="tg-c3ow"></td>
      <td class="tg-baqh"></td>
      <td class="tg-0lax"></td>
      <td class="tg-0lax">الاجمالي</td>
      <td class="tg-c3ow">{{$arr[0][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[1][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[0][0]["total"]+$arr[1][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[0][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[1][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[0][1]["total"]+$arr[1][1]["total"]}}</td>
      <td class="tg-baqh">{{$arr[0][2]["total"]}}</td>
      <td class="tg-baqh">{{$arr[1][2]["total"]}}</td>
      <td class="tg-baqh">{{$arr[0][2]["total"]+$arr[1][2]["total"]}}</td>
      <td class="tg-baqh">{{$arr[0][3]["total"]}}</td>
      <td class="tg-baqh">{{$arr[1][3]["total"]}}</td>
      <td class="tg-baqh">{{$arr[0][3]["total"]+$arr[1][3]["total"]}}</td>
    </tr>
  <?php endforeach; ?>

</table>

<br>
<div class="col-md-12">
  <small>جدول رقم 3 مسائي</small>
<button onclick="exportTableToExcel('table2', 'جدول رقم 3 مسائي')">
تصدير اكسل
</button>
</div>
<table class="tg" id="table2">
  <tr>
    <th class="tg-c3ow" rowspan="2">دراسة صباحي او مسائية</th>
    <th class="tg-baqh" rowspan="2">الجامعة</th>
    <th class="tg-0lax" rowspan="2">الكلية</th>
    <th class="tg-0lax" rowspan="2">العمر</th>
    <th class="tg-c3ow" colspan="3">مرحلة اولى</th>
    <th class="tg-c3ow" colspan="3">مرحلة ثانية</th>
    <th class="tg-baqh" colspan="3">مرحلة ثالثة</th>
    <th class="tg-baqh" colspan="3">مرحلة رابعة</th>
  </tr>
  <tr>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
  </tr>
  <?php foreach ($arrs as $key => $arr): ?>
    <?php foreach ($arr[2][0]["age"] as $key1 => $value1): ?>
      <tr>
        <td class="tg-c3ow">{{$arr[2][0]["study"]}}</td>
        <td class="tg-baqh">جامعة البيان</td>
        <td class="tg-0lax">{{$arr[2][0]["college"]}}</td>
        <td class="tg-0lax">{{$key1}}</td>
        <td class="tg-c3ow">{{$arr[2][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[3][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[2][0]["age"][$key1]+$arr[3][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[2][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[3][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arr[2][1]["age"][$key1]+$arr[3][1]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[2][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[3][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[2][2]["age"][$key1]+$arr[3][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[2][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[3][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arr[2][3]["age"][$key1]+$arr[3][3]["age"][$key1]}}</td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td class="tg-c3ow"></td>
      <td class="tg-baqh"></td>
      <td class="tg-0lax"></td>
      <td class="tg-0lax">الاجمالي</td>
      <td class="tg-c3ow">{{$arr[2][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[3][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[2][0]["total"]+$arr[3][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[2][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[3][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arr[2][1]["total"]+$arr[3][1]["total"]}}</td>
      <td class="tg-baqh">{{$arr[2][2]["total"]}}</td>
      <td class="tg-baqh">{{$arr[3][2]["total"]}}</td>
      <td class="tg-baqh">{{$arr[2][2]["total"]+$arr[3][2]["total"]}}</td>
      <td class="tg-baqh">{{$arr[2][3]["total"]}}</td>
      <td class="tg-baqh">{{$arr[3][3]["total"]}}</td>
      <td class="tg-baqh">{{$arr[2][3]["total"]+$arr[3][3]["total"]}}</td>
    </tr>
  <?php endforeach; ?>

</table>

<br>
<div class="col-md-12">
  <small>جدول رقم 3 صباحي كلي</small>
  <button onclick="exportTableToExcel('table3', 'جدول رقم 3 صباحي كلي')">
تصدير اكسل
  </button>
</div>
<table class="tg" id="table3">
  <tr>
    <th class="tg-c3ow" rowspan="2">دراسة صباحي او مسائية</th>
    <th class="tg-baqh" rowspan="2">الجامعة</th>
    <th class="tg-0lax" rowspan="2">الكلية</th>
    <th class="tg-0lax" rowspan="2">العمر</th>
    <th class="tg-c3ow" colspan="3">مرحلة اولى</th>
    <th class="tg-c3ow" colspan="3">مرحلة ثانية</th>
    <th class="tg-baqh" colspan="3">مرحلة ثالثة</th>
    <th class="tg-baqh" colspan="3">مرحلة رابعة</th>
  </tr>
  <tr>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
  </tr>
    <?php foreach ($arrs1[5][0][0]["age"] as $key1 => $value1): ?>
      <tr>
        <td class="tg-c3ow">{{$arrs1[5][0][0]["study"]}}</td>
        <td class="tg-baqh">جامعة البيان</td>
        <td class="tg-0lax"></td>
        <td class="tg-0lax">{{$key1}}</td>
        <td class="tg-c3ow">{{$arrs1[5][0][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][1][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][0][0]["age"][$key1]+$arrs1[5][1][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][0][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][1][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][0][1]["age"][$key1]+$arrs1[5][1][1]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][0][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][1][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][0][2]["age"][$key1]+$arrs1[5][1][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][0][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][1][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][0][3]["age"][$key1]+$arrs1[5][1][3]["age"][$key1]}}</td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td class="tg-c3ow"></td>
      <td class="tg-baqh"></td>
      <td class="tg-0lax"></td>
      <td class="tg-0lax">الاجمالي</td>
      <td class="tg-c3ow">{{$arrs1[5][0][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][1][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][0][0]["total"]+$arrs1[5][1][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][0][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][1][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][0][1]["total"]+$arrs1[5][1][1]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][0][2]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][1][2]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][0][2]["total"]+$arrs1[5][1][2]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][0][3]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][1][3]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][0][3]["total"]+$arrs1[5][1][3]["total"]}}</td>
    </tr>

</table>

<br>
<div class="col-md-12">
  <small>جدول رقم 3 كلي مسائي</small>
<button onclick="exportTableToExcel('table4', 'جدول رقم 3 كلي مسائي')">
تصدير اكسل
</button>
</div>
<table class="tg" id="table4">
  <tr>
    <th class="tg-c3ow" rowspan="2">دراسة صباحي او مسائية</th>
    <th class="tg-baqh" rowspan="2">الجامعة</th>
    <th class="tg-0lax" rowspan="2">الكلية</th>
    <th class="tg-0lax" rowspan="2">العمر</th>
    <th class="tg-c3ow" colspan="3">مرحلة اولى</th>
    <th class="tg-c3ow" colspan="3">مرحلة ثانية</th>
    <th class="tg-baqh" colspan="3">مرحلة ثالثة</th>
    <th class="tg-baqh" colspan="3">مرحلة رابعة</th>
  </tr>
  <tr>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-c3ow">ذكور</td>
    <td class="tg-c3ow">اناث</td>
    <td class="tg-c3ow">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
    <td class="tg-baqh">ذكور</td>
    <td class="tg-baqh">اناث</td>
    <td class="tg-baqh">مجموع</td>
  </tr>
    <?php foreach ($arrs1[5][2][0]["age"] as $key1 => $value1): ?>
      <tr>
        <td class="tg-c3ow">{{$arrs1[5][2][0]["study"]}}</td>
        <td class="tg-baqh">جامعة البيان</td>
        <td class="tg-0lax"></td>
        <td class="tg-0lax">{{$key1}}</td>
        <td class="tg-c3ow">{{$arrs1[5][2][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][3][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][2][0]["age"][$key1]+$arrs1[5][3][0]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][2][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][3][1]["age"][$key1]}}</td>
        <td class="tg-c3ow">{{$arrs1[5][2][1]["age"][$key1]+$arrs1[5][3][1]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][2][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][3][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][2][2]["age"][$key1]+$arrs1[5][3][2]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][2][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][3][3]["age"][$key1]}}</td>
        <td class="tg-baqh">{{$arrs1[5][2][3]["age"][$key1]+$arrs1[5][3][3]["age"][$key1]}}</td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td class="tg-c3ow"></td>
      <td class="tg-baqh"></td>
      <td class="tg-0lax"></td>
      <td class="tg-0lax">الاجمالي</td>
      <td class="tg-c3ow">{{$arrs1[5][2][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][3][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][2][0]["total"]+$arrs1[5][3][0]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][2][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][3][1]["total"]}}</td>
      <td class="tg-c3ow">{{$arrs1[5][2][1]["total"]+$arrs1[5][3][1]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][2][2]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][3][2]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][2][2]["total"]+$arrs1[5][3][2]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][2][3]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][3][3]["total"]}}</td>
      <td class="tg-baqh">{{$arrs1[5][2][3]["total"]+$arrs1[5][3][3]["total"]}}</td>
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
