<?php

namespace Backstage\UserManagement\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebTrafficDetected
//  implements ShouldBroadcast // (if needed for real-time)
{
    use Dispatchable, SerializesModels;

    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
