<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GraphController extends Controller
{
    private $input_data = [['x' => 1, 'y' => 2], ['x' => 2, 'y' => 4], ['x' => 3, 'y' => 7]];

    public function get(Request $request)
    {
        $input_data = json_encode($this->input_data);
        
        return view('graph', compact('input_data') );
    }
}
