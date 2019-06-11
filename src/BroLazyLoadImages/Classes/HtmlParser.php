<?php

namespace BroLazyLoadImages\Classes;


class HtmlParser
{


    public function removeAttribute($atr, $str)
    {
        return preg_replace("~{$atr}=[\"|'](.*)[\"|']\s~imU", '', $str);
    }

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


        $clean = array_map('trim', $split);

        $o = [];
        foreach ($clean as $value) {
            $t = str_replace(["'", '"'], '', $value);
            $a = explode('=', $t);
            $o[$a[0]] = $a[1];
        }

        return $o;
    }
}
