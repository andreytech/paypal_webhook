<?php

namespace Andreytech\PaypalWebhook\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @property-read int id
 * @property int billing_agreement_id
 * @property int status
 * @property int total_value
 *
 * @property-read Collection paypalTransactions
 *
 * @mixin Builder
 *
 */
class PaypalSubscription extends Model
{
    /**
     * fillable attributes.
     * @var array
     */
    protected $fillable = [
        'billing_agreement_id',
        'status',
        'total_value',
    ];

    public function paypalTransactions(): HasMany
    {
        return $this->hasMany(PaypalTransaction::class);
    }
}
