<?php
class Rename {
    public function __construct($names_file_path)
    {
        $this->names_file_path = $names_file_path;
        $this->names = null;
        $this->dir = __DIR__;

        $this->errors = [];


        $this->run();
    }

    protected function loadNamesFile()
    {
       return json_decode(file_get_contents("{$this->dir}\\{$this->names_file_path}"));
    }


    public function run()
    {
        $this->names = $this->loadNamesFile();
        echo print_r($this->names);
    }
}


$rename = new Rename('names.json');