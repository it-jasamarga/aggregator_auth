<?php

use Ramsey\Uuid\Uuid;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BitbucketWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth.api'], function() {
    Route::get('/testing', function() {
        
        $data = Cache::remember('per_employee_JSMR_10706', 10 * 60, function () {
            return Employee::where('NPP', '10706')->with([
                'position' => function($query) {
                    $query->select('employee_id', DB::raw('npp as npp_karyawan'));
                    $query->orderBy('id', 'desc');
                }
            ])->first();
        });
        
        return $data;
    });
});

Route::get('/token', function() {
    $uuid = Uuid::uuid4();

    return $uuid->toString();
});

Route::post('/bitbucket-webhook', BitbucketWebhookController::class);