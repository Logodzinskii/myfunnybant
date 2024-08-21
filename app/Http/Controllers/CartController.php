<?php

namespace App\Http\Controllers;

use App\Events\UserCreateOffer;
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
use App\Http\Controllers\User\VisitorsController;
use App\Http\Controllers\User\DeliveryController;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public $visitor, $delivery;
    public $cart;
    public function __construct()
    {

        $this->visitor = new VisitorsController('','','');
        $this->delivery = new DeliveryController('', '', '', '');

    }

    protected function getUser()
    {
        if(Auth::check()) {

            $sessId = Auth::user();

        }else{

            $arr=[];
            foreach (session()->all() as $key=>$name){

                $arr[] = $key;
            }
            if(count($arr)>5){

                $sessId = explode('_', $arr[5])[0];

            }else{

                $sessId = $this->visitor->getSessionVisitor();
            }

        }
        return $sessId;
    }

    public function pushToCart(Request $request)
    {

        $userId = $this->getUser();
        $product = OzonShopItem::where('ozon_id', '=', $request->ozon_id)->firstOrFail();

        \Cart::session('_token')->add([
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

        \Cart::session('_token');
        $items = \Cart::getContent();
        return view('main.cart', ['cart'=>$items]);

    }

    public function index()
    {
        try {

            if(Auth::user()){
                $cart = OfferUser::where('email', '=', Auth::user()->email)->get();
                $totalSum = UserCart::where('user_id', '=', $cart[0]->session_user)->get();
            }elseif (strlen(session('user')['email'])>0){
                $cart = OfferUser::where('email', '=', session('user')['email'])->get();
                $totalSum = UserCart::where('user_id', '=', $cart[0]->user_id)->get();
            }else{
                $cart = OfferUser::where('email', '=', session('user')['email'])->get();
                return response()->view('main.allOffers', ['carts'=>$cart,
                    'totalQuantity'=>0,
                    'totalSum'=>0]);
            }
            return response()->view('main.allOffers', ['carts'=>$cart,
                'totalQuantity'=>$totalSum->sum('quantity'),
                'totalSum'=>$totalSum->sum('total_price')]);
        }catch (Exception $e){
                return $e->getMessage() . session('user')['email'];
        }
    }

    public function counter()
    {
        try{
            if(Auth::user()){
                $cart = OfferUser::where('email','=',Auth::user()->email)->get();
                $totalSum = UserCart::where('user_id','=',$cart[0]->session_user)->sum('quantity');
            }elseif (strlen(session('user')['email'])>0){
                $totalSum = UserCart::where('user_id','=',$this->getUser())->get();
                $cart = OfferUser::where('email', '=', session('user')['email'])->get();
                $totalSum = UserCart::where('user_id', '=', $cart->first()->user_id)->sum('quantity');
            }else{
                return 0;
            }

            return $totalSum;
        }catch (Exception $exception){
            return 0;
        }

    }

    public function getCountCartItem()
    {
        try{

            $userId = $this->getUser();
            $total = \Cart::session('_token')->getSubTotal();
            $totalQuantity = \Cart::session('_token')->getTotalQuantity();

            return [$total,$totalQuantity];
        }catch (Exception $exception)
        {
            return 'CartController' . $exception->getMessage() . $this->getUser();
        }

    }

    public function updateCart(Request $request)
    {
        $userId = $this->getUser();
        \Cart::session('_token')->update($request->id,
            [
                'quantity' => $request->quantity,
            ]);
        $items = \Cart::getContent();
        return $items[$request->id]->quantity;
    }

    public function deleteCart(Request $request)
    {
        try{
            $userId = $this->getUser();
            \Cart::session('_token')->remove($request->id);

        }catch (Exception $exception)
        {
            return $exception->getMessage();
        }

        return ['id'=>$request->id, 'count'=>\Cart::session('_token')->getTotalQuantity()];
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
            $message .='<p>Мы ценим Ваше доверие и стремимся предоставить Вам наилучший сервис. Для оплаты заказа мы направили реквизиты нашей карты Сбербанка после того как проверим и соберем заказ</p>';
            $message .='<p>С уважением, Команда <a href="https://myfunnybant.ru">myfunnybant.ru</a></p>';
            CartConfirmEvent::dispatch($message, $email, $name);

            return view('user.confirm',['data'=>'На вашу электронную почту будет направлено сообщение с реквизитами для оплаты, после сборки заказа']);
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
            DB::beginTransaction();
            $session_user = session('user')['session'];
            $offer_id = 'default';
            $user_id = $session_user;
            $email = $request->input('email');
            $name = $request->input('first_name');
            $tel = $request->input('tel');
            $status = 'Новый';

            $this->visitor->setNameVisitors($name, $email, $tel);
            $this->delivery->setDelivery($request->input('input_delivery_city'), $request->input('input_delivery_adress_cdek'), $request->input('input_delivery_price'), $request->input('input_CDEK_id'));
            \Cart::session('_token');
            $items = \Cart::getContent();
            $this->cart = $items;
            $confirm = password_hash($email, PASSWORD_DEFAULT);

            $offer= new OfferUser();
            $offer->offer_id = $this->cart;
            $offer->user_id = $user_id;
            $offer->email = $email;
            $offer->tel = $tel;
            $offer->status = $status;
            $offer->confirm = $confirm;
            $offer->session_user = $session_user;
            $offer->save();
            $offer_id = $offer->id;

            $hash = $confirm;

            $arr = [];
            foreach (json_decode(OfferUser::where('id','=',$offer_id)->select('offer_id')->first(),true) as $item){
                $arr[]=json_decode($item,true);
            }

            foreach ($arr[0] as $lid)
            {
                $lidAdd = new UserCart();
                $lidAdd->ozon_id = count($lid)>0?$lid['associatedModel']['ozon_id']:die('корзина не заполнена');
                $lidAdd->user_id = $session_user;
                $lidAdd->quantity = $lid['quantity'];
                $lidAdd->price = $lid['price'];
                $lidAdd->total_price = $lid['quantity'] * $lid['price'];
                $lidAdd->cdek_id = $request->input('input_CDEK_id');
                $lidAdd->cdek_info = $request->input('input_delivery_city') . ' ' .$request->input('input_delivery_adress_cdek');
                $lidAdd->delivery_price = $request->input('input_delivery_price');
                $lidAdd->offer_id = $offer_id;
                $lidAdd->status_offer = 'ожидает оплаты';
                $lidAdd->save();
            }


            $message = '<h1 style="color: #6f42c1"><b>Здравствуйте, '.$name.'!</b></h1>';
            $message .= '<p>Мы рады сообщить Вам о том, что Ваш заказ, успешно оформлен.</p>';
            $message .= '<p>Ниже приведены детали Вашего заказа:</p>';
            $message .= '<ul><li class="h4"> Номер заказа: <b style="color: mediumvioletred"> '.$offer_id.'</b></li>';
            $message .= '<li>Дата оформления: '.OfferUser::where('id', $offer_id)->firstOrFail()->created_at.'</li>';
            //$message .= '<li class="h4">Сумма заказа:<b style="color: mediumvioletred">#TOTAL#</b> </li></ul>';
            $message .= '<h2 style="color: #6f42c1"><b>Информация о доставке</b></h2>';
            $message .= '<ul><li class="h4">Город доставки:<b style="color: mediumvioletred"> '.$request->input('input_delivery_city').'</b></li>';
            $message .= '<li class="h4">Адрес доставки:<b style="color: mediumvioletred"> '.$request->input('input_delivery_adress_cdek').'</b></li>';
            $message .= '<li class="h4">СТОИМОСТЬ ДОСТАВКИ: <b style="color: mediumvioletred">'.$request->input('input_delivery_price').'</b></li></ul>';
            $confirm = '<h2>Для подтверждения заказа, на электронную почту '.$email.' направлена ссылка.</h2>';
            $confirm .= '<p>Зайдите в вашу электронную почту и перейдите по ссылке для подтверждения заказа и дальнейшей оплаты.</p>';
            $link = '<h2>Перейдите по ссылке для подтверждения заказа</h2>';
            $link .= 'https://myfunnybant.ru/security/?link='.$hash;
            $adminMessage = 'Новый заказ от: ' . $name .', номер: '.$offer_id.', дата оформления: '.OfferUser::where('id', $offer_id)->firstOrFail()->created_at. 'http://myfunnybant.locals/admin/view/offers';

            CartConfirmEvent::dispatch($message.$link, $email, $name);
            UserCreateOffer::dispatch($adminMessage);

            $res = UserCart::where('user_id', '=', $session_user)->sum('quantity');

            \Cart::session('_token');
            \Cart::session('_token')->clear();
            $this->visitor->setNameVisitors($name,$email,$tel);
            DB::commit();
            return response()->json([
                'success'=>true,
            ]);
        }catch (Exception $exception)
        {
            DB::rollBack();
            return response()->json([
                'message'=>$exception->getMessage(),
                'success'=>false,
            ]);
        }

    }

    public function codeConfirm(Request $request)
    {
        $info = 'На вашу электронную почту направлено сообщение для подтверждения заказа';
        return view('user.confirm',['data'=>$info]);
    }

}
