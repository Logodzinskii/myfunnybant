<?php

namespace App\Listeners;

use App\Events\ClickOzonLink;
use App\Models\Visitors;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AddToDBVisitWebInformation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ClickOzonLink  $event
     * @return void
     */
    public function handle(Request $request, $event)
    {
        $ipVisitor = $request->ip();
        $path = $request->path();
        $fullUrl = $request->fullUrl();
        $header = $request->header('X-Header-Name');
        $userAgent = $request->server('HTTP_USER_AGENT');

        if($this->isBot($userAgent) == true)
        {
            // visitors::create([
            //     'ip'=>$ipVisitor,
            //     'path'=>$path,
            //     'fullUrl'=>$fullUrl,
            //     'header'=>$header,
            //    'userAgent'=>$userAgent,
            //  ]);

            //  Log::info('Посетитель сайта' .'; '. $ipVisitor .'; '. $path .'; '. $fullUrl .'; '. $header .'; '. $userAgent);
            $chatId = config('telegram.TELEGRAMADMIN');
            $token = config('telegram.TELEGRAMTOKEN');
            $message = 'переход в озон: ' . $userAgent;
            $response = array(
                'chat_id' => $chatId,
                'text' => $message,
            );

            $ch = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            curl_close($ch);
        }

    }

    protected function isBot($userAgent){

        if (!empty($userAgent)) {
            $options = array(
                'YandexBot', 'YandexAccessibilityBot', 'YandexMobileBot','YandexDirectDyn',
                'YandexScreenshotBot', 'YandexImages', 'YandexVideo', 'YandexVideoParser',
                'YandexMedia', 'YandexBlogs', 'YandexFavicons', 'YandexWebmaster',
                'YandexPagechecker', 'YandexImageResizer','YandexAdNet', 'YandexDirect',
                'YaDirectFetcher', 'YandexCalendar', 'YandexSitelinks', 'YandexMetrika',
                'YandexNews', 'YandexNewslinks', 'YandexCatalog', 'YandexAntivirus',
                'YandexMarket', 'YandexVertis', 'YandexForDomain', 'YandexSpravBot',
                'YandexSearchShop', 'YandexMedianaBot', 'YandexOntoDB', 'YandexOntoDBAPI',
                'Googlebot', 'Googlebot-Image', 'Mediapartners-Google', 'AdsBot-Google',
                'Mail.RU_Bot', 'bingbot', 'Accoona', 'ia_archiver', 'Ask Jeeves',
                'OmniExplorer_Bot', 'W3C_Validator', 'WebAlta', 'YahooFeedSeeker', 'Yahoo!',
                'Ezooms', '', 'Tourlentabot', 'MJ12bot', 'AhrefsBot', 'SearchBot', 'SiteStatus',
                'Nigma.ru', 'Baiduspider', 'Statsbot', 'SISTRIX', 'AcoonBot', 'findlinks',
                'proximic', 'OpenindexSpider','statdom.ru', 'Exabot', 'Spider', 'SeznamBot',
                'oBot', 'C-T bot', 'Updownerbot', 'Snoopy', 'heritrix', 'Yeti',
                'DomainVader', 'DCPbot', 'PaperLiBot', 'bingbot', 'PetalBot', 'Bot'

            );

            foreach($options as $row) {
                if (stripos($_SERVER['HTTP_USER_AGENT'], $row) !== false) {
                    return true;
                }
            }
        }

        return false;

    }

}
