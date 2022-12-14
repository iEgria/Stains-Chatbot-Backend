<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;

class IntroController extends Controller
{
    public function store(Request $request)
    {
        return Nasabah::create(['identity' => $request->clientId, 'name' => $request->clientName]);
    }
}
