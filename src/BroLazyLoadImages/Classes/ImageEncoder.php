<?php

namespace BroLazyLoadImages\Classes;


trait ImageEncoder
{

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
