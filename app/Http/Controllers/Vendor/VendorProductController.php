<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VendorProductController extends Controller
{
    public function index()
    {
        $vendor = auth()->guard('vendor')->user();
        // dd($vendor->id);
        $products = $vendor->products()->latest()->get();
        // dd($products);
        return view('Vendor.productList', compact('products'));
    }

    public function toggleStatus(Product $product)
    {
        $this->authorize('update', $product); // Optional: use policy
        $product->update(['status' => !$product->status]);
        return back()->with('success', 'Product status updated.');
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('vendor.products.create',compact('categories','subcategories'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'extra_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'sdescription' => 'required|string',
            'ldescription' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'extra1' => 'nullable|integer|max:10',
            'feild1' => 'nullable|string',
            'feild2' => 'nullable|string',
            'feild3' => 'nullable|string',
            'feild4' => 'nullable|string',

        ]);

        $vendor = auth()->guard('vendor')->user();
        // dd($vendor);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['vendor_id'] = $vendor->id;
        $data['status'] = 'inactive';
        $data['created_by'] = $vendor->id;
        $data['extra1'] = $request->extra1 ?? 0;
        $data['feild1'] = $request->feild1 ?? null;
        $data['feild2'] = $request->feild2 ?? null;
        $data['feild3'] = $request->feild3 ?? null;
        $data['feild4'] = $request->feild4 ?? null;

        $product = Product::create($data);

        if ($request->hasFile('extra_images')) {
            foreach ($request->file('extra_images') as $file) {
                $path = $file->store('product_images', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('vendor.products.index')->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product); // Optional
        return view('vendor.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

}
