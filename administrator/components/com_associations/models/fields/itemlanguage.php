<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  __DEPLOY_VERSION__
 */
class JFormFieldItemLanguage extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   __DEPLOY_VERSION__
	 */
	protected $type = 'ItemLanguage';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getOptions()
	{
		$options = array();

		$input = JFactory::getApplication()->input;

		$referenceId         = $input->get('id', '');
		$associatedComponent = $input->get('acomponent', '');
		$associatedView      = $input->get('aview', '');
		$extension           = $input->get('extension', '');
		$forcedLanguage      = $input->get('forcedlanguage', '');

		if ($associatedComponent == 'com_categories')
		{
			if (file_exists(JPATH_SITE . '/components/' . $extension . '/helpers/association.php'))
			{
				$componentName = ucfirst(substr($extension, 4));
				JLoader::register($componentName . 'HelperAssociation', JPATH_SITE . '/components/' . $extension . '/helpers/association.php');


				if (method_exists($componentName . 'HelperAssociation', 'getCategoryAssociations'))
				{
					$associations = call_user_func(array($componentName . 'HelperAssociation', 'getCategoryAssociations'), $referenceId, $extension);
					$existingLanguages = JHtml::_('contentlanguage.existing', false, true);
				}
			}
		}
		elseif ($associatedComponent == 'com_menus')
		{
			$helpAssoc = 'MenusHelper';
			JLoader::register($helpAssoc, JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
			$associations = call_user_func(array($helpAssoc, 'getAssociations'), $referenceId);
			$existingLanguages = JHtml::_('contentlanguage.existing', false, true);
		}
		else
		{
			$helpAssoc = str_replace('com_', '', $associatedComponent . 'HelperAssociation');
			JLoader::register($helpAssoc, JPATH_SITE . '/components/' . $associatedComponent . '/helpers/association.php');
			$helpRoute = str_replace('com_', '', $associatedComponent . 'HelperRoute');
			JLoader::register($helpRoute, JPATH_SITE . '/components/' . $associatedComponent . '/helpers/route.php');
			if (class_exists($helpAssoc) && is_callable(array($helpAssoc, 'getAssociations')))
			{
				$associations = call_user_func(array($helpAssoc, 'getAssociations'), $referenceId, $associatedView);
				$existingLanguages = JHtml::_('contentlanguage.existing', false, true);
			}
			
		}

		foreach ($existingLanguages as $key => $lang)
		{
			if ($lang->value == $forcedLanguage)
			{
				unset($existingLanguages[$key]);
			}
			if (isset($associations[$lang->value]))
			{
				parse_str($associations[$lang->value], $contents);
				$associatedID = $contents['id'];
				$removeExtra  = explode(":", $associatedID);
				$lang->value  = $associatedComponent == 'com_menus' ? $associations[$lang->value] : $removeExtra[0];
			}
			else
			{
				$lang->value = 0;
			}
		}

		$options = array_merge(parent::getOptions(), $existingLanguages);

		return $options;
	}
}
