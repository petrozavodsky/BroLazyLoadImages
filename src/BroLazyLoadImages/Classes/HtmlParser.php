<?php

namespace BroLazyLoadImages\Classes;


class HtmlParser
{

    public function updateAttribute($atr, $str)
    {
        $attr = $this->getAttribute($atr, $str);
    }

    /**
     * Remove attribute
     * @param $atr
     * @param $str
     * @return string|string[]|null
     */
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

        if (isset($m[1])) {
            return $m[1];
        }

        return false;
    }

    public function getAttributes($sts, $tag = 'img')
    {
        preg_match("~<{$tag}(.*)\/?>~imU", $sts, $m);

        if (!isset($m[1]) || empty($m[1])) {
            return false;
        }

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
