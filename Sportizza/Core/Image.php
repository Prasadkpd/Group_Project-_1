<?php

namespace Core;
use Exception;

class Image
{
    public $img_errors = [];
    private $target_dir = __DIR__."/../public/Assets/uploads/";
    private $file_path = "";
    private $final_name;
    public function __construct(string $file_name)
    {
        if(!isset($_FILES[$file_name])){
            $this->img_errors["image"] = 'Please upload an image';
            return;
        }
        $this->file_name = $file_name;

        $this->final_name = time() . "_" . rand(1, 100) . "." .  pathinfo($_FILES[$file_name]["name"], PATHINFO_EXTENSION);

        $this->file_path = $this->target_dir .$this->final_name;

        $file_ext = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));

        if ($_FILES[$file_name]["size"] < 1) {
            $this->img_errors["image0"] = 'Please upload an image';
            // throw new  Exception("Sorry, your file is too large.", 413);
        }
        elseif (file_exists($this->file_path)) {
            $this->img_errors["image1"] = 'Sorry, an image already exists';
            // throw new  Exception("Sorry, file already exists.", 400);
        }

        elseif ($_FILES[$file_name]["size"] > 5000000) {
            $this->img_errors["image2"] = 'Sorry, your image is too large';
            // throw new  Exception("Sorry, your file is too large.", 413);
        }

        elseif (!in_array(strtolower($file_ext), array("jpg", "png", "jpeg", "gif", "svg", "heic"))) {
            $this->img_errors["image3"] = 'Sorry, only JPG, JPEG, PNG , HEIC  & GIF files are allowed';
            // throw new  Exception("Sorry, only JPG, JPEG, PNG , HEIC  & GIF files are allowed.", 400);
        }

        elseif (move_uploaded_file($_FILES[$file_name]["tmp_name"], $this->file_path)) {
            // File has been successfully uploaded and moved
        } else {
            // file move has failed
            $this->img_errors["image4"] = 'Sorry, there was an error when uploading your image';
            // throw new  Exception("Sorry, there was an error uploading your file.", 500);
        }
        return $this->img_errors;
    }

    public function getURL()
    {
        return "/Assets/uploads/" . $this->final_name ;
    }

    public function __toString()
    {
        return $this->getURL();
    }
}
