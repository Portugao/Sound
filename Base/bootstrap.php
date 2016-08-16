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

/**
 * Bootstrap called when application is first initialised at runtime.
 *
 * This is only called once, and only if the core has reason to initialise this module,
 * usually to dispatch a controller request or API.
 */
// initialise doctrine extension listeners
$helper = ServiceUtil::getService('doctrine_extensions');
$helper->getListener('timestampable');
$helper->getListener('standardfields');
$translatableListener = $helper->getListener('translatable');
//$translatableListener->setTranslatableLocale(ZLanguage::getLanguageCode());
$currentLanguage = preg_replace('#[^a-z-].#', '', FormUtil::getPassedValue('lang', System::getVar('language_i18n', 'en'), 'GET'));
$translatableListener->setTranslatableLocale($currentLanguage);
/*
 * Sometimes it is desired to set a default translation as a fallback if record does not have a translation
 * on used locale. In that case Translation Listener takes the current value of Entity.
 * But there is a way to specify a default locale which would force Entity to not update it`s field
 * if current locale is not a default.
 */
//$translatableListener->setDefaultLocale(System::getVar('language_i18n', 'en'));

