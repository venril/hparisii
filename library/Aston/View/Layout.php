<?php

namespace Aston\View;

class Layout extends Template
{
    private $view;
    private $content;

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        $this->view = (string) $view;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    protected function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }

    public function render($view, array $data = array())
    {
        $content = new Template($this->getPath());
        $this->setContent($content->render($view, $data));
        return parent::render($this->getView(), $data);
    }
}
