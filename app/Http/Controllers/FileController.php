<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function load(Request $request)
    {
        Log:info('performing loading');

        $path = $request->file('file')->store('uploads', 'public');

        return view('loader', compact($path));
    }
}
