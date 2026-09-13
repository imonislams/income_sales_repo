<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Financial Dashboard');
    }

    public function test_user_can_create_income()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/income', [
            'amount' => 5000.50,
            'category' => 'Salary',
            'description' => 'Monthly Salary',
            'date' => now()->toDateString(),
            'payment_method' => 'Bank',
            'note' => 'Paid on time',
        ]);

        $response->assertRedirect(route('income.index'));
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 5000.50,
            'category' => 'Salary',
        ]);
    }

    public function test_user_can_create_expense()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/expense', [
            'amount' => 1200.00,
            'category' => 'Food',
            'description' => 'Supermarket Grocery',
            'date' => now()->toDateString(),
            'payment_method' => 'Cash',
        ]);

        $response->assertRedirect(route('expense.index'));
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 1200.00,
            'category' => 'Food',
        ]);
    }

    public function test_user_cannot_view_or_edit_another_users_transaction()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $transactionA = Transaction::create([
            'user_id' => $userA->id,
            'type' => 'income',
            'category' => 'Salary',
            'description' => 'Private Income',
            'amount' => 10000,
            'date' => now()->toDateString(),
        ]);

        // User B attempts to access User A's transaction
        $response = $this->actingAs($userB)->get("/transactions/{$transactionA->id}");
        $response->assertStatus(404);

        // User B attempts to edit User A's transaction
        $editResponse = $this->actingAs($userB)->get("/transactions/{$transactionA->id}/edit");
        $editResponse->assertStatus(404);

        // User B attempts to update User A's transaction
        $updateResponse = $this->actingAs($userB)->put("/transactions/{$transactionA->id}", [
            'type' => 'expense',
            'category' => 'Hack',
            'amount' => 100,
            'date' => now()->toDateString(),
        ]);
        $updateResponse->assertStatus(404);

        // User B attempts to delete User A's transaction
        $deleteResponse = $this->actingAs($userB)->delete("/transactions/{$transactionA->id}");
        $deleteResponse->assertStatus(404);
    }

    public function test_dashboard_totals_calculation()
    {
        $user = User::factory()->create();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'income',
            'category' => 'Salary',
            'amount' => 1000.00,
            'date' => now()->toDateString(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'expense',
            'category' => 'Food',
            'amount' => 300.00,
            'date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('700.00'); // 1000 - 300 Net Balance
    }
}
