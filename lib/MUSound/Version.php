<?php
/**
 * MUSound.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license 
 * @package MUSound
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.1 (http://modulestudio.de) at Sun Feb 09 14:58:13 CET 2014.
 */

/**
 * Version information implementation class.
 */
class MUSound_Version extends MUSound_Base_Version
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
        $meta['version']              = '1.0.0';
        // the displayed name of the module
        $meta['displayname']          = $this->__('MUSound');
        // the module description
        $meta['description']          = $this->__('MUSound - a small module to handle sound');
        
        return $meta;
        
    }
}
