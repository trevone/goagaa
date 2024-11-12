<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
//use App\Http\Requests\SubjectRequest;
//use App\Http\Resources\Subject\SubjectCollection;
//use App\Http\Resources\Subject\SubjectEdit;
use App\Models\Campaign;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CampaignService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Campaign\CampaignCollection;

class CampaignController extends Controller
{
  const PER_PAGE = 20;
  const ORDER = 'asc';
  const BY = 'id';

  protected $campaignService;

  public function __construct(CampaignService $campaignService)
  {
    $this->attributes = array_merge(Campaign::ATTRIBUTES, Campaign::BOOL_ATTRIBUTES, Campaign::JSON_ATTRIBUTES);
    $this->campaignService = $campaignService;
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

  /**
    * Show the form for editing the specified resource.
    */
  public function edit(string $id)
  {
    $campaign = new CampaignEdit($this->campaignService->getById($id));

    return Inertia::render('Admin/Campaigns/Edit', compact('campaign'));
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
    * Remove the specified resource from storage.
    */
  public function destroy(Request $request, string $id)
  {
    // TODO check if authorised to delete the resource
    if ($id != $request->id) {
      abort(403, 'Not authorised.');
    }

    try {

      $this->campaignService->delete($id);
    } catch (\Exception $exception) {

      return redirect()->back()->with($this->error($exception->getMessage()));
    }

    return redirect()->route('admin.subjects.index')->with($this->success('You have successfully deleted the entry.'));
  }
}
