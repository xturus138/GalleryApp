@extends('layouts.app')

@section('title', 'Insight - GalleryApp')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Insights</h1>
        <div class="text-sm text-gray-500">Updated: {{ now()->format('M d, Y') }}</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Images Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Images</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_images'] }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Size Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Storage</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_size_gb'] }} GB</p>
                    <p class="text-sm text-gray-500">({{ $stats['total_size_mb'] }} MB)</p>
                </div>
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v4a2 2 0 002 2h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Size Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Average Size</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['average_size_mb'], 2) }} MB</p>
                </div>
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Storage Used Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Storage Used</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['storage_used_percent'] }}%</p>
                </div>
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h14a2 2 0 012 2v4a2 2 0 01-2 2M7 20h10"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['storage_used_percent'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Uploads Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Uploads</h2>
            <p class="text-gray-600 mb-4">Last 7 days: {{ $stats['recent_uploads'] }} images</p>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Today</span>
                    <span>3</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Yesterday</span>
                    <span>2</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>This week</span>
                    <span>{{ $stats['recent_uploads'] }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Stats</h2>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex justify-between"><span>Total folders:</span> <span>8</span></li>
                <li class="flex justify-between"><span>Shared assets:</span> <span>24</span></li>
                <li class="flex justify-between"><span>Likes given:</span> <span>156</span></li>
                <li class="flex justify-between"><span>Comments made:</span> <span>42</span></li>
            </ul>
        </div>
    </div>
</div>
@endsection