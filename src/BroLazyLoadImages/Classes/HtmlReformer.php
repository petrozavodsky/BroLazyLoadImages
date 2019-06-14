<?php
/**
 * User: vladimir rambo petrozavodsky
 * Date: 2019-06-14
 */

namespace BroLazyLoadImages\Classes;


class HtmlReformer extends HtmlParser
{

    public function __construct()
    {

        add_filter('the_content', [$this, 'postHtml']);
    }

    public function postHtml($html)
    {

        $this->regexSrc($html);

        return $html;
    }

    public function regexSrc($str)
    {
        preg_match_all('~<img.*>~Uim', $str, $images);

        foreach ($images[0] as $image) {
            $this->getAttachmentIdByClassName($image);
        }
        return $str;
    }

    public function getAttachmentIdByClassName($str)
    {
        preg_match("~<img.*class=[\"|'].*wp-image-(\d+).*[\"|'].*>~im", $str, $match);

        if (isset($match[1])) {
            return (int)$match[1];
        }

        d($this->getAttribute('src', $str));
    }

    private function getImgIdByUrl($url = null)
    {
        global $wpdb;

        if (!$url) {
            return false;
        }

        $name = basename($url); // имя файла

        $name = preg_replace('~-[0-9]+x[0-9]+(?=\..{2,6})~', '', $name);

        $name = preg_replace('~\.[^.]+$~', '', $name);

        // очистим чтобы привести к стандартному виду
        $name = sanitize_title(sanitize_file_name($name));

        $attachment_id = $wpdb->get_var(
            $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'attachment'", $name)
        );

        return intval($attachment_id);
    }

}
