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

namespace MU\SoundModule\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\GroupsModule\Constant as GroupsConstant;
use Zikula\GroupsModule\Entity\RepositoryInterface\GroupRepositoryInterface;

/**
 * Configuration form type base class.
 */
abstract class AbstractConfigType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var array
     */
    protected $moduleVars;

    /**
     * ConfigType constructor.
     *
     * @param TranslatorInterface      $translator      Translator service instance
     * @param object                   $moduleVars      Existing module vars
     * @param GroupRepositoryInterface $groupRepository GroupRepository service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        $moduleVars,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->setTranslator($translator);
        $this->moduleVars = $moduleVars;

        // prepare group selector values
        foreach (['moderationGroupForAlbums'] as $groupFieldName) {
            $groupId = intval($this->moduleVars[$groupFieldName]);
            if ($groupId < 1) {
                // fallback to admin group
                $groupId = GroupsConstant::GROUP_ID_ADMIN;
            }
            $this->moduleVars[$groupFieldName] = $groupRepository->find($groupId);
        }
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addGeneralSettingsFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addListViewsFields($builder, $options);
        $this->addImagesFields($builder, $options);
        $this->addIntegrationFields($builder, $options);

        $builder
            ->add('save', SubmitType::class, [
                'label' => $this->__('Update configuration'),
                'icon' => 'fa-check',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->add('cancel', SubmitType::class, [
                'label' => $this->__('Cancel'),
                'icon' => 'fa-times',
                'attr' => [
                    'class' => 'btn btn-default',
                    'formnovalidate' => 'formnovalidate'
                ]
            ])
        ;
    }

    /**
     * Adds fields for general settings fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addGeneralSettingsFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxSizeCover', IntegerType::class, [
                'label' => $this->__('Max size cover') . ':',
                'required' => false,
                'data' => isset($this->moduleVars['maxSizeCover']) ? intval($this->moduleVars['maxSizeCover']) : intval(102400),
                'empty_data' => intval('102400'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the max size cover.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('maxSizeTrack', IntegerType::class, [
                'label' => $this->__('Max size track') . ':',
                'required' => false,
                'data' => isset($this->moduleVars['maxSizeTrack']) ? intval($this->moduleVars['maxSizeTrack']) : intval(1024000),
                'empty_data' => intval('1024000'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the max size track.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('maxSizeZip', IntegerType::class, [
                'label' => $this->__('Max size zip') . ':',
                'required' => false,
                'data' => isset($this->moduleVars['maxSizeZip']) ? intval($this->moduleVars['maxSizeZip']) : intval(1024000),
                'empty_data' => intval('1024000'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the max size zip.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('allowedExtensionCover', TextType::class, [
                'label' => $this->__('Allowed extension cover') . ':',
                'required' => false,
                'data' => isset($this->moduleVars['allowedExtensionCover']) ? $this->moduleVars['allowedExtensionCover'] : '',
                'empty_data' => 'gif, jpeg, jpg, png',
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the allowed extension cover.')
                ],
            ])
            ->add('allowedExtensionTrack', TextType::class, [
                'label' => $this->__('Allowed extension track') . ':',
                'required' => false,
                'data' => isset($this->moduleVars['allowedExtensionTrack']) ? $this->moduleVars['allowedExtensionTrack'] : '',
                'empty_data' => 'mp3',
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the allowed extension track.')
                ],
            ])
        ;
    }

    /**
     * Adds fields for moderation fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('moderationGroupForAlbums', EntityType::class, [
                'label' => $this->__('Moderation group for albums') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Used to determine moderator user accounts for sending email notifications.')
                ],
                'help' => $this->__('Used to determine moderator user accounts for sending email notifications.'),
                'data' => isset($this->moduleVars['moderationGroupForAlbums']) ? $this->moduleVars['moderationGroupForAlbums'] : '',
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Choose the moderation group for albums.')
                ],// Zikula core should provide a form type for this to hide entity details
                'class' => 'ZikulaGroupsModule:GroupEntity',
                'choice_label' => 'name',
                'choice_value' => 'gid'
            ])
        ;
    }

    /**
     * Adds fields for list views fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addListViewsFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('albumEntriesPerPage', IntegerType::class, [
                'label' => $this->__('Album entries per page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The amount of albums shown per page')
                ],
                'help' => $this->__('The amount of albums shown per page'),
                'required' => false,
                'data' => isset($this->moduleVars['albumEntriesPerPage']) ? intval($this->moduleVars['albumEntriesPerPage']) : intval(10),
                'empty_data' => intval('10'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the album entries per page.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('linkOwnAlbumsOnAccountPage', CheckboxType::class, [
                'label' => $this->__('Link own albums on account page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Whether to add a link to albums of the current user on his account page')
                ],
                'help' => $this->__('Whether to add a link to albums of the current user on his account page'),
                'required' => false,
                'data' => (bool)(isset($this->moduleVars['linkOwnAlbumsOnAccountPage']) ? $this->moduleVars['linkOwnAlbumsOnAccountPage'] : true),
                'attr' => [
                    'title' => $this->__('The link own albums on account page option.')
                ],
            ])
            ->add('trackEntriesPerPage', IntegerType::class, [
                'label' => $this->__('Track entries per page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The amount of tracks shown per page')
                ],
                'help' => $this->__('The amount of tracks shown per page'),
                'required' => false,
                'data' => isset($this->moduleVars['trackEntriesPerPage']) ? intval($this->moduleVars['trackEntriesPerPage']) : intval(10),
                'empty_data' => intval('10'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the track entries per page.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('linkOwnTracksOnAccountPage', CheckboxType::class, [
                'label' => $this->__('Link own tracks on account page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Whether to add a link to tracks of the current user on his account page')
                ],
                'help' => $this->__('Whether to add a link to tracks of the current user on his account page'),
                'required' => false,
                'data' => (bool)(isset($this->moduleVars['linkOwnTracksOnAccountPage']) ? $this->moduleVars['linkOwnTracksOnAccountPage'] : true),
                'attr' => [
                    'title' => $this->__('The link own tracks on account page option.')
                ],
            ])
            ->add('collectionEntriesPerPage', IntegerType::class, [
                'label' => $this->__('Collection entries per page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The amount of collections shown per page')
                ],
                'help' => $this->__('The amount of collections shown per page'),
                'required' => false,
                'data' => isset($this->moduleVars['collectionEntriesPerPage']) ? intval($this->moduleVars['collectionEntriesPerPage']) : intval(10),
                'empty_data' => intval('10'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the collection entries per page.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('linkOwnCollectionsOnAccountPage', CheckboxType::class, [
                'label' => $this->__('Link own collections on account page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Whether to add a link to collections of the current user on his account page')
                ],
                'help' => $this->__('Whether to add a link to collections of the current user on his account page'),
                'required' => false,
                'data' => (bool)(isset($this->moduleVars['linkOwnCollectionsOnAccountPage']) ? $this->moduleVars['linkOwnCollectionsOnAccountPage'] : true),
                'attr' => [
                    'title' => $this->__('The link own collections on account page option.')
                ],
            ])
        ;
    }

    /**
     * Adds fields for images fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addImagesFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enableShrinkingForAlbumUploadCover', CheckboxType::class, [
                'label' => $this->__('Enable shrinking') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Whether to enable shrinking huge images to maximum dimensions. Stores downscaled version of the original image.')
                ],
                'help' => $this->__('Whether to enable shrinking huge images to maximum dimensions. Stores downscaled version of the original image.'),
                'required' => false,
                'data' => (bool)(isset($this->moduleVars['enableShrinkingForAlbumUploadCover']) ? $this->moduleVars['enableShrinkingForAlbumUploadCover'] : false),
                'attr' => [
                    'title' => $this->__('The enable shrinking option.'),
                    'class' => 'shrink-enabler'
                ],
            ])
            ->add('shrinkWidthAlbumUploadCover', IntegerType::class, [
                'label' => $this->__('Shrink width') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The maximum image width in pixels.')
                ],
                'help' => $this->__('The maximum image width in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['shrinkWidthAlbumUploadCover']) ? intval($this->moduleVars['shrinkWidthAlbumUploadCover']) : intval(800),
                'empty_data' => intval('800'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the shrink width.') . ' ' . $this->__('Only digits are allowed.'),
                    'class' => 'shrinkdimension-shrinkwidthalbumuploadcover'
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('shrinkHeightAlbumUploadCover', IntegerType::class, [
                'label' => $this->__('Shrink height') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The maximum image height in pixels.')
                ],
                'help' => $this->__('The maximum image height in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['shrinkHeightAlbumUploadCover']) ? intval($this->moduleVars['shrinkHeightAlbumUploadCover']) : intval(600),
                'empty_data' => intval('600'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the shrink height.') . ' ' . $this->__('Only digits are allowed.'),
                    'class' => 'shrinkdimension-shrinkheightalbumuploadcover'
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailModeAlbumUploadCover', ChoiceType::class, [
                'label' => $this->__('Thumbnail mode') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail mode (inset or outbound).')
                ],
                'help' => $this->__('Thumbnail mode (inset or outbound).'),
                'data' => isset($this->moduleVars['thumbnailModeAlbumUploadCover']) ? $this->moduleVars['thumbnailModeAlbumUploadCover'] : '',
                'empty_data' => 'inset',
                'attr' => [
                    'title' => $this->__('Choose the thumbnail mode.')
                ],'choices' => [
                    $this->__('Inset') => 'inset',
                    $this->__('Outbound') => 'outbound'
                ],
                'choices_as_values' => true,
                'multiple' => false
            ])
            ->add('thumbnailWidthAlbumUploadCoverView', IntegerType::class, [
                'label' => $this->__('Thumbnail width view') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail width on view pages in pixels.')
                ],
                'help' => $this->__('Thumbnail width on view pages in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['thumbnailWidthAlbumUploadCoverView']) ? intval($this->moduleVars['thumbnailWidthAlbumUploadCoverView']) : intval(32),
                'empty_data' => intval('32'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail width view.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailHeightAlbumUploadCoverView', IntegerType::class, [
                'label' => $this->__('Thumbnail height view') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail height on view pages in pixels.')
                ],
                'help' => $this->__('Thumbnail height on view pages in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['thumbnailHeightAlbumUploadCoverView']) ? intval($this->moduleVars['thumbnailHeightAlbumUploadCoverView']) : intval(24),
                'empty_data' => intval('24'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail height view.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailWidthAlbumUploadCoverDisplay', IntegerType::class, [
                'label' => $this->__('Thumbnail width display') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail width on display pages in pixels.')
                ],
                'help' => $this->__('Thumbnail width on display pages in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['thumbnailWidthAlbumUploadCoverDisplay']) ? intval($this->moduleVars['thumbnailWidthAlbumUploadCoverDisplay']) : intval(240),
                'empty_data' => intval('240'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail width display.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailHeightAlbumUploadCoverDisplay', IntegerType::class, [
                'label' => $this->__('Thumbnail height display') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail height on display pages in pixels.')
                ],
                'help' => $this->__('Thumbnail height on display pages in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['thumbnailHeightAlbumUploadCoverDisplay']) ? intval($this->moduleVars['thumbnailHeightAlbumUploadCoverDisplay']) : intval(180),
                'empty_data' => intval('180'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail height display.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailWidthAlbumUploadCoverEdit', IntegerType::class, [
                'label' => $this->__('Thumbnail width edit') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail width on edit pages in pixels.')
                ],
                'help' => $this->__('Thumbnail width on edit pages in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['thumbnailWidthAlbumUploadCoverEdit']) ? intval($this->moduleVars['thumbnailWidthAlbumUploadCoverEdit']) : intval(240),
                'empty_data' => intval('240'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail width edit.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailHeightAlbumUploadCoverEdit', IntegerType::class, [
                'label' => $this->__('Thumbnail height edit') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail height on edit pages in pixels.')
                ],
                'help' => $this->__('Thumbnail height on edit pages in pixels.'),
                'required' => false,
                'data' => isset($this->moduleVars['thumbnailHeightAlbumUploadCoverEdit']) ? intval($this->moduleVars['thumbnailHeightAlbumUploadCoverEdit']) : intval(180),
                'empty_data' => intval('180'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail height edit.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
        ;
    }

    /**
     * Adds fields for integration fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addIntegrationFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabledFinderTypes', ChoiceType::class, [
                'label' => $this->__('Enabled finder types') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Which sections are supported in the Finder component (used by Scribite plug-ins).')
                ],
                'help' => $this->__('Which sections are supported in the Finder component (used by Scribite plug-ins).'),
                'data' => isset($this->moduleVars['enabledFinderTypes']) ? $this->moduleVars['enabledFinderTypes'] : '',
                'empty_data' => '',
                'attr' => [
                    'title' => $this->__('Choose the enabled finder types.')
                ],'choices' => [
                    $this->__('Album') => 'album',
                    $this->__('Track') => 'track',
                    $this->__('Collection') => 'collection'
                ],
                'choices_as_values' => true,
                'multiple' => true
            ])
        ;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'musoundmodule_config';
    }
}
