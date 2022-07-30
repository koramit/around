<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $subscription = Subscription::query()->findByUnhashKey($request->input('subscription_id'))->firstOrFail();

        $request->user()->subscriptions()->toggle($subscription->id);

        return $request->input('subscribed')
            ? ['icon' => 'bell', 'label' => 'Subscribe']
            : ['icon' => 'bell-slash', 'label' => 'Unsubscribe'];
    }
}
