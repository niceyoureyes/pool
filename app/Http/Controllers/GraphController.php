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
        $label_x = "X label";
        $label_y = "Y label";

        if( $request->has('label_x') )
            $label_x = $request->input('label_x');

        if( $request->has('label_y') )
            $label_y = $request->input('label_y');

        if( $request->has('input_data') )
            $input_data = json_encode($request->input('input_data'));
        
        return view('graph', compact('input_data', 'label_x', 'label_y') );
    }
}
