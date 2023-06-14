<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

trait InAppBrowsingAware
{
    protected function inAppBrowsing(Request $request): bool
    {
        $agent = new Agent();

        $agentName = trim($request->header('User-Agent').' '.($agent->isRobot() ? $agent->robot() : ''));

        return preg_match("/Line\//i", $agentName) || preg_match('/facebook/i', $agentName);
    }
}
