<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Log;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
     /**
     * GET ALL EVENTS
     * @param $params; active-events or event id
     * @return json
     */
    public function index($params = NULL)
    {
        if ($params == 'active-events')
            $data = Event::active()->get();
        else if ($params != NULL)
            $data = Event::where('id', $params)->get();
        else
            $data = Event::all();

        return response()->json($data->toArray());

    }

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
            Event::create([
                'id' => Str::orderedUuid(),
                'name' => $request->name,
                'slug' => $request->slug,
                'startAt' => $request->start,
                'endAt' => $request->end,
            ]);
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
            $event->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'startAt' => $request->start,
                'endAt' => $request->end,
            ]);
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
    public function delete($id)
    {
        $event = Event::where('id', $id);

        if ($event->count() == 0) {
            Log::error("[Delete Event] Event not found {$id}");
            return response()->json(['message' => "Event not found"], 400);
        }

        $event->delete();

        return response()->json(['message' => "Event id {$id} has been deleted"]);
    }
}
