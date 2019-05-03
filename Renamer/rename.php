<?php
class Rename {
    public function __construct($names_file_path)
    {
        $this->names_file_path = $names_file_path;
        $this->names = null;
        $this->dir = __DIR__;
        $this->errors = [];

        $this->init();
    }

    protected function buildPath(...$paths)
    {
        return preg_replace('~[/\\\\]+~', DIRECTORY_SEPARATOR, implode(DIRECTORY_SEPARATOR, $paths));
    }

    protected function showErrors()
    {
        if(count($this->errors)) {
            echo "\nОшибки:\n";
            print_r($this->errors);
        }
    }

    protected function abort()
    {
        $this->showErrors();
        exit();
    }

    protected function loadNamesFile()
    {
        $absolute_names_file_path = $this->buildPath($this->dir, $this->names_file_path);
        if(file_exists($absolute_names_file_path)) {
            return json_decode(file_get_contents($absolute_names_file_path));
        } else {
            $this->errors[] = 'Не найден файл с именами: ' . $absolute_names_file_path;

            $this->abort();
        }
    }

    protected function rename()
    {
        foreach ($this->names as $file => $name) {
            $path = $this->buildPath($this->dir, $file);
            $path_parts = pathinfo($file);
            $ext = isset($path_parts['extension']) ? $path_parts['extension'] : '';
            $name = "{$name}.{$ext}";
            $new_name = $this->buildPath($this->dir, $name);

            if(!file_exists($path)) {
                $this->errors[] = 'Не найден файл: ' . $path;
                continue;
            }

            $result = @rename($path, $new_name);

            if(!$result) {
                $this->errors[] = 'Не удалось переименовать файл: ' . $path;
                continue;
            }
            
            echo "{$file} -> {$name}\n";
        }

        $this->showErrors();
    }

    public function init()
    {
        $this->names = $this->loadNamesFile();

        $this->rename();
    }
}
// =================================
$rename = new Rename('names.json');