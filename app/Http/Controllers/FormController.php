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

    // Handle form submission
    public function submit(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:15',
            'message' => 'required|string|max:500',
        ]);

        // For now just return success
        // Later we will save to DB
        return back()->with('success', 'Form submitted successfully!');
    }
}
