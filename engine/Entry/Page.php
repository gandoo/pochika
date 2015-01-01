<?php namespace Pochika\Entry;

use Layout;
use PageRepository;

class Page extends Entry {

    public $date;

    /**
     * process
     *
     * @return void
     */
    protected function process()
    {
        parent::process();
        
        //if (!$this->date) {
        //    $this->date = filemtime($this->path);
        //}

        $this->url = $this->url();
    }

    /**
     * render 
     * 
     * @param array $payload 
     * @return string
     */
    public function render($payload = [])
    {
        $this->convert();

        $payload = array_merge([
            'page' => $this->payload(),
        ], $payload);

        $layout = element('layout', $this->data);
        if ($layout) {
            return Layout::find($layout)->render($payload);
        } else {
            return $this->content;
        }
    }

    /**
     * @todo
     */
    protected function makeKey()
    {
        $dir = source_path('pages');

        $key = ltrim(str_replace($dir, '', $this->path), '/');

        return preg_replace('/\..*$/', '', $key);
    }

    /**
     * payload 
     * 
     * @return array
     */
    protected function payload($convert = false)
    {
        if ($convert) {
             $this->convert();
        }

        return [
            'title' => $this->title,
            'content' => $this->content,
            'published' => $this->published,
            'url' => $this->url,
        ];
    }

    /**
     * url
     *
     * @access protected
     * @return string
     * @todo customize permalink url
     */
    protected function url()
    {
        return url($this->key);
    }
    
    /**
     * __callStatic 
     * 
     * @param string $name 
     * @param array $argv 
     * @return mixed
     */
    public static function __callStatic($name, $argv)
    {
        if (!in_array($name, ['all', 'clear', 'count', 'find', 'search'])) {
            throw new \BadMethodCallException('Undefined method: Page::'.$name);
        }

        switch (count($argv)) {
            case 0:
                return PageRepository::$name();
            case 1:
                return PageRepository::$name($argv[0]);
//            case 2:
//                return PageRepository::$name($argv[0], $argv[1]);
//            case 3:
//                return PageRepository::$name($argv[0], $argv[1], $argv[2]);
            default:
                throw new \InvalidArgumentException('invalid arguments count');
        }
    }

}