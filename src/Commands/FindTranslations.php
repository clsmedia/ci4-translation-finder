<?php

namespace clsmedia\translations\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FindTranslations extends BaseCommand
{

    protected $group = 'CLSMedia/Translations';
    protected $name = 'translations:find';
    protected $description = 'Finds translation string in code.';
    protected $searchPath = APPPATH;

    public $translationStrings = [];


    public function run(array $params = [])
    {

        CLI::clearScreen();
        CLI::write('Lets Go!', 'green');
        $this->processFiles();
        CLI::showProgress(false);
        if (! count($this->getTranslationStrings()))
        {
            CLI::newLine();
            $thead = ['Ooops!'];
            $tbody = [
                ['Nothing found! Make some translation strings first'],
                ['More info @ https://codeigniter4.github.io/CodeIgniter4/outgoing/localization.html#basic-usage']
            ];
            CLI::table($tbody, $thead);
        return null;
        }

        $thead = ['Translation Strings Found'];
        $tbody = [
            [count($this->getTranslationStrings())]
        ];
        CLI::table($tbody, $thead);
        CLI::newLine();
        foreach ($this->getTranslationStrings() as $translationString)
        {
            echo $translationString.PHP_EOL;
        }
    }


    public function getTranslationStrings(): array
    {
        $this->translationStrings = array_filter($this->translationStrings);
        $this->translationStrings = $this->arrayFlatten($this->translationStrings);
        $this->translationStrings = array_unique($this->translationStrings);
        sort($this->translationStrings);
        return $this->translationStrings;
    }

    function arrayFlatten($array)
    {

        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return = array_merge($return, $this->arrayFlatten($value));
            } else {
                $return[$key] = $value;
            }
        }
        return $return;
    }


    public function getDirContents($dir, &$results = array())
    {
        $files = scandir($dir);
        CLI::write('Parsing ' . $dir, 'green');
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }

        return $results;
    }


    public function parseFile($path)
    {
        $result = array();
        $file = fopen($path, "r");
        while (!feof($file)) {
            $line = ($this->parseLine(fgets($file)));
            if (!empty($line)) {
                $result[] = $line;
            }
        }
        fclose($file);
        return $result;
    }


    public function parseLine($line)
    {
        $pattern = "~ lang\(([\'\"]([^\'\"]+)[\'\"])[)\]];? ~";

        $result = array();
        if (preg_match($pattern, $line, $match)) {
            $string = substr($match[0], strpos($match[0], "(") + 1);
            $string = substr($string, 0, strpos($string, ")"));
            $string = str_replace(['"',"'"], "", $string);
            $result[] = $string;
        }
        return $result;
    }

    private function processFiles(): void
    {
        $directories = $this->getDirContents($this->searchPath);
        $totalSteps = count($directories);
        $currStep = 1;

        foreach ($directories as $dir) {
            CLI::showProgress($currStep++, $totalSteps);

            if (str_ends_with($dir, '.php')) {

                $this->translationStrings[] = $this->parseFile($dir);
            }
        }
    }


}
