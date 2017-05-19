<?php

class PageDetails
{
    public static function putting( $html = NULL)
    {
        $smallimage = new TImage("app/images/logo_small.png");
        $largeimage = new TImage("app/images/logo_large.png");

        $html = str_replace('{smallLogo}', $smallimage, $html);
        $html = str_replace('{largeLogo}', $largeimage, $html);
        $html = str_replace('{systemname}', "RBSIC", $html);
        $html = str_replace('{systemversion}', "Beta", $html);
        $html = str_replace('{systemowner}', "e-Code", $html);
        $html = str_replace('{pagetitle}', "RBSIC", $html);
        $html = str_replace('{pagefavicon}', "app/images/favicon.png", $html);
        $html = str_replace('{homepage}', "#", $html);

        return $html;
    }
}
