<?php

namespace App\Http\Controllers;

use App\Mail\Users\ApplicationSubmitted;
use App\Mail\Users\ApplicationUpdated;
use App\Models\Application;
use App\Models\RentToOwnApplication;
use App\Http\Requests\StoreRentToOwnApplicationRequest;
use App\Http\Requests\UpdateRentToOwnApplicationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RentToOwnApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $applications = new RentToOwnApplication();

        if ($request->search_by && $request->search_query) {
            switch ($request->search_by) {

                case "islands":
                    $applications = $applications->where('island', $request->search_query);
                    break;
                case "statuses":
                    $applications = $applications->where('status', $request->search_query);
                    break;
                case "fname":
                case "lname":
                case "email":
                case "phone":
                case "nib_no":
                    $applications = $applications->where($request->search_by, "like", "%" . $request->search_query . "%");
                    break;
                default:
                    $applications = [];
            }
        }

        return response()->json($applications->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRentToOwnApplicationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRentToOwnApplicationRequest $request)
    {
        $validated = $request->validated();

        $rto_application = auth()->user()->rto_applications()->create($validated);

        $rto_application->addMediaFromRequest('passport_photo')->toMediaCollection('passport_photo');
        $rto_application->addMediaFromRequest('nib_photo')->toMediaCollection('nib_photo');
        $rto_application->addMediaFromRequest('job_letter_document')->toMediaCollection('job_letter_document');
        $rto_application->addMediaFromRequest('paystub_photo')->toMediaCollection('paystub_photo');
        $rto_application->addMediaFromRequest('credit_facilities')->toMediaCollection('credit_facilities');

        Mail::to(auth()->user())->send(new ApplicationSubmitted());

        return response()->json($rto_application);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RentToOwnApplication  $rentToOwnApplication
     * @return \Illuminate\Http\Response
     */
    public function show(RentToOwnApplication $rto_application)
    {
        return response()->json($rto_application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRentToOwnApplicationRequest  $request
     * @param  \App\Models\RentToOwnApplication  $rentToOwnApplication
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRentToOwnApplicationRequest $request, RentToOwnApplication $rto_application)
    {
        $rto_application->update($request->validated());

        Mail::to($rto_application->user)->send(new ApplicationUpdated($rto_application->refresh()));

        return response()->json($rto_application);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RentToOwnApplication  $rentToOwnApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(RentToOwnApplication $rto_application)
    {
        $rto_application->delete();

        return response()->json(['message' => 'Application deleted!']);
    }

    public function getApplicationStatus()
    {
        $application = auth()->user()->rto_applications()->latest()->first();

        $ret = [
           'status' => $application ? $application->status : null,
           'comments' => $application ? $application->comments : null,
           'can_submit' => !$application || ($application->status != RentToOwnApplication::STATUS_SUBMITTED && $application->status != RentToOwnApplication::STATUS_REVIEWING),
        ];

        return response()->json($ret);
    }

    public function getFilterQueries()
    {
        $islands = Application::select('island')->distinct()->get()->map(function ($application) {
            return [
                "key" => $application->island,
                "value" => $application->island
            ];
        });

        $statuses = [
            [
                "key" => "submitted",
                "value" => "Submitted"
            ],
            [
                "key" => "reviewing",
                "value" => "Reviewing"
            ],
            [
                "key" => "resubmit",
                "value" => "Resubmit"
            ],[
                "key" => "approved",
                "value" => "Approved"
            ],[
                "key" => "declined",
                "value" => "Declined"
            ],
        ];

        return response()->json([
            'islands' => $islands,
            'statuses' => $statuses
        ]);
    }
}
