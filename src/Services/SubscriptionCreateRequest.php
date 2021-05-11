<?php

namespace Andreytech\PaypalWebhook\Services;

use PayPalHttp\HttpRequest;

class SubscriptionCreateRequest extends HttpRequest
{
    function __construct($plan_id)
    {
        parent::__construct("/v1/billing/subscriptions?", "POST");
        $this->headers["Content-Type"] = "application/json";
        $this->headers["Prefer"] = 'return=representation';

        $this->body = [
            'plan_id' => $plan_id,
        ];

//        $environment = config("paypal_webhook.paypal_environment");
//        if($environment === 'sandbox') {
//            $this->body['start_time'] = date(DATE_ISO8601, strtotime('01-01-2020'));
//        }

        //{
        //      "plan_id": "P-2UF78835G6983425GLSM44MA",
        //      "start_time": "2020-02-27T06:00:00Z",
        //      "subscriber": {
        //        "name": {
        //          "given_name": "John",
        //          "surname": "Doe"
        //        },
        //        "email_address": "customer@example.com"
        //      },
        //      "application_context": {
        //        "brand_name": "example",
        //        "locale": "en-US",
        //        "shipping_preference": "SET_PROVIDED_ADDRESS",
        //        "user_action": "SUBSCRIBE_NOW",
        //        "payment_method": {
        //          "payer_selected": "PAYPAL",
        //          "payee_preferred": "IMMEDIATE_PAYMENT_REQUIRED"
        //        },
        //        "return_url": "https://example.com/returnUrl",
        //        "cancel_url": "https://example.com/cancelUrl"
        //      }
        //    }
    }

    public function prefer($prefer)
    {
        $this->headers["Prefer"] = $prefer;
    }

    public function setPayerEmail($email)
    {
        $this->body['subscriber']['email_address'] = $email;
    }
}
