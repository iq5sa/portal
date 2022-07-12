<?php

namespace App\Http\Controllers;

use App\NewsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewsHistoryController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NewsHistory $newsHistory
     * @return \Illuminate\Http\Response
     */
    public function show(NewsHistory $newsHistory)
    {

    }

    public function showJobNews(Request $request)
    {
        $rules = [
            'form_id' => 'required|integer|exists:job_requests,form_id'
        ];
        $message = [
            'required' => 'يجب ادخال رقم مقدم طلب التعيين.',
            'integer' => 'يجب ادخال ارقام فقط.',
            'exists' => 'رقم الاستمارة غير صحيح.',
        ];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $form_id = $request->input('form_id');

        $job_info = DB::table('job_requests')
            ->select('job_requests.id as jid','job_types.title', 'job_types.certificate as tcer','speciality','number', 'firstName', 'middleName', 'lastName', 'surname', 'form_id')
            ->join('job_types', 'job_types.id', '=', 'job_requests.job_types_id')
            ->where('form_id', '=', $form_id)
            ->get();

        $job_info = $job_info->get(0);

        $news = DB::table('news_histories')
            ->select('*')
            ->where('job_requests_id', '=', $job_info->jid)
            ->get();
        return view('news.show', ['news' => $news,'job'=>$job_info]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NewsHistory $newsHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(NewsHistory $newsHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\NewsHistory $newsHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewsHistory $newsHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NewsHistory $newsHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsHistory $newsHistory)
    {
        //
    }
}
