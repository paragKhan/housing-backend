<?php

namespace App\Http\Controllers;

use App\Models\RTO;
use App\Http\Requests\StoreRTORequest;
use App\Http\Requests\UpdateRTORequest;
use Illuminate\Http\JsonResponse;

class RTOController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Rto::paginate(20));
    }

    public function store(StoreRTORequest $request)
    {
        return response()->json(RTO::create($request->validated()));

    }

    public function show(RTO $rto)
    {
        return response()->json($rto);
    }


    public function update(UpdateRTORequest $request, RTO $rto)
    {
        $rto->update($request->validated());

        return response()->json($rto);
    }


    public function destroy(RTO $rto)
    {
        $rto->delete();

        return response()->json(['message' => 'RTO deleted']);
    }
}
