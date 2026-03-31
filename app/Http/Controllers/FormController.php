<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Models\Submission;
use App\Models\LgdState;
use App\Models\LgdDistrict;
use App\Models\LgdSubdistrict;
use App\Models\LgdBlock;

class FormController extends Controller
{
    // Show the form
    public function index()
    {
        $states = LgdState::query()
            ->orderBy('state_name')
            ->get(['state_code', 'state_name']);

        return view('form', compact('states'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:100",
            "email" => "required|email",
            "phone" => "required|string|max:15",
            'state_code' => ['required', 'integer', 'exists:lgd_states,state_code'],
            'district_code' => [
                'required',
                'integer',
                Rule::exists('lgd_districts', 'district_code')->where(function ($query) use ($request) {
                    $query->where('state_code', $request->input('state_code'));
                }),
            ],
            'subdistrict_code' => [
                'nullable',
                'integer',
                Rule::exists('lgd_subdistricts', 'subdistrict_code')->where(function ($query) use ($request) {
                    $query->where('district_code', $request->input('district_code'));
                }),
            ],
            'block_code' => [
                'nullable',
                'integer',
                Rule::exists('lgd_blocks', 'block_code')->where(function ($query) use ($request) {
                    $query->where('district_code', $request->input('district_code'));
                }),
            ],
            "message" => "required|string|max:500",
            "photo" => "nullable|string",
        ]);

        //now submit the form save to postgresql

        Submission::create([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "phone" => $validated['phone'],
            'state_code' => $validated['state_code'],
            'district_code' => $validated['district_code'],
            'subdistrict_code' => $validated['subdistrict_code'] ?? null,
            'block_code' => $validated['block_code'] ?? null,
            "message" => $validated['message'],
            "photo" => $validated['photo'] ?? null,
        ]);

        return back()->with("success", "Form submitted successfully!");
    }

    public function districts(Request $request): JsonResponse
    {
        $request->validate([
            'state_code' => ['required', 'integer', 'exists:lgd_states,state_code'],
        ]);

        $districts = LgdDistrict::query()
            ->where('state_code', $request->integer('state_code'))
            ->orderBy('district_name')
            ->get(['district_code', 'district_name']);

        return response()->json($districts);
    }

    public function subdistricts(Request $request): JsonResponse
    {
        $request->validate([
            'district_code' => ['required', 'integer', 'exists:lgd_districts,district_code'],
        ]);

        $subdistricts = LgdSubdistrict::query()
            ->where('district_code', $request->integer('district_code'))
            ->orderBy('subdistrict_name')
            ->get(['subdistrict_code', 'subdistrict_name']);

        return response()->json($subdistricts);
    }

    public function blocks(Request $request): JsonResponse
    {
        $request->validate([
            'district_code' => ['required', 'integer', 'exists:lgd_districts,district_code'],
        ]);

        $blocks = LgdBlock::query()
            ->where('district_code', $request->integer('district_code'))
            ->orderBy('block_name')
            ->get(['block_code', 'block_name']);

        return response()->json($blocks);
    }
}
