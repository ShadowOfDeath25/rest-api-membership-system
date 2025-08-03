<?php

namespace App\Http\Controllers;

use App\Models\PaymentCard;

class PaymentController extends Controller
{
    public static function process($cardNumber, $amount): array
    {
        $card = PaymentCard::where("card_number", $cardNumber)->first();
        if (!$card) {
            return [
                "message" => "Card not found",
                "status" => false,
                "code" => 404
            ];
        }
        if ($card->balance < $amount) {
            return [
                "message" => "Insufficient funds",
                "status" => false,
                "code" => 400
            ];
        }
        $card->balance -= $amount;
        if (!$card->save()) {
            return [
                "status" => false,
                "message" => "DB Error",
                "code"=>500,
            ];
        }
        return [
            'status' => true,
            'message' => 'Payment successful',
            'transaction_id' => uniqid('txn_'),
            'remaining_balance' => $card->balance,
            'code' => 200
        ];
    }
}
