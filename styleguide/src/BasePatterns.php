<?php

use \Michelf\MarkdownExtra;
#use \Mustangostang\Spyc;

class BasePatterns {

    public $directory;
    public $directoryMap;
    
    public function __construct($dir)
    {
        $this->directory = $dir;
        $this->directoryMap = $this->_directoryMap($dir);
    }

    /*
     * Output pattern markup
     */
    public function content()
    {
        $patterns = $this->_content($this->directoryMap, $this->directory);

        return $patterns;
    }

    /*
     * Output menu markup
     */
    public function menu()
    {
        $menu = $this->_menu($this->directoryMap, $this->directory);

        return $menu;
    }

    /*
     * Humanize directory or file name
     */
    public function humanize($str)
    {
        $str = preg_replace('/^[\d+]\\./', '', $str);
        $str = preg_replace('/\\.[^.\\s]{2,4}$/', '', $str);
        return ucwords(preg_replace('/[_]+/', ' ', strtolower(trim($str))));
    }

    /*
     * Anchorize directory or file name
     */
    public function anchorize($str)
    {
        $str = preg_replace('/[\d+]\\./', '', $str);
        $str = preg_replace('/\\.[^.\\s]{2,4}$/', '', $str);
        $str = trim($str, DIRECTORY_SEPARATOR);
        $str = str_replace(DIRECTORY_SEPARATOR, '-', $str);
        return preg_replace('/[_]+/', '-', strtolower(trim($str)));
    }

    /*
     * Recursively parse patterns
     */
    private function _content($arr, $dir = '', $level = 0)
    {
        $out = '';
        $dir = rtrim($dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $level++;

        foreach($arr as $key => $val)
        {
            $out .= '<div class="sg-section">';

            if (is_array($val)) {
                $out .= '<h'.$level.' class="sg-h'.$level.'" id="'.$this->anchorize($dir.$key).'">'.$this->humanize($key).'</h'.$level.'>';
                $out .= $this->_content($val, $dir.$key, $level);
            } elseif(stristr($val, '.html') || stristr($val, '.md')) {
                $title = $description = $source = '';
                $text  = file_get_contents($dir.$val);
                $parts = preg_split('/[\r\n]+[-]{3}[\r\n]/', $text, 2, PREG_SPLIT_NO_EMPTY);

                if (count($parts) == 2) {
                    $yaml = Spyc::YAMLLoadString($parts[0]);

                    if(isset($yaml['title'])) {
                        $title = $yaml['title'];
                    }
                    if(isset($yaml['description'])) { 
                        $description = MarkdownExtra::defaultTransform($yaml['description']);
                    }
                    $source = $parts[1];
                } else {
                    $title = $this->humanize($val);
                    $source = $text;
                }
                $out .= '<div class="sg-head"><h'.$level.' class="sg-h'.$level.'" id="'.$this->anchorize($dir.$val).'">'.$title.'</h'.$level.'></div>';
                $out .= '<div class="sg-descr">'.$description.'</div>';
                if('' !== trim($source)) {
                    $out .= '<div class="sg-demo">'.$source.'</div>';
                    $out .= '<div class="sg-source"><pre><code class="language-markup">'.htmlentities($source).'</code></pre></div>';
                }
            }
            $out .= '</div>';
        }

        return $out;
    }

    /*
     * Recursively parse patterns menu
     */
    private function _menu($arr, $dir = '', $level = 0)
    {
        $out = '';
        $dir = rtrim($dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $level++;

        foreach($arr as $key => $val)
        {
            if(is_array($val)) {
                $out .= '<li><a href="#'. $this->anchorize($dir.$key) .'">'. $this->humanize($key) .'</a>';
                $out .= $this->_menu($val, $dir.$key.DIRECTORY_SEPARATOR);
                $out .= '</li>';
            } else {
                $out .= '<li><a href="#'. $this->anchorize($dir.$val) .'">'. $this->humanize($val) .'</a></li>';
            }
        }
        $out = '<ul>'.$out.'</ul>';

        return $out;
    }

    /*
     * Recursively parse directory into array
     */
    private function _directoryMap($dir)
    {
        $tree = array();
        $dir = rtrim($dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $files = scandir($dir);

        foreach($files as $file)
        {
            if($file[0] == '.') {  
                continue;
            }    
            if (is_dir($dir.$file)) {
                $tree[$file] = $this->_directoryMap($dir.$file.DIRECTORY_SEPARATOR);
            } else {
                $tree[] = $file;
            }
        }

        return $tree;
    }
}