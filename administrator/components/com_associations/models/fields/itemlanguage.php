<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JLoader::register('AssociationsHelper', JPATH_ADMINISTRATOR . '/components/com_associations/helpers/associations.php');
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

		$component     = AssociationsHelper::getComponentProperties($input->get('component', '', 'string'));
		$referenceId   = $input->get('id', 0, 'int');
		$realView            = isset($component->extension) ? $component->extension : $component->item;

		JLoader::register($component->associations->gethelper->class, $component->associations->gethelper->file);

		$associations      = call_user_func(
				array(
					$component->associations->gethelper->class, 
					$component->associations->gethelper->method), 
					$referenceId, $realView
			);

		// Get reference language.
		$table = clone $component->table;
		$table->load($referenceId);

		$existingLanguages = JHtml::_('contentlanguage.existing', false, true);

		foreach ($existingLanguages as $key => $lang)
		{
			// If is equal to reference language
			if ($lang->value == $table->{$component->fields->language})
			{
				unset($existingLanguages[$key]);
			}
			if (isset($associations[$lang->value]))
			{
				if ($component->component != 'com_menus')
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
				$lang->value .= '|0';
			}
		}

		$options = array_merge(parent::getOptions(), $existingLanguages);

		return $options;
	}
}
