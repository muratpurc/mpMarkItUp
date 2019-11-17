<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * PLUGIN INSTALLER for CONTENIDO >= 4.8
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     CONTENIDO
 * @subpackage  PluginInstaller
 * @version     0.5
 * @author      Martin Horwath (horwath@dayside.net)
 * @author      Paul Sauer (contenido@saueronline.de)
 * @author      Murat Purc <murat@purc.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @since       File available since contenido release >= 4.8
 *
 * {@internal
 *   26.09.2004 - Martin Horwath <horwath@dayside.net> - maybe initial release
 *   10.10.2005 - unknown - unknown changes
 *   15.02.2006 - Paul Sauer <contenido@saueronline.de> - unknown changes
 *   12.09.2006 - Murat Purc <murat@purc.de> - some modifications, like simple templating
 *   26.10.2008 - Murat Purc <murat@purc.de> - XHTML, cleanup, etc.
 *   $Id: install.php 110 2010-02-16 14:28:22Z Murat $
 * }}
 */


defined('CON_FRAMEWORK') or define('CON_FRAMEWORK', true);


################################################################################
# Initialization

$contenido_path = '../../';

// include security class and check request variables
include_once($contenido_path . 'classes/class.security.php');
Contenido_Security::checkRequests();

$cfg['debug']['installer'] = false;
$bCheckTableStatus = true;

include_once($contenido_path . 'includes/startup.php');

// set url path to backend
$aPath = parse_url($cfg['path']['contenido_fullhtml']);
$contenido_html = $aPath['path'];

cInclude('includes', 'functions.general.php');

$cfg['debug']['backend_exectime']['fullstart'] = getmicrotime();

cInclude('includes', 'functions.i18n.php');
cInclude('includes', 'functions.api.php');
cInclude('includes', 'functions.general.php');
cInclude('includes', 'functions.database.php');
cInclude('includes', 'functions.str.php');

cInclude('classes', 'class.xml.php');
cInclude('classes', 'class.navigation.php');
cInclude('classes', 'class.template.php');
cInclude('classes', 'class.backend.php');
cInclude('classes', 'class.notification.php');
cInclude('classes', 'class.area.php');
cInclude('classes', 'class.action.php');
cInclude('classes', 'class.layout.php');
cInclude('classes', 'class.treeitem.php');
cInclude('classes', 'class.user.php');
cInclude('classes', 'class.group.php');
cInclude('classes', 'class.cat.php');
cInclude('classes', 'class.client.php');
cInclude('classes', 'class.inuse.php');
cInclude('classes', 'class.table.php');

page_open(array('sess' => 'Contenido_Session', 'auth' => 'Contenido_Challenge_Crypt_Auth', 'perm' => 'Contenido_Perm'));

i18nInit($cfg['path']['contenido'] . $cfg['path']['locale'], $belang);
cInclude('includes', 'cfg_language_de.inc.php');

// overwrite error reporting
error_reporting (E_ALL ^ E_NOTICE);

################################################################################
# Some installer classes

// @todo: Outsource the classes below!

/**
 * Plugin setup interface, each plugin installer class should implement this
 * interface.
 *
 * @package     CONTENIDO
 * @subpackage  PluginInstaller
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 */
interface IPluginSetup {
    /**
     * Installs plugin.
     *
     * @return  void
     */
    public function install();

    /**
     * Updates plugin.
     *
     * @return  void
     */
    public function upgrade();

    /**
     * Uninstalls plugin.
     *
     * @return  void
     */
    public function uninstall();
}

/**
 * Abstract plugin setup base class, each plugin installer should extend this
 * class.
 *
 * @package     CONTENIDO
 * @subpackage  PluginInstaller
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 */
abstract class PluginSetupAbstract {
    /**
     * CONTENIDO configuration array
     * @var  array
     */
    protected $_cfg;

    /**
     * Database instance
     * @var  DB_Contenido
     */
    protected $_db;

