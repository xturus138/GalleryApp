<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PeopleController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('people.index', compact('users'));
    }
}