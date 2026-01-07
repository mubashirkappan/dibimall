<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
    public function contactUs(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'message' => 'required|string',
        ]);

        $contact = ContactUs::create($validated);

        return response()->json([
            'success' => 'Your message has been received. We will get back to you soon.',
            'data' => $contact,
        ], 201);
    }
}
