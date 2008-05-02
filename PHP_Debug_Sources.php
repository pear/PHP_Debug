<?php

/**
 * Display package source
 * 
 * @package PHP_Debug
 * @filesource
 * 
 * @version    CVS: $Id$
 */ 

include 'PHP/Debug.php';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">     
  <head>  
    <title>PEAR::PHP_Debug, sources</title>
  </head>
  <body>
  <h1>PHP_Debug, package sources</h1>
  <h2>Sourceforge release : <?php echo PHP_Debug::RELEASE; ?></h2>
  <h2>Pear release : <?php echo PHP_Debug::PEAR_RELEASE; ?></h2>
<?php
// Display source code =========================================================

// file
function showSource($dir, $file)
{
    $path = $dir. $file;
	echo '<div>';
    echo '<h1>'. $path. '</h1>';
    highlight_file($path);
    echo '</div>'. "\n";
}

// Dir
function parseDir($dir, $parent)
{
    $path = $parent. ($dir['name'] != '/' ? $dir['name']. '/' : '');
    foreach($dir->file as $file) {
        if (in_array($file['role'], array('test', 'php'))) {
            showSource($path, $file['name']);
        }
    }	
    foreach($dir->dir as $child) {
        parseDir($child, $path);
    }
    return;
}

$package = simplexml_load_file('package.xml');
$dir = '';
parseDir($package->contents->dir, $dir);
?>
  </body>
</html>