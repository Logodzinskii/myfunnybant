<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class VisitorsController extends Controller
{
    public array $visitor;

    public function __construct($name, $email, $tel)
    {
        if(session()->has('user')){


        }else{
            session([
                'user',
                'session' => session()->getId(),
                [
                    'name'  => 'Пользователь',
                    'email' => '',
                    'tel'   => '',
                ]
            ]);
        }
        $this->visitor = [
            'user',
            'session' => session()->getId(),
            [
                'name'  => $name,
                'email' => $email,
                'tel'   => $tel,
                'status'=> session()->all(),
            ]
        ];

    }

    public function visitor()
    {
        return response()->json($this->visitor, 200);
    }

    public function setNameVisitors($name, $email, $tel)
    {
        session()->put('1',[
            'name'  => $name,
            'email' => $email,
            'tel'   => $tel,
        ]);
    }

    public function getSessionVisitor()
    {
        return session('session');
    }
    public function getVisitor()
    {
        return session('1');
    }

    public function deleteVisitor()
    {
        session()->forget('user');
    }
}
