<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
//use App\Http\Requests\SubjectRequest;
//use App\Http\Resources\Subject\SubjectCollection;
//use App\Http\Resources\Subject\SubjectEdit;
use App\Models\Connector;
use App\Events\LogUpdated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ConnectorService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConnectorRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\Connector\ConnectorEdit;
use App\Http\Resources\Connector\ConnectorCollection;

class ConnectorController extends Controller
{
  const PER_PAGE = 20;
  const ORDER = 'asc';
  const BY = 'id';

  protected $connectorService;

  public function __construct(ConnectorService $connectorService)
  {
    $this->attributes = array_merge(Connector::ATTRIBUTES, Connector::BOOL_ATTRIBUTES, Connector::JSON_ATTRIBUTES);
    $this->connectorService = $connectorService;
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
        $this->connectorService
          //->setAggregates(['tiles'])
          ->order($by, $order)
          ->perPage($per_page)
          ->search($search)
          ->getAllPaginated()
    );

    return Inertia::render('Admin/Connectors/Index', compact('data'));
  }

  /**
    * Show the form for creating a new resource.
    */
  public function create()
  {
    return Inertia::render('Admin/Connectors/Create');
  }

  /**
    * Store a newly created resource in storage.
    */
  public function store(ConnectorRequest $request)
  {
    try {

      $attr = $request->only($this->attributes); 
      $new_connector = $this->connectorService->create($attr);

    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->route('admin.connectors.edit', $new_connector->id)->with($this->success('You have successfully stored data.'));
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
    $process = new Connector($this->connectorService->getById($id));

    return Inertia::render('Admin/Connectors/Edit', compact('process'));
  }

  /**
    * Update the specified resource in storage.
    */
  public function update(ConnectorRequest $request, int $id)
  {
   
    try { 
      $attr = $request->only($this->attributes); 
      $attr['process_id'] = $request->process_id;
      $attr['data'] = $request->jsond;
      
      $this->connectorService->update($id, $attr);
    } catch (\Exception $exception) {
      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->back()->with($this->success('You have successfully updated data.'));
  }

  
  public function aimltest(Request $request, int $id)
  {
 
    $connector = Connector::find($id); 
    $class = 'App\\Jobs\\' . $connector->process->class;
    $class::dispatch($connector, [], ['broadcasting' => true])->onQueue("default");
    return redirect()->back()->with($this->success('Check the logs for the output.'));
  }
  /**
    * Remove the specified resource from storage.
    */
  public function destroy(Request $request, string $id)
  {
    // TODO check if authorised to delete the resource
  

    try {
      $parent = Connector::where('connector_id', $id)->first();
     
      if($parent){
        $parent->connector_id = null;
        $parent->save();

      }
      $this->connectorService->delete($id);
    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->back()->with($this->success('You have successfully deleted the entry.'));
  }
}
