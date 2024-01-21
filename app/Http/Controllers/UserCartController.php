<?php

namespace App\Http\Controllers;

use App\Events\CartConfirmEvent;
use App\Models\OfferUser;
use App\Models\User;
use App\Models\UserCart;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use Darryldecode\Cart\Cart;

class UserCartController extends Controller
{
    public function index()
    {
        if(Auth::user()->role===1){
            return view('admin.panel.index');
        }
        if(!Auth::check()){
            return redirect('login');
        }else{
            $cart = OfferUser::where('user_id','=',Auth::user()->id)->get();
            $totalSum = UserCart::where('user_id','=',Auth::user()->id)->get();

            return view('main.allOffers', ['carts'=>$cart,
                'totalQuantity'=>'',
                'totalSum'=>$totalSum->sum('total_price')]);
        }


    }

    public function createOffer(Request $request)
    {
        try{
            $validated = $request->validate([
                'first_name'=>'required|min:3|max:255',
                'email'=>'required|email',
                'tel'=>'required|numeric|',
                'input_delivery_city'=>'required|min:2|max:255',
                'input_delivery_adress_cdek'=>'required|min:2|max:255',
                'input_delivery_price'=>'required|numeric|',
                'input_CDEK_id'=>'required|min:2|max:255',
                'ozon_id'=>'required|min:2|max:255',
            ]);
        }catch (ValidationException $e){
           die($e->getMessage());
        }
        /**
         * 1. Создаем пользователя.
         * 2. Создаем заказ пользователя
         * 3. Информацию с секретной ссылкой для подтверждения заказа высылаем на почту
         * 4. ждем 2 дня перехода по ссылке пользователя
         * 5. если подтвердил, то связываемся с покупателем утоняем заказ
         * 6. отправляем реквизиты для оплаты (админка менеджера сайта)
         */
        return $request->session()->all();
        /*$offer = OfferUser::create([
            'offer_id'=>uniqid(),
            'user_id'=>User::where('email', '=', Auth::user()->email)->firstOrFail()->id,
        ]);
        $offer->save();
        $offer_id = $offer->id;

        $lidArray = json_decode($request->input('ozon_id'), true);
        $hash = password_hash(Auth::user()->email, PASSWORD_DEFAULT);
        foreach ($lidArray as $lid)
        {
            $lidAdd = UserCart::create([
                'ozon_id' => $lid['ozon_id'],
                'user_id' => User::where('email', '=', Auth::user()->email)->firstOrFail()->id,
                'quantity' => $lid['quantity'],
                'price' => $lid['price'],
                'total_price' => $lid['quantity'] * $lid['price'],
                'cdek_id' => $request->input('input_CDEK_id'),
                'cdek_info' => $request->input('input_delivery_city') . ' ' .$request->input('input_delivery_adress_cdek'),
                'delivery_price' => $request->input('input_delivery_price'),
                'offer_id' => $offer_id,
                'status_offer'=>$hash
            ]);
            $lidAdd->save();
        }

        $userId = Auth::user();
        \Cart::session($userId);
        \Cart::session($userId)->clear();

        $message = '<h1 style="color: #6f42c1"><b>Здравствуйте, '.Auth::user()->name.'!</b></h1>';
        $message .= '<p>Мы рады сообщить Вам о том, что Ваш заказ, успешно оформлен.</p>';
        $message .= '<p>Ниже приведены детали Вашего заказа:</p>';
        $message .= '<ul><li class="h4"> Номер заказа: <b style="color: mediumvioletred"> '.$offer_id.'</b></li>';
        $message .= '<li>Дата оформления: '.OfferUser::where('id', $offer_id)->firstOrFail()->created_at.'</li>';
        $message .= '<li class="h4">Сумма заказа:<b style="color: mediumvioletred">#TOTAL#</b> </li></ul>';
        $message .= '<h2 style="color: #6f42c1"><b>Информация о доставке</b></h2>';
        $message .= '<ul><li class="h4">Город доставки:<b style="color: mediumvioletred"> '.$request->input('input_delivery_city').'</b></li>';
        $message .= '<li class="h4">Адрес доставки:<b style="color: mediumvioletred"> '.$request->input('input_delivery_adress_cdek').'</b></li>';
        $message .= '<li class="h4">СТОИМОСТЬ ДОСТАВКИ: <b style="color: mediumvioletred">'.$request->input('input_delivery_price').'</b></li></ul>';
        $confirm = '<h2>Для подтверждения заказа, на электронную почту '.Auth::user()->email.' направлена ссылка.</h2>';
        $confirm .= '<p>Зайдите в вашу электронную почту и перейдите по ссылке для подтверждения заказа и дальнейшей оплаты.</p>';
        $link = '<h2>Перейдите по ссылке для подтверждения заказа</h2>';
        $link .= 'https://myfunnybant/security/?link='.$hash;
        /*$message .= '<div class="h4"><b style="color: mediumvioletred">Номер трэка для отслеживания посылки на сайте СДЭК будет направлен на вашу почту: '.Auth::user()->email.', в течение 2 рабочих дней</b></div>';
            Для получения заказа, пожалуйста, следуйте нижеуказанным шагам:
Проверьте правильность указанной информации о заказе. Если у Вас возникнут вопросы, свяжитесь с нашей службой поддержки по телефону #SUPPORT_PHONE# или по электронной почте #SUPPORT_EMAIL#.
Посетите наш пункт выдачи заказов (ПВЗ) или курьерскую службу, указанную при оформлении заказа. Вам потребуется предъявить документ, удостоверяющий личность, и назвать номер Вашего заказа.
    После проверки Ваших документов сотрудник ПВЗ или курьер предоставит Вам Ваш заказ. Проверьте комплектацию и качество товаров, а также наличие всех необходимых документов (гарантийные талоны, инструкции и т.д.).
При отсутствии претензий к качеству и комплектации товара, подпишите документы, подтверждающие получение заказа.</p>'
        CartConfirmEvent::dispatch($message.$link);

        $res = UserCart::where('user_id', '=', Auth::user()->id)->sum('quantity');

        return $res;
        //return view('main.offer', ['result'=>$message.$confirm]);*/

    }

    public function deleteOffer(Request $request)
    {
        UserCart::findOrFail($request->id)->delete();
        return $request->id;
    }

}
