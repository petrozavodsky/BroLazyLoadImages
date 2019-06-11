<?php

namespace BroLazyLoadImages\Classes;


class HtmlParser
{


    /**
     * Get html attribute by name
     * @param $str
     * @param $atr
     * @return mixed
     */
    public function getAttribute($atr, $str)
    {
        preg_match("~{$atr}=[\"|'](.*)[\"|']\s~imU", $str, $m);

        return $m[1];
    }

    public function getAttributes($sts, $tag = 'img')
    {
        preg_match("~<{$tag}(.*)\/?>~imU", $sts, $m);

        $split = preg_split("~\s?[\"|'](\s+)~imU", $m[1]);


        $split = array_filter($split, function ($item) {
            if (empty($item)) {
                return false;
            }
            return true;
        });

        return array_map('trim', $split);

    }
}
