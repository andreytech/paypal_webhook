<?php

namespace Andreytech\PaypalWebhook\Services;

use Andreytech\PaypalWebhook\Models\PaypalSubscription;
use Andreytech\PaypalWebhook\Models\PaypalTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use Illuminate\Support\Facades\Validator;

class PayPalService
{
    public static function createSubscription($email): ?string
    {
        $planId = config("paypal_webhook.paypal_plan_id");
        $clientId = config("paypal_webhook.paypal_client_id");
        $clientSecret = config("paypal_webhook.paypal_client_secret");
        $environment = config("paypal_webhook.paypal_environment");

        $paypalRequest = new SubscriptionCreateRequest($planId);
        $paypalRequest->setPayerEmail($email);

        if($environment === 'sandbox') {
            $environmentObj = new SandboxEnvironment($clientId, $clientSecret);
        }else {
            $environmentObj = new ProductionEnvironment($clientId, $clientSecret);
        }

        $client = new PayPalHttpClient($environmentObj);

        $response = $client->execute($paypalRequest);
        foreach($response->result->links as $link) {
            if($link->rel === 'approve') {
                return $link->href;
            }
        }
    }

    public static function addTransaction($data): bool
    {
        $validator = Validator::make($data, [
            'resource.billing_agreement_id' => 'required|min:1|max:255',
            'resource.id' => 'required|min:1|max:255|unique:paypal_transactions,paypal_id',
            'resource.amount.total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            Log::info(json_encode($errors->all()));
            return false;
        }

        $billingAgreementId = $data['resource']['billing_agreement_id'];
        $transactionId = $data['resource']['id'];
        $totalAmount = self::convertMoneyToDB($data['resource']['amount']['total']);

        DB::beginTransaction();
        try {
            $subscription = PaypalSubscription::firstOrCreate(
                ['billing_agreement_id' => $billingAgreementId],
                [
                    'status' => 'Active',
                    'total_value' => 0,
                ]
            );

            $ordinal = intval($subscription->paypalTransactions->max('ordinal')) + 1;

            PaypalTransaction::create([
                'paypal_subscription_id' => $subscription->id,
                'paypal_id' => $transactionId,
                'total_amount' => $totalAmount,
                'ordinal' => $ordinal,
            ]);

            $subscription->total_value += $totalAmount;
            $subscription->save();

        } catch (\Exception $e) {
            DB::rollBack();

            Log::info('Error - ' . $e->getMessage() . ' in ' . $e->getFile() . ', line ' . $e->getLine());

            return false;
        }

        DB::commit();

        return true;
    }

    public static function convertMoneyToDB($decimalAmount): int
    {
        return intval($decimalAmount * 100);
    }

    public static function cancelSubscription($id): bool
    {
        try {
            $subscription = PaypalSubscription::where('billing_agreement_id', $id)->first();
            $subscription->status = 'Cancelled';
            $subscription->save();
        } catch (\Exception $e) {
            Log::info('Error - ' . $e->getMessage() . ' in ' . $e->getFile() . ', line ' . $e->getLine());

            return false;
        }

        return true;
    }
}
