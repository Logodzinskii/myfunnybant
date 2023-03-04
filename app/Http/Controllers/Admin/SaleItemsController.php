<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\saleitems;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleItemsController extends Controller
{
    public function index()
    {

    }
    public function showAllSaleItems()
    {
        $items = saleitems::paginate(30)->withQueryString();
        return view('admin/itemsView', ['items'=>$items]);
    }
}
