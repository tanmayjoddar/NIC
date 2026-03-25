<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    // Show the form
    public function index()
    {
        return view('form');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:15',
            'message' => 'required|string|max:500',
            'picture' => 'required|string|base64',
        ]);

        return back()->with('success', 'Form submitted successfully!');
    }
}
