<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(Request $req)
    {
        $req->validate([
            // Ganti username ke email dan pastikan formatnya email
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:5',
        ]);

        Admin::create([
            'email' => $req->email, // Sesuaikan kolom database
            'password' => Hash::make($req->password),
        ]);

        return redirect('/admin/login')->with('success', 'Admin registered');
    }

    public function login(Request $req)
    {
        // Cari admin berdasarkan email, bukan username
        $admin = Admin::where('email', $req->email)->first();

        if (!$admin || !Hash::check($req->password, $admin->password)) {
            return back()->with('error', 'Invalid login');
        }

        // SESSION FIX → gunakan "admin_logged_in"
        session([
            'admin_logged_in' => true,
            'admin_id' => $admin->id
        ]);

        return redirect('/admin');
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_id']);
        return redirect('/admin/login');
    }
}