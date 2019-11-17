<?php
/**
 * Project:
 * Contenido Content Management System
 *
 * Description:
 * Include file for editing content of type CMS_MIUBBCODE.
 *
 * Initializes the editing and renders view for editing and preview or redirects
 * to backend view.
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     Plugin_markItUp
 * @subpackage  Inside_Editing
 * @version     $Id$
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2010 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @link        http://www.purc.de
 *
 * {@internal
 *   created 2008-12-xx
 *   $Id$
 * }}
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

} elseif (isset($_GET['domarkituppreview']) && $_GET['domarkituppreview'] == '1') {

    $oMarkUpEditing->renderPreview();

} else {

    header("Content-Type: text/html; charset={$encoding[$lang]}");
    $oMarkUpEditing->renderEditor();

}
