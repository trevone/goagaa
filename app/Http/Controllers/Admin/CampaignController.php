<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
//use App\Http\Requests\SubjectRequest;
//use App\Http\Resources\Subject\SubjectCollection;
//use App\Http\Resources\Subject\SubjectEdit;
use App\Models\Process;
use App\Models\Campaign;
use App\Models\Connector;
use App\Events\LogUpdated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ProcessService;
use App\Services\CampaignService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignRequest;
use App\Http\Resources\Campaign\CampaignEdit;
use App\Http\Resources\Campaign\CampaignCollection;

class CampaignController extends Controller
{
  const PER_PAGE = 20;
  const ORDER = 'asc';
  const BY = 'id';

  protected $campaignService;
  protected $processService;

  public function __construct(CampaignService $campaignService, ProcessService $processService)
  {
    $this->attributes = array_merge(Campaign::ATTRIBUTES, Campaign::BOOL_ATTRIBUTES, Campaign::JSON_ATTRIBUTES);
    $this->campaignService = $campaignService;
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

    $data = new CampaignCollection(
        $this->campaignService
          //->setAggregates(['tiles'])
          ->order($by, $order)
          ->perPage($per_page)
          ->search($search)
          ->getAllPaginated()
    );

    return Inertia::render('Admin/Campaigns/Index', compact('data'));
  }

  /**
    * Show the form for creating a new resource.
    */
  public function create()
  {
    return Inertia::render('Admin/Campaigns/Create');
  }

  /**
    * Store a newly created resource in storage.
    */
  public function store(CampaignRequest $request)
  {
    try {

      $attr = $request->only($this->attributes); 
      $new_campaign = $this->campaignService->create($attr);

    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->route('admin.campaigns.edit', $new_campaign->id)->with($this->success('You have successfully stored data.'));
  }

  /**
    * Display the specified resource.
    */
  public function show(string $id)
  {
      //
  }

  public function getConnectorChain($connector, &$chain){

  
  
     
      if(data_get($connector, 'connector_id', null)){
        $this->getConnectorChain($connector[$k], $chain);
      } 
 
  }

  /**
    * Show the form for editing the specified resource.
    */
  public function edit(string $id)
  {
    $campaign = new CampaignEdit($this->campaignService->setRelations(['connectors.process', 'connectors.output_connector'])->getById($id));
    $options = [];
      
    $processes = Process::all();
    $i=0;
    foreach($processes as $process){ 
      $options[$i] = (object)[
        "value" => $process->id,
        "label" => $process->class,
        
      ];
      $options[$i]->fields = [];
      foreach(data_get($process, 'data', []) as $k => $v){
        $options[$i]->fields[] = $k;
      }
      $i++; 
    } 

    $chain = [];
    $parent_id = null;
    foreach(data_get($campaign,'connectors',[]) as $k => $v){ 
      $chain[$k] = [];
      $c = $v;
      do {
        $chain[$k][] = $c;
        $parent_id = $c->id;
        $c = $c->output_connector;
      } while ($c); 
    }
    
    return Inertia::render('Admin/Campaigns/Edit', compact('campaign', 'options', 'chain', 'parent_id'));
  }

  

  /**
    * Update the specified resource in storage.
    */
  public function update(CampaignRequest $request, int $id)
  {
    try {

      $attr = $request->only($this->attributes); 
      $this->campaignService->update($id, $attr);

    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->back()->with($this->success('You have successfully updated data.'));
  }

  /**
    * Update the specified resource in storage.
    */
    public function attach(Request $request, string $id)
    {
        $connector = new Connector;
        $connector->process_id = $request->process_id;
     
        if(!is_null($request->parent_id)){  
          $connector->campaign_id = null;
        } else {
          $connector->campaign_id = $id; 
        }
        $connector->data = $request->jsond;
        $connector->save();
        if(!is_null($request->parent_id)){ 
          $parent = Connector::find($request->parent_id);
          $parent->connector_id = $connector->id;
          $parent->save();
        }

      
       // $campaign->connectors()->attach($connector);
         
        //$this->campaignService->update($id, $attr);
   
  
      return redirect()->back()->with($this->success('You have successfully updated data.'));
    }

  /**
    * Remove the specified resource from storage.
    */
  public function destroy(Request $request, string $id)
  {
    // TODO check if authorised to delete the resource
  

    try {

      $this->campaignService->delete($id);
    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->route('admin.campaigns.index')->with($this->success('You have successfully deleted the entry.'));
  }
}
