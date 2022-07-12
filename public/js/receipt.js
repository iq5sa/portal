//English to Arabic digits.
String.prototype.toArabicِِDigits = function () {
    return this.replace(/\d/g, d => '٠١٢٣٤٥٦٧٨٩'[d])
}
let url;
if ($(location).attr("port") ==="8888"){
    url = "http://localhost:8888/portal/";
}else {
    url = "http://hr.albayan.edu.iq/";
}
function toEnglishDigits(str) {
    const persianNumbers = ["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"]
    const arabicNumbers = ["١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩", "٠"]
    const englishNumbers = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"]

    return str.split("").map(c => englishNumbers[persianNumbers.indexOf(c)] ||
        englishNumbers[arabicNumbers.indexOf(c)] || c).join("")
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function selectedStudent(item) {
    let studentName = $("#studentName");
    let level = $("#level");
    let college_name = $("#college_name");
    let shift = $("#shift");
    let student_id = $("#student_id");
    let searchResultMenu = $("#searchResult");
    let student_fees = $("#student_fees");


    // console.log(item);
    studentName.val(item.first_name);
    level.val(item.level);
    level.attr("disabled","disabled")
    college_name.val(item.college_name);
    college_name.attr("disabled","disabled")

    shift.val(item.shift);
    shift.attr("disabled","disabled")

    student_id.val(item.student_id);
    // student_id.attr("disabled","disabled")



    //get Student fees
    let url_getStudentFees = url + "admin/receipt/student/fees/" + student_id.val();

    axios.get(url_getStudentFees).then(function (response) {

        console.log(response.data);
        student_fees.html("");

        for (let i = 0; i < response.data.length; i++) {
            let html = "<option value='" + response.data[i].id + "'>";
            html += response.data[i].start_year + " ";
            html += response.data[i].end_year + " ";
            html += response.data[i].required_amount + " ";

            html += response.data[i].fee_name + " ";

            html += "</option>";
            student_fees.append(html);

        }

    });


    searchResultMenu.hide();
}

function btnPrint(){
    $("#fees_area").hide();

    $('#receipt').printThis({
        importCSS: true,
        importStyle: true,

    });
}

function receiptNew(){
    location.reload(true);
}
function printAndSave() {
    let url_submitPayment = url + "admin/receipt/submit";
    let studentName = $("#studentName").val();
    let payment_date = $(".payment-date").val();
    let note = $(".payment-note").text();
    let payment_id = $("#payment_id").val();
    let student_id = $("#student_id").val();
    let student_fees = $("#student_fees");
    let collage_name = $("#college_name").val();
    let collage_level = $("#level").val();
    let shift = $("#shift").val();


    let lines = $("#amountNumbers").val().split('\n');
    let totalAmount = 0;
    $("#fees_area").hide();


    for (let i = 0; i < lines.length; i++) {
        let enNum = toEnglishDigits(lines[i]);
        totalAmount = totalAmount + parseInt(enNum);

    }


    let data = $("#receipt_form").serialize();


    axios.post(url_submitPayment, data)

        .then(function (response) {
            console.log(response);

            swal.fire({
                type: 'success',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
            });
            if (response.data.status) {
                $('#receipt').printThis({
                    importCSS: true,
                    importStyle: true,

                });

            }


        }).catch(function (error) {
        let response = JSON.parse(JSON.stringify(error));


        swal.fire({
            type: 'error',
            title: response.response.data.message,
            showConfirmButton: false,
        });

    });


}


$(document).ready(function () {
    let amount_number = $('.amount-number');
    let url_searchForStudent = url + "admin/receipt/student/search";
    let studentName = $("#studentName");
    let searchResultMenu = $("#searchResult");
    let searchResultBar = $("#searchResultBar");
    let btnClose = $("#btnClose");
    let amountArea = $("#amountArea");

    $("#amountNumbers").on("keyup", function (event) {
        let currentVal = $(this).val();
        let convertToAr = currentVal.toArabicِِDigits();
        let lines = currentVal.split('\n');
        let totalAmount = 0;
        let amountText = $('.amount-text');

        for (let i = 0; i < lines.length; i++) {
            if (lines[i] !== "") {
                let enNum = toEnglishDigits(lines[i]);

                totalAmount = totalAmount + parseInt(enNum);
            }



        }

        $("input[name*='payment_amount']").val(totalAmount);

        amountText.text(tafqit(totalAmount));
        console.log(totalAmount);


        $(this).val(currentVal.toArabicِِDigits());


        //get student fees


    });


    btnClose.click(function () {

        searchResultMenu.hide();
    });


    //setup before functions
    let typingTimer;                //timer identifier
    let doneTypingInterval = 5000;  //time in ms, 5 second for example

    //on keydown, clear the countdown
    $("#studentName").on('keydown', function () {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping(val) {

        if (val !== "") {

            axios.get(url_searchForStudent, {
                params: {
                    name: val
                }
            }).then(function (response) {


                searchResultMenu.show();
                searchResultBar.html("");

                if (response.data.length === 0) {

                    $("#fees_area").hide();
                    $("#student_id").val("");
                    searchResultMenu.hide();
                } else {
                    $("#fees_area").show();

                    for (let i = 0; i < response.data.length; i++) {
                        let html = "<div>";
                        // onclick="+ selectedStudent(response.data[i]) + "
                        html += "<a href='#' " + "onclick='selectedStudent(";
                        html += JSON.stringify(response.data[i]);
                        html += ")'>";
                        html += response.data[i].student_id + "  ";
                        html += response.data[i].first_name + "(" + response.data[i].college_name + ")"
                        html += "</a></div>";

                        searchResultBar.append(html);


                    }


                }


            });
        }
    }

    $("#studentName").on("keyup", function (event) {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping($(this).val()), doneTypingInterval);




    })

});
