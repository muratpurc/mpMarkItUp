<?php
/**
 * Include file for editing content of type CMS_MIUTEXY.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */


defined('CON_FRAMEWORK') or die('Illegal call');

plugin_include('markitup', 'classes/class.markup_cms_type_editing.php');

$options = array(
    'markup_name'  => 'texy',
    'cmstype_name' => 'CMS_MIUTEXY',
    'parser_file'  => 'class.markup_texy_parser.php',
    'parser_name'  => 'Markup_TexyParser',
);
$oMarkUpEditing = new Markup_CmsTypeEditing($cfg, $options);


if ($doedit == '1') {

    $oMarkUpEditing->save($CMS_MIUTEXY);
    $oMarkUpEditing->redirectToEditView();
    exit();

} else {

    header("Content-Type: text/html; charset={$encoding[$lang]}");
    $oMarkUpEditing->renderEditor();

}
