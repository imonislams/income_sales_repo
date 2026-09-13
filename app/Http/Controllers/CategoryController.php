<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Category::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        });

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->input('search')}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $categories = $query->orderBy('is_default', 'desc')->orderBy('name')->paginate(15)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_default'] = false;

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $userId = auth()->id();

        $category = Category::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $userId = auth()->id();

        $category = Category::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->findOrFail($id);

        if ($category->is_default && $category->user_id === null) {
            // If default system category, create user custom category
            Category::create([
                'user_id' => $userId,
                'name' => $request->validated('name'),
                'type' => $request->validated('type'),
                'is_default' => false,
            ]);
        } else {
            $category->update($request->validated());
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $userId = auth()->id();

        $category = Category::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->findOrFail($id);

        // Check if referenced in user's transactions
        $inUse = Transaction::where('user_id', $userId)
            ->where('category', $category->name)
            ->exists();

        if ($inUse) {
            return back()->with('error', 'Cannot delete category because it is currently used in transactions.');
        }

        if ($category->is_default && $category->user_id === null) {
            return back()->with('error', 'Default system categories cannot be deleted.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
