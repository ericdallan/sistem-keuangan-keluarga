<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_expense_sets_pending_status()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $service = new ExpenseService();

        $expense = $service->store([
            'amount' => 1000,
            'description' => 'Test',
            'date' => now(),
        ]);

        $this->assertEquals('pending', $expense->status);
    }

    public function test_approve_expense()
    {
        $expense = Expense::factory()->create([
            'status' => 'pending'
        ]);

        $service = new ExpenseService();

        $service->approve($expense);

        $this->assertEquals('approved', $expense->fresh()->status);
    }

    public function test_reject_expense()
    {
        $expense = Expense::factory()->create([
            'status' => 'pending'
        ]);

        $service = new ExpenseService();

        $service->reject($expense);

        $this->assertEquals('rejected', $expense->fresh()->status);
    }

    public function test_delete_expense_removes_file()
    {
        Storage::fake('public');

        $expense = Expense::factory()->create([
            'evidence_path' => 'evidence/test.jpg'
        ]);

        Storage::disk('public')->put('evidence/test.jpg', 'dummy');

        $service = new ExpenseService();

        $service->delete($expense);

        Storage::disk('public')->assertMissing('evidence/test.jpg');
    }
}
