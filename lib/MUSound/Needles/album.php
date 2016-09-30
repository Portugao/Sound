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
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

include_once 'Needles/Base/Abstractalbum.php';

/**
 * Replaces a given needle id by the corresponding content.
 *
 * @param array $args Arguments array
 *     int nid The needle id
 *
 * @return string Replaced value for the needle
 */
function MUSound_needleapi_album($args)
{
    return MUSound_needleapi_album_base($args)
}
