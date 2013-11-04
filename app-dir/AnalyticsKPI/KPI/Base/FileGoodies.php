<?php
namespace KPI\Base;

class FileGoodies {

    function ___construct() {}

    function realPath ($filename)
    {
        // Check for absolute path
        if (realpath($filename) == $filename) {
            return $filename;
        }

        // Otherwise, treat as relative path
        $paths = explode(\PATH_SEPARATOR, get_include_path());
        foreach ($paths as $path) {
            if (substr($path, -1) == \DIRECTORY_SEPARATOR) {
                $fullpath = $path.$filename;
            } else {
                $fullpath = $path.\DIRECTORY_SEPARATOR.$filename;
            }
            if (file_exists($fullpath)) {
                return $fullpath;
            }
        }

        return false;
    }

    function destroy($dir) {
        $mydir = opendir($dir);
        while (false !== ($file = readdir($mydir))) {
            if ($file != "." && $file != "..") {
                chmod($dir . $file, 0777);
                if (is_dir($dir . $file)) {
                    chdir('.');
                    destroy($dir . $file . '/');
                    rmdir($dir . $file) or DIE("couldn't delete $dir$file<br />");
                }
                else
                    unlink($dir . $file) or DIE("couldn't delete $dir$file<br />");
            }
        }
        closedir($mydir);
    }

}

?>
