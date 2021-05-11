<?php

namespace Andreytech\PaypalWebhook\Controllers;

use Andreytech\PaypalWebhook\Models\PaypalSubscription;
use Andreytech\PaypalWebhook\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class PaypalController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();

        if(empty($data['event_type'])) {
            return;
        }

        switch ($data['event_type']) {
            case 'PAYMENT.SALE.COMPLETED':
                PayPalService::addTransaction($data);
                break;
            case 'BILLING.SUBSCRIPTION.CANCELLED':
                if(!empty($data['resource']['id']))
                PayPalService::cancelSubscription($data['resource']['id']);
                break;
        }
    }

    public function getSubscriptions(Request $request)
    {
        return response()->json(PaypalSubscription::with('paypalTransactions')->get());
    }

    public function createSubscription(Request $request)
    {
        $approveUrl = PayPalService::createSubscription($request->input('email'));
        if($approveUrl) {
            return redirect()->away($approveUrl);
        }

        return abort(500);
    }
}
