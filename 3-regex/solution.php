<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 11/25/16
 * Time: 9:06 PM
 */

/**
 * Only CLI
 */
if (php_sapi_name() !== 'cli') {
    exit("Program should be run from command line\n");
}

/**
 * Arrange
 */
$basedir = '/datafiles/';
$ext = '.txt';
$pattern = $basedir . '*' . $ext;

/**
 * Find
 */
$files = array_filter(glob($pattern), function ($file) use ($ext) {
    return preg_match('#[[:alnum:]]+#', basename($file, $ext));
});

/**
 * Extract basename
 */
$files = array_map(function ($file) {
    return basename($file);
}, $files);

/**
 * Sort
 */
natsort($files);

/**
 * Print
 */
$files = implode(PHP_EOL, $files) . PHP_EOL;
echo $files;