<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Exports\JobRequestExport;
use App\JobCategory;
use App\JobRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AdminRequestController extends Controller
{
    public function ajax(Request $request)
    {
        $id = $request->input('filter_category_id');
        $data = JobRequest::all()->where('job_category_id', '=', $id);
        return Datatables::of($data)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('filter_category_id'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return $row['job_category_id'] == $request->get('filter_category_id') ? true : false;
                    });
                }
                if (!empty($request->get('filter_job_types'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return $row['job_types_id'] == $request->get('filter_job_types') ? true : false;
                    });
                }
                if (!empty($request->get('filter_certificate'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return $row['certificate'] == $request->get('filter_certificate') ? true : false;
                    });
                }

                if (!empty($request->get('search')['value'])) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if (Str::contains(Str::lower($row['id']), Str::lower($request->get('search')['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['form_id']), Str::lower($request->get('search')['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['fullname']), Str::lower($request->get('search')['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['certificate']), Str::lower($request->get('search')['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['specialityGeneral']), Str::lower($request->get('search')['value']))) {
                            return true;
                        } else if (Str::contains(Str::lower($row['specialitySpacial']), Str::lower($request->get('search')['value']))) {
                            return true;
                        }
                        return false;
                    });
                }
            })
            ->addColumn('fullname', function ($row) {
                return $row->firstName . ' ' . $row->middleName . ' ' . $row->lastName . ' ' . $row->surname;
            })
            ->addColumn('certificate', function (JobRequest $jobRequest) {
                return $jobRequest->certificate_name->name;
            })
            ->addColumn('action', 'admin.datatablesButtons')
            ->addColumn('image',function (JobRequest $jobRequest){
                foreach ($jobRequest->documents as $doc){
                    if ($doc->type == 0){
                        $url = asset('storage/employees/'.$doc->path);
                        return "<div class='text-center'><img src=".$url." height='40' width='40' class='rounded-circle' align='center' /></div>";
                    }
                }

                return "<div class='text-center'><i class='fa fa-user'></i></div>";

            })
            ->rawColumns(['action','image'])
            ->only(['image','job_types_id', 'job_category_id', 'form_id', 'id', 'fullname', 'certificate', 'specialityGeneral', 'specialitySpacial', 'action', 'DT_RowIndex'])
            ->make(true);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $certificates = Certificate::all();
        $categories = JobCategory::all();
        return view('admin.requests.index')
            ->with('categories', $categories)
            ->with('certificates', $certificates);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $photo = "";
        // job photo path
        $photo_document = DB::table('job_request_documents')->select('path', 'type_name')
            ->where('job_requests_id', '=', $id)
            ->where('type', '=', 0)
            ->get();

        if (Storage::disk('public')->exists('employees/'.$photo_document->first()->path)){
            $photo = asset('storage/employees/'.$photo_document->first()->path);
        }

        $requestData = JobRequest::find($id);
        $documents = $requestData->documents;
        return view('admin.requests.show')
            ->with('requestData', $requestData)
            ->with('photo', $photo)
            ->with('documents',$documents);
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

    public function export(Request $request)
    {
        $job_category_id = $request->input('job_category_id');
        $types_id = $request->input('types_id');
        $certificate = $request->input('certificate');

        return Excel::download(new JobRequestExport($job_category_id,$types_id,$certificate), 'المتقدمين_للتعيين.xlsx');

    }

    public function downloadUserDocuments($id)
    {
        $jobRequest = JobRequest::find($id);
        $fullname = $jobRequest->firstName . ' ' . $jobRequest->middleName . ' ' . $jobRequest->lastName . ' ' . $jobRequest->surname;
        $form_id = $jobRequest->form_id;
        $documents = $jobRequest->documents;
        if ($documents->count() > 0) {
            $first_document = $documents->first(function ($value, $key) {
                return $value;
            });
            $document_folder_name = explode('/', $first_document->path, 2)[0];
            $zip_file = $form_id . '.zip';
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $path = storage_path('files/uploads/' . $document_folder_name);
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file) {
                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    // extracting filename with substr/strlen
                    $relativePath = $fullname . '/' . substr($filePath, strlen($path) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            return response()->download($zip_file);
        }
    }

    public function getJobTypes(Request $request)
    {
        $category = JobCategory::find($request->input('category_id'));
        if ($category != null) {
            $job_types = $category->types;
            return response()->json($job_types, 200);
        }
    }
}
