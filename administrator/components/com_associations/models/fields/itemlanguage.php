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
		$realView            = $extension !== '' ? $extension : $associatedView;

		$key = $extension !== '' ? 'com_categories.category|' . $extension : $associatedComponent . '.' . $associatedView;
		$cp  = AssociationsHelper::getComponentProperties($key);
		$associations      = call_user_func(array($cp->associations->gethelper->class, $cp->associations->gethelper->method), $referenceId, $realView);
		$existingLanguages = JHtml::_('contentlanguage.existing', false, true);

		foreach ($existingLanguages as $key => $lang)
		{
			if ($lang->value == $forcedLanguage)
			{
				unset($existingLanguages[$key]);
			}
			if (isset($associations[$lang->value]))
			{
				if ($associatedComponent != 'com_menus')
				{
					parse_str($associations[$lang->value], $contents);
					$removeExtra  = explode(":", $contents['id']);
					$lang->value  = $lang->value . "|" . $removeExtra[0];
				}
				else
				{
					$lang->value = $lang->value . "|" . $associations[$lang->value];
				}
			}
			else
			{
				$lang->value = $lang->value . "|" . 0;
			}
		}

		$options = array_merge(parent::getOptions(), $existingLanguages);

		return $options;
	}
}
