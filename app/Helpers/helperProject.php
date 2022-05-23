<?php
if (!function_exists('_getAppLang')) {
    function _getAppLang(){
        $appLang = '';
        if(Session::has('locale')){
            $appLang = session('locale');
        }
        else{
            $appLang = Config::get('app.locale');
        }
        return $appLang;
    }
}
if (!function_exists('_getHomeUrl')) {
    function _getHomeUrl($urlSegment='') {
        if(config('constants.urlType') == 'betagit'){
            return config('constants.betagitUrls.homeUrl').$urlSegment;
        }
        else if(config('constants.urlType') == 'live'){
            return config('constants.liveUrls.homeUrl').$urlSegment;
        }
    }
}
if (!function_exists('_getLogoutUrl')) {
    function _getLogoutUrl() {
        if(config('constants.urlType') == 'betagit'){
            return config('constants.betagitUrls.logoutUrl');
        }
        else if(config('constants.urlType') == 'live'){
            return config('constants.liveUrls.logoutUrl');
        }
    }
}
if (!function_exists('_getMerchantDashboardUrl')) {
    function _getMerchantDashboardUrl() {
        if(config('constants.urlType') == 'betagit'){
            return config('constants.betagitUrls.merchantDashboardUrl');
        }
        else if(config('constants.urlType') == 'live'){
            return config('constants.liveUrls.merchantDashboardUrl');
        }
    }
}
?>