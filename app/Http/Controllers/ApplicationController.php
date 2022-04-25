<?php

namespace App\Http\Controllers;

use App\Mail\Users\ApplicationSubmitted;
use App\Mail\Users\ApplicationUpdated;
use App\Models\Application;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Executive;
use App\Models\Photo;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isStaff()){
             return response()->json(Application::whereNull('forwardable_type')->whereNull('forwardable_id')->where('status', Application::STATUS_SUBMITTED)->paginate(20));
        }else if(isExecutive()){
            return response()->json(Application::whereHasMorph('forwarder', [Staff::class])->paginate(20));
        }

        $applications = Application::orderByRaw("FIELD(status , 'submitted', 'reviewing', 'resubmit', 'approved', 'declined') ASC")->paginate(20);

        return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreApplicationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApplicationRequest $request)
    {
        $validated = $request->validated();

        $application = auth()->user()->applications()->create($validated);

        $application->addMediaFromRequest('nib_photo')->toMediaCollection('nib_photo');
        $application->addMediaFromRequest('pre_approved_letter_photo')->toMediaCollection('pre_approved_letter_photo');
        $application->addMediaFromRequest('job_letter_document')->toMediaCollection('job_letter_document');
        if ($request->has('passport_photo')) {
            $application->addMediaFromRequest('passport_photo')->toMediaCollection('passport_photo');
        }

        Mail::to(auth()->user())->send(new ApplicationSubmitted());

        return response()->json($application);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        $application->load(['subdivision', 'housingModel']);
        return response()->json($application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateApplicationRequest $request
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApplicationRequest $request, Application $application)
    {
        return $request->validated();

        $application->update($request->validated());

        Mail::to($application->user)->send(new ApplicationUpdated($application->refresh()));

        return response()->json($application);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json(['message' => 'Application deleted.']);
    }

    public function canSubmitApplication()
    {
        $application = auth()->user()->applications()->latest()->first();

        if ($application && ($application->status == 'submitted' || $application->status == 'reviewing')) {
            return response()->json(['canSubmit' => false]);
        }
        return response()->json(['canSubmit' => true]);
    }

    public function getApplicationStatus()
    {
        $application = auth()->user()->applications()->latest('created_at')->first();

        if ($application) {
            return response()->json(['status' => $application->status, 'comments' => $application->comments]);
        } else {
            return response()->json(['status' => "undefined"]);
        }
    }

    public function forward(Application $application){
        $application->forwarder()->associate(auth()->user());
        $application->status = Application::STATUS_REVIEWING;
        $application->save();
        return response()->json($application);
    }
}
