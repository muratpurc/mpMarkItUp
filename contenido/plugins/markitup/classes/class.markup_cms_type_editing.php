<?php

defined('CON_FRAMEWORK') or die('Illegal call');


plugin_include('markitup', 'includes/config.plugin.php');


class Markup_CmsTypeEditing {

    private $_cfg;

    private $_markupName;
    private $_cmsTypeName;
    private $_parserFile;
    private $_parserName;


    function __construct(array $cfg, array $options) {
        $this->_cfg = $cfg;

        $this->_markupName  = $options['markup_name'];
        $this->_cmsTypeName = $options['cmstype_name'];
        $this->_parserFile  = $options['parser_file'];
        $this->_parserName  = $options['parser_name'];
    }


    public function save($cmsTypeContent) {

        plugin_include('markitup', 'classes/' . $this->_parserFile);

        if (trim($cmsTypeContent) !== '') {
            $parser    = new $this->_parserName;
            $parser->setConfig($this->_cfg['markitup']);
            $sRawCode  = $cmsTypeContent;
            $sHTMLCode = $parser->parse(stripslashes($cmsTypeContent));
        } else {
            $sRawCode  = $cmsTypeContent;
            $sHTMLCode = '';
        }

        // damn globals
        global $idartlang, $typenr, $idart;

        plugin_include('markitup', 'includes/config.plugin.php');
        conSaveContentEntry ($idartlang, $this->_cmsTypeName, $typenr, $sHTMLCode);
        markitup_saveContentEntry($idartlang, $this->_cmsTypeName, $typenr, $sRawCode);
        conMakeArticleIndex ($idartlang, $idart);
        conGenerateCodeForArtInAllCategories($idart);
    }


    public function redirectToEditView() {
        // damn globals
        global $sess, $idart, $idcat, $cfgClient, $client, $tmp_area, $lang;

        $url = $sess->url($cfgClient[$client]['path']["htmlpath"]."front_content.php?area=$tmp_area&idart=$idart&idcat=$idcat&lang=$lang&changeview=edit");
        header('Location: ' . $url);
#        echo '<a href="' . $url . '">' . $url . '</a>';
    }


    public function renderEditor(array $contents=array()){
        // damn globals
        global $idartlang, $sess, $idart, $idcat, $cfgClient, $client, $tmp_area, $lang, $encoding;
        global $type, $typenr, $a_description, $a_rawcontent;

        markitup_getAvailableContentTypes($idartlang);
        $tmp_area  = 'con_editcontent';
        $cancelUrl = $sess->url($cfgClient[$client]['path']["htmlpath"] . "front_content.php?area=$tmp_area&idart=$idart&idcat=$idcat&lang=$lang");

        // set markItUp configuration
        $mIU_setCfg = (isset($this->_cfg['markitup']['sets'][$this->_markupName])) ? $this->_cfg['markitup']['sets'][$this->_markupName] : array();
        $mIU_jsCode = (isset($mIU_setCfg['js_code'])) ? $mIU_setCfg['js_code'] : '// no js code';
        $mIU_preEditorArea = (isset($mIU_setCfg['pre_editor_area'])) ? $mIU_setCfg['pre_editor_area'] : '<!-- no pre_editor_area -->';
        $mIU_postEditorArea = (isset($mIU_setCfg['post_editor_area'])) ? $mIU_setCfg['post_editor_area'] : '<!-- no post_editor_area -->';

        $oTpl = new Template();
        
        $oTpl->set('s', 'CHARSET', $encoding[$lang]);
        $oTpl->set('s', 'MARKITUP_SET', $this->_markupName);
        $oTpl->set('s', 'TITLE', 'Contenido :: Plugin :: markitup :: ' . $this->_cmsTypeName);
        $oTpl->set('s', 'PATH_PLUGIN', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['plugins'] . 'markitup/');
        $oTpl->set('s', 'PATH_STYLES', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['styles']);
        $oTpl->set('s', 'DESCRIPTION', $typenr . ' ' . $a_description[$type][$typenr] . ':');
        $oTpl->set('s', 'FORM_ACTION', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['includes'] . 'include.backendedit.php');
        $oTpl->set('s', 'MARKITUP_JS_CODE', $mIU_jsCode);
        $oTpl->set('s', 'SESSION_NAME', $sess->name);
        $oTpl->set('s', 'SESSION_ID', $sess->id);
        $oTpl->set('s', 'LANG', $lang);
        $oTpl->set('s', 'TYPENR', $typenr);
        $oTpl->set('s', 'IDART', $idart);
        $oTpl->set('s', 'TYPE', $type);
        $oTpl->set('s', 'IDCAT', $idcat);
        $oTpl->set('s', 'IDARTLANG', $idartlang);
        $oTpl->set('s', 'CMS_TYPE', $this->_cmsTypeName);
//        $oTpl->set('s', 'SYNTAX_CHEATSHEET', markitup_getSyntaxCheatsheet($this->_markupName));

        $oTpl->set('s', 'PRE_EDITOR_AREA', $mIU_preEditorArea);
        $oTpl->set('s', 'EDITOR_CONTENT', urldecode($a_rawcontent[$type][$typenr]));
        $oTpl->set('s', 'POST_EDITOR_AREA', $mIU_postEditorArea);

        $oTpl->set('s', 'CANCEL_URL', $cancelUrl);
        $oTpl->set('s', 'PATH_IMAGES', $this->_cfg['path']['contenido_fullhtml'] . $this->_cfg['path']['images']);

        $oTpl->generate(
            $this->_cfg['path']['contenido'] . $this->_cfg['path']['plugins'] . 'markitup/templates/include.CMS_MARKITUP.html', 0, 0
        );

//var_dump($a_description);

    }

}