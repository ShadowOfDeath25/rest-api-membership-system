<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(): JsonResponse
    {
        return response()->json(Plan::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePlanRequest $request): JsonResponse
    {
        $plan = new Plan($request->validated());
        if ($plan->save()) {
            return response()->json([
                "message" => "Plan was created successfully",
                "plan" => $plan,
            ], 201);
        }
        return response()->json(["message" => "Plan creation failed"], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan): JsonResponse
    {
        return response()->json($plan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        if ($plan->update($request->validated())) {
            return response()->json([
                "message" => "Plan was updated successfully",
                "plan" => $plan
            ]);
        }
        return response()->json(["message" => "Plan updating failed"], 400);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()) {
            $plan->subscriptions()->delete();
        }
        if ($plan->delete()) {
            return response()->json('', 204);
        }
        return response()->json('', 400);

    }
}
