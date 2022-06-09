<?php

namespace App\Http\Controllers;

use App\Models\event;
use Illuminate\Http\Request;
use App\Http\Requests\StoreeventRequest;
use App\Http\Requests\UpdateeventRequest;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //get events by user id
        $events = event::all();

        // return $this->getEvents(event::where('user_id',Auth::id())->get());
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

        $event=event::find($id);
        if(!$event){
            return response()->json(['error' => 'Event not found']);
        }
        $event->delete();
        return response()->json(['success' => 'deleted']);
    }
}
