<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Let's pass the user to a dashboard view depending on his role
        // For now, we assume it's an Etudiant view
        return view('dashboard', compact('user'));
    }
}