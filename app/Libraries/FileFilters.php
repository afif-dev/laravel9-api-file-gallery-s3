<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;

class FileFilters
{
    /**
     * Filter upload file, check if have any script contain in file and delete it.
     *
     * @param  $file_path
     * @return boolean
     */
    public static function uploadFileScript($file_path)
    {
        $file_content = Storage::disk('s3')->get($file_path);
        // print_r($file_content);
        // print_r($file_path);die;

        $search_script = [
            "<script>",
            "javascript:",
            "php", 
            "echo",
            "print(",
            "println(",
            "write(",
            "document.",
            "window.",
            "alert(",
            "onload",
            "console."
        ];

        foreach($search_script as $sscript) {
            if(strpos($file_content, $sscript)){
                Storage::disk('s3')->delete($file_path);
                return false;
            }
        }

        return true;
    }
}
