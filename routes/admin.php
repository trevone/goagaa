<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application; 
use App\Http\Controllers\Admin\ProcessController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ConnectorController;

/*s
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');
Route::post('/campaigns/{campaign}/attach', [CampaignController::class, 'attach'])->name('admin.campaigns.attachs');
Route::post('/connectors/{connector}/aimltest', [ConnectorController::class, 'aimltest'])->name('admin.connectors.aimltest');
Route::resource('/campaigns', CampaignController::class, ['as' => 'admin']);  
Route::resource('/processes', ProcessController::class, ['as' => 'admin']); 
Route::resource('/connectors', ConnectorController::class, ['as' => 'admin']); 