    /**
     * Constructor, sets some properties-
     * @return  void
     */
    public function __construct(){
        $this->_cfg = $GLOBALS['cfg']; // damn globals
        $this->_db  = new DB_Contenido();
    }
}

/**
 * Final plugin setup factory class
 *
 * @package     CONTENIDO
 * @subpackage  PluginInstaller
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 */
final class PluginSetupFactory {
    /**
     * Returns the plugin installer instance.
     * Note:
     * The file contains installer clas must be included before!
     *
     * @param   string  $className  Classname of plugin installer to instantiate.
     * @return  IPluginSetup
     * @throws  Exception  If classname is missing.
     */
    public static function getInstaller($className) {
        if (!class_exists($className)) {
            throw new Exception('PluginSetupFactory:getInstaller() Class "' . $className . '" doesn\'t exists, include classfile before calling factory');
        }
        return new $className();
    }
}


################################################################################
# Main installer process

$cfg['debug']['backend_exectime']['start'] = getmicrotime();

// include markItUp installer
plugin_include('markitup', 'classes/class.markitupinstaller.php');

// instanzen der db_contenido
$db  = new DB_Contenido;
$db2 = new DB_Contenido;

// mr installer instance
$oPluginInstaller = PluginSetupFactory::getInstaller('MarkItUpInstaller');

$data['top']         = 'Plugin "markItUp" installer';
$data['content']     = '';
$data['bottom']      = '';
$data['body_bottom'] = '';

$sBackendLink = '<br /><a href="' . $contenido_html . '?contenido=' . $contenido . '" title="Switch to backend">Switch to CONTENIDO backend</a>' . "\n";

if ($bCheckTableStatus) {
    $aRequiredFields = array(
        'idplugin', 'name', 'version', 'author', 'idinternal', 'url',
        'status', 'description', 'install', 'uninstall', 'date'
    );

//    $sRequiredTable = "DROP TABLE " . $cfg['tab']['plugins'] . ";
    $sRequiredTable = "RENAME TABLE " . $cfg['tab']['plugins'] . " TO " . $cfg['tab']['plugins'] . "_" . date('Ymd') . ";
                      CREATE TABLE " . $cfg['tab']['plugins'] . " (
                          idplugin INT(10) NOT NULL default '0',
                          name VARCHAR(60) default NULL,
                          version VARCHAR(10) NOT NULL default '0',
                          author VARCHAR(60) default NULL,
                          idinternal VARCHAR(32) NOT NULL default '0',
                          url TEXT,
                          status INT(10) NOT NULL default '0',
                          description TEXT,
                          install TEXT,
                          uninstall TEXT,
                          date DATETIME NOT NULL default '0000-00-00 00:00:00',
                          PRIMARY KEY (idplugin)
                      ) ENGINE=MyISAM;";
    // now we check if the plugin table has the right format...
    msg('Checking status ' . $cfg['tab']['plugins']);
    $aPluginTableMeta = $db->metadata($cfg['tab']['plugins']);

    $aFoundKeys = array();
    foreach ($aPluginTableMeta as $key) {
        if (!in_array($key['name'], $aRequiredFields)) {
            msg($key['name'] . ' (this key can be deleted)', 'unused key');
        } else {
            $aAvailableKeys[] = $key['name'];
        }
        $aFoundKeys[] = $key['name'];
    }
    foreach ($aRequiredFields as $key) {
        if (!in_array($key, $aFoundKeys)) {
            msg($key . ' (this key must be added)', 'missing key');
            $aMissingKeys[] = $key;
        }
    }
    unset ($aFoundKeys, $key);
    // available elements in table are stored in array -> $aAvailableKeys;
    // missing elements in table are stored in array -> $aMissingKeys;
    // this is a possible way to handle new versions of plugin installer
    // since this is initial release the table will be dropped and recreated
    // when a missing element is found.
    if (count($aMissingKeys) > 0) {
        $sSqlData   = remove_remarks($sRequiredTable);
        $aSqlPieces = split_sql_file($sSqlData, ';');
        msg(count($aSqlPieces) . ' queries', 'Executing:');
        foreach ($aSqlPieces as $sqlinit) {
            $db->query($sqlinit);
            msg($sqlinit);
        }
    } else {
        msg('ok');
    }
}

