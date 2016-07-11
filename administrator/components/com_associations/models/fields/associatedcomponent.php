<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('groupedlist');
/**
 * A drop down containing all components that implement associations
 *
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 * @since       __DEPLOY_VERSION__
 */
class JFormFieldAssociatedComponent extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $type = 'AssociatedComponent';
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getGroups()
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$options = array();

		$componentsDirectory         = JPATH_ADMINISTRATOR . '/components';
		$frontendComponentsDirectory = JPATH_SITE . '/components';
		$backendComponents           = glob($componentsDirectory . '/*', GLOB_NOSORT | GLOB_ONLYDIR);
		$frontendComponents          = glob($frontendComponentsDirectory . '/*', GLOB_NOSORT | GLOB_ONLYDIR);
		
		// Keeping only directory name
		for ($i = 0; $i < count($backendComponents); $i++)
		{ 
			$backendComponents[$i] = basename($backendComponents[$i]);
		}

		// Keeping only directory name
		for ($i = 0; $i < count($frontendComponents); $i++)
		{ 
			$frontendComponents[$i] = basename($frontendComponents[$i]);
		}
		
		$components = array_intersect($frontendComponents, $backendComponents);

		foreach ($components as $component)
		{
			$currentDir = $componentsDirectory . '/' . $component . '/models/';

			if (JFolder::exists($currentDir))
			{
				$componentModel = scandir($currentDir);

				foreach ($componentModel as $key => $value)
				{
					if (JFile::exists($currentDir . $value))
					{
						$file = file_get_contents($currentDir . $value);

						if ($component != 'com_categories' && $component != 'com_menus')
						{
							if (strpos($file, 'protected $associationsContext'))
							{
								$modelsPath = JPATH_ADMINISTRATOR . '/components/'
								. $component . '/models';

								$removeExtension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $value);

								JModelLegacy::addIncludePath($modelsPath, ucfirst($removeExtension) . 'Model');
								$model = JModelLegacy::getInstance(ucfirst($removeExtension), ucfirst(substr($component, 4)) . 'Model', array('ignore_request' => true));
								
								$options[JText::_($component)][] = JHtml::_('select.option', $model->typeAlias, JText::_($component));
							}

							if (JFile::exists($frontendComponentsDirectory . "/" . $component . "/helpers/association.php"))
							{
								$file = file_get_contents($frontendComponentsDirectory . "/" . $component . "/helpers/association.php");

								if (strpos($file, 'getCategoryAssociations'))
								{
									$options[JText::_($component)][] = JHtml::_('select.option', 'com_categories.category|' . $component, JText::_("JCATEGORIES"));
								}
							}
							break;
						}
					}
				}
			}
		}

		$options[JText::_("COM_MENUS_SUBMENU_MENUS")][] = JHtml::_('select.option', 'com_menus.item', JText::_("COM_MENUS_SUBMENU_ITEMS"));

		$options = array_merge(parent::getGroups(), $options);

		return $options;
	}
}
