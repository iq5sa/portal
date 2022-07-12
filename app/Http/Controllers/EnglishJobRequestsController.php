<?php

namespace App\Http\Controllers;

use App\EnglishJobRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnglishJobRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('EnglishJobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('EnglishJobs.create');
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
            "firstName" => "required|string",
            "middleName" => "required|string",
            "lastName" => "required|string",
            "firstNameE" => "required|string",
            "middleNameE" => "required|string",
            "lastNameE" => "required|string",
            "dateOfBirth" => "required|string",
            "gender" => "required|string",
            "socialStatus" => "required|string",
            "speciality" => "required|string",
            "academicCertificate" => "required|string",
            "englishCertificate" => "required|string",
            "experienceCertificate" => "required|string",
            "otherExperience" => "required|string",
            "computerExperience" => "required|integer",
        ];

        $message = [
            'required' => 'يجب ادخال بيانات الحقل.',
            'string' => 'يجب ادخال نصوص فقط.',
            'integer' => 'يجب ادخال ارقام فقط.',
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $job = new EnglishJobRequests();
        $job->formId = abs(crc32(uniqid()));
        $job->firstName = $request->input('firstName');
        $job->middleName = $request->input('middleName');
        $job->lastName = $request->input('lastName');
        $job->firstNameE = $request->input('firstNameE');
        $job->middleNameE = $request->input('middleNameE');
        $job->lastNameE = $request->input('lastNameE');
        $job->dateOfBirth = $request->input('dateOfBirth');
        $job->gender = $request->input('gender');
        $job->socialStatus = $request->input('socialStatus');
        $job->speciality = $request->input('speciality');
        $job->academicCertificate = $request->input('academicCertificate');
        $job->englishCertificate = $request->input('englishCertificate');
        $job->experienceCertificate = $request->input('experienceCertificate');
        $job->otherExperience = $request->input('otherExperience');
        $job->computerExperience = $request->input('computerExperience');
        $job->save();

        return response()->json($job, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EnglishJobRequests $englishJobRequests
     * @return \Illuminate\Http\Response
     */
    public function show(EnglishJobRequests $englishJobRequests)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EnglishJobRequests $englishJobRequests
     * @return \Illuminate\Http\Response
     */
    public function edit(EnglishJobRequests $englishJobRequests)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\EnglishJobRequests $englishJobRequests
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnglishJobRequests $englishJobRequests)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EnglishJobRequests $englishJobRequests
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnglishJobRequests $englishJobRequests)
    {
        //
    }
}
