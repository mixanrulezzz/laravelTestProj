<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    public function index() {
        $users = Cache::remember('allUsers', '3600', function () {
            return User::all();
        });

        return view('pages.index', [
            'users' => $users,
        ]);
    }
}
