<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EventsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function() {

    /**
     * @param params
     *      `active-events` = all active events
     *      [empty] = all events;
     *     [event id] = get event by id
     */
    Route::get('/events/{params?}', [EventsController::class, 'index']);

    /**
     * CREATE EVENT
     */
    Route::post('/events', [EventsController::class, 'create']);

    /**
     * UPDATE EVENT
     */
    Route::put('/events/{id}', [EventsController::class, 'update']);


    /**
     * PARTIALLY UPDATE EVENT
     */
    Route::patch('/events/{id}', [EventsController::class, 'patch']);

    /**
     * DELETE EVENT
     */
    Route::delete('/events/{id}', [EventsController::class, 'delete']);
});
