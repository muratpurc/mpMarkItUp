<?php
/**
 * Contains Texy parser for markItUp! plugin.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */

defined('CON_FRAMEWORK') or die('Illegal call');


include_once('interface.markupparser.php');
plugin_include('markitup', 'libs/parser/texy-2.0-beta2/texy/texy.php');


/**
 * Contains Texy parser for markItUp! plugin.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
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
#        $text = parent::normalize($text);
        return $this->process($text);
    }

}
