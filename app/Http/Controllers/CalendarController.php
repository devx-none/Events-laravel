<?php

namespace App\Http\Controllers;

use App\Models\calendar;
use App\Http\Requests\StorecalendarRequest;
use App\Http\Requests\UpdatecalendarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
       $user = $this->request->user();
       $calendars = $user->calendars->load('events');
         return response(['calendar'=>$calendars]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecalendarRequest  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        //
        if(!Gate::allows('create-calendar', calendar::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to create a calendar'
            ], 403);
        }

        $calendar = Calendar::create([
            'title' => $request->title,
            'user_id' => auth()->user()->id,
        ]);
        return response()->json($calendar);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        if(!Gate::allows('view-calendar', calendar::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to view this calendar'
            ], 403);
        }
        $calendar = calendar::find($request->id);
        return response([
            'status' => 'success',
            'calendar' => $calendar
        ]);




    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function edit(calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecalendarRequest  $request
     * @param  \App\Models\calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        if(!Gate::allows('update-calendar', calendar::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to update this calendar'
            ], 403);
        }
        $calendar = Calendar::find($request->id);
        $calendar->title = $request->title;
        $calendar->save();
        return response(['calendar'=>$calendar]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //delete calendar and all events associated with it from database   and return success message to frontend if successful or error message if not successful

        if(!Gate::allows('delete-calendar', calendar::class)){
            return response([
                'status' => 'error',
                'message' => 'You are not authorized to delete this calendar'
            ], 403);
        }
        $calendar = Calendar::find($request->id);
        if($calendar){
            $calendar->delete();
            return response(['status'=>'success']);
        }
        else{
            return response(['status'=>'error']);
        }






    }
}
