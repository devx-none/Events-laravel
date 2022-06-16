<?php

namespace App\Http\Controllers;

use App\Models\event;
use Illuminate\Http\Request;
use App\Http\Requests\StoreeventRequest;
use App\Http\Requests\UpdateeventRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $events = DB::table('events')
        ->join('calendars', 'events.calendar_id', '=', 'calendars.id')
        ->join('users', 'calendars.user_id', '=', 'users.id')
        ->select('events.*', 'calendars.title', 'users.name')
        ->get();
        return $this->getEvents($events);;


    }

    public function getEvents($events)
    {
        $eventsArray = [];
        //array of events
        foreach ($events as $event) {
            $eventsData = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date,
                'end' => $event->end_date,
                'description' => $event->description,
                'textColor' => $event->textColor,
                'color' => $event->color,


            ];
            array_push($eventsArray, $eventsData);

        }
        // dd(response()->json($eventsArray));

        return response()->json($eventsArray);


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreeventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        if(!Gate::allows('create-event', event::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to create an event'
            ], 403);
        }

        event::create([
            "title" => $request->title,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "description" => $request->description,
            "color" => $request->color,
            "textColor" => "white",
            "user_id" => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Event Created Successfully']);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        if(!Gate::allows('view-event', event::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to view this event'
            ], 403);
        }
        $event = event::find($id);

        return view('show',['event'=>$event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateeventRequest  $request
     * @param  \App\Models\event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if(!Gate::allows('update-event', event::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to update this event'
            ], 403);
        }
        $event = event::find($id);
        if(!$event){
            return response()->json(['error' => 'Event not found']);
        }
        $event->update([
            "start_date" => $request->start,
            "end_date" => $request->end
        ]);
        return response()->json(['success' => 'updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $event = event::find($id);
        if(!Gate::allows('delete-event', event::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to delete this event'
            ], 403);
        }

        if(!$event){
            return response()->json(['error' => 'Event not found']);
        }else{
            $event->delete();
            return response()->json(['success' => 'deleted']);
        }

    }
}
