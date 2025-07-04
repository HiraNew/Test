<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\RecentView;
use App\Models\Subcategory;
use App\Models\User;
use App\View\Components\ProductCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    // public function toggleStatus(Product $product)
    // {
    //     $this->authorize('update', $product); // Optional: use policy
    //     $product->update(['status' => !$product->status]);
    //     return back()->with('success', 'Product status updated.');
    // }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $product = new Product(); // empty product object for create form
        return view('vendor.products.create',compact('product', 'categories', 'subcategories'));
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
        $data['extra5'] = $vendor->name;
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
          $this->authorize('update', $product); // optional

            $categories = Category::all();
            $extraImages = ProductImage::where('product_id',$product->id)->get();
            // dd($extraImages);
            $subcategories = Subcategory::all();
        return view('vendor.products.create', compact('product', 'categories', 'subcategories','extraImages'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'extra_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'sdescription' => 'required|string',
            'ldescription' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'extra1' => 'nullable|integer|max:10',
            'feild1' => 'nullable|string',
            'feild2' => 'nullable|string',
            'feild3' => 'nullable|string',
            'feild4' => 'nullable|string',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'weight' => 'nullable|string',
        ]);

        // Handle main image update
        if ($request->hasFile('image')) {
            // Optionally delete the old image from storage here
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Update product with validated data
        $product->update($data);

        // Handle extra images
        if ($request->hasFile('extra_images')) {
            // Delete old extra images from storage and database
            foreach ($product->extraImages as $extraImage) {
                \Storage::disk('public')->delete($extraImage->image_path); // Delete file
                $extraImage->delete(); // Delete DB record
            }

            // Store new extra images
            foreach ($request->file('extra_images') as $file) {
                $path = $file->store('product_images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully!');
    }


    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        // ProductCard
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    public function deleteExtraImage(ProductImage $image)
    {
        $vendor = auth()->guard('vendor')->user();

        // Ensure the image belongs to a product of this vendor
        if ($image->product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        // Delete image file from storage
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete image record from database
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }


    // Manging availabe order for vendor start
    public function vendorOrderList()
    {
        $vendor = auth('vendor')->user();

       $orders = Payment::with(['product', 'address', 'user']) // Add 'address' here
        ->where('vendor_id', $vendor->id)
        ->latest()
        ->get();
        // dd($orders->user);

        $deliveryPartners = Partner::all();
        return view('Vendor.orders.index', compact('orders', 'deliveryPartners'));
    }

    public function confirm($id)
    {
        $payment = Payment::where('vendor_id', auth('vendor')->id())->findOrFail($id);
        $payment->update(['status' => 'confirmed']);

        return back()->with('success', 'Order confirmed.');
    }

    public function ship(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'delivery_partner_id' => 'required|exists:partners,id',
            'feild1' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s]+$/',
                'max:20'
            ],

        ]);


        $payment = Payment::where('id', $request->payment_id)
        ->where('vendor_id', auth('vendor')->id())
        ->where('status', 'confirmed')
        ->firstOrFail();
        // dd($payment);
        $payment->update([
            'status' => 'shipped',
            'delivery_partner_id' => $request->delivery_partner_id,
            'feild1' => $request->feild1,
        ]);

        return redirect()->route('vendor.orders.index')->with('success', 'Order marked as shipped.');
    }

    // sending notification on the basis user order item for particular order either canceled order or delivered
    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'payment_id'   => 'nullable|integer',
            'user_id'      => 'nullable|integer',
            'product_id'   => 'required|integer',
            'status'       => 'nullable|string',
            'message'      => 'required|string',
            'send_to_all'  => 'nullable|boolean',
        ]);

        $vendor = auth()->guard('vendor')->user();

        if ($request->boolean('send_to_all')) {
            // Send notification to all customers of the vendor
            $userIds = Payment::where('vendor_id', $vendor->id)
                            ->pluck('user_id')
                            ->unique();

            foreach ($userIds as $userId) {
                Notification::create([
                    'user_id'      => $userId,
                    'product_id'   => $validated['product_id'],
                    'sender_name'  => $vendor->name,
                    'notification' => $validated['message'],
                    'status'       => $validated['status'] ?? null,
                ]);
            }

            return redirect()->back()->with('success', '✅ Notification sent to all your customers.');
        }

        // Otherwise send to a single user
        if (!$validated['user_id']) {
            return redirect()->back()->with('error', '❌ User ID is required if not sending to all.');
        }

        Notification::create([
            'user_id'      => $validated['user_id'],
            'product_id'   => $validated['product_id'],
            'sender_name'  => $vendor->name,
            'notification' => $validated['message'],
            'status'       => $validated['status'] ?? null,
        ]);

        return redirect()->back()->with('success', '✅ Notification sent successfully.');
    }

    public function sentNotifications()
    {
        $vendor = auth()->guard('vendor')->user();

        // Group notifications by product_id for this vendor
        $productNotifications = \App\Models\Notification::where('sender_name', $vendor->name)
            ->with('product')
            ->selectRaw('product_id, COUNT(*) as notification_count')
            ->groupBy('product_id')
            ->get();

        return view('Vendor.Notification.notification', compact('productNotifications'));
    }
    public function productNotifications($productId)
    {
        $vendor = auth()->guard('vendor')->user();

        $notifications = \App\Models\Notification::with('user')
            ->where('sender_name', $vendor->name)
            ->where('product_id', $productId)
            ->latest()
            ->get();

        return response()->json($notifications);
    }





    // user list who belong to vendor id
    public function user()
    {
        $vendorId = auth()->guard('vendor')->user()->id;

        // Get all payments for this vendor, with product and user
        $payments = Payment::with(['product', 'user'])
            ->where('vendor_id', $vendorId)
            ->get();

        $groupedCustomers = $payments->groupBy('user_id')->map(function ($userPayments) {
            $user = $userPayments->first()->user;

            return [
                'user' => $user,
                'products' => $userPayments->pluck('product')->unique('id')->values(),
                'payments' => $userPayments->values(), // Include all payments for this user
            ];
        });


        // Also fetch all products owned by this vendor
        $vendorProducts = \App\Models\Product::where('vendor_id', $vendorId)->get();

        return view('vendor.users.index', [
            'customers' => $groupedCustomers,
            'vendorProducts' => $vendorProducts
        ]);

    }

    public function analysis()
    {
        $vendorId = auth()->guard('vendor')->user()->id;

        $recentViews = \App\Models\RecentView::whereHas('product', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
        ->with('product')
        ->select('product_id')
        ->selectRaw('COUNT(DISTINCT user_id) as unique_user_count')
        ->groupBy('product_id')
        ->get();

        return view('Vendor.ProductAnalysis.views', compact('recentViews'));
    }


    public function productAnalysis($productId)
    {
        $lastWeek = now()->subWeek();
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        $data = [
            'today' => RecentView::with('user')
                ->where('product_id', $productId)
                ->whereDate('viewed_at', now())->get(),

            'yesterday' => RecentView::with('user')
                ->where('product_id', $productId)
                ->whereDate('viewed_at', now()->subDay())->get(),

            'last_week' => RecentView::with('user')
                ->where('product_id', $productId)
                ->where('viewed_at', '>=', $lastWeek)->get(),

            'product' => \App\Models\Product::find($productId)->name ?? 'Product',
        ];

        return response()->json($data);
    }



    // Managign available order for vendor end


}
