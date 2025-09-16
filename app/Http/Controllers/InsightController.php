<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function index()
    {
        // Dummy data for visualization
        $stats = [
            'total_images' => 150,
            'total_size_gb' => 2.5,
            'total_size_mb' => 2560,
            'average_size_mb' => 17.07,
            'storage_used_percent' => 75,
            'recent_uploads' => 12,
        ];

        return view('insight.index', compact('stats'));
    }
}