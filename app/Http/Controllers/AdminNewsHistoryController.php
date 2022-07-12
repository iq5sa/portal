<?php

namespace App\Http\Controllers;

use App\JobRequest;
use App\NewsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminNewsHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = NewsHistory::all();
        return view('admin.news.index')
            ->with('newsHistory', $news);
    }

    public function getUserNames(Request $request)
    {
        $term = trim($request->input('selectedNames'));

        if (empty($term)) {
            return response()->json([], 200);
        }

        $tags = JobRequest::query()
            ->whereLike(['firstName', 'surname'], $term)
            ->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->firstName . ' ' . $tag->middleName . ' ' . $tag->lastName . ' ' . $tag->surname];
        }

        return response()->json($formatted_tags, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            "title" => "required|string",
            "body" => "required|string",
            "sendTo" => [
                "required",
                "integer",
                function ($attribute, $value, $fail) {
                    if (\request()->input('selectedNames') == null && $value == 2) {
                        $fail('يجب اختيار اسم المستلم');
                    }
                },
            ],
            "selectedNames" => "integer"
        ];

        $message = [
            'required' => 'يجب ادخال بيانات الحقل.',
            'string' => 'يجب ادخال نصوص فقط.',
            'integer' => 'يجب ادخال ارقام فقط.',
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $sendTo = $request->input('sendTo');
        $title = $request->input('title');
        $body = $request->input('body');

        if ($sendTo == 1) {
            $jobRequests = JobRequest::all();
            foreach ($jobRequests as $jobRequest) {
                $news = new NewsHistory();
                $news->title = $title;
                $news->body = $body;
                $news->job_requests_id = $jobRequest->id;
                $news->save();
            }
        } else if ($sendTo == 2) {
            $job_request_id = $request->input('selectedNames');
            $news = new NewsHistory();
            $news->title = $title;
            $news->body = $body;
            $news->job_requests_id = $job_request_id;
            $news->save();
        }


        return back()->with('success', 'تم أرسال التوجيهات بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form_id = $id;

        $job_info = DB::table('job_requests')
            ->select('job_requests.id as jid', 'job_types.certificate as tcer', 'speciality', 'number', 'firstName', 'middleName', 'lastName', 'surname', 'form_id')
            ->join('job_types', 'job_types.id', '=', 'job_requests.job_types_id')
            ->where('form_id', '=', $form_id)
            ->get();

        $job_info = $job_info->get(0);

        $news = DB::table('news_histories')
            ->select('*')
            ->where('job_requests_id', '=', $job_info->jid)
            ->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
