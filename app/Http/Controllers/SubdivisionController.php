<?php

namespace App\Http\Controllers;

use App\Models\Subdivision;
use App\Http\Requests\StoreSubdivisionRequest;
use App\Http\Requests\UpdateSubdivisionRequest;

class SubdivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subdivisions = Subdivision::paginate(20);

        return response()->json($subdivisions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubdivisionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubdivisionRequest $request)
    {
        $validated = $request->validated();

        $subdivision = Subdivision::create($validated);

        return response()->json($subdivision);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subdivision  $subdivision
     * @return \Illuminate\Http\Response
     */
    public function show(Subdivision $subdivision)
    {
        return response()->json($subdivision);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubdivisionRequest  $request
     * @param  \App\Models\Subdivision  $subdivision
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubdivisionRequest $request, Subdivision $subdivision)
    {
        $subdivision->update($request->validated());

        return response()->json($subdivision);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subdivision  $subdivision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subdivision $subdivision)
    {
        $subdivision->delete();

        return response()->json(['message' => 'Subdivision deleted.']);
    }
}
