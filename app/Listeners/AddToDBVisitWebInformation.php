<?php

namespace App\Listeners;

use App\Events\ClickOzonLink;
use App\Models\Visitors;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
    public function handle(ClickOzonLink $event)
    {
        $ipVisitor = $event->request->ip();
        $path = $event->request->path();
        $fullUrl = $event->request->fullUrl();
        $header = $event->request->header('X-Header-Name');
        $userAgent = $event->request->server('HTTP_USER_AGENT');

        if($this->isBot($userAgent) == true)
        {
            Visitors::create([
                'ip'=>$ipVisitor,
                'path'=>$path,
                'fullUrl'=>$fullUrl,
                'header'=>$header,
                'userAgent'=>$userAgent,
            ]);

            Log::info('Посетитель сайта' .'; '. $ipVisitor .'; '. $path .'; '. $fullUrl .'; '. $header .'; '. $userAgent);

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
                'DomainVader', 'DCPbot', 'PaperLiBot', 'bingbot'
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
