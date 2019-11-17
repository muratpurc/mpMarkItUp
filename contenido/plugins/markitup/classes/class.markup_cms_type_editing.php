<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Contains CONTENIDO CMS-Type editing class.
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     Plugin_markItUp
 * @subpackage  CmsTypeEditing
 * @version     $Id: class.markup_cms_type_editing.php 110 2010-02-16 14:28:22Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @link        http://www.purc.de
 *
 * {@internal
 *   created 2008-12-xx
 *   $Id: class.markup_cms_type_editing.php 110 2010-02-16 14:28:22Z Murat $
 * }}
 */


defined('CON_FRAMEWORK') or die('Illegal call');


plugin_include('markitup', 'includes/config.plugin.php');


/**
 * CONTENIDO CMS-Type editing class.
 *
 * Used to save markItUp related values (contents of markItUp CMS-Types), to render the edit form
 * on article edit-view or to display the preview.
 *
 * @package     Plugin_markItUp
 * @subpackage  CmsTypeEditing
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 */
class Markup_CmsTypeEditing
{

    /**
     * CONTENIDO configuration
     * @var array
     */
    private $_cfg;

    /**
     * Name of markup (e. g. bbcode, markdown, textile, etc.)
     * @var string
     */
    private $_markupName;

    /**
     * CMS-Type (e. g. CMS_MIUBBCODE, CMS_MIUMARKDOWN, etc.)
     * @var string
     */
    private $_cmsTypeName;

    /**
     * Name of parser file inside {plugin_dir}/classes/
     * @var string
     */
    private $_parserFile;

    /**
     * Name of parser class
     * @var string
     */
    private $_parserName;


    /**
     * Constructor, sets passed properties.
     * @param   array  $cfg      CONTENIDO configuration array
     * @param   array  $options  Options as follows:
     *                           - $options['markup_name'] =  Markup name, e. g. 'bbcode'
     *                           - $options['cmstype_name'] = Name of CMS-Type, e. g. 'CMS_MIUBBCODE'
     *                           - $options['parser_file'] = Filename of parser class, e. g. 'class.markup_bbcode_parser.php'
     *                           - $options['parser_name'] = Parser class name, e. g. 'Markup_BBCodeParser'
     * @return  void
     */
    function __construct(array $cfg, array $options)
    {
        $this->_cfg = $cfg;

        $this->_markupName  = $options['markup_name'];
        $this->_cmsTypeName = $options['cmstype_name'];
        $this->_parserFile  = $options['parser_file'];
        $this->_parserName  = $options['parser_name'];
    }


    /**
     * Saves the markup content.
     *
     * @param   string  $cmsTypeContent  The markup data to save
     * @return  void
     */
    public function save($cmsTypeContent)
    {
#echo "<pre>" . $cmsTypeContent . "</pre>";

        if (trim($cmsTypeContent) !== '') {
            $parser    = $this->_getParser();
            $sRawCode  = $cmsTypeContent;
            $sHTMLCode = $parser->parse($this->_prepareMarkup($cmsTypeContent));
        } else {
            $sRawCode  = $cmsTypeContent;
            $sHTMLCode = '';
        }

        plugin_include('markitup', 'includes/config.plugin.php');
        conSaveContentEntry ($GLOBALS['idartlang'], $this->_cmsTypeName, $GLOBALS['typenr'], $sHTMLCode);
        markitup_saveContentEntry($GLOBALS['idartlang'], $this->_cmsTypeName, $GLOBALS['typenr'], $sRawCode);
        conMakeArticleIndex ($GLOBALS['idartlang'], $GLOBALS['idart']);
        conGenerateCodeForArtInAllCategories($GLOBALS['idart']);
    }


