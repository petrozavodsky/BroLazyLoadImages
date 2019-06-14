<?php

namespace BroLazyLoadImages\Classes;


class HtmlReformer extends HtmlParser
{

    private $image;

    public function __construct()
    {

        add_filter('the_content', [$this, 'postHtml'], 300);

        $this->image = new ProgressiveImage();
    }

    public function postHtml($html)
    {

        return $this->regexSrc($html);
    }

    public function regexSrc($str)
    {
        preg_match_all('~<img.*>~Uim', $str, $images);

        if (!isset($images[0])) {
            return $str;
        }

        foreach ($images[0] as $image) {
            $id = $this->getAttachmentIdAttribute($image);
            if (false !== $id) {

                $str = str_replace(
                    $image,
                    "<div> {$image} </div>",
                    $str
                );
            }
        }

        return $str;
    }

    /**
     * Вытаскиваем из поста все изображения
     * @param $str
     * @return bool|int
     */
    public function getAttachmentIdAttribute($str)
    {
        preg_match("~<img.*class=[\"|'].*wp-image-(\d+).*[\"|'].*>~im", $str, $match);

        if (isset($match[1])) {
            $out = (int)$match[1];
            if ($this->checkAttachment($out)) {
                return $out;
            }
        }

        $dbOut = $this->getImgIdByUrl($this->getAttribute('src', $str));

        if (!empty($dbOut) || $this->checkAttachment($dbOut)) {
            return $dbOut;
        }

        return false;
    }

    /**
     * Получаем id вложениея по его url
     * @param null $url
     * @return bool|int
     */
    private function getImgIdByUrl($url = null)
    {
        global $wpdb;

        if (!$url) {
            return false;
        }

        $name = basename($url); // имя файла
        $name = preg_replace('~\.[^.]+$~', '', $name);
        $name = preg_replace('~-[0-9]+x[0-9]+$~', '', $name);

        $name = sanitize_title(sanitize_file_name($name));

        $name = $wpdb->esc_like($name);

        $query = "SELECT ID FROM `rc_posts` WHERE `guid` LIKE '%{$name}%' AND `post_type` = 'attachment'";

        $attachment_id = $wpdb->get_var(
            $query
        );

        return intval($attachment_id);
    }

    /**
     * проверяем по id действительно ли существуе такое вложение на нашем сайте
     * @param $id
     * @return bool
     */
    private function checkAttachment($id)
    {
        global $wpdb;

        $out = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT post_status FROM `rc_posts` WHERE `ID` = '%d' AND `post_type` = 'attachment'",
                $id
            )
        );

        if ("inherit" == $out) {
            return true;
        }

        return false;
    }

}