// con_sequence update
updateSequence();


if ($installsql = file_get_contents('install.sql')) {
    // get info from sql file
    if (preg_match('/####(.*)####/i', $installsql, $pinfo)) {
        $pinfo = explode(';', $pinfo[1]);
        // take some nice names easier to work with...
        $pname       = $pinfo[0];
        $pversion    = $pinfo[1];
        $pauthor     = $pinfo[2];
        $pinternalid = $pinfo[3];

        unset($pinfo);
        // first show info
        $data['content'] .= "<div class='col1'>Plugin Name:</div><div class='col2'>" . $pname . "</div><br class='clear' />\n";
        $data['content'] .= "<div class='col1'>Plugin Version:</div><div class='col2'>" . $pversion . "</div><br class='clear' />\n";
        $data['content'] .= "<div class='col1'>Author:</div><div class='col2'>" . $pauthor . "</div><br class='clear' />\n";
        $data['content'] .= "<div class='col1'>Internal ID:</div><div class='col2'>" . $pinternalid . "</div><br class='clear' />\n";
        $data['content'] .= "<br />\n";

        // the user don't need this info...
        $installsql = preg_replace('/####(.*)####/i', '', $installsql);

        $pstatus = true;
    } else {
        $data['content'] .= 'Info missing. First line of install.sql should include following line:<br />';
        $data['content'] .= '<strong>####NAME;VERSION;AUTHOR;INTERNAL_ID####</strong><br />';
        $data['content'] .= 'No further action takes place<br />';

        $pstatus = false;
    }

    // check if idinternal is allready available in table
    $sql = "SELECT * FROM " . $cfg["tab"]["plugins"] . " WHERE idinternal='" . $pinternalid . "';";
    $db->query($sql);
    if ($db->next_record()) {
        $mode     = 'update';
        $message .= "Plugin with this internal id allready exists in table.<br />\n";
        if ($pversion == $db->f('version')) {
            $message .= "This version is allready installed.<br />\n";
            $mode     = 'uninstall';
        } else {
            $message .= "Switching to upgrade mode.<br />\n";
        }
        $pluginid = $db->f('idplugin');
    } else {
        $mode     = 'install';
        $message .= 'No plugin with this internal id exists in table.<br />' . "\n";
        $pluginid = false;
    }

    if (!$install && !$uninstall) {
        $data['content'] .= '<br />' . $message;
    }

    if (!$install && $mode == 'update') {
        $data['content'] .= "<br /><a class=\"submit\" href=\"$PHP_SELF?install=1&amp;contenido=$contenido\" title=\"Update plugin\">Update $pname $pversion</a><br />\n";
    }

    if (!$install && $mode == 'install') {
        $data['content'] .= "<br /><a class=\"submit\" href=\"$PHP_SELF?install=1&amp;contenido=$contenido\" title=\"Install plugin\">Install $pname $pversion</a><br />\n";
    }

    if (!$uninstall && $mode == 'uninstall') {
        $data['content'] .= "<br /><a class=\"submit\" href=\"$PHP_SELF?uninstall=1&amp;contenido=$contenido\" title=\"UnInstall plugin\">UnInstall $pname $pversion</a><br />\n";
        $data['content'] .= "<br /><br /><strong>Note:</strong><br />";
        $data['content'] .= "The UnInstaller will only remove plugin related entries from database (plugins table). Any done changes on directories or files must be reset manually.<br />";
    }

    if ($uninstall && $pluginid) {
        $sql = "SELECT uninstall FROM " . $cfg['tab']['plugins'] . " WHERE idplugin='" . $pluginid . "'";
        msg($sql);
        $db->query($sql);
        $db->next_record();

        $uninstallsql = $db->f('uninstall');
        $sSqlData     = remove_remarks($uninstallsql);
        $aSqlPieces   = split_sql_file($sSqlData, ';');

        msg(count($aSqlPieces).' queries', 'Executing:');
        foreach ($aSqlPieces as $sqlinit) {
            $db->query($sqlinit);
            msg($sqlinit);
        }

        $oPluginInstaller->uninstall();

        $data['content'] .= "<br /><strong>Uninstall complete.</strong><br />\n";
    }

    if ($pstatus && $install) {
        if ($mode == 'install') { // insert all data from install.sql
            $pluginid = $db->nextid($cfg['tab']['plugins']); // get next free id using phplib method

            $PID = 100 + $pluginid; // generate !PID! replacement
            $replace = array('!PREFIX!' => $cfg['sql']['sqlprefix'], '!PID!' => $PID);

            $installsql = strtr($installsql, $replace);

            $sql = "INSERT INTO " . $cfg['tab']['plugins'] . " (idplugin,name,`version`,author,idinternal,`status`,`date`) VALUES ('" . $pluginid . "','" . $pname . "','" . $pversion . "','" . $pauthor . "','" . $pinternalid . "','0','" . date("Y-m-d H:i:s") . "');";
            $uninstallsql = "DELETE FROM " . $cfg['tab']['plugins'] . " WHERE idplugin='" . $pluginid . "';\r\n";
            msg($sql, 'Insert statement for plugin: ');
            $db->query($sql);

            msg($installsql, 'Install query:');

            $sSqlData   = remove_remarks($installsql);
            $aSqlPieces = split_sql_file($sSqlData, ';');
            msg(count($aSqlPieces) . ' queries', 'Executing:');
            foreach ($aSqlPieces as $sqlinit) {
                // $sqlinit = strtr($sqlinit, $replace);
                // create uninstall.sql for each insert entry
                if (preg_match("/INSERT\s+INTO\s+(.*)\s+VALUES\s*\([´\"'\s]*(\d+)/i", $sqlinit, $tmpsql)) {
                    $tmpidname = $db->metadata(trim(str_replace("`", "", $tmpsql[1])));
                    $tmpidname = $tmpidname[0]['name'];
                    $uninstallsql = "DELETE FROM " . trim($tmpsql[1]) . " WHERE " . $tmpidname . "='" . trim($tmpsql[2]) . "';\r\n" . $uninstallsql;
                }

                $db->query($sqlinit);
                msg($sqlinit);
            }

            if ($uninstallsqlfile = file_get_contents('uninstall.sql')) {
                $uninstallsqlfile = remove_remarks($uninstallsqlfile); // remove all comments

                $uninstallsql .= strtr($uninstallsqlfile, $replace); // add to generated sql
                $data['content'] .= "I found uninstall.sql in " . dirname(__FILE__) . "<br />Statements added to uninstall query.<br />\n";
            }

            msg($uninstallsql, 'Uninstall query:');

            $sql = "UPDATE " . $cfg['tab']['plugins'] . " SET install=0x" . bin2hex($installsql) . ", uninstall=0x" . bin2hex($uninstallsql) . " WHERE (idplugin='" . $pluginid . "');";
            msg($sql, 'un/install statements stored');
            $db->query($sql);

            $oPluginInstaller->install();

            $data['content'] .= "<br /><strong>Install complete.</strong><br />\n";
        }

        if ($mode == 'update') {
            $sql  = "UPDATE " . $cfg['tab']['plugins'] . " SET version = '" . $pversion . "' WHERE (idplugin='" . $pluginid . "');";
            msg($sql, 'Store new plugin version: ');
            $db->query($sql);
            if ($updatesqlfile = @file_get_contents('update.sql')) {
                $sql = "SELECT uninstall FROM " . $cfg['tab']['plugins'] . " WHERE idplugin='" . $pluginid . "'";
                msg($sql, "Getting stored uninstall statements: ");
                $db->query($sql);
                $db->next_record();

                $uninstallsql  = $db->f('uninstall');
                $updatesqlfile = remove_remarks($updatesqlfile); // remove all comments

                $data['content'] .= "I found update.sql in " . dirname(__FILE__) . "<br />\n";

                $PID = 100 + $pluginid; // generate !PID! replacement
                $replace = array('!PREFIX!' => $cfg['sql']['sqlprefix'], '!PID!' => $PID);
                $updatesql .= strtr($updatesqlfile, $replace); // add to generated sql

                $aSqlPieces = split_sql_file($updatesql, ';');
                msg(count($aSqlPieces) . ' queries', 'Executing:');
                foreach ($aSqlPieces as $sqlinit) {
                    // $sqlinit = strtr($sqlinit, $replace);
                    // create uninstall.sql for each insert entry
                    if (preg_match("/INSERT\s+INTO\s+(.*)\s+VALUES\s*\([´\"'\s]*(\d+)/i", $sqlinit, $tmpsql)) {
                        $tmpidname    = $db->metadata(trim(str_replace('`', '', $tmpsql[1])));
                        $tmpidname    = $tmpidname[0]['name'];
                        $uninstallsql = "DELETE FROM " . trim($tmpsql[1]) . " WHERE " . $tmpidname . "='" . trim($tmpsql[2]) . "';\r\n" . $uninstallsql;
                    } else if (preg_match("/REPLACE \s+INTO\s+(.*)\s+VALUES\s*\([´\"'\s]*(\d+)/i", $sqlinit, $tmpsql)) {
                        $tmpidname    = $db->metadata(trim(str_replace('`', '', $tmpsql[1])));
                        $tmpidname    = $tmpidname[0]['name'];
                        $uninstallsql = "DELETE FROM " . trim($tmpsql[1]) . " WHERE " . $tmpidname . "='" . trim($tmpsql[2]) . "';\r\n" . $uninstallsql;
                    }

                    $db->query($sqlinit);
                    msg($sqlinit);
                }
                $sql = "UPDATE " . $cfg['tab']['plugins'] . " SET uninstall = 0x" . bin2hex($uninstallsql) . " WHERE (idplugin='" . $pluginid . "');";
                msg($sql, 'New uninstall statements stored: ');
                $db->query($sql);
            }

            $oPluginInstaller->upgrade();

            $data['content'] .= '<br /><strong>Update complete.</strong><br />' . "\n";
        }

        // con_sequence update
        updateSequence();
    }
} else {
    $data['content'] .= 'Sorry i found no install.sql in ' . dirname(__FILE__) . '<br />' . "\n";
}


