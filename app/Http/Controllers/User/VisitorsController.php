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
            session()->put('user',[
                    'name'  => 'Пользователь',
                    'email' => '',
                    'tel'   => '',
                    'session' => session()->getId(),
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
        $arr = session()->all();

        foreach ($arr as $key=>$item)
        {
            $cart = [];
            if(strpos($key, 'cart')>0 && count($item)>0 )
            {
                $cart = [$key => $item];
            }

        }
        return count($cart)>0? ($cart) : 0;
        //return response()->json(session('1DkjYnU0Dstrrq1LNMNG2GfWGvLtKlGzrHgKe9mD_cart_items'),200);
    }

    public function setNameVisitors($name, $email, $tel)
    {
        session()->put('user',[
            'name'  => $name,
            'email' => $email,
            'tel'   => $tel,
            'session' => session()->getId(),
        ]);
    }

    public function getSessionVisitor()
    {
        return session('user')['session'];
    }
    public function getVisitor()
    {
        return session('user');
    }

    public function deleteVisitor()
    {
        session()->forget('user');
    }
}
