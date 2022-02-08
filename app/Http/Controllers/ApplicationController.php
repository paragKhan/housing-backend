<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = Application::orderByRaw("FIELD(status , 'submitted', 'reviewing', 'resubmit', 'approved', 'declined') ASC")->paginate(20);

        return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApplicationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApplicationRequest $request)
    {

        $validated = $request->validated();

        $application = auth()->user()->applications()->create($validated);

        return response()->json($application);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        return response()->json($application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApplicationRequest  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $application->update($request->validated());

        return response()->json($application);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json(['message' => 'Application deleted.']);
    }

    public function canSubmitApplication(){
        $application = auth()->user()->applications()->where('status', 'submitted')->orWhere('status', 'reviewing')->first();

        if($application){
            return response()->json(['canSubmit' => false]);
        }
        return response()->json(['canSubmit' => true]);
    }

    public function getApplicationStatus(){
        $application = auth()->user()->applications()->latest('created_at')->first();

        if($application){
            return response()->json(['status' => $application->status]);
        }
        else{
            return response()->json(['status' => "undefined"]);
        }
    }
}
