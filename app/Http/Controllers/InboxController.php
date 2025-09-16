<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        // Dummy notifications data
        $notifications = [
            [
                'id' => 1,
                'title' => 'New comment on your image',
                'message' => 'John Doe commented on your photo "Sunset Beach"',
                'time' => '2 hours ago',
                'read' => false,
                'type' => 'comment',
                'user' => 'John Doe',
                'avatar' => '/placeholder-user.jpg'
            ],
            [
                'id' => 2,
                'title' => 'Asset shared with you',
                'message' => 'Jane Smith shared an asset with you',
                'time' => '1 day ago',
                'read' => true,
                'type' => 'share',
                'user' => 'Jane Smith',
                'avatar' => '/placeholder-user.jpg'
            ],
            [
                'id' => 3,
                'title' => 'Like on your asset',
                'message' => 'Mike Johnson liked your video "Mountain Adventure"',
                'time' => '3 days ago',
                'read' => false,
                'type' => 'like',
                'user' => 'Mike Johnson',
                'avatar' => '/placeholder-user.jpg'
            ],
            [
                'id' => 4,
                'title' => 'Folder update',
                'message' => 'Your folder "Travel Photos" has been updated',
                'time' => '5 days ago',
                'read' => true,
                'type' => 'folder',
                'user' => 'System',
                'avatar' => null
            ]
        ];

        return view('inbox.index', compact('notifications'));
    }
}