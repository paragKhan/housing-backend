<?php

namespace App\Http\Controllers;

use App\Models\HousingModel;
use App\Http\Requests\StoreHousingModelRequest;
use App\Http\Requests\UpdateHousingModelRequest;

class HousingModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(HousingModel::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreHousingModelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHousingModelRequest $request)
    {
        $housingModel = HousingModel::create($request->validated());

        return response()->json($housingModel);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HousingModel  $housingModel
     * @return \Illuminate\Http\Response
     */
    public function show(HousingModel $housingModel)
    {
        return response()->json($housingModel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHousingModelRequest  $request
     * @param  \App\Models\HousingModel  $housingModel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHousingModelRequest $request, HousingModel $housingModel)
    {
        $housingModel->update($request->validated());

        return response()->json($housingModel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HousingModel  $housingModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(HousingModel $housingModel)
    {
        $housingModel->delete();

        return response()->json(['message' => 'Housing model deleted.']);
    }
}