$data['bottom']      .= $sBackendLink;
$data['body_bottom']  = getDebugMsg();

$cfg['debug']['backend_exectime']['end'] = getmicrotime();

if ($cfg['debug']['rendering'] == true) {
    $data['body_bottom'] .= 'Rendering this page took: ' . ($cfg['debug']['backend_exectime']['end'] - $cfg['debug']['backend_exectime']['start']) . ' seconds<br />';
    $data['body_bottom'] .= 'Building the complete page took: ' . ($cfg['debug']['backend_exectime']['end'] - $cfg['debug']['backend_exectime']['fullstart']) . ' seconds<br />';

    if (function_exists('memory_get_usage')) {
        $data['body_bottom'] .= 'Include memory usage: ' . human_readable_size(memory_get_usage() - $cfg['debug']['oldmemusage']) . '<br />';
        $data['body_bottom'] .= 'Complete memory usage: ' . human_readable_size(memory_get_usage()) . '<br />';
    }
}


page_close();


################################################################################
##### Output

echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Plugin installer</title>
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="stylesheet" type="text/css" href="{$contenido_html}styles/contenido.css" />
    <style type="text/css"><!--
    body {font-family:verdana; font-size:12px;}
    #wrap {width:750px; margin:50px auto; border:1px solid #b3b3b3;}
    #head {width:100%; border-bottom:1px solid black;}
    #content_top {background-color:#e2e2e2; font-weight:bold; padding:5px 0 5px 10px; border-bottom:1px solid #b3b3b3;}
    #content {padding:10px;}
    a:link, a:visited, a:hover {color:#0060b1; font-size:12px;}
    a:hover {text-decoration:underline;}
    a.submit, a.submit:hover {display:block; height:18px; padding-left:20px;}
    a.submit {background:transparent url({$contenido_html}images/submit.gif) no-repeat;}
    a.submit:hover {background:transparent url({$contenido_html}images/submit_hover.gif) no-repeat;}
    .col1 {width:10em; float:left; padding-bottom:0.3em;}
    .col2 {width:auto; float:left; padding-bottom:0.3em;}
    .clear {clear:both; font-size:0px; line-height:0px; }
    --></style>
</head>
<body>

<div id="wrap">
    <div id="head">
      <a id="head_logo" href="{$contenido_html}?contenido=$contenido" title="Switch to CONTENIDO backend">
        <img src="{$contenido_html}images/conlogo.gif" alt="CONTENIDO Logo" /></a>
    </div>
    <br class="clear" />

    <div id="content_top">
        {$data['top']}
    </div>

    <div id="content">
        <form name="frmPluginInstall" id="frmPluginInstall" method="post" action="$PHP_SELF">
        <input type="hidden" name="contenido" value="$contenido" />
        {$data['content']}
        {$data['bottom']}
        </form>
        {$data['body_bottom']}
    </div>
</div>

</body>
</html>
HTML;


################################################################################
##### Functions

// some functions to work with...
/**
 * removes '# blabla...' from the mysql_dump.
 * This function was originally developed for phpbb 2.01
 * (C) 2001 The phpBB Group http://www.phpbb.com
 *
 * @return string input_without_#
 */
function remove_remarks($sql) {
    $lines = explode("\n", $sql);
    // try to keep mem. use down
    $sql = '';

    $linecount = count($lines);
    $output = '';

    for ($i = 0; $i < $linecount; $i++) {
        if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
            $output .= ($lines[$i][0] != '#') ? $lines[$i] . "\n" : "\n";

            // Trading a bit of speed for lower mem. use here.
            $lines[$i] = '';
        }
    }
    return $output;
}

