<?php

namespace Backstage\UserManagement\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class WebTrafficDetected
//  implements ShouldBroadcast // (if needed for real-time)
{
    use Dispatchable;
    use SerializesModels;

    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
