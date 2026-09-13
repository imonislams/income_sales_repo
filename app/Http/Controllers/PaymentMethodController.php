<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        });

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->input('search')}%");
        }

        $paymentMethods = $query->orderBy('is_default', 'desc')->orderBy('name')->paginate(15)->withQueryString();

        return view('payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('payment-methods.create');
    }

    public function store(StorePaymentMethodRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_default'] = false;

        PaymentMethod::create($data);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method created successfully.');
    }

    public function edit($id)
    {
        $userId = auth()->id();

        $paymentMethod = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->findOrFail($id);

        return view('payment-methods.edit', compact('paymentMethod'));
    }

    public function update(UpdatePaymentMethodRequest $request, $id)
    {
        $userId = auth()->id();

        $paymentMethod = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->findOrFail($id);

        if ($paymentMethod->is_default && $paymentMethod->user_id === null) {
            PaymentMethod::create([
                'user_id' => $userId,
                'name' => $request->validated('name'),
                'is_default' => false,
            ]);
        } else {
            $paymentMethod->update($request->validated());
        }

        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $userId = auth()->id();

        $paymentMethod = PaymentMethod::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->findOrFail($id);

        $inUse = Transaction::where('user_id', $userId)
            ->where('payment_method', $paymentMethod->name)
            ->exists();

        if ($inUse) {
            return back()->with('error', 'Cannot delete payment method because it is currently used in transactions.');
        }

        if ($paymentMethod->is_default && $paymentMethod->user_id === null) {
            return back()->with('error', 'Default system payment methods cannot be deleted.');
        }

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }
}
