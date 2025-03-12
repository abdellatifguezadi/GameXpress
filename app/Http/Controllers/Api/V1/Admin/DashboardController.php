<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DashboardController extends BaseController
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
            'low_stock_products' => $this->getLowStockProducts(),
            'recent_products' => $this->getRecentProducts(),
            'stock_alerts' => $this->getStockAlerts(),
        ];

        return $this->sendResponse($stats, 'Dashboard statistics retrieved successfully');
    }

    private function getLowStockProducts()
    {
        $lowStockThreshold = 5;

        $lowStockProducts = Product::where('stock', '<=', $lowStockThreshold)
            ->where('status', 'available')
            ->with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'category' => $product->category ? $product->category->name : 'No Category'
                ];
            });



        if ($lowStockProducts->isNotEmpty()) {
            $this->sendLowStockNotifications($lowStockProducts);
        }

        return $lowStockProducts;
    }

    private function getRecentProducts()
    {
        return Product::with('category')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category' => $product->category->name,
                    'created_at' => $product->created_at->format('Y-m-d H:i:s')
                ];
            });
    }

    private function getStockAlerts()
    {
        return [
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)
                ->where('stock', '<=', 5)
                ->count(),
        ];
    }

    private function sendLowStockNotifications($lowStockProducts)
    {
        $admins = User::role('super_admin')->get();

        if ($admins->isEmpty()) {
            Log::warning('No admin users found to send notifications to');
            return;
        }

        foreach ($admins as $admin) {
            Log::info('Sending notification to admin:', ['email' => $admin->email]);
            $admin->notify(new LowStockNotification($lowStockProducts));
        }

    }
}