/**
 * Splits sql- statements into handy pieces.
 * This function was original developed for the phpbb 2.01
 * (C) 2001 The phpBB Group http://www.phpbb.com
 *
 * @return array sql_pieces
 */
function split_sql_file($sql, $delimiter) {
  // Split up our string into "possible" SQL statements.
  $tokens = explode($delimiter, $sql);
  // try to save mem.
  $sql = '';
  $output = array();
  // we don't actually care about the matches preg gives us.
  $matches = array();
  // this is faster than calling count($oktens) every time thru the loop.
  $token_count = count($tokens);
  for ($i = 0; $i < $token_count; $i++) {
    // Dont wanna add an empty string as the last thing in the array.
    if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
      // This is the total number of single quotes in the token.
      $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
      // Counts single quotes that are preceded by an odd number of backslashes,
      // which means they're escaped quotes.
      $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

      $unescaped_quotes = $total_quotes - $escaped_quotes;
      // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
      if (($unescaped_quotes % 2) == 0) {
        // It's a complete sql statement.
        $output[] = $tokens[$i];
        // save memory.
        $tokens[$i] = '';
      } else {
        // incomplete sql statement. keep adding tokens until we have a complete one.
        // $temp will hold what we have so far.
        $temp = $tokens[$i] . $delimiter;
        // save memory..
        $tokens[$i] = '';
        // Do we have a complete statement yet?
        $complete_stmt = false;

        for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
          // This is the total number of single quotes in the token.
          $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
          // Counts single quotes that are preceded by an odd number of backslashes,
          // which means theyre escaped quotes.
          $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

          $unescaped_quotes = $total_quotes - $escaped_quotes;

          if (($unescaped_quotes % 2) == 1) {
            // odd number of unescaped quotes. In combination with the previous incomplete
            // statement(s), we now have a complete statement. (2 odds always make an even)
            $output[] = $temp . $tokens[$j];
            // save memory.
            $tokens[$j] = '';
            $temp = '';
            // exit the loop.
            $complete_stmt = true;
            // make sure the outer loop continues at the right point.
            $i = $j;
          } else {
            // even number of unescaped quotes. We still dont have a complete statement.
            // (1 odd and 1 even always make an odd)
            $temp .= $tokens[$j] . $delimiter;
            // save memory.
            $tokens[$j] = '';
          }
        } // for..
      } // else
    }
  }
  return $output;
}


