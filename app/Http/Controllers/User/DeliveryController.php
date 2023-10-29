<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public array $delivery;

    public function __construct($deliveryCity, $deliveryAdressCdek, $deliveryPrice, $deliveryCdekid)
    {
        if(session()->has('delivery')){


        }else{
            session()->put('delivery',
                [
                    'delivery_city'  => '',
                    'delivery_adress_cdek' => '',
                    'delivery_price'   => '',
                    'CDEK_id'=>''
            ]);
        }
        $this->delivery = [
            'delivery',
            'session' => session()->getId(),
            [
                'delivery_city'  => $deliveryCity,
                'delivery_adress_cdek' => $deliveryAdressCdek,
                'delivery_price'   => $deliveryPrice,
                'CDEK_id'=> $deliveryCdekid
            ]
        ];

    }

    public function setDelivery($deliveryCity, $deliveryAdressCdek, $deliveryPrice, $deliveryCdekid)
    {
        session()->put('delivery',[
            'delivery_city'  => $deliveryCity,
            'delivery_adress_cdek' => $deliveryAdressCdek,
            'delivery_price'   => $deliveryPrice,
            'CDEK_id'=>$deliveryCdekid
        ]);
    }

    public function getSessionVisitor()
    {
        return session('session');
    }
    public function getDelivery()
    {
        return session()->all();
    }

    public function deleteDelivery()
    {
        session()->forget('delivery');
    }
}
