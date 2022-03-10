<?php

namespace Raydragneel\Herauth\Controllers;
use Config\Services;

class Language extends BaseHerauthController
{
    public function lang()
    {
        $data = $this->getDataRequest();
        $file = $data['file'];
        $locale = $this->request->getLocale();
        $path = "Language/{$locale}/{$file}.php";
        $lang = $this->requireFile($path);
        return json_encode($lang);
    }

    protected function requireFile(string $path): array
    {
        $files   = Services::locator()->search($path, 'php', false);
        $strings = [];

        foreach ($files as $file) {
            // On some OS's we were seeing failures
            // on this command returning boolean instead
            // of array during testing, so we've removed
            // the require_once for now.
            if (is_file($file)) {
                $strings[] = require $file;
            }
        }

        if (isset($strings[1])) {
            $strings = array_replace_recursive(...$strings);
        } elseif (isset($strings[0])) {
            $strings = $strings[0];
        }

        return $strings;
    }
}
