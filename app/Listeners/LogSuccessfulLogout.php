<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

class LogSuccessfulLogout
{
    public function __construct(private Request $request) {}

    public function handle(Logout $event): void
    {
        if ($event->user) {
            activity('auth')
                ->performedOn($event->user)
                ->causedBy($event->user)
                ->withProperties([
                    'ip' => $this->request->ip(),
                    'user_agent' => $this->request->userAgent(),
                ])
                ->log('Sistemden çıkış yaptı');
        }
    }
}