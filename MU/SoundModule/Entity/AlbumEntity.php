<?php
/**
 * Sound.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\SoundModule\Entity;

use MU\SoundModule\Entity\Base\AbstractAlbumEntity as BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for album entities.
 * @Gedmo\TranslationEntity(class="MU\SoundModule\Entity\AlbumTranslationEntity")
 * @ORM\Entity(repositoryClass="MU\SoundModule\Entity\Repository\AlbumRepository")
 * @ORM\Table(name="mu_sound_album",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class AlbumEntity extends BaseEntity
{
    // feel free to add your own methods here
}