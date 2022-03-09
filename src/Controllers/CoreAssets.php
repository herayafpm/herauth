<?php

namespace Raydragneel\Herauth\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class CoreAssets extends BaseHerauthController
{
    public function file($any = false)
    {
        $path = str_replace(base_url('core_assets')."/", '', (string)current_url(true));
        if(strpos($path,'?') !== false){
            $path = explode('?',$path);
            $path = $path[0];
        }
        $file = __DIR__ . "/../../assets/vendor/adm/$path";
        if (file_exists($file)) {
            $ctype = mime_content_type($file);
            if($ctype === 'directory'){
                throw new PageNotFoundException();
            }
            $ctype = parseMimeType($path,$ctype);
            header("Pragma:public");
            header("Expired:0");
            header("Cache-Control:must-revalidate");
            header("Content-Control:public");
            header("Content-Description: File Transfer");
            header("Content-Type: $ctype");
            header("Content-Transfer-Encoding:binary");
            header("Content-Length:" . filesize($file));
            flush();
            readfile($file);
            exit();
        } else {
            throw new PageNotFoundException();
        }
    }

    
}
