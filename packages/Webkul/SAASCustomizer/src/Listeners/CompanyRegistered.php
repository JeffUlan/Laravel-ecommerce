<?php

namespace Webkul\SAASCustomizer\Listeners;

use Company;
use Illuminate\Support\Facades\Mail;
use Webkul\SAASCustomizer\Mail\NewCompanyNotification;

/**
 * New company registered events handler
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CompanyRegistered
{
    public function handle()
    {
        $company = Company::getCurrent();

        foreach(config('purge-pool') as $key => $pool) {
            $poolInstance = app($pool);

            $poolInstance->createPreOrderData($company->id);
        }

        Mail::queue(new NewCompanyNotification($company));
    }
}