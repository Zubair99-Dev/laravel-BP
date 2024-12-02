<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.settings');
    }
    
    public function updateCaptchaSetting(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'status' => 'required|in:0,1', // Ensure the status is either 0 or 1
        ]);
    
        // Update the setting in the database
        $status = $request->status;
    
        DB::table('settings')
            ->where('key', 'captcha_enabled')
            ->update(['value' => $status]);
    
        // Return a success response
        return response()->json(['success' => true]);
    }
    
}
