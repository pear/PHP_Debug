<?php
/**
 * This the changelog page
 *
 * @version V2.0
 * @since 4 sept 2006
 * @author Vernet Loic
 * @copyright Phoenix
 * @package PHP_Debug_Website
 */


// CONFIG ======================================================================


// Applications variables & gen includes  ======================================
require_once '../config/ApplicationCfg.php';
require_once PD_WEB_INCLUDES_ROOT. 'common.inc.php';

// Page constants ==============================================================
define('PAGE_TR_ID' , 'changelog');
define('PAGE_ID',     PAGE_TR_ID. PD_WEB_FILE_EXT);
define('_FILE_', basename(__FILE__));
define('DEFAULT_ACTION', 'CHANGELOG');


// MODEL =======================================================================


// CONTROLLER ==================================================================
$Dbg->setAction(DEFAULT_ACTION);

$changelog = file_get_contents(PD_WEB_DOCS_ROOT. 'CHANGELOG');
$smarty->assign('changelog', $changelog);


// VIEW ========================================================================


// Assign debug infos
$smarty->assign('file', PD_WEB_PHP_DEBUG_ROOT. DIRECTORY_SEPARATOR. PAGE_ID);
$smarty->assign('bodyTpl', PAGE_TR_ID);
$smarty->assign('debugBuffer', $Dbg->getOutput());       

// Display template
$smarty->display('index.tpl');

?>