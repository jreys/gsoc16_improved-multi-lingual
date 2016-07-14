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

		$referenceId = $input->get('id', '');
		$associatedComponent = $input->get('acomponent', '');
		$associatedView = $input->get('aview', '');

		if ($associatedComponent == 'com_categories')
		{

		}
		elseif ($associatedComponent == 'com_menus')
		{

		}
		else
		{
			$class = str_replace('com_', '', $associatedComponent . 'HelperAssociation');
			JLoader::register($class, JPATH_SITE . '/components/' . $associatedComponent . '/helpers/association.php');
			JLoader::register($class, JPATH_SITE . '/components/' . $associatedComponent . '/helpers/route.php');
			if (class_exists($class) && is_callable(array($class, 'getAssociations')))
			{
				$cassociations = call_user_func(array($class, 'getAssociations'), $referenceId, $associatedView);
			}
			print_r($cassociations);
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
