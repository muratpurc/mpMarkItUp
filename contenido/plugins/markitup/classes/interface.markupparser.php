<?php
/**
 * Contains interface definition for markup parser.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */

defined('CON_FRAMEWORK') or die('Illegal call');


/**
 * Interface definition for markup parser. Each used markup parser must implement this.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
interface I_MarkupParser {

    /**
     * Interface function to set parser configuration
     *
     * @param   array  Parser configuration
     */
    public function setConfig(array $config);

    /**
     * Interface function to parse markup content
     *
     * @param   string  $text  The markup content to parse
     * @return  string  Parsed HTML-Code
     */
    public function parse($text);

}
