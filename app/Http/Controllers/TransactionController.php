<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::where('user_id', $userId);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
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

        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest('date')->oldest('id');
        } elseif ($sort === 'amount_high') {
            $query->orderBy('amount', 'desc');
        } elseif ($sort === 'amount_low') {
            $query->orderBy('amount', 'asc');
        } else {
            $query->latest('date')->latest('id');
        }

        $transactions = $query->paginate(15)->withQueryString();

        $categories = Category::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('transactions.index', compact('transactions', 'categories', 'paymentMethods'));
    }

    public function show($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $userId = auth()->id();
        $transaction = Transaction::where('user_id', $userId)->findOrFail($id);

        $categories = Category::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('transactions.edit', compact('transaction', 'categories', 'paymentMethods'));
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $transaction->update($data);

        return redirect()->route('transactions.show', $transaction->id)->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
