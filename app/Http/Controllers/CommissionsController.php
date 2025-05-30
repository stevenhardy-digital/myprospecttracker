<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommissionsController extends Controller
{
    public function index()
    {
        $commissions = auth()->user()->commissions()
            ->with(['referredUser' => function ($query) {
                $query->select('id', 'name', 'payment_status');
            }])
            ->latest()
            ->get();

        return view('billing.commissions', compact('commissions'));
    }
}
