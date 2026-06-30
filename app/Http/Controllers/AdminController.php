<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin')
            abort(403);
    }

    public function dashboard()
    {
        $this->checkAdmin();

        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalWarungs = Warung::where('is_verified', true)->count();
        $pendingWarungs = Warung::where('is_verified', false)->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');
        $recentOrders = Order::with(['user', 'warung'])->latest()->take(5)->get();
        $warungsNeedVerify = Warung::where('is_verified', false)->with('user')->latest()->take(5)->get();

        $userGrowthWeekly = $this->getUserGrowth('week');
        $userGrowthMonthly = $this->getUserGrowth('month');
        $transactionWeekly = $this->getTransactionStats('week');
        $transactionMonthly = $this->getTransactionStats('month');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalWarungs',
            'pendingWarungs',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'warungsNeedVerify',
            'userGrowthWeekly',
            'userGrowthMonthly',
            'transactionWeekly',
            'transactionMonthly'
        ));
    }

    private function getUserGrowth($period)
    {
        $labels = [];
        $data = [];

        if ($period === 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('d M');
                $data[] = User::where('role', '!=', 'admin')
                    ->whereDate('created_at', $date->toDateString())
                    ->count();
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->translatedFormat('M Y');
                $data[] = User::where('role', '!=', 'admin')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getTransactionStats($period)
    {
        $labels = [];
        $counts = [];
        $totals = [];

        if ($period === 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('d M');
                $counts[] = Order::whereDate('created_at', $date->toDateString())->count();
                $totals[] = Order::whereDate('created_at', $date->toDateString())
                    ->where('status', 'selesai')
                    ->sum('total_price');
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->translatedFormat('M Y');
                $counts[] = Order::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $totals[] = Order::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'selesai')
                    ->sum('total_price');
            }
        }

        return ['labels' => $labels, 'counts' => $counts, 'totals' => $totals];
    }

    public function warungs()
    {
        $this->checkAdmin();
        $warungs = Warung::with('user')->latest()->get();
        return view('admin.verifikasi-warung', compact('warungs'));
    }

    public function verify($id)
    {
        $warung = Warung::findOrFail($id);
        $warung->update(['is_verified' => true]);
        return redirect()->back()->with('success', 'Warung berhasil diverifikasi!');
    }

    public function reject($id)
    {
        $warung = Warung::findOrFail($id);
        $warung->delete();
        return redirect()->back()->with('success', 'Warung berhasil ditolak dan dihapus.');
    }
    public function users()
    {
        $this->checkAdmin();
        $users = User::where('role', '!=', 'admin')->with('warung')->latest()->get();
        return view('admin.manage-user', compact('users'));
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
    public function transaksi()
    {
        $this->checkAdmin();
        $orders = Order::with(['user', 'warung'])->latest()->get();
        $totalRevenue = $orders->where('status', 'selesai')->sum('total_price');
        return view('admin.transaksi', compact('orders', 'totalRevenue'));
    }

    public function pengaturan()
    {
        $this->checkAdmin();
        $settings = [
            'platform_name' => Setting::get('platform_name', 'WarungKu'),
            'footer_text' => Setting::get('footer_text', '© 2026 WarungKu Universitas Mataram'),
            'max_warungs' => Setting::get('max_warungs', 50),
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
            'contact_wa' => Setting::get('contact_wa', ''),
            'contact_email' => Setting::get('contact_email', ''),
            'faq_link' => Setting::get('faq_link', ''),
            'api_key_email' => Setting::get('api_key_email', ''),
            'api_key_wa' => Setting::get('api_key_wa', ''),
        ];
        return view('admin.pengaturan', compact('settings'));
    }

    public function updatePengaturan(Request $request)
    {
        $this->checkAdmin();

        $keys = [
            'platform_name',
            'footer_text',
            'max_warungs',
            'maintenance_mode',
            'contact_wa',
            'contact_email',
            'faq_link',
            'api_key_email',
            'api_key_wa'
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

}