<?php

include_once('interface.markupparser.php');

plugin_include('markitup', 'libs/parser/textile-2.0.0/classTextile.php');

class Markup_TextileParser extends Textile implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        parent::Textile();
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
        return $this->TextileThis($text);
    }

}
