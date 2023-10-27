<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorsController extends Controller
{

    public function visitor()
    {
        if(session()->has('user')){
            $sessinVsistor = session()->get('user');
        }else{
            $sessinVsistor = session('user',session()->getId());
        }
        return response()->json($sessinVsistor, 200);
    }
}
