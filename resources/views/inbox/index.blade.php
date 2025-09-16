@extends('layouts.app')

@section('title', 'Inbox - GalleryApp')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Inbox</h1>
        <div class="text-sm text-gray-500">Total Notifications: {{ count($notifications) }}</div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="divide-y divide-gray-200">
            @foreach($notifications as $notification)
            <div class="px-6 py-4 hover:bg-gray-50 @if(!$notification['read']) bg-blue-50 border-l-4 border-l-blue-500 @endif">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $notification['avatar'] ? asset($notification['avatar']) : '/placeholder-user.jpg' }}" alt="{{ $notification['user'] }}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</h3>
                            <span class="text-sm text-gray-500 ml-2">{{ $notification['time'] }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                        @if($notification['read'])
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Read
                            </span>
                        </div>
                        @else
                        <div class="mt-2">
                            <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Mark as Read
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection