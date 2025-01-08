<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
class FacturAIController extends Controller
{
    public function index()
    {
        return view('facturai');
    }

}
