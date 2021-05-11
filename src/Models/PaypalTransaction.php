<?php

namespace Andreytech\PaypalWebhook\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @property-read int id
 * @property int paypal_subscription_id
 * @property int total_amount
 * @property int ordinal
 *
 * @property-read PaypalSubscription paypalSubscription
 *
 * @mixin Builder
 *
 */
class PaypalTransaction extends Model
{
    /**
     * fillable attributes.
     * @var array
     */
    protected $fillable = [
        'paypal_subscription_id',
        'paypal_id',
        'total_amount',
        'ordinal',
    ];

    public function paypalSubscription(): BelongsTo
    {
        return $this->belongsTo(PaypalSubscription::class);
    }
}
