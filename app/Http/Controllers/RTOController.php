<?php

namespace App\Http\Controllers;

use App\Models\RTO;
use App\Http\Requests\StoreRTORequest;
use App\Http\Requests\UpdateRTORequest;

class RTOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRTORequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRTORequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RTO  $rTO
     * @return \Illuminate\Http\Response
     */
    public function show(RTO $rTO)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRTORequest  $request
     * @param  \App\Models\RTO  $rTO
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRTORequest $request, RTO $rTO)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RTO  $rTO
     * @return \Illuminate\Http\Response
     */
    public function destroy(RTO $rTO)
    {
        //
    }
}
