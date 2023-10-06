<?php

namespace App\Http\Controllers;

use App\Events\CartConfirmEvent;
use App\Models\OfferUser;
use App\Models\User;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;


class UserCartController extends Controller
{
    public function index()
    {

    }

    public function createOffer(Request $request)
    {
        $offer = OfferUser::create([
            'offer_id'=>uniqid(),
            'user_id'=>User::where('email', '=', Auth::user()->email)->firstOrFail()->id,
        ]);
        $offer->save();
        $offer_id = $offer->id;

        $lidArray = json_decode($request->ozon_id, true);

        foreach ($lidArray as $lid)
        {
            $lidAdd = UserCart::create([
                'ozon_id' => $lid['ozon_id'],
                'user_id' => User::where('email', '=', Auth::user()->email)->firstOrFail()->id,
                'quantity' => $lid['quantity'],
                'price' => $lid['price'],
                'total_price' => $lid['quantity'] * $lid['price'],
                'cdek_id' => '1',
                'cdek_info' => $request['input_delivery_city'] . ' ' .$request['input_delivery_adress_cdek'],
                'delivery_price' => $request['input_delivery_price'],
                'offer_id' => $offer_id,
            ]);
            $lidAdd->save();
        }

        $message = '<h1 style="color: #6f42c1"><b>Здравствуйте, '.Auth::user()->name.'!</b></h1>';
        $message .= '<p>Мы рады сообщить Вам о том, что Ваш заказ, успешно оформлен.</p>';
        $message .= '<p>Ниже приведены детали Вашего заказа:</p>';
        $message .= '<ul><li class="h4"> Номер заказа: <b style="color: mediumvioletred"> '.$offer_id.'</b></li>';
        $message .= '<li>Дата оформления: '.OfferUser::where('id', $offer_id)->firstOrFail()->created_at.'</li>';
        $message .= '<li class="h4">Сумма заказа:<b style="color: mediumvioletred">#TOTAL#</b> </li></ul>';
        $message .= '<h2 style="color: #6f42c1"><b>Информация о доставке</b></h2>';
        $message .= '<ul><li class="h4">Город доставки:<b style="color: mediumvioletred"> '.$request->input_delivery_city.'</b></li>';
        $message .= '<li class="h4">Адрес доставки:<b style="color: mediumvioletred"> '.$request->input_delivery_adress_cdek.'</b></li>';
        $message .= '<li class="h4">СТОИМОСТЬ ДОСТАВКИ: <b style="color: mediumvioletred">'.$request->input_delivery_price.'</b></li></ul>';
        $message .= '<div class="h4"><b style="color: mediumvioletred">Номер трэка для отслеживания посылки в на сайте СДЭК будет направлен на вашу почту: '.Auth::user()->email.', в течение 2 рабочих дней</b></div>';
            /*Для получения заказа, пожалуйста, следуйте нижеуказанным шагам:
Проверьте правильность указанной информации о заказе. Если у Вас возникнут вопросы, свяжитесь с нашей службой поддержки по телефону #SUPPORT_PHONE# или по электронной почте #SUPPORT_EMAIL#.
Посетите наш пункт выдачи заказов (ПВЗ) или курьерскую службу, указанную при оформлении заказа. Вам потребуется предъявить документ, удостоверяющий личность, и назвать номер Вашего заказа.
    После проверки Ваших документов сотрудник ПВЗ или курьер предоставит Вам Ваш заказ. Проверьте комплектацию и качество товаров, а также наличие всех необходимых документов (гарантийные талоны, инструкции и т.д.).
При отсутствии претензий к качеству и комплектации товара, подпишите документы, подтверждающие получение заказа.</p>'*/
        CartConfirmEvent::dispatch($message);
        return view('main.offer', ['result'=>$message]);
    }

    public function deleteOffer()
    {

    }

}
