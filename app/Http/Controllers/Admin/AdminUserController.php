<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfferUser;
use App\Models\UserCart;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Events\CartConfirmEvent;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if($request->input('inlineRadioOptions') == null)
        {
            $cart = OfferUser::where('status', '=', 'false')->get();
        }else{
            $cart = OfferUser::where('status', '=', $request->input('inlineRadioOptions'))->get();
        }
        $totalSum = UserCart::all();


        return view('admin.shop.allOffers', ['carts'=>$cart,
            'totalQuantity'=>'',
            'totalSum'=>$totalSum->sum('total_price')]);
    }

    public function update(Request $request)
    {
        try{
            $validate = $request->validate([
               'id'=>'required|max:255',
               'val'=>'required|max:255' ,
            ]);
            OfferUser::where('id',$request->input('id'))
                ->update([
                    'status'=>$request->input('val')
                ]);
            $message = '<h2>Здравствуйте, уважаемый покупатель!</h2>';
            $message .= '<p>Хотим сообщить вам, что ваш заказ №'. $request->input('id') .' был успешно изменен.</p>';
            $message .= '<h2>Статус: '. $request->input('val') .'</h2>';
            $message .= '<p>Благодарим вас за выбор нашего интернет-магазина! С уважением, Ваша команда myfunnybant.ru</p>';

            CartConfirmEvent::dispatch($message, OfferUser::where('id',$request->input('id'))->get()[0]->email, OfferUser::where('id',$request->input('id'))->get()[0]->name);
            return response()->json([
                'success' => true,
                'error' => 'done',
            ]);

        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function addTrack(Request $request)
    {
        try{
            $validate = $request->validate([
                'id'=>'required|max:255',
                'val'=>'required|max:255' ,
            ]);
            OfferUser::where('id',$request->input('id'))
                ->update([
                    'offer'=>$request->input('val')
                ]);
            $message = '<h2>Здравствуйте, уважаемый покупатель!</h2>';
            $message .= '<p>Хотим сообщить вам, что ваш заказ №'. $request->input('id') .' был отправлен.</p>';
            $message .= '<h2>Номер трэка для отслеживания в СДЭК: '. OfferUser::where('id',$request->input('id'))->get()[0]->offer .'</h2>';
            $message .= '<p>Благодарим вас за выбор нашего интернет-магазина! С уважением, Ваша команда myfunnybant.ru</p>';
            CartConfirmEvent::dispatch($message, OfferUser::where('id',$request->input('id'))->get()[0]->email, OfferUser::where('id',$request->input('id'))->get()[0]->name);
            return response()->json([
                'success' => true,
                'error' => 'done',
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
