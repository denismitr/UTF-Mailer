<?php

namespace Denismitr\UTFMail;


class Template
{
    protected $folder;

    public function __construct($folder)
    {
		$this->folder = $folder;
    }

    public function render($template, $data = [])
    {
        $filename = $this->getFileName($template);

        if (! is_readable($filename)) {
            throw new \Exception("File not found: " . $filename);
        }

        ob_start();

        extract($data);

        require_once $filename;

        return ob_get_clean();
    }

    protected function getFileName($template)
    {
        return base_path() . $this->folder . $template . '.php';
    }
}