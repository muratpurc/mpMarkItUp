<?php
/**
 * Include file for editing content of type CMS_MIUBBCODE.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */


defined('CON_FRAMEWORK') or die('Illegal call');

plugin_include('markitup', 'classes/class.markup_cms_type_editing.php');

$options = array(
    'markup_name'  => 'bbcode',
    'cmstype_name' => 'CMS_MIUBBCODE',
    'parser_file'  => 'class.markup_bbcode_parser.php',
    'parser_name'  => 'Markup_BBCodeParser',
);
$oMarkUpEditing = new Markup_CmsTypeEditing($cfg, $options);


if ($doedit == '1') {

    $oMarkUpEditing->save($CMS_MIUBBCODE);
    $oMarkUpEditing->redirectToEditView();
    exit();

} else {

    header("Content-Type: text/html; charset={$encoding[$lang]}");
    $oMarkUpEditing->renderEditor();

}
