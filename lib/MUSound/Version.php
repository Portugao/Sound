<?php
/**
 * MUSound.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUSound
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de) at Fri Sep 30 16:36:10 CEST 2016.
 */

/**
 * Version information implementation class.
 */
class MUSound_Version extends MUSound_Base_AbstractVersion
{
    /**
    * Retrieves meta data information for this application.
    *
    * @return array List of meta data.
    */
    public function getMetaData()
    {
        $meta = parent::getMetaData();
        
        // the current module version
        $meta['version']              = '1.1.0';
        // the displayed name of the module
        $meta['displayname']          = $this->__('MUSound');
        // the module description
        $meta['description']          = $this->__('MUSound - a small module to handle sound');
        
        return $meta;
        
    }
}
