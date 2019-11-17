<?php
/**
 * Contains Contenido CMS-Type editing class.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */


defined('CON_FRAMEWORK') or die('Illegal call');


plugin_include('markitup', 'includes/config.plugin.php');


/**
 * Contenido CMS-Type editing class.
 *
 * Used to save markItUp related values (contents of markItUp CMS-Types), to render the edit form
 * on article edit-view or to display the preview.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
class Markup_CmsTypeEditing {

    /**
     * Contenido configuration
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


    function __construct(array $cfg, array $options) {
        $this->_cfg = $cfg;

        $this->_markupName  = $options['markup_name'];
        $this->_cmsTypeName = $options['cmstype_name'];
        $this->_parserFile  = $options['parser_file'];
        $this->_parserName  = $options['parser_name'];
    }


    /**
     * Saves the markup content
     *
     * @param  string  $cmsTypeContent  The markup data to save
     */
    public function save($cmsTypeContent) {

        if (trim($cmsTypeContent) !== '') {
            $parser    = $this->_getParser();
            $sRawCode  = $cmsTypeContent;
            $sHTMLCode = $parser->parse(stripslashes($cmsTypeContent));
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
    public function redirectToEditView() {
        $url = $GLOBALS['sess']->url(
            $GLOBALS['cfgClient'][$GLOBALS['client']]['path']['htmlpath'] . 'front_content.php?area=' 
          . $GLOBALS['tmp_area'] . '&idart=' . $GLOBALS['idart'] . '&idcat=' . $GLOBALS['idcat']
          . '&lang=' . $GLOBALS['lang'] . '&changeview=edit'
        );
        header('Location: ' . $url);
#        echo '<a href="' . $url . '">' . $url . '</a>';
    }


    /**
     * Renders the preview
     *
     * @todo: add preview template
     */
    public function renderPreview() {
        if (isset($_POST['data'])) {
            $parser    = $this->_getParser();
            $sHTMLCode = $parser->parse(stripslashes(trim($_POST['data'])));
        } else {
            $sHTMLCode = 'No preview data!';
        }

        echo <<<PREVIEW
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>markItUp! preview</title>
    <style type="text/css"><!--
    .markitup_wrap   {margin:10px;}
    // --></style>
</head>
<body>
<div class="markitup_wrap">
$sHTMLCode
</div>
</body>
</html>
PREVIEW;
    }


    public function renderEditor(array $contents=array()){

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
        $oTpl->set('s', 'TITLE', 'Contenido :: Plugin :: markitup :: ' . $this->_cmsTypeName);
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
//        $oTpl->set('s', 'SYNTAX_CHEATSHEET', markitup_getSyntaxCheatsheet($this->_markupName));

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
    private function _getParser($setConfig=true) {
        plugin_include('markitup', 'classes/' . $this->_parserFile);
        $parser = new $this->_parserName;
        if ($setConfig) {
            $parser->setConfig($this->_cfg['markitup']);
        }
        return $parser;
    }

}