// simple function to update con_sequence
function updateSequence($table = false) {
    global $db, $db2, $cfg;

    if (!$table) {
        $sql = 'SHOW TABLES';
        $db->query($sql);
        while ($db->next_record()) {
            dbUpdateSequence($cfg['sql']['sqlprefix'] . '_sequence', $db->f(0), $db2);
        }
    } else {
        dbUpdateSequence($cfg['sql']['sqlprefix'] . '_sequence', $table, $db2);
    }
}


// read out next free id * deprecated
function getSequenceId($table) {
    global $db2, $cfg;
    $sql = "SELECT nextid FROM " . $cfg['sql']['sqlprefix'] . "_sequence" . " WHERE seq_name = '$table'";
    $db2->query($sql);
    if ($db2->next_record()) {
        return ($db2->f('nextid') + 1);
    } else {
        msg($table, "missing in " . $cfg['sql']['sqlprefix'] . "_sequence");
        return 0;
    }
}


// debug functions
function msg($value, $info = false) {
    global $cfg;
    if (trim($cfg['debug']['messages']) == '') $cfg['debug']['messages'] = "<br /><strong>DEBUG:</strong>";
    if (!$cfg['debug']['installer']) {
        return;
    }
    if ($info) {
        $cfg['debug']['messages'] .= "<strong>$info</strong> -> ";
    }
    if (is_array($value)) {
        $value = print_r($value, true);
    }
    $cfg['debug']['messages'] .= htmlspecialchars($value) . "<br />";
}


