<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config(['services.mailgun.secret'=>"cbd00314a354ff445c93cd6ba555590d-2bab6b06-22ae5f2f",
                'services.mailgun.domain'=>"sandbox72bb628e93af4ab4827f59a922a99842.mailgun.org",
                'services.mailgun.endpoint'=>"api.mailgun.net"
                ]); 
    }
}
