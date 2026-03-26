<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;

class FormController extends Controller
{
    // Show the form
    public function index()
    {
        return view("form");
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:100",
            "email" => "required|email",
            "phone" => "required|string|max:15",
            "message" => "required|string|max:500",
            "photo" => "nullable|string",
        ]);

        //now submit the form save to postgresql

        Submission::create([
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "message" => $request->message,
            "photo" => $request->photo,
        ]);

        return back()->with("success", "Form submitted successfully!");
    }
}
