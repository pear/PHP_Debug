<?php

/**
 * Minimal test
 * 
 * @package PHP_Debug
 * @filesource
 * 
 * @version    CVS: $Id$
 */ 

echo '<?xml version="1.0" encoding="UTF-8"?>';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">     
  <head>  
    <title>PEAR::PHP_Debug, Hello World !</title>
<?php

// Options array for Debug object
$options = array(
    'HTML_DIV_images_path' => 'images', 
    'HTML_DIV_css_path' => 'css', 
    'HTML_DIV_js_path' => 'js',
);

/**
 * Include Debug Class
 */ 
include_once('PHP/Debug.php');

// Debug object
$Dbg = new PHP_Debug($options);

?>
    <script type="text/javascript" src="<?php echo $options['HTML_DIV_js_path']; ?>/html_div.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $options['HTML_DIV_css_path']; ?>/html_div.css" />
  </head>
  <body>
  <div>
    <a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
  </div>
<?php

echo '<div><h1>PEAR::PHP_Debug, Hello World !</h1></div>';
$Dbg->add('DEBUG INFO');
$Dbg->display();

?>
  </body>
</html>