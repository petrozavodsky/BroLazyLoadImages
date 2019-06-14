<?php

namespace BroLazyLoadImages\Classes;

class Reformer
{

    private $exclude = [];

    private $size;

    public $embedImages = true;

    private $image;

    public function __construct($exclude)
    {

        $this->size = AddImageSize::$size;

        $this->exclude = $exclude;

        $this->image = new ProgressiveImage();

        add_filter('post_thumbnail_html', [$this, 'imageHtml'], 10, 3);
    }

    public function imageHtml($html, $post_id, $post_thumbnail_id)
    {
        if (!in_array(intval($post_id), $this->exclude)) {

            return $this->image->html($html, $post_thumbnail_id);
        }

        return $html;

    }


}
