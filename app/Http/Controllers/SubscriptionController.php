<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return response()->json(SubscriptionResource::collection(Subscription::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubscriptionRequest $request)
    {
        $data = $request->validated();
        $plan = Plan::find($data['plan_id']);
        $start_date = Carbon::now()->format('Y-m-d');
        $end_date = Carbon::now()->addMonths($plan->duration_in_months);
        $data["user_id"] = Auth::id();
        $data['start_date'] = $start_date;
        $data['expiration_date'] = $end_date;


        $payment = PaymentController::process($data["card_number"], $plan->price);
        if (!$payment["status"]) {
            return response()->json(["message" => $payment["message"]], $payment["code"]);
        }


        $subscription = new Subscription($data);
        if ($subscription->save()) {
            return response()->json([
                "message" => "Subscription successful",
                "subscription" => SubscriptionResource::make($subscription),
                "remaining_balance" => $payment["remaining_balance"],
                "transaction_id" => $payment["transaction_id"],
            ], 201);
        }
        return response()->json([
            "message" => "Subscription failed",

        ], 409);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        return response()->json(SubscriptionResource::make($subscription));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $data = $request->validated();
        if (isset($data["start_date"])) {
            $data["start_date"] = Carbon::parse($data["start_date"]);
            $data["end_date"] = $data["start_date"]->addMonths($subscription->plan->duration_in_months);
        }
        if ($subscription->update($data)) {
            return response()->json([
                "message" => "Subscription was updated successfully",
                "subscription" => SubscriptionResource::make($subscription)
            ]);
        }
        return response()->json([
            "message" => "Subscription update failed"
        ], 400);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        if ($subscription->delete()) {
            return response()->json("", 204);
        }
        return response()->json("", 400);

    }
}
