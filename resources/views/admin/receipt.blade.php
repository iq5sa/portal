<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title}}</title>

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
          href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/font.css')}}">


    <style>

        body{
            font-family: tahoma !important;
        }

        input {
            border: 0;
            font-weight: bold;
            color: black;
            font-size: 18px;
        }

        .receipt_name {
            text-align: center;
            margin: 10px;
            background: #f29c2b;
            width: 200px;
            padding: 7px;
            border-radius: 22px;
        }

        .top-area, .middle-area {
            position: relative;
            height: 123px;
        }

        .top-item {
            float: right;
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);

        }

        .middle-area {
            top: 0;
        }

        #receipt {
            width: 1000px;
            margin: auto;
            font-size: 18px;
            background-color: white
        }

        .albayan-logo-font {
            width: 150px;
        }

        .logo-main {
            width: 117px;
        }

        .albayan-top-text {
            line-height: 6em;
            text-align: center

        }

        .inputs-items {
            margin: 15px;

        }

        .nameOfPayed {
            width: 80%;
            border-bottom: 2px dashed;
            font-size: 25px;
            float: right;
            text-align: center;
            margin-right: 5px;
            font-weight: bold;
            color: black;
        }

        .accounting-guide-table {
            border: 1px solid;
            height: 300px;
            margin: 5px;
        }

        #searchResult {
            width: 400px;
            height: 123px;
            background-color: #a0a0a0;
            position: absolute;
            top: 33px;
            right: 30%;
            display: none;
            padding: 5px;
            text-align: right;
        }

        #searchResult a {
            color: black;
            font-size: 18px;
        }

        .receipt-footer {
            width: 100%;
            padding: 10px;
            font-size: 10px;
            background-color: #f29c2b !important;

        }

        @media print {
            .receipt-footer {
                background-color: #f29c2b !important;
                -webkit-print-color-adjust: exact;

            }

            .receipt_name {
                background-color: #f29c2b !important;
                -webkit-print-color-adjust: exact;
            }
        }

        .small-dashed-value {
            border-bottom: 2px dashed;
            height: 26px;
            font-size: 20px;
            float: right;
            width: 130px;
            text-align: center;
            color: black;
            font-weight: bold;
        }

        .small-item-title {
            font-size: 18px;
            float: right;
            width: 12%;
            text-align: center;
            font-weight: bold;
            color: black;

        }

        #amountNumbers {
            border: 0;
            width: 90%;
            height: 244px;
            resize: none;
            padding: 10px;
            font-size: 20px;
            padding-top: 30px;
            line-height: 2em;
            text-align: right !important;
            margin: 10px;
            font-weight: bold;
        }

        input:disabled{
            background-color: white;
            font-weight: bold;
            color: black;
        }
    </style>
</head>
<body class="app sidebar-mini rtl">
<br>

<div class="container text-center mt-5 mb-5">
    <button class="btn btn-primary btn-lg" onclick="printAndSave()"> حفظ و طباعة</button>
    <button class="btn btn-info btn-lg" onclick="btnPrint()">طباعة</button>
    <button class="btn btn-success btn-lg" onclick="receiptNew()">جديد</button>

</div>

