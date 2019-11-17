<?php

include_once('interface.markupparser.php');

plugin_include('markitup', 'libs/parser/wikiparser-1.0\class_WikiParser.php');

class Markup_WikiParser extends WikiParser implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        parent::WikiParser();
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
        return parent::parse($text);
    }

}
