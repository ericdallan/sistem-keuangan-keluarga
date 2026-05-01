<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\User;
use App\Models\Expense;
use App\Livewire\Expenses\Create;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_expense()
    {
        $user = User::factory()->child()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('amount', 100000)
            ->set('description', 'Beli makan')
            ->set('date', now()->format('Y-m-d'))
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id, 
            'amount' => 100000,
            'description' => 'Beli makan',
            'status' => 'pending',
        ]);
    }

    public function test_create_expense_validation_required()
    {
        $user = User::factory()->child()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->call('save')
            ->assertHasErrors(['amount', 'description', 'date']);
    }

    public function test_user_only_sees_their_own_expenses()
    {
        $user = User::factory()->child()->create();
        $otherUser = User::factory()->child()->create();

        Expense::factory()->create([
            'user_id' => $otherUser->id,
            'description' => 'Punya orang lain'
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Expenses\Index::class)
            ->assertDontSee('Punya orang lain');
    }

    public function test_admin_can_approve_expense()
    {
        $admin = User::factory()->admin()->husband()->create();

        $expense = Expense::factory()->create([
            'status' => 'pending'
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Expenses\Index::class)
            ->call('confirmAction', $expense->uuid_expenses, 'approve')
            ->call('executeAction');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => 'approved'
        ]);
    }

    public function test_user_can_delete_expense()
    {
        $user = User::factory()->child()->create();

        $expense = Expense::factory()->create([
            'user_id' => $user->id
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Expenses\Index::class)
            ->call('confirmDelete', $expense->uuid_expenses)
            ->call('destroy');

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id
        ]);
    }
}
