<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Implementasi dashboard analytics
        return view('analytics.index');
    }
}
