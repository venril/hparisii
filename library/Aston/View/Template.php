<?php

namespace Aston\View;

class Template extends View
{
    public function render($view, array $data = array())
    {
        $charlist = '/\\';
        $path     = rtrim($this->getPath(), $charlist);
        $view     = ltrim($view, $charlist);

        $filename = $path . DIRECTORY_SEPARATOR . $view;

        if (!file_exists($filename)) {
            throw new Exception('File "'.$filename.'" not found');
        }

        ob_start();
        extract($data);
        include $filename;
        return ob_get_clean();
    }
}
