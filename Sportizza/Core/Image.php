<?php

namespace Core;
use Exception;

class Image
{
    private $target_dir = __DIR__."/../public/Assets/uploads/";
    private $file_path = "";
    private $final_name;
    public function __construct(string $file_name)
    {
        $this->file_name = $file_name;

        $this->final_name = time() . "_" . rand(1, 100) . "." .  pathinfo($_FILES[$file_name]["name"], PATHINFO_EXTENSION);

        $this->file_path = $this->target_dir .$this->final_name  ;

        $file_ext = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));

        if (file_exists($this->file_path)) {
            throw new  Exception("Sorry, file already exists.", 400);
        }

        if ($_FILES[$file_name]["size"] > 5000000) {
            throw new  Exception("Sorry, your file is too large.", 413);
        }

        if (!in_array(strtolower($file_ext), array("jpg", "png", "jpeg", "gif", "svg", "heic"))) {
            throw new  Exception("Sorry, only JPG, JPEG, PNG , HEIC  & GIF files are allowed.", 400);
        }

        if (move_uploaded_file($_FILES[$file_name]["tmp_name"], $this->file_path)) {
            // File has been successfully uploaded and moved
        } else {
            // file move has failed
            throw new  Exception("Sorry, there was an error uploading your file.", 500);
        }
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