<div>






    <div class="" id="receipt" style="font-family: auto;">
        <form method="post" id="receipt_form">


            <div class="top-area">
                <div class="top-item" style="right: 14px">
                    <img src="{{asset("images/albayan-logo-font.png")}}" class="albayan-logo-font"/>
                </div>

                <div class="top-item" style="right: 42%">
                    <img src="{{asset("images/logo-main.png")}}" class="logo-main"/>
                </div>

                <div class="top-item" style="left: 14px;">
                    <h4 class="albayan-top-text">ALBAYAN UNIVERSITY</h4>
                </div>
                <div class="clearfix"></div>

            </div>

            <input type="hidden" name="student_id" id="student_id">
            <input type="hidden" name="payment_amount" id="payment_amount">


            <div class="middle-area">

                <div class="top-item" style="right: 14px">
                    <h4 style="padding: 10px;text-align: center;font-size: 14px;"><span>التاريخ : </span>
                        <input type="text" name="payment_date" value="@if(isset($payment_date)) {{$payment_date}} @else {{date('Y/m/d')}} @endif">

                    </h4>
                </div>

                <div class="top-item" style="right: 37%;">
                    <h5 class="receipt_name" style="text-align: center"> وصل قبض</h5>
                </div>


                <div class="top-item" style="left: 14px;">
                    <h4 style="text-align: center;padding: 10px;font-size: 14px;"><span>الرقم : </span>
                        <input type="number" id="payment_id" value="@if(isset($payment_id)){{$payment_id}}@endif"/>
                    </h4>
                </div>
            </div>

            <div class="inputs-items" style="position: relative;">

                <div class="small-item-title">
                    <span style="float: right">استلمت من</span>
                    <span>  :  </span>
                </div>

                <input type="text" class="nameOfPayed" name="student_name" id="studentName"
                       value="@if(isset($student_name)){{$student_name}} @endif"/>

                <div id="searchResult">
                    <i class="fa fa-close" style="float: left" id="btnClose"></i>
                    <div id="searchResultBar"></div>
                </div>


            </div>

            <div class="clearfix"></div>

            <br/>
            <div class="inputs-items">

                <div class="small-item-title">
                    <span style="float: right">مبلغ وقدرة  </span>
                    <span> : </span>
                </div>

                <div class="nameOfPayed">
                    <span>  فقط </span>
                    <span class="amount-text" id="amount_text"></span>
                    <span>دينار عراقي لا غيرها</span>
                </div>


                <div class="clearfix"></div>

            </div>


            <div class="clearfix"></div>

            <br/>
            <div class="inputs-items">

                <div class="small-item-title" style="width: 14%">
                    <p> نقدا \ رقم الصك : </p>
                </div>

                <input type="number" name="cheque_number" class="small-dashed-value"/>

                <div class="small-item-title">
                    <p> المصرف : </p>
                </div>
                <input class="small-dashed-value" name="bank_name"/>
                <div class="small-item-title">
                    <p> التاريخ : </p>
                </div>

                <input name="cheque_date" class="payment-date small-dashed-value"
                    value="@if(isset($payment_date)) {{$payment_date}} @else {{date('Y/m/d')}} @endif" />

            </div>

            <div class="clearfix"></div>

            <br/>


            <div class="inputs-items">

                <div class="small-item-title" style="text-align: right;width: 8%">
                    <p> وذلك عن : </p>
                </div>

                <input type="text" disabled class="small-dashed-value"
                       value="@if(isset($payment_amount) && $payment_amount =="10000"){{"اصدار هوية"}}@elseif($payment_amount !=="10000"){{"تسديد اقساط"}}@endif">


                <div class="small-item-title">
                    <p> الكلية : </p>
                </div>

                <input class="small-dashed-value" name="collage_name" type="text" id="college_name"
                       @if(isset($collage_name)) disabled @endif/>

                <div class="small-item-title">
                    <p> المرحلة : </p>
                </div>

                <input type="number" name="collage_level" class="small-dashed-value" contenteditable="true" id="level" value="{{$level}}"/>

                <div class="small-item-title">
                    <p> نوع الدراسة : </p>
                </div>

                <input  name="shift" class="small-dashed-value" contenteditable="true" id="shift" value="{{$type}}">

            </div>

            <div class="clearfix"></div>

            <br/>



            <div class="clearfix"></div>

            <div class="inputs-items">

                <div class="" style="font-size: 14px;text-align: left;float: right;font-weight: bold;">
                    <p> الملاحضات : </p>
                </div>

                <div class="nameOfPayed" contenteditable="true"></div>

            </div>
            <div class="clearfix"></div>

            <div class="inputs-items" id="fees_area">
                <label>اختر القسط</label>
                <select class="form-control" id="student_fees">

                </select>
            </div>

            <div class="clearfix"></div>

            <br/>

        </form>


        <div class="accounting-guide-table">

            <div style="width: 200px;height: 100%;border-left: 1px solid;float: right">
                <div style="text-align: center;border-bottom: 1px solid;padding: 5px;">المبلغ</div>

                <textarea id="amountNumbers">{{$payment_amount}}</textarea>

            </div>

            <div style="width: 250px;height: 100%;border-left: 1px solid;float: right">
                <div style="text-align: center;border-bottom: 1px solid;padding: 5px;"> رقم الدليل المحاسبي</div>
                <div style="">

                    <div style="float: right;border-left:1px solid;width: 83px;text-align: center;height: 267px">
                        ٣
                        <div style="border-bottom: 1px solid"></div>
                    </div>
                    <div style="float: right;border-left:1px solid;width: 83px;text-align: center;height: 267px">
                        ٤
                        <div style="border-bottom: 1px solid"></div>

                    </div>
                    <div style="float: right;width: 83px;text-align: center;height: 267px">
                        ٥
                        <div style="border-bottom: 1px solid"></div>

                    </div>


                </div>
            </div>


            <div style="width: 538px;height: 100%;float: right">
                <div style="text-align: center;border-bottom: 1px solid;padding: 5px;"> اسم الحساب</div>
            </div>


        </div>


        <div class="float-left" style="margin-left: 40px;margin-top: 20px;">
            <span> امين الصندوق</span>
        </div>

        <div class="clearfix"></div>

        <br/>


        <div class="receipt-footer">

            <div style="float: right">بغداد - نهاية جسر الجادرية بأتجاه السيدية</div>
            <div style="float: right;margin-right: 6%">Tel : 6111</div>
            <div style="float: right;margin-right: 6%">P.O.Box : Jadiriya 2268</div>
            <div style="float: right;margin-right: 6%">Email : info@albayan.edu.iq</div>
            <div style="float: right;margin-right: 6%">Website : albayan.edu.iq</div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<!-- Essential javascripts for application to work-->
{{--<script src="{{asset('js/plugins/sweetalert.min.js')}}"></script>--}}

<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>

<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- Scripts -->
<script src="{{asset("js/receipt.js")}}"></script>

<script src="{{asset("js/printThis.js")}}"></script>
<script src="{{asset("js/tafqit.js")}}"></script>
<script src="{{ asset('js/main.js') }}" defer></script>

<!-- The javascript plugin to display page loading on top-->
<script data-pace-options='{ "ajax": true }' src="{{asset('js/plugins/pace.min.js')}}"></script>

</body>
</html>