function getDebugMsg() {
    global $cfg;
    if ($cfg['debug']['installer']) {
        return "<div style=\"font-family: Verdana, Arial, Helvetica, Sans-Serif; font-size: 11px; color: #000000\">"
            . $cfg['debug']['messages']
            . "</div>";
    } else {
        return '';
    }
}


/**
 * isWriteable:
 * Checks if a specific file is writeable. Includes a PHP 4.0.4
 * workaround where is_writable doesn't return a value of type
 * boolean. Also clears the stat cache and checks if the file
 * exists.
 *
 * Copied from /setup/lib/functions.filesystem.php
 *
 * @param $file string    Path to the file, accepts absolute and relative files
 * @return boolean true if the file exists and is writeable, false otherwise
 */
function isWriteable($file) {
    clearstatcache();
    return (file_exists($file)) ? is_writable($file) : false;
}


function copyFile($source, $destination, $backupName=null) {
    global $cfg;

    // check source and destination, allow filesystem processes only inside htdocs
    if (strpos($source, $cfg['path']['frontend']) === false) {
        return false;
    } elseif (strpos($destination, $cfg['path']['frontend']) === false) {
        return false;
    } elseif (isset($backupName) && strpos($backupName, $cfg['path']['frontend']) === false) {
        return false;
    }

    if ($backupName !== null) {
        if (!rename($destination, $backupName)) {
            return false;
        }
    }

    if (!copy($source, $destination . '.bak')) {
        return false;
    }

    return true;
}
