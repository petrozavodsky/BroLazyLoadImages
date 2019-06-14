<?php

namespace BroLazyLoadImages\Classes;


class ProgressiveImage extends HtmlParser
{

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

    public function insertImageSrc($image_id)
    {
        $preview = wp_get_attachment_image_src($image_id, $this->size);

        return array_shift($preview);
    }

    public function insertBase64EncodedImage_src($image_id)
    {
        $data = wp_get_attachment_metadata($image_id);
        $mime = get_post_mime_type($image_id);

        if (array_key_exists($this->size, $data['sizes'])) {
            $file_name = $data['sizes'][$this->size]['file'];
        } else if (0 < count($data['sizes'])) {
            $first_size = array_shift($data['sizes']);
            $file_name = $first_size['file'];
        } else {
            $file_name = basename($data["file"]);
        }

        $dirname = dirname($data['file']);
        $upload_dir = wp_get_upload_dir();
        $file = $upload_dir['basedir'] . '/' . $dirname . '/' . $file_name;

        if (file_exists($file)) {
            $imageData = base64_encode(file_get_contents($file));
            $imageSrc = "data:{$mime};base64,{$imageData}";
        } else {
            $imageSrc = $this->insertImageSrc($image_id);
        }

        return $imageSrc;
    }
}
