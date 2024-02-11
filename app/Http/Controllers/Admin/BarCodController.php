<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StatGetOzon;
use App\Models\OzonShopItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Support\Facades\Storage;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use VK\Actions\Storage as ActionsStorage;

class BarCodController extends Controller
{
    public function index($id)
    {
        $data = '{
            "offer_id": "",
            "product_id": "'.$id.'",
            "sku": 0
        }';
        $method = '/v2/product/info';
        $barcod = StatGetOzon::getOzonCurlHtml($data,$method);
        
        $squ = OzonShopItem::where('ozon_id',$id)
        ->get();
        
        $bar = \App::make('BarCode');
$barcodes = [
                'text' => $barcod['result']['barcode'],
                'size' => 50,
                'orientation' => 'horizontal',
                'code_type' => 'code128',
                'print' => true,
                'sizefactor' => 1,
                'filename' => '../barcode.png',
            ];
$barcontent = $bar->barcodeFactory()->renderBarcode(
                                    $text=$barcodes["text"], 
                                    $size=$barcodes['size'], 
                                    $orientation=$barcodes['orientation'], 
                                    $code_type=$barcodes['code_type'], // code_type : code128,code39,code128b,code128a,code25,codabar 
                                    $print=$barcodes['print'], 
                                    $sizefactor=$barcodes['sizefactor'],
                                    $filename = $barcodes['filename']
                            )->filename($barcodes['filename']);
            $colors = '';
            foreach (json_decode($squ[0]->colors, true) as $color)
            {
                $colors .= $color . ', ';
            }
            $materials = '';
            foreach (json_decode($squ[0]->material, true) as $material)
            {
                $materials .= $material . ', ';
            }
            $str = '';
            //$str .=  '<img alt="testing" src="/'.$barcontent.'"/><br/>';
            $str .=  'СЗ Логодзинская В.Л.'. ' ';
            $str .=  $squ[0]->name . '<br/>';
            $str .=  'Артикул: '. $barcod['result']['offer_id'] .' ';
            $str .=  'Цв.'.  $colors .' ';
            $str .=  'Раз.'.  ($squ[0]->width)/10 .'см*' . ($squ[0]->depth)/10 .'см' . '<br/>';
            $str .=  'Срок годности: 01.01.2035. ';
            $str .=  'Бренд: myfunnybant'. '<br/>';
            $str .=  'Состав: '. $materials ;
            //$pdf = PDF::loadView('admin.barcode.barcode',['data'=>$str,'img'=>$barcontent]);    
            //return $pdf->download('demo.pdf');
            return view('admin.barcode.barcode',['data'=>$str, 'img'=>$barcontent]);
    }
}
