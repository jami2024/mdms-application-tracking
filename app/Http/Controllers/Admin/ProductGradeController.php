<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductGrade;
use Illuminate\Http\Request;

class ProductGradeController extends Controller
{
    public function index()
    {
        $grades = ProductGrade::orderBy('code')->paginate(15);
        return view('admin.settings.product-grades.index', compact('grades'));
    }

    public function create()
    {
        return view('admin.settings.product-grades.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:product_grades,code'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        ProductGrade::create($data);
        return redirect()->route('admin.product-grades.index')->with('status', 'Product grade created.');
    }

    public function edit(ProductGrade $productGrade)
    {
        return view('admin.settings.product-grades.edit', ['grade' => $productGrade]);
    }

    public function update(Request $request, ProductGrade $productGrade)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:product_grades,code,' . $productGrade->id],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $productGrade->update($data);
        return redirect()->route('admin.product-grades.index')->with('status', 'Product grade updated.');
    }

    public function destroy(ProductGrade $productGrade)
    {
        $productGrade->delete();
        return back()->with('status', 'Product grade deleted.');
    }
}
