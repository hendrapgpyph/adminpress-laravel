<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentationCon extends Controller
{
    public function index()
    {
        return view('doc.index');
    }
}
