<?php
/**
 * Plugin markItUp CMS-Type inside editing area.
 *
 * NOTE:
 * global variable $currentMarkupCmsType whick contains the current CMS-Type must be set before
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © Murat Purc 2008
 * @package     Contenido
 * @subpackage  markItUp
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

    $div = new cHTMLDiv;
    $div->setID(implode('_', array(str_replace('CMS_', '', $currentMarkupCmsType), $db->f('idtype'), $val)));
    $div->setEvent('focus', 'this.style.border=\'1px solid #bb5577\'');
    $div->setEvent('blur', 'this.style.border=\'1px dashed #bfbfbf\'');
    $div->setStyleDefinition('border', '1px dashed #bfbfbf');
    $div->updateAttributes(array('makupContentEditable' => 'true')); // this is normally contentEditable, but since 4.8.9 a click on ot will load tinymce
    $div->setStyleDefinition('direction', langGetTextDirection($lang));

    $editlink = new cHTMLLink();
    $editlink->setLink($sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=$currentMarkupCmsType&typenr=$val&lang=$lang"));

    $editimg = new cHTMLImage();
    $editimg->setSrc($cfg['path']['contenido_fullhtml'] . $cfg['path']['images'] . 'but_edittext.gif');

    $savelink = new cHTMLLink();
    $savelink->setLink("javascript:setcontent('$idartlang','0')");

    $saveimg = new cHTMLImage();
    $saveimg->setSrc($cfg['path']['contenido_fullhtml'] . $cfg['path']['images'] . 'but_ok.gif');

    $savelink->setContent($saveimg);

    $editlink->setContent($editimg);

    $div->setContent($content);

    $tmp = implode('', array($div->render(), $editlink->render(), ' ', $savelink->render()));
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

