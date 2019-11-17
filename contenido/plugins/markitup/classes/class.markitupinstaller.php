<?php
/**
 * Installer for markItUp Plugin, used by plugin setup.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */


defined('CON_FRAMEWORK') or die('Illegal call');

if (!class_exists('PluginSetupAbstract')) {
    throw new Exception('ModRewriteInstaller: Base class "PluginSetupAbstract" doesn\'t exists, classfile must be included before.');
}


/**
 * Installer for markItUp Plugin, used by plugin setup.
 *
 * Some features are taken over from initial functions.mod_rewrite_setup.php file beeing created by
 * Stefan Seifarth (aka stese).
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
class MarkItUpInstaller extends PluginSetupAbstract implements IPluginSetup {

    /**
     * Constructor, initializes parent.
     */
    public function _construct(){
        parent::_construct();
    }


    /**
     * Installs the plugin, interface function implementation.
     *
     * Handle upgrading of markItUp needed database table columns
     */
    public function install(){
        // check the existance of content.raw_value
        $sql = "SELECT * FROM " . $this->_cfg['tab']['content'] . " LIMIT 0,1";
        $this->_db->query($sql);
        if (!$this->_db->next_record() || !$this->_db->f('raw_value')) {
            // add field 'raw_value' to table
            $sql = "ALTER TABLE " . $this->_cfg['tab']['content'] . " ADD raw_value LONGTEXT AFTER value";
            $this->_db->query($sql);
        }
    }


    /**
     * Upgrade plugin, interface function implementation.
     *
     * Handle upgrading of markItUp needed database table columns
     */
    public function upgrade(){
        $this->install();
    }


    /**
     * Delete plugin, interface function implementation.
     *
     * Handle deleteting of markItUp needed database table columns
     */
    public function uninstall() {
        // remove field 'raw_value' from 'content' table
        $sql = "ALTER TABLE " . $this->_cfg['tab']['content'] . " DROP raw_value";
        $this->_db->query($sql);
    }

}
