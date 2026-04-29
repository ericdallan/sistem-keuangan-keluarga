<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\FundRequest;
use App\Policies\ExpensePolicy;
use App\Policies\FundRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Expense::class     => ExpensePolicy::class,
        FundRequest::class => FundRequestPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
