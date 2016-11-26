<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 11/25/16
 * Time: 9:06 PM
 */

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
$files = implode(PHP_EOL, $files);
echo $files . PHP_EOL;