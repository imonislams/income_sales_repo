<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::where('user_id', $userId)->where('type', 'income');

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

        $incomes = $query->latest('date')->latest('id')->paginate(15)->withQueryString();

        $categories = Category::where('type', 'income')
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('income.index', compact('incomes', 'categories', 'paymentMethods'));
    }

    public function create()
    {
        $userId = auth()->id();

        $categories = Category::where('type', 'income')
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('income.create', compact('categories', 'paymentMethods'));
    }

    public function store(StoreIncomeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['type'] = 'income';

        Transaction::create($data);

        return redirect()->route('income.index')->with('success', 'Income added successfully.');
    }

    public function show($id)
    {
        $income = Transaction::where('user_id', auth()->id())
            ->where('type', 'income')
            ->findOrFail($id);

        return view('income.show', compact('income'));
    }

    public function edit($id)
    {
        $userId = auth()->id();

        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->findOrFail($id);

        $categories = Category::where('type', 'income')
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })->pluck('name')->unique();

        $paymentMethods = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('income.edit', compact('income', 'categories', 'paymentMethods'));
    }

    public function update(UpdateIncomeRequest $request, $id)
    {
        $income = Transaction::where('user_id', auth()->id())
            ->where('type', 'income')
            ->findOrFail($id);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['type'] = 'income';

        $income->update($data);

        return redirect()->route('income.index')->with('success', 'Income updated successfully.');
    }

    public function destroy($id)
    {
        $income = Transaction::where('user_id', auth()->id())
            ->where('type', 'income')
            ->findOrFail($id);

        $income->delete();

        return redirect()->route('income.index')->with('success', 'Income deleted successfully.');
    }
}