    /**
     * Redirects to edit view. Is usually called after saving content.
     */
    public function redirectToEditView()
    {
        $url = $GLOBALS['sess']->url(
            $GLOBALS['cfgClient'][$GLOBALS['client']]['path']['htmlpath'] . 'front_content.php?area='
          . $GLOBALS['tmp_area'] . '&idart=' . $GLOBALS['idart'] . '&idcat=' . $GLOBALS['idcat']
          . '&lang=' . $GLOBALS['lang'] . '&changeview=edit'
        );
#        echo '<a href="' . $url . '">' . $url . '</a>';
        header('Location: ' . $url);
    }


    /**
     * Renders the preview view at CONTENIDO backend
     *
     * @todo: add preview template
     */
    public function renderPreview()
    {
        if (isset($_POST['data'])) {
            $parser    = $this->_getParser();
            $sHTMLCode = $parser->parse($this->_prepareMarkup($_POST['data']));
        } else {
            $sHTMLCode = 'No preview data!';
        }

        if ($this->_cfg['markitup']['preview_css_file'] !== '') {
            $sCssPreview = '<link rel="stylesheet" href="' . $this->_cfg['markitup']['preview_css_file'] . '" type="text/css" media="screen" />';
        } else {
            $sCssPreview = '';
        }

        $baseTag = $this->_generatePreviewBaseTag();

        echo <<<PREVIEW
<!doctype html>
<head>
    $baseTag
    <title>markItUp! preview</title>
    $sCssPreview
    <style type="text/css"><!--
    .markitupWrap   {margin:10px;}
    // --></style>
</head>
<body>
<div class="markitupWrap">
$sHTMLCode
</div>
</body>
</html>
PREVIEW;
    }


