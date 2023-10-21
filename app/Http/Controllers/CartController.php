<?php

namespace App\Http\Controllers;

use App\Models\OfferUser;
use App\Models\OzonShopItem;
use App\Models\StatusPriceShopItems;
use App\Models\User;
use App\Models\UserCart;
use Darryldecode\Cart\Cart;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CartConfirmEvent;
use Exception;

class CartController extends Controller
{
    public $user;

    public function __construct()
    {

    }

    protected function getUser()
    {
        if(Auth::check()) {
            $sessId = Auth::user();
        }else{
            if(session()->has('user')){
                $sessId = session()->get('user');
            }else{
                $sessId = session('user',session()->getId());
            }
        }
        return $sessId;
    }

    public function pushToCart(Request $request)
    {
            $userId = $this->getUser();
            $product = OzonShopItem::where('ozon_id', '=', $request->ozon_id)->firstOrFail();

            \Cart::session($userId)->add([
                'id'=>$product->id,
                'user_id' => $userId,
                'name' => $product->name,
                'price' => StatusPriceShopItems::where('ozon_id', '=', $request->ozon_id)->firstOrFail()->action_price,
                'quantity' => 1,
                'attributes' => [],
                'associatedModel' => $product,
            ]);
            return $this->getCountCartItem();
    }

    public function indexCart()
    {

        \Cart::session($this->getUser());
        $items = \Cart::getContent();
        return view('main.cart', ['cart'=>$items]);

    }

    public function index()
    {
        try {
            if(Auth::user()){
                $cart = OfferUser::where('email','=',Auth::user()->email)->get();
                $totalSum = UserCart::where('user_id','=',$cart[0]->session_user)->get();
            }else{
                $cart = OfferUser::where('session_user','=',$this->getUser())->get();
                $totalSum = UserCart::where('user_id','=',$this->getUser())->get();
            }

            return response()->view('main.allOffers', ['carts'=>$cart,
                'totalQuantity'=>$totalSum->sum('quantity'),
                'totalSum'=>$totalSum->sum('total_price')]);
        }catch (Exception $e){
            return back()->withError($e->getMessage())->withInput();
        }
    }

    public function counter()
    {
        if(Auth::user()){
            $cart = OfferUser::where('email','=',Auth::user()->email)->get();
            $totalSum = UserCart::where('user_id','=',$cart[0]->session_user)->get();
        }else{
            $totalSum = UserCart::where('user_id','=',$this->getUser())->get();
        }

        return $totalSum->sum('quantity');
    }

    public function getCountCartItem()
    {
        $userId = $this->getUser();
        $total = \Cart::session($userId)->getSubTotal();
        $totalQuantity = \Cart::session($userId)->getTotalQuantity();

        return [$total,$totalQuantity];
    }


    public function updateCart(Request $request)
    {
        $userId = $this->getUser();
        \Cart::session($userId)->update($request->id,
            [
                'quantity' => $request->quantity,
            ]);
        $items = \Cart::getContent();
        return $items[$request->id]->quantity;
    }

