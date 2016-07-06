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
 * A drop down containing all valid HTTP 1.1 response codes.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_redirect
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

	protected $articles = array(
		'com_content.articles' => 'Articles',
		'com_content.categories' => 'Categories',
	);
	
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

		$options = array(array());

		$componentsDirectory = JPATH_ADMINISTRATOR . "/components";
		$frontendComponentsDirectory = JPATH_SITE . "/components";
		$components          = scandir($componentsDirectory);

		foreach ($components as $key => $value)
		{
			$currentDir = $componentsDirectory . "/" . $value . "/models/";

			if (JFolder::exists($currentDir))
			{
				$componentModel = scandir($currentDir);

				foreach ($componentModel as $key2 => $value2)
				{
					if (JFile::exists($currentDir . $value2))
					{
						$file = file_get_contents($currentDir . $value2);

						if (strpos($file, 'protected $associationsContext'))
						{
							JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/'
								. $value . '/models', ucfirst(preg_replace('/\\.[^.\\s]{3,4}$/', '', $value2)) . 'Model');
							/** @var XxxxxModelXxxx $model */
							$model = JModelLegacy::getInstance(ucfirst($value2), ucfirst(substr($value, 4)) . 'Model', array('ignore_request' => true));

							print_r($model);

							$lang = JFactory::getLanguage();
							$lang->load($value);
							$options[JText::_($value)][] = JHtml::_('select.option', $key2, JText::_($value));
							if (JFile::exists($frontendComponentsDirectory . "/" . $value . "/helpers/association.php"))
							{
								$options[JText::_($value)][] = JHtml::_('select.option', $key2, JText::_("JCategories"));
							}
						}
					}
				}
			}
		}

		$options = array_merge(parent::getGroups(), $options);

		return $options;
	}
}