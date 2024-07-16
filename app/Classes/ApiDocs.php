<?php

namespace App\Classes;

use Carbon\Carbon;

class ApiDocs
{
    public function __construct($testname,$prefix = null)
    {
        $this->filename = $this->getTestName($testname,$prefix);
        $this->filepath = base_path().'/storage/APIDocs/'.$this->filename.'.json';
        if (!file_exists(dirname($this->filepath))) {
            mkdir(dirname($this->filepath), 0777, true);
        }
        if (env('CLEAR_API_TEST_DOCS')) {
          file_put_contents($this->filepath, '[]');
        }
    }

    public static function getTestName($testname,$prefix)
    {
        $last_back = strrchr($testname, '\\');
        $testname = substr($last_back, 1, strlen($last_back) - 1);
        $testname = str_replace('Test', 'ApiDoc', $testname);
        if($prefix){
          $testname = $prefix.$testname;
        }
        return $testname;
    }

    public function createApiDoc($apiDoc)
    {
        $apiDoc['API_FILE'] = $this->filename;
        $apiDoc['Last Updated'] = Carbon::now()->timezone('Asia/Kolkata')->format('d M Y H:i:s e');
        if (env('FILL_API_TEST_DOCS')) {
          $e = json_decode(file_get_contents($this->filepath));
          array_push($e, $apiDoc);
          file_put_contents($this->filepath, print_r(json_encode($e, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), true));
        }
    }
}
