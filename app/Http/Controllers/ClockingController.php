<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClockingController extends Controller
{
    public function index()
    {
        return view('clocking.index');
    }
}
