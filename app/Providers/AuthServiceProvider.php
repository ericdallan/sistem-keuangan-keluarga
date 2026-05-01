<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\FundRequest;
use App\Models\Income;
use App\Policies\ExpensePolicy;
use App\Policies\FundRequestPolicy;
use App\Policies\IncomePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Expense::class     => ExpensePolicy::class,
        FundRequest::class => FundRequestPolicy::class,
        Income::class      => IncomePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
