<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Plugin markItUp! CMS-Type inside editing area.
 *
 * NOTE:
 * global variable $currentMarkupCmsType which contains the current CMS-Type
 * must be set before.
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     Plugin_markItUp
 * @subpackage  Inside_Editing
 * @version     $Id: markup_inside_editing_area.php 110 2010-02-16 14:28:22Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @link        http://www.purc.de
 *
 * {@internal
 *   created 2008-12-xx
 *   $Id: markup_inside_editing_area.php 110 2010-02-16 14:28:22Z Murat $
 * }}
 */


defined('CON_FRAMEWORK') or die('Illegal call');


if (!isset($currentMarkupCmsType)) {
    die('markup_inside_editing_area.php: Missing CMS-Type');
}


cInclude('classes', 'class.htmlelements.php');
cInclude('includes', 'functions.lang.php');

$content = $a_content[$currentMarkupCmsType][$val];
$content = urldecode($content);
$content = htmldecode($content);
$content = str_replace('&nbsp;', ' ', $content);
if ($content == '') {
    $content = '&nbsp;';
}

if ($edit) {

    $editUr = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=$currentMarkupCmsType&typenr=$val&lang=$lang");

    $div = new cHTMLDiv();
    $div->setID(implode('_', array(str_replace('CMS_', '', $currentMarkupCmsType), $db->f('idtype'), $val)));
    $div->setEvent('focus', 'this.style.border=\'1px solid #bb5577\'');
    $div->setEvent('blur', 'this.style.border=\'1px dashed #bfbfbf\'');
    $div->setStyleDefinition('border', '1px dashed #bfbfbf');
    $div->updateAttributes(array('makupContentEditable' => 'true')); // this is normally contentEditable, but since 4.8.9 a click on ot will load tinymce
    $div->setStyleDefinition('direction', langGetTextDirection($lang));

    $editlink = new cHTMLLink();
    $editlink->setLink($editUr);

    $editimg = new cHTMLImage();
    $editimg->setSrc($cfg['path']['contenido_fullhtml'] . $cfg['path']['images'] . 'but_edittext.gif');

    $savelink = new cHTMLLink();
    $savelink->setLink("javascript:setcontent('$idartlang', '0')");

    $saveimg = new cHTMLImage();
    $saveimg->setSrc($cfg['path']['contenido_fullhtml'] . $cfg['path']['images'] . 'but_ok.gif');

    $savelink->setContent($saveimg);

    $editlink->setContent($editimg);

    $div->setContent($content);

    $tmp = implode("\n", array($div->render(), $editlink->render(), ' ', $savelink->render()));
    $tmp = str_replace('"', '\"', $tmp);

    $tmp = '
<!-- start: ' . $currentMarkupCmsType . ' -->
' . $tmp . '
<!-- end: ' . $currentMarkupCmsType . ' -->
    ';
} else {

    $tmp = $content;
    $tmp = str_replace('"', '\"', $tmp);

}

$tmp = addslashes($tmp);
$tmp = str_replace('$', '\\\$', $tmp);

