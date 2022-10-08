<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Log;
use Illuminate\Support\Facades\Validator;
use App\Services\MailSender;
use Illuminate\Support\Facades\Redis;

class EventsController extends Controller
{
     /**
     * GET ALL EVENTS
     * @param $params; active-events or event id
     * @return json
     */
    public function index($params = NULL)
    {
        $data = NULL;
        if ( $params && $params != 'active-events' ) {
            $eventCache = Redis::get($params);
            if ( $eventCache ) {
                $data = [json_decode($eventCache, true)];
            }
        }
        if (!$data) {
            if ($params == 'active-events')
                $data = Event::active()->get();
            else if ($params != NULL)
                $data = Event::where('id', $params)->get();
            else
                $data = Event::all();

            $data = $data->toArray();
        }

        return response()->json($data);

    }

    /**
     * CREATE NEW EVENT
     * @param $params;
     *      name = event name
     *      slug
     *      start = event start datetime
     *      end = event end datetime
     * @return json
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'required|unique:events|max:255',
            'start' => 'required|date',
            'end' => 'required|date'
        ]);

        if ($validator->fails()) {
            $msg = "Validation Error, Please check the parameters.";
            // CHECK IF SLUG IS NOT AVAILABLE
            if ( Event::where('slug', $request->slug)->count() > 0
            ) {
                $msg = 'Event slug is not available.';
            }
            return response()->json([
                'message' => $msg
            ], 400);
        }
        try {
            DB::beginTransaction();
            $eventArr = [
                'id' => Str::orderedUuid(),
                'name' => $request->name,
                'slug' => $request->slug,
                'startAt' => $request->start,
                'endAt' => $request->end,
            ];
            $newEvent = Event::create($eventArr);

            MailSender::send('Event Created', "Event id {$newEvent->id} has been created", auth()->user()->email);

            $eventArr['id'] = $newEvent->id;

            Redis::set($newEvent->id, json_encode($eventArr));

            DB::commit();
        } catch (\Exception $e) {
            Log::error("[Create Event] Error creating: {$e->getMessage()}", $request->all());

            DB::rollback();
            return response()->json([
                'message' => "Something went wrong while creating event."
            ], 400);
        }

        return response()->json(['message' => "Event {$request->name} has been created"]);
    }

     /**
     * UPDATE EVENT
     * @param $params;
     *      name = event name
     *      slug
     *      start = event start datetime
     *      end = event end datetime
     *      id
     * @return json
     */
    public function update(Request $request, $id = NULL)
    {

        $event = Event::where('id', $id);


        if ( $event->count() < 1 ) {
            return $this->create(new Request($request->all()));
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'start' => 'required|date',
            'end' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => "Validation Error, Please check the parameters."
            ], 400);
        }

        // CHECK IF SLUG IS NOT AVAILABLE
        if ( Event::where('slug', $request->slug)
                ->where('id', '!=', $id)->count() > 0
                ) {

            return response()->json([
                'message' => "Slug is not available"
            ], 400);
        }

        try {
            DB::beginTransaction();
            $eventArr = [
                'name' => $request->name,
                'slug' => $request->slug,
                'startAt' => $request->start,
                'endAt' => $request->end,
            ];
            $event->update($eventArr);

            $eventArr['id'] = $id;
            Redis::del($id);

            Redis::set($id, json_encode($eventArr));

            DB::commit();
        } catch (\Exception $e) {
            Log::error("[Update Event] Error updating: {$e->getMessage()}", $request->all());

            DB::rollback();
            return response()->json([
                'message' => "Something went wrong while updating event."
            ], 400);
        }

        return response()->json(['message' => "Event {$request->name} has been updated"]);
    }

    /**
     * PARTIALLY UPDATE EVENT
     * @param $params;
     *      name = event name
     *      slug
     *      start = event start datetime
     *      end = event end datetime
     *      id
     * @return json
     */
    public function patch(Request $request, $id = NULL)
    {
        $event = Event::where('id', $id);

        if ( $event->count() < 1 ) {
            return response()->json([
                'message' => "Event does not exist"
            ], 400);
        }

        $eventData = [];
        if ($request->name) {
            $eventData['name'] = $request->name;
        }
        if ($request->slug) {
            $eventData['slug'] = $request->slug;
        }
        if ($request->start) {
            $validateStart = Validator::make($request->all(), [
                'start' =>'date'
            ]);

            if ($validateStart->fails()) {
                return response()->json([
                    'message' => "Validation Error, Please check the parameters."
                ], 400);
            }
            $eventData['startAt'] = $request->start;
        }
        if ($request->end) {
            $validateStart = Validator::make($request->all(), [
                'end' =>'date'
            ]);

            if ($validateStart->fails()) {
                return response()->json([
                    'message' => "Validation Error, Please check the parameters."
                ], 400);
            }
            $eventData['endAt'] = $request->end;
        }

        if (!$eventData) {
            return response()->json([
                'message' => "No data to update."
            ], 400);
        }

        try {
            DB::beginTransaction();
            $event->update($eventData);

            Redis::del($id);
            Redis::set($id, json_encode($event->first()->toArray()));
            DB::commit();
        } catch (\Exception $e) {
            Log::error("[Update Event] Error updating: {$e->getMessage()}", $request->all());

            DB::rollback();
            return response()->json([
                'message' => "Something went wrong while updating event."
            ], 400);
        }

        return response()->json(['message' => "Event id {$id} has been updated"]);
    }

    /**
     * DELETE AN EVENT
     * @param $params;
     *      id
     * @return json
     */
    public function delete($id)
    {
        $event = Event::where('id', $id);

        if ($event->count() == 0) {
            Log::error("[Delete Event] Event not found {$id}");
            return response()->json(['message' => "Event not found"], 400);
        }

        $event->delete();
        Redis::del($id);

        return response()->json(['message' => "Event id {$id} has been deleted"]);
    }
}
