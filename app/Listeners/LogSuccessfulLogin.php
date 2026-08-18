<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    public function __construct(private Request $request) {}

    public function handle(Login $event): void
    {
        // Spatie Activitylog ile giriş aktivitesini logluyoruz
        activity('auth')
            ->performedOn($event->user)
            ->causedBy($event->user)
            ->withProperties([
                'ip' => $this->request->ip(),
                'user_agent' => $this->request->userAgent(),
            ])
            ->log('Sisteme giriş yaptı');
    }
}