    public function deleteCart(Request $request)
    {
        $userId = $this->getUser();
        \Cart::session($userId)->remove($request->id);

        return $request->id;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * Переход по ссылки из почты, для подтверждения заказа
     */
    public function confirmLink(Request $request)
    {

        $hash = $request->get('link');
        $res = OfferUser::where('confirm', '=', $hash)->get();
        if(count($res)===0){
            return 'denied';
        }else{
            OfferUser::where('confirm', '=', $hash)
                ->update(['confirm'=>'подтвержден']);
            $email = $res[0]->email;
            $name = $res[0]->name;
            $message = '<h2 style="color: #6f42c1">Благодарим Вас за подтверждение нашего заказа!</h2>';
            $message .='<p>Мы ценим Ваше доверие и стремимся предоставить Вам наилучший сервис. Для оплаты заказа мы направили реквизиты нашей карты Сбербанка :</p>';
             $message .= '<h2 style="color: mediumvioletred">5555-5555-5555-5555-5555</h2>';
            $message .=  '<p>В назначении платежа, пожалуйста укажите номер заказа:</p>';
                 $message .='<h2 style="color: mediumvioletred">'.$res[0]->id.'</h2>';
            $message .=
              '<p>После отправки Вашего заказа мы пришлём Вам номер трека компании СДЭК для отслеживания
                        в течение 24 часов после оплаты заказа.
             Ожидайте получения Вашего заказа в ближайшее время.
              Если у Вас возникнут вопросы или пожелания, пожалуйста, не стесняйтесь обращаться к нам.
                        С уважением, Команда myfunnybant.ru</p>';
            CartConfirmEvent::dispatch($message, $email, $name);

            return redirect('/user/get/cart');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * Пользователь нажал кнопку оформить заказ
     */
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
            ]);
            session(['user_information', [
                'session_user' => $this->getUser(),
                'email' => $request->input('email'),
                'name' => $request->input('first_name'),
                'tel' => $request->input('tel'),
            ]]);
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
        try{
        $session_user = $this->getUser();
        $offer_id = 'default';
        $user_id = 'default';
        $email = $request->input('email');
        $name = $request->input('first_name');
        $tel = $request->input('tel');
        $status = 'false';
        $confirm = password_hash($email, PASSWORD_DEFAULT);

            $offer = OfferUser::create([
                'offer_id'=>$offer_id,
                'user_id'=>$user_id,
                'email'=>$email,
                'name'=>$name,
                'tel'=>$tel,
                'status'=>$status,
                'confirm'=>$confirm,
                'session_user'=>$session_user,
            ]);
            $offer->save();
            $offer_id = $offer->id;
        }catch (Exception $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }

        $hash = $confirm;

        \Cart::session($this->getUser());
        $items = \Cart::getContent();
        $arr = [];
        foreach ($items as $item){
            $arr[]=$item;
        }

        foreach ($arr as $lid)
        {
            $lidAdd = UserCart::create([
                'ozon_id' => $lid['associatedModel']['ozon_id'],
                'user_id' => $session_user,
                'quantity' => $lid['quantity'],
                'price' => $lid['price'],
                'total_price' => $lid['quantity'] * $lid['price'],
                'cdek_id' => $request->input('input_CDEK_id'),
                'cdek_info' => $request->input('input_delivery_city') . ' ' .$request->input('input_delivery_adress_cdek'),
                'delivery_price' => $request->input('input_delivery_price'),
                'offer_id' => $offer_id,
                'status_offer'=>'ожидает оплаты'
            ]);
            $lidAdd->save();
        }

        \Cart::session($session_user);
        \Cart::session($session_user)->clear();

        $message = '<h1 style="color: #6f42c1"><b>Здравствуйте, '.$name.'!</b></h1>';
        $message .= '<p>Мы рады сообщить Вам о том, что Ваш заказ, успешно оформлен.</p>';
        $message .= '<p>Ниже приведены детали Вашего заказа:</p>';
        $message .= '<ul><li class="h4"> Номер заказа: <b style="color: mediumvioletred"> '.$offer_id.'</b></li>';
        $message .= '<li>Дата оформления: '.OfferUser::where('id', $offer_id)->firstOrFail()->created_at.'</li>';
        $message .= '<li class="h4">Сумма заказа:<b style="color: mediumvioletred">#TOTAL#</b> </li></ul>';
        $message .= '<h2 style="color: #6f42c1"><b>Информация о доставке</b></h2>';
        $message .= '<ul><li class="h4">Город доставки:<b style="color: mediumvioletred"> '.$request->input('input_delivery_city').'</b></li>';
        $message .= '<li class="h4">Адрес доставки:<b style="color: mediumvioletred"> '.$request->input('input_delivery_adress_cdek').'</b></li>';
        $message .= '<li class="h4">СТОИМОСТЬ ДОСТАВКИ: <b style="color: mediumvioletred">'.$request->input('input_delivery_price').'</b></li></ul>';
        $confirm = '<h2>Для подтверждения заказа, на электронную почту '.$email.' направлена ссылка.</h2>';
        $confirm .= '<p>Зайдите в вашу электронную почту и перейдите по ссылке для подтверждения заказа и дальнейшей оплаты.</p>';
        $link = '<h2>Перейдите по ссылке для подтверждения заказа</h2>';
        $link .= 'https://myfunnybant/security/?link='.$hash;

        //CartConfirmEvent::dispatch($message.$link, $email, $name);

        $res = UserCart::where('user_id', '=', $session_user)->sum('quantity');

        return '<a href="/user/confirm/" class="btn btn-primary">На страницу подтверждения заказа</a>';
        //return redirect('/user/confirm')->with('email',$email);

    }

    public function codeConfirm(Request $request)
    {
        try{
            $validated = $request->validate([
                'code'=>'required|min:4|max:4',
            ]);
            $res = OfferUser::where('offer_id', '=', $request->input('code'))->get();
            if(count($res)>0){

                return redirect('/user/confirm/')->with('result', 'success');
            }else{
                return redirect('/user/confirm/')->with('denied', 'not found');
            }
        }catch (ValidationException $e){
            return back()->withError($e->getMessage())->withInput();
        }
    }

}
