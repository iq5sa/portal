<?php

namespace App\Http\Controllers;

use App\JobCategory;
use App\JobRequest;
use App\JobRequestDocument;
use App\JobType;
use App\NewsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Prophecy\Exception\Exception;


class JobRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobCategory = JobCategory::where('active','=',1)->get();
        $today = Carbon::today();
        $end_date = Carbon::createFromFormat('Y-m-d', $jobCategory->first()->end_date);

        if ($today->greaterThan($end_date)) {
            return view('job.closed');
        }

        if ($jobCategory->isEmpty()){
            return view('job.closed');
        }

        return view('job.create')
            ->with('category',$jobCategory);
    }

    public function get_all_job_type()
    {
        $job_types = JobType::all();
        return response()->json($job_types, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $rules = [
            "job_types_id" => 'required|integer',
            "firstName" => 'required|string',
            "middleName" => 'required|string',
            "lastName" => 'required|string',
            "surname" => 'required|string',
            "mother_firstName" => 'required|string',
            "mother_middleName" => 'required|string',
            "mother_lastName" => 'required|string',
            "placeOfBirth" => 'required|string',
            "dateOfBirth" => 'required|string',
            "city" => 'required|string',
            "county" => 'required|string',
            "town" => 'required|string',
            "streetNumber" => [
                'required',
                function ($attribute, $value, $fail) {
                    $streetNUmber = $value;
                    try {
                        $streetNUmber = (int)$streetNUmber;
                        if ($streetNUmber == 0) {
                            $fail('يجب أدخال رقم الزقاق بصيغة ارقام فقط.');
                        }
                    } catch (Exception $e) {
                        $fail('يجب أدخال رقم الزقاق بصيغة ارقام فقط.');
                    }
                },
            ],
            "doorNumber" => [
                'required',
                function ($attribute, $value, $fail) {
                    $doorNumber = $value;

                    try {
                        $doorNumber = intval($doorNumber);
                        if ($doorNumber == 0) {
                            $fail('يجب أدخال رقم الدار بصيغة ارقام فقط.');
                        }
                    } catch (Exception $e) {
                        $fail('يجب أدخال رقم الدار بصيغة ارقام فقط.');
                    }
                },
            ],
            "certificate" => 'required|integer',
            "specialityGeneral" => 'required|string',
            "specialitySpacial" => 'required|string',
            "graduateYear" => 'string',
            'graduateScore' => [
                function ($attribute, $value, $fail) {
                    $certificate = \request('certificate');
                    $graduateScore = $value;
                    try {
                        $certificate = intval($certificate);
                        $graduateScore = intval($graduateScore);

                        if ($certificate != 3 && $graduateScore == null) {
                            $fail('يجب اختيار معدل التخرج.');
                        }
                    } catch (Exception $e) {
                        $fail('يجب اختيار معدل التخرج بشكل صحيح.');
                    }
                },
            ],
            'graduateSequence' => [
                function ($attribute, $value, $fail) {
                    $certificate = \request('certificate');
                    $graduateSequence = $value;
                    try {
                        $certificate = intval($certificate);
                        $graduateSequence = intval($graduateSequence);

                        if ($certificate != 3 && ($graduateSequence == null || $graduateSequence ==0)) {
                            $fail('يجب أدخال تسلسل التخرج على الكلية او المعهد.');
                        }

                    } catch (Exception $e) {
                        $fail('يجب أدخال ارقام صحيحة فقط.');
                    }
                },
            ],
            'graduateSequenceByDepartment' => [
                function ($attribute, $value, $fail) {
                    $certificate = \request('certificate');
                    $graduateSequenceByDepartment = $value;
                    try {
                        $certificate = intval($certificate);
                        $graduateSequenceByDepartment = intval($graduateSequenceByDepartment);
                        if ($certificate != 3 && ($graduateSequenceByDepartment == null || $graduateSequenceByDepartment == 0)) {
                            $fail('يجب أدخال تسلسل التخرج بالنسبة للقسم.');
                        }

                    } catch (Exception $e) {
                        $fail('يجب أدخال ارقام فقط.');
                    }

                },
            ],
            "countryOfStudy" => 'required|string',
            "universityOrCollege" => 'required|string',
            "jobStatus" => 'required|integer',
            'ex_dateOfContract' => [
                function ($attribute, $value, $fail) {
                    $jobStatus = \request('jobStatus');
                    $ex_dateOfContract = $value;

                    try {
                        $jobStatus = intval($jobStatus);
                        if ($jobStatus != 1 && $ex_dateOfContract == null) {
                            $fail('يجب اختيار تأريخ نهاية العقد.');
                        } else {
                            if (is_int($ex_dateOfContract)) {
                                $fail('يجب أدخال التأريخ بشكل صحيح.');
                            }
                        }
                    } catch (Exception $e) {
                        $fail('يجب أدخال ارقام فقط.');
                    }


                },
            ],
            'ministry' => [
                function ($attribute, $value, $fail) {
                    $jobStatus = \request('jobStatus');
                    $ministry = \request('ministry');
                    try {
                        $jobStatus = intval($jobStatus);
                        if ($jobStatus != 1 && $ministry == null) {
                            $fail('يجب ادخال بيانات الحقل.');
                        } else {
                            if (is_int($ministry)) {
                                $fail('يجب أدخال نص فقط.');
                            }
                        }
                    } catch (Exception $e) {
                        $fail('يجب أدخال ارقام فقط.');
                    }

                },
            ],
            'company' => [
                function ($attribute, $value, $fail) {
                    $jobStatus = \request('jobStatus');
                    $company = \request('company');
                    try {
                        $jobStatus = intval($jobStatus);
                        if ($jobStatus != 1 && $company == null) {
                            $fail('يجب ادخال بيانات الحقل.');
                        }
                    } catch (Exception $e) {
                        $fail('يجب أدخال ارقام فقط.');
                    }
                },
            ],
            'jobExperience' => [
                function ($attribute, $value, $fail) {
                    $jobStatus = \request('jobStatus');
                    $jobExperience = $value;
                    try {
                        $jobStatus = intval($jobStatus);
                        if ($jobStatus != 1 && $jobExperience == null) {
                            $fail('يجب ادخال بيانات الحقل.');
                        }
                    } catch (Exception $e) {
                        $fail('يجب أدخال ارقام فقط.');
                    }
                },
            ],
            "otherExperience" => 'required|string|max:300',
            'gender' => "required|string",
            'phone' => "required|string",
            'social_status' => "required|string",
            'children' => 'required|integer',
            'documents' => [
                'array',
                function ($attribute, $value, $fail) {
                    $array = [0, 1, 2, 3, 4];
                    $doc_not_found = [];
                    foreach ($array as $a) {
                        if (!$this->type_id_exists($value, $a)) {
                            array_push($doc_not_found, $a + 1);
                        }
                    }

                    if (sizeof($doc_not_found) > 0) {
                        $comma_separated = implode(",", $doc_not_found);
                        $fail('يجب رفع الوثائق بالتسلسلات التالية ' . $comma_separated);
                    }
                },
                /*function ($attribute, $value, $fail) {
                    $jobStatus = \request('jobStatus');
                    if ($jobStatus == 0 && !$this->type_id_exists($value, 7)) {
                        $fail('بجب رفع ملف (كتاب اجازة من الدائرة) لكونك موظف.');
                    }
                },*/
                function ($attribute, $value, $fail) {
                    foreach ($value as $file_data) {
                        $document_type = $file_data['type_id'];
                        $file = $file_data['file'];
                        if ($file != null) {
                            $mime = $file->getClientMimeType();
                            $image_types_array = ['image/jpeg', 'image/png', 'imag/jpg'];
                            $size = $file->getSize();
                            if ($size > 1048576) {
                                $fail('يجب ان لايتجاوز حجم الملف 5 ميكابايت');
                            }

                            if ($document_type == 0) {
                                if (!in_array($mime, $image_types_array)) {
                                    $fail('يجب رفع ملف الصورة الشخصية بصيغة (jpeg,jpg,png).');
                                }
                            } else if ($document_type > 0 && $document_type <= 10) {
                                if ($mime != 'application/pdf') {
                                    $d = $document_type + 1;
                                    $fail('يجب أختيار ملف الوثيقة ' . $d . ' بصيغة PDF.');
                                };
                            } else {
                                $fail('حدث خطأ ما يرجى التأكد من الملفات التي تود رفعها');
                            }
                        }
                    }
                },

            ],
            'email' => 'email|unique:job_requests',
            "idNumber" => [
                'required',
                function ($attribute, $value, $fail) {
                    $idNumber = $value;
                    try {
                        $idNumber = intval($idNumber);
                        if ($idNumber == 0){
                            $fail('يجب أدخال رقم الهوية بصيغة ارقام فقط.');
                        }
                    } catch (Exception $e) {
                        $fail('يجب أدخال رقم الهوية بصيغة ارقام فقط.');
                    }
                },
            ],
            'idIssueAuthority' => 'required|string',
            'idIssueDate' => 'required|string',
        ];

        $message = [
            'job_request_documents_id.required' => 'يجب رفع ملف الوثائق قبل ارسال الاستمارة.',
            'job_request_documents_id.file' => 'يجب اختيار ملف',
            'required' => 'يجب ادخال بيانات الحقل.',
            'string' => 'يجب ادخال نصوص فقط.',
            'integer' => 'يجب ادخال ارقام فقط.',
            'email' => "البريد الالكتروني غير صحيح.",
            'email.unique' => "البريد الالكتروني مستخدم بالفعل.",
            'max' => "يجب ان لايتجاوز عدد الاحرف المدخلة 300 حرفاً.",

        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $job = new JobRequest();
        $job->form_id = abs(crc32(uniqid()));
        $job->job_types_id = $request->input('job_types_id');
        $job->firstName = $request->input('firstName');
        $job->middleName = $request->input('middleName');
        $job->lastName = $request->input('lastName');
        $job->surname = $request->input('surname');
        $job->mother_firstName = $request->input('mother_firstName');
        $job->mother_middleName = $request->input('mother_middleName');
        $job->mother_lastName = $request->input('mother_lastName');
        $job->placeOfBirth = $request->input('placeOfBirth');
        $job->dateOfBirth = $request->input('dateOfBirth');
        $job->city = $request->input('city');
        $job->county = $request->input('county');
        $job->town = $request->input('town');
        $job->streetNumber = $request->input('streetNumber');
        $job->doorNumber = $request->input('doorNumber');
        $job->certificate = $request->input('certificate');
        $job->specialityGeneral = $request->input('specialityGeneral');
        $job->specialitySpacial = $request->input('specialitySpacial');
        $job->graduateYear = $request->input('graduateYear');
        $job->graduateScore = $request->input('graduateScore');
        $job->graduateSequence = $request->input('graduateSequence');
        $job->graduateSequenceByDepartment = $request->input('graduateSequenceByDepartment');
        $job->countryOfStudy = $request->input('countryOfStudy');
        $job->universityOrCollege = $request->input('universityOrCollege');
        $job->jobStatus = $request->input('jobStatus');
        $job->ex_dateOfContract = $request->input('ex_dateOfContract');
        $job->ministry = $request->input('ministry');
        $job->company = $request->input('company');
        $job->jobExperience = $request->input('jobExperience');
        $job->otherExperience = $request->input('otherExperience');
        $job->gender = $request->input('gender');
        $job->phone = $request->input('phone');
        $job->social_status = $request->input('social_status');
        $job->children = $request->input('children');
        $job->email = $request->input('email');
        $job->idNumber = $request->input('idNumber');
        $job->idIssueAuthority = $request->input('idIssueAuthority');
        $job->idIssueDate = $request->input('idIssueDate');
        $job->save();

        $type_names = array(
            "0" => 'الصورة الشخصية',
            "1" => 'السيرة الذاتية باللغة العربية والانكليزية',
            "2" => 'المستمسكات الشخصية',
            "3" => 'الشهادة',
            "4" => 'شهادات الخبره والدورات',
            "5" => 'شهادة طرائق التدريس',
            "6" => 'البحوث المنشورة',
            "7" => 'أمر اللقب العلمي',
            "8" => 'أمر الاحاله على التقاعد',
            "9" => 'شهادة أختبار صلاحية التدريس',
        );
        $all = $request->all();
        $documents = $all['documents'];
        $folder = hash('sha256', time());
        foreach ($documents as $document) {
            $file = $document['file'];
            $type_id = $document['type_id'];

            $type_name = $type_names[$type_id];
            $filename = $type_id . '.' . $file->getClientOriginalExtension();
            $path = $folder . '/' . $filename;
            if (Storage::disk('uploads')->put($path, File::get($file))) {
                $input['filename'] = $filename;
                $input['type'] = $type_id;
                $input['mime'] = $file->getClientMimeType();
                $input['path'] = $path;
                $input['size'] = $file->getClientSize();
                $input['type_name'] = $type_name;
                $input['job_requests_id'] = $job->id;
                JobRequestDocument::create($input);
            }

            if ($type_id == 0){
                Storage::disk('public')->put('employees/'.$path, File::get($file));
            }
        }


        // update news
        $job_type = DB::table('job_types')->select('*')
            ->where('id', '=', $job->job_types_id)
            ->get();

        $certificate = $job_type->first()->certificate;
        $speciality = $job_type->first()->speciality;
        $title = $job_type->first()->title;

        $job_title = $certificate . ' / ' . $speciality . '/ ' . $title;

        $news = new NewsHistory();
        $news->title = 'تم استلام طلب التعيين بنجاح.';
        $news->body = 'تم استلام طلب التعيين على الدرجة الوضيفية ' . $job_title . ' يرجى متابعة حالة طلبك وتوجيهات اللجنة باستمرار.';
        $news->job_requests_id = $job->id;
        $news->save();

        return response()->json($job, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JobRequest $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*$jobRequest = JobRequest::find($id);
        return response()->json(
            [],200);*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobRequest $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(JobRequest $jobRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\JobRequest $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobRequest $jobRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobRequest $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobRequest $jobRequest)
    {
        //
    }

    public function upload(Request $request)
    {

    }

    public function download($id)
    {

    }

    public function loadPDF()
    {

    }



    public function type_id_exists($array, $type_id)
    {
        $a = [];
        for ($i = 0; $i < sizeof($array); $i++) {
            array_push($a, $array[$i]['type_id']);
        }

        return in_array($type_id, $a);
    }



}
