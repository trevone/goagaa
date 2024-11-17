<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
//use App\Http\Requests\SubjectRequest;
//use App\Http\Resources\Subject\SubjectCollection;
//use App\Http\Resources\Subject\SubjectEdit;
use App\Models\Process;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ProcessService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessRequest;
use App\Http\Resources\Process\ProcessEdit;
use App\Http\Resources\Process\ProcessCollection;

class ProcessController extends Controller
{
  const PER_PAGE = 20;
  const ORDER = 'asc';
  const BY = 'id';

  protected $processService;

  public function __construct(ProcessService $processService)
  {
    $this->attributes = array_merge(Process::ATTRIBUTES, Process::BOOL_ATTRIBUTES, Process::JSON_ATTRIBUTES);
    $this->processService = $processService;
  }

  /**
    * Display a listing of the resource.
    */
  public function index(Request $request)
  {
    $per_page = self::PER_PAGE;
    $order    = $request->order ?? self::ORDER;
    $by       = $request->by ?? self::BY;
    $search   = $request->f ?? '';

    // dd($search);

    $data = new ProcessCollection(
        $this->processService
          //->setAggregates(['tiles'])
          ->order($by, $order)
          ->perPage($per_page)
          ->search($search)
          ->getAllPaginated()
    );

    return Inertia::render('Admin/Processes/Index', compact('data'));
  }

  /**
    * Show the form for creating a new resource.
    */
  public function create()
  {
    return Inertia::render('Admin/Processes/Create');
  }

  /**
    * Store a newly created resource in storage.
    */
  public function store(ProcessRequest $request)
  {
    try {

      $attr = $request->only($this->attributes); 
      $new_process = $this->processService->create($attr);

    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->route('admin.processes.edit', $new_process->id)->with($this->success('You have successfully stored data.'));
  }

  /**
    * Display the specified resource.
    */
  public function show(string $id)
  {
      //
  }

  /**
    * Show the form for editing the specified resource.
    */
  public function edit(string $id)
  {
    $process = new ProcessEdit($this->processService->getById($id));

    return Inertia::render('Admin/Processes/Edit', compact('process'));
  }

  /**
    * Update the specified resource in storage.
    */
  public function update(ProcessRequest $request, int $id)
  {
    try { 
      $attr = $request->only($this->attributes); 
      $attr['data'] = $request->jsond;
      $this->processService->update($id, $attr);
    } catch (\Exception $exception) {
      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->back()->with($this->success('You have successfully updated data.'));
  }

  /**
    * Remove the specified resource from storage.
    */
  public function destroy(Request $request, string $id)
  {
    // TODO check if authorised to delete the resource
  

    try {

      $this->processService->delete($id);
    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->route('admin.processes.index')->with($this->success('You have successfully deleted the entry.'));
  }
}
