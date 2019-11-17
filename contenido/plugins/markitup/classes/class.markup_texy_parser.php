<?php

include_once('interface.markupparser.php');

plugin_include('markitup', 'libs/parser/texy-2.0-beta2/texy/texy.php');

class Markup_TexyParser extends Texy implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        parent::__construct();
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
        // @todo: set texy configuration
        return $this->process($text);
    }

}
