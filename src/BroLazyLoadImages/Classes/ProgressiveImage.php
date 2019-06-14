<?php

namespace BroLazyLoadImages\Classes;


class ProgressiveImage extends HtmlParser
{
    use ImageEncoder;

    private $size;

    public $embedImages = true;

    public function __construct()
    {
        $this->size = AddImageSize::$size;
    }

    public function html($html, $thumbnailId)
    {
        if ($this->embedImages) {
            $preview = $this->insertBase64EncodedImage_src($thumbnailId);
        } else {
            $preview = $this->insertBase64EncodedImage_src($thumbnailId);
        }

        // получаем атрибуты
        $attributes = $this->getAttributes($html);

        // обрабатываем атрибуты
        $attributesJson = json_encode($attributes);
        $attributesBase64 = base64_encode($attributesJson);

        $html = $this->updateAttribute('src', $preview, $html);
        $html = $this->removeAttribute('srcset', $html);
        $html = $this->removeAttribute('sizes', $html);
        $html = $this->updateAttribute('class', 'preview', $html);
        $html = $this->updateAttribute('style', "max-height: {$attributes['height']}px;", $html);

        $o = '';
        $o .= "<div data-attributes='{$attributesBase64}' class='primary progressive replace'>";
        $o .= $html;
        $o .= "</div>";

        return $o;
    }


}
