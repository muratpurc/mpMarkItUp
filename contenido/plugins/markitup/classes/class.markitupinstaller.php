<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Contains installer for markItUp! plugin.
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     Plugin_markItUp
 * @subpackage  Installer
 * @version     $Id: class.markitupinstaller.php 111 2010-02-16 14:28:51Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @link        http://www.purc.de
 *
 * {@internal
 *   created 2008-12-xx
 *   $Id: class.markitupinstaller.php 111 2010-02-16 14:28:51Z Murat $
 * }}
 */


defined('CON_FRAMEWORK') or die('Illegal call');

if (!class_exists('PluginSetupAbstract')) {
    throw new Exception('ModRewriteInstaller: Base class "PluginSetupAbstract" doesn\'t exists, classfile must be included before.');
}


/**
 * Installer for markItUp! plugin, used by plugin setup.
 *
 * @package     Plugin_markItUp
 * @subpackage  Installer
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 */
class MarkItUpInstaller extends PluginSetupAbstract implements IPluginSetup
{

    /**
     * Constructor, initializes parent.
     */
    public function _construct()
    {
        parent::_construct();
    }


    /**
     * Installs the plugin, interface function implementation.
     *
     * Handle upgrading of markItUp needed database table columns
     */
    public function install()
    {
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
    public function upgrade()
    {
        $this->install();
    }


    /**
     * Delete plugin, interface function implementation.
     *
     * Handle deleteting of markItUp needed database table columns
     */
    public function uninstall()
    {
        // remove field 'raw_value' from 'content' table
        $sql = "ALTER TABLE " . $this->_cfg['tab']['content'] . " DROP raw_value";
        $this->_db->query($sql);
    }

}