    /**
     * Renders the editor for current markup at CONTENIDO backend.
     *
     * @param   array  $contents  Some additional contents, not used at the moment!
     * @return  void
     */
    public function renderEditor(array $contents = array())
    {
        // load all available content types
        markitup_getAvailableContentTypes($GLOBALS['idartlang']);
        $GLOBALS['tmp_area']  = 'con_editcontent';
        $cancelUrl = $GLOBALS['sess']->url(
            $GLOBALS['cfgClient'][$GLOBALS['client']]['path']['htmlpath'] . 'front_content.php?area='
          . $GLOBALS['tmp_area'] . '&idart=' . $GLOBALS['idart'] . '&idcat=' . $GLOBALS['idcat']
          . '&lang=' . $GLOBALS['lang']
        );

        // set markItUp configuration
        $mIU_setCfg = (isset($this->_cfg['markitup']['sets'][$this->_markupName])) ? $this->_cfg['markitup']['sets'][$this->_markupName] : array();
        $mIU_jsCode = (isset($mIU_setCfg['js_code'])) ? $mIU_setCfg['js_code'] : '// no js code';
        if ($mIU_jsCode !== '// no js code') {
            $previewUrl = $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['includes']
                       . 'include.backendedit.php?' . 'contenido=' . $GLOBALS['contenido'] . '&lang=' . $GLOBALS['lang']
                       . '&typenr=' . $GLOBALS['typenr'] . '&idart=' . $GLOBALS['idart'] . '&action=' . $GLOBALS['action']
                       . '&type=' . $GLOBALS['type'] . '&idcat=' . $GLOBALS['idcat'] . '&idartlang=' . $GLOBALS['idartlang']
                       . '&changeview=edit&domarkituppreview=1';
            $mIU_jsCode = str_replace('{PREVIEWPARSERPATH}', $previewUrl, $mIU_jsCode);
        }

        $mIU_preEditorArea  = (isset($mIU_setCfg['pre_editor_area'])) ? $mIU_setCfg['pre_editor_area'] : '<!-- no pre_editor_area -->';
        $mIU_postEditorArea = (isset($mIU_setCfg['post_editor_area'])) ? $mIU_setCfg['post_editor_area'] : '<!-- no post_editor_area -->';

        // create template object and set contents
        $oTpl = new Template();
        $oTpl->set('s', 'CHARSET', $GLOBALS['encoding'][$GLOBALS['lang']]);
        $oTpl->set('s', 'MARKITUP_SET', $this->_markupName);
        $oTpl->set('s', 'TITLE', 'CONTENIDO :: Plugin :: markitup :: ' . $this->_cmsTypeName);
        $oTpl->set('s', 'PATH_PLUGIN', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['plugins'] . 'markitup/');
        $oTpl->set('s', 'PATH_STYLES', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['styles']);
        $oTpl->set('s', 'DESCRIPTION', $GLOBALS['typenr'] . '. ' . $GLOBALS['a_description'][$GLOBALS['type']][$GLOBALS['typenr']] . ':');
        $oTpl->set('s', 'FORM_ACTION', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['includes'] . 'include.backendedit.php');
        $oTpl->set('s', 'MARKITUP_JS_CODE', $mIU_jsCode);
        $oTpl->set('s', 'SESSION_NAME', $GLOBALS['sess']->name);
        $oTpl->set('s', 'SESSION_ID', $GLOBALS['sess']->id);
        $oTpl->set('s', 'LANG', $GLOBALS['lang']);
        $oTpl->set('s', 'TYPENR', $GLOBALS['typenr']);
        $oTpl->set('s', 'IDART', $GLOBALS['idart']);
        $oTpl->set('s', 'TYPE', $GLOBALS['type']);
        $oTpl->set('s', 'IDCAT', $GLOBALS['idcat']);
        $oTpl->set('s', 'IDARTLANG', $GLOBALS['idartlang']);
        $oTpl->set('s', 'CMS_TYPE', $this->_cmsTypeName);
        $oTpl->set('s', 'SYNTAX_CHEATSHEET', markitup_getSyntaxCheatsheet($this->_markupName));

        $oTpl->set('s', 'PRE_EDITOR_AREA', $mIU_preEditorArea);
        $oTpl->set('s', 'EDITOR_CONTENT', urldecode($GLOBALS['a_rawcontent'][$GLOBALS['type']][$GLOBALS['typenr']]));
        $oTpl->set('s', 'POST_EDITOR_AREA', $mIU_postEditorArea);

        $oTpl->set('s', 'CANCEL_URL', $cancelUrl);
        $oTpl->set('s', 'PATH_IMAGES', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['images']);

        $oTpl->generate(
            $this->_cfg['path']['contenido'] . $this->_cfg['path']['plugins'] . 'markitup/templates/include.CMS_MARKITUP.html', 0, 0
        );
    }


    /**
     * Returns the parser object
     *
     * @param   bool  $setConfig  Flag to set parser configuration
     * @return  I_MarkupParser  A parser based on interface I_MarkupParser
     */
    protected function _getParser($setConfig = true)
    {
        plugin_include('markitup', 'classes/' . $this->_parserFile);
        $parser = new $this->_parserName;
        if ($setConfig) {
            $parser->setConfig($this->_cfg['markitup']);
        }
        return $parser;
    }


    /**
     * Prepare markup
     *
     * @param  string  $markup
     * @return  string
     */
     protected function _prepareMarkup($markup)
     {
        $markup = stripslashes(trim($markup));
        $markup = str_replace("\n\r", "\n", $markup);
        $markup = str_replace("\r\n", "\n", $markup);
        return $markup;
     }


    /**
     * Generates the base tag which will be added to the preview page
     *
     * @return  string
     */
    protected function _generatePreviewBaseTag()
    {
        // Code is taken over from front_content.php

        $baseUri = $GLOBALS['cfgClient'][$GLOBALS['client']]['path']['htmlpath'];

        // CEC for base href generation
        $baseUri = CEC_Hook::executeAndReturn('Contenido.Frontend.BaseHrefGeneration', $baseUri);

        if (PI_MARKITUP_ISXHTML) {
            $baseTag = '<base href="' . $baseUri . '" />';
        } else {
            $baseTag = '<base href="' . $baseUri . '">';
        }

        return $baseTag;
    }

}