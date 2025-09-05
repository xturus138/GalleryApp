@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-sm">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center md:text-left">Profile</h1>
    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
        <div class="flex-shrink-0">
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : '/placeholder-user.jpg' }}" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-gray-100">
        </div>
        <div class="flex-1 text-center md:text-left">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $user->name }}</h2>
            <div class="mb-6">
                <span class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm font-medium shadow-sm">PIN: *****</span>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-200">Edit Profile</a>
        </div>
    </div>
</div>
@endsection
