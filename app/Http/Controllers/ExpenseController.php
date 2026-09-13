<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::where('user_id', $userId)->where('type', 'expense');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }

        $expenses = $query->latest('date')->latest('id')->paginate(15)->withQueryString();

        $categories = Category::where('type', 'expense')
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('expense.index', compact('expenses', 'categories', 'paymentMethods'));
    }

    public function create()
    {
        $userId = auth()->id();

        $categories = Category::where('type', 'expense')
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('expense.create', compact('categories', 'paymentMethods'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['type'] = 'expense';

        Transaction::create($data);

        return redirect()->route('expense.index')->with('success', 'Expense added successfully.');
    }

    public function show($id)
    {
        $expense = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->findOrFail($id);

        return view('expense.show', compact('expense'));
    }

    public function edit($id)
    {
        $userId = auth()->id();

        $expense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->findOrFail($id);

        $categories = Category::where('type', 'expense')
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('expense.edit', compact('expense', 'categories', 'paymentMethods'));
    }

    public function update(UpdateExpenseRequest $request, $id)
    {
        $expense = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->findOrFail($id);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['type'] = 'expense';

        $expense->update($data);

        return redirect()->route('expense.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->findOrFail($id);

        $expense->delete();

        return redirect()->route('expense.index')->with('success', 'Expense deleted successfully.');
    }
}
