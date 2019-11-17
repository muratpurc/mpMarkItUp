<?php

include_once('interface.markupparser.php');

plugin_include('markitup', 'libs/parser/markitup.bbcode-parser/markitup.bbcode-parser.php');

class Markup_BBCodeParser implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        // donut
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
        if (!defined('EMOTICONS_DIR')) {
            define('EMOTICONS_DIR', $this->_aCfg['emoticon_path']);
        }
    	return BBCode2Html($text);
    }

}

