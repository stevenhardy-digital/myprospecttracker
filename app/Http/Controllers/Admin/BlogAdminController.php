<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogAdminController extends Controller
{
    public function index() {
        return view('admin.posts.index', ['posts' => BlogPost::latest()->get()]);
    }

    public function create() {
        return view('admin.posts.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required',
            'excerpt' => 'required',
            'body' => 'required',
        ]);

        BlogPost::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
        ]);

        return redirect()->route('admin.posts')->with('success', 'Blog post published!');
    }
}

