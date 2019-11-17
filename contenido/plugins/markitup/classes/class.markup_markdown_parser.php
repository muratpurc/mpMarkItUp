<?php

include_once('interface.markupparser.php');

plugin_include('markitup', 'libs/parser/markdown/Markdown.php');

class Markup_MarkdownParser extends Markdown_Parser implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        // donut
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
    	return $this->transform($text);
    }

}

