<?php

namespace App\Http\Controllers;

use App\JobRequest;
use App\JobRequestDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF; // at the top of the file

class FileEntriesController extends Controller
{
    public function index()
    {
        $files = JobRequestDocument::all();
        return view('files.index', compact('files'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function uploadFile(Request $request)
    {
        $this->validate($request, [
            'file' => [
                'file',
                'max:3000',
                'required',
                function ($attribute, $value, $fail) {
                    $type = 1;
                    if (!is_int($type)) {
                        $fail('يجب أختيار نوع الملف بشكل صحيح.');
                    }

                    $file = \request('file');
                    $mime = $file->getClientMimeType();
                    $image_types_array = ['image/jpeg', 'image/png', 'imag/jpg'];
                    if ($type == 1 && !in_array($mime, $image_types_array)) {
                        $fail($mime);
                        //$fail('يجب أختيار ملف بصيغة (jpg,png).');
                    } else {

                    }
                },
            ]
        ]);
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = hash('sha256', time());
        if (Storage::disk('uploads')->put($path . '/' . $filename, File::get($file))) {
            $input['filename'] = $filename;
            $input['mime'] = $file->getClientMimeType();
            $input['path'] = $path;
            $input['size'] = $file->getClientSize();
            $file = JobRequestDocument::create($input);
            return response()->json([
                'success' => true,
                'id' => $file->id
            ], 200);
        }
        return response()->json([
            'success' => false
        ], 500);
    }

    public function downloadFile($id)
    {
        // job
        $job = JobRequest::find($id);
        if ($job != null) {
            // job title
            $job_type = DB::table('job_types')->select('*')
                ->where('id', '=', $job->job_types_id)
                ->get();
            $certificate = $job_type->first()->certificate;
            $speciality = $job_type->first()->speciality;
            $title = $job_type->first()->title;
            $job_title = $certificate . ' / ' . $speciality . ' / ' . $title;

            // job photo path
            $photo_document = DB::table('job_request_documents')->select('path', 'type_name')
                ->where('job_requests_id', '=', $job->id)
                ->where('type', '=', 0)
                ->get();
            $photo = $photo_document->first()->path;
            $documents = DB::table('job_request_documents')->select('type_name')
                ->where('job_requests_id', '=', $job->id)
                ->get();


            $lg = Array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['a_meta_dir'] = 'rtl';
            $lg['a_meta_language'] = 'fa';
            $lg['w_page'] = 'page';

            // set some language-dependent strings (optional)
            PDF::setLanguageArray($lg);

            // set font
            PDF::SetFont('dejavusans', '', 12);

            $style = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => true,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 4
            );


            PDF::SetFont('aealarabiya', '', 12);
            $html1 = '

            <!-- EXAMPLE OF CSS STYLE -->
            <style>
                table.ljna td {
                    border: 1px solid #000;
                    background-color: #fff;
                    text-align: right;
                }
                table.first {
                    color: #000;
                    font-size: 11pt;
                    border: 1px solid #000;
                }
                table.first td {
                    border: 2px solid #000;
                    background-color: #fff;
                    text-align: center;
                }
                table.first td.title {
                     background-color: #ccc;
                }
                .document_list{
                    margin: 0;
                    font-size: 9px;
                    padding: 0;
                }
                .document_list li {
                    font-size: 9px;
                    padding: 0;
                }

            </style>

            <table class="first" cellpadding="2" cellspacing="5">
             <tr>
              <td width="25%" class="title"><b>الاسم الرباعي</b></td>
              <td width="75%">' . $job->firstName . ' ' . $job->middleName . ' ' . $job->lastName . ' ' . $job->surname . '</td>
             </tr>
             <tr>
              <td width="25%" class="title"><b>أسم الام الثلاثي</b></td>
              <td width="75%">' . $job->mother_firstName . ' ' . $job->mother_middleName . ' ' . $job->mother_lastName . '</td>
             </tr>
             <tr>
                <td width="6.3%" class="title"><b>الجنس</b></td>
                <td width="6.3%">' . $job->gender . '</td>
                <td width="10.4%" class="title"><b>الحالة الزوجية</b></td>
                <td width="6%">' . $job->social_status . '</td>
                <td width="10%" class="title"><b>عدد الاطفال</b></td>
                <td width="6%">' . $job->children . '</td>
                <td width="11%" class="title"><b>محل الولادة</b></td>
                <td width="13%">' . $job->placeOfBirth . '</td>
                <td width="8.3%" class="title"><b>التولد</b></td>
                <td width="15%">' . $job->dateOfBirth . '</td>
             </tr>
             <tr>
                <td width="9%" class="title"><b>المحافظة</b></td>
                <td width="15%">' . $job->city . '</td>
                <td width="8.1%" class="title"><b>القضاء</b></td>
                <td width="14.6%">' . $job->county . '</td>
                <td width="8.5%" class="title"><b>المحله</b></td>
                <td width="6.7%">' . $job->town . '</td>
                <td width="8.5%" class="title"><b>الزقاق</b></td>
                <td width="6.7%">' . $job->streetNumber . '</td>
                <td width="8.5%" class="title"><b>رقم الدار</b></td>
                <td width="6.7%">' . $job->doorNumber . '</td>
             </tr>
             <tr>
                <td width="21%" class="title"><b>رقم هوية الاحوال المدنية</b></td>
                <td width="21%">' . $job->idNumber . '</td>
                <td width="11%" class="title"><b>جهة الاصدار</b></td>
                <td width="18.1%">' . $job->idIssueAuthority . '</td>
                <td width="11%" class="title"><b>تأريخ الاصدار</b></td>
                <td width="14%">' . $job->idIssueDate . '</td>
             </tr>
             <tr>
                <td width="15%" class="title"><b>التحصيل الدراسي</b></td>
                <td width="25%" class="title"><b>التخصص العام</b></td>
                <td width="25%" class="title"><b>التخصص الدقيق</b></td>
                <td width="16%" class="title"><b>سنة التخرج</b></td>
                <td width="16.1%" class="title"><b>معدل التخرج</b></td>
             </tr>
             <tr>
                <td width="15%">' . $job->certificate_name->name . '</td>
                <td width="25%">' . $job->specialityGeneral . '</td>
                <td width="25%">' . $job->specialitySpacial . '</td>
                <td width="16%">' . $job->graduateYear . '</td>
                <td width="16.1%">';
            if ($job->graduateScore != null) {
                $html1 .= $job->graduateScore_name->name;
            }
            $html1 .=
                '</td>
             </tr>
             <tr>
                <td width="38%" class="title"><b>تسلسل التخرج على الكلية او المعهد</b></td>
                <td width="11.1%">' . $job->graduateSequence . '</td>
                <td width="38%" class="title"><b>تسلسل التخرج بالنسبة للقسم</b></td>
                <td width="11%">' . $job->graduateSequenceByDepartment . '</td>
             </tr>
             <tr>
                <td width="18%" class="title"><b>بلد الحصول على الشهادة</b></td>
                <td width="31.1%">' . $job->countryOfStudy . '</td>
                <td width="18%" class="title"><b>أسم الجامعة أو الكلية</b></td>
                <td width="31%">' . $job->universityOrCollege . '</td>

             </tr>

             <tr>
                <td width="37%" class="title"><b>هل انت موظف بعقد حاليا</b></td>
                <td width="9%">';
            if ($job->jobStatus == 0) {
                $html1 .= 'نعم';
            } elseif ($job->jobStatus == 1) {
                $html1 .= 'كلا';
            }

            $html1 .=
                '</td>
                <td width="37%" class="title"><b>تأريخ نهاية العقد</b></td>
                <td width="15.1%">' . $job->ex_dateOfContract . '</td>
             </tr>
             <tr>
                <td width="529" class="title"><b>هل انت موظف سابقا او حاليا؟</b></td>
             </tr>
             <tr>
                <td width="10%" class="title"><b>الوزارة</b></td>
                <td width="28.5%">' . $job->ministry . '</td>
                <td width="10%" class="title"><b>الدائرة</b></td>
                <td width="28.5%">' . $job->company . '</td>
                <td width="10%" class="title"><b>مدة الخدمة</b></td>
                <td width="9.3%">' . $job->jobExperience . '</td>
             </tr>
             <tr>
                <td width="529" style="color: red;border: none; font-size: 11px;"><b>المهارات  ,المعارف  ,الدورات والشهادات الاضافية</b></td>
             </tr>
             <tr>
                <td width="529" height="70" style="text-wrap: normal; text-align: justify;font-size: 9px">' . $job->otherExperience . '</td>
             </tr>
             <tr>
              <td width="25%" class="title"><b>العنوان الوضيفي المقدم عليه</b></td>
              <td width="75%">' . $job_title . '</td>
             </tr>
             <tr>
              <td width="15%" class="title"><b>الهاتف</b></td>
              <td width="34%">' . $job->phone . '</td>
              <td width="15%" class="title"><b>البريد الالكتروني</b></td>
              <td width="34%">' . $job->email . '</td>
             </tr>
            </table>

            <table cellspacing="5" cellpadding="3" style="border: none">
                <tr>
                    <td width="70%" style="color: #ff0000; font-size: 12px">أتعهد بصحة المعلومات الواردة اعلاه وبخلافه يهمل الطلب.</td>
                    <td width="8%" align="right" style="border: 1px solid #000; background-color: #ccc;">التوقيع</td>
                    <td width="23%" style="border: 1px solid #000"></td>
                 </tr>

                <tr>
                    <td width="52.5%">
                        <table class="ljna">
                            <tr><td align="right" colspan="3" style="border: none; font-size: 10px; text-decoration: underline"><b>المرفقات</b></td></tr>
                            <tr>
                                <td align="right" width="100%" style="border: none">
                                    <ol class="document_list">';
                                        foreach($documents as $document){
                                            $type_name = $document->type_name;
                                            $html1 .='<li style="font-size: 9px">'.$type_name.'</li>';
                                        }
                                        $html1 .='
                                    </ol>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="center" width="50%">
                        <table cellpadding="40" cellspacing="5">
                            <tr>
                                <td align="center">
                                    <span style="font-family: aefurat">أ. م. د. حسن شاكر مجدي</span>
                                    <br>
                                    العميد
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';

            PDF::SetCreator(PDF_CREATOR);
            PDF::SetAuthor('وحدة تكنولوجيا المعلومات');
            PDF::SetTitle('أستمارة طلب تعيين');
            PDF::SetSubject('جامعة البيان');
            PDF::SetKeywords('جامعة البيان');

// Custom Header
            PDF::setHeaderCallback(function ($pdf) use ($job) {
                $pdf->SetY(5);
                // Set font
                $pdf->SetFont('aealarabiya', '', 12);;
                $path = url('/images/logo_new.jpg');
                $img = '<img src="' . $path . '" width="64">';
                // Title
                $html = '
            <table>
                <tr>
                    <td width="33.33%"></td>
                    <td width="33.33%" align="center" rowspan="2">' . $img . '</td>
                    <td width="33.33%"></td>
                </tr>
                <tr>
                    <td width="33.33%" align="center" >
                    وزارة التعليم العالي والبحث العلمي
                    <br>
                   جامعة البيان
                    </td>
                    <td width="33.33%;" align="center">
                    <b>Ministry of Higher Education and Scientific Research</b>
                    <br>
                    <b>Al Bayan University College</b>
                    </td>
                </tr>
            </table>
            <hr>

            ';
                PDF::writeHTML($html, true, false, true, false, '');
            });

            PDF::setFooterCallback(function ($pdf) use ($job) {
                $pdf->SetY(-15);
                // Set font
                $pdf->SetFont('aealarabiya', '', 12);
                $html_footer = '
            <hr>
            <table>
                <tr>
                    <td align="center">
                        <strong>جميع الحقوق محفوظة / جامعة البيان / وحدة تكنولوجيا المعلومات &nbsp;2019 ©</strong>
                        <br>
                        <span style="font-size: 10px"><b>' . $job->created_at . '</b></span>
                    </td>
                </tr>
            </table>
            ';
                PDF::writeHTML($html_footer, true, false, true, false, '');

            });


            PDF::AddPage();
            // set JPEG quality
            PDF::setJPEGQuality(75);
            PDF::Ln(21);
            $path = '../storage/files/uploads/' . $photo;

            PDF::writeHTML('

        <table cellpadding="0" cellspacing="3">
            <tr>
                <td width="88.5%"></td>
                <td width="12%" height="75"><img width="75" height="85" src="' . $path . '" style="border: 1px solid #000"></td>
            </tr>
        </table>

        ', true, false, true, false, '');
            PDF::Ln(-33);
            PDF::Cell(0, 0, 'رقم الاستمارة', 0, 1);
            PDF::Ln(1);
            PDF::write1DBarcode($job->form_id, 'C39', '', '', '', 18, 0.4, $style, 'N');
            PDF::Ln(2);
            PDF::writeHTML($html1, true, false, true, false, '');
            PDF::Output($job->form_id . '.pdf');
        } else {
            return redirect(route('job.create'));
        }

    }

}
