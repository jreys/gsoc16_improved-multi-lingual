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
		$realView            = !is_null($component->extension) ? $component->extension : $component->item;

		JLoader::register($component->associations->gethelper->class, $component->associations->gethelper->file);

		$associations      = call_user_func(
				array(
					$component->associations->gethelper->class, 
					$component->associations->gethelper->method), 
					$referenceId, $realView
			);

		// Get reference language.
		$table         = clone $component->table;
		$table->load($referenceId);
		$referenceLang = $table->{$component->fields->language};
		$user          = JFactory::getUser();

		$existingLanguages = JHtml::_('contentlanguage.existing', false, true);

		foreach ($existingLanguages as $key => $lang)
		{
			// If is equal to reference language
			if ($lang->value == $referenceLang)
			{
				unset($existingLanguages[$key]);
			}
			if (isset($associations[$lang->value]))
			{
				if ($component->component != 'com_menus')
				{
					parse_str($associations[$lang->value], $contents);
					$removeExtra  = explode(":", $contents['id']);
					$itemId       = $removeExtra[0];
					$lang->value  = $lang->value . "|" . $itemId;
				}
				else
				{
					$itemId      = $associations[$lang->value];
					$lang->value = $lang->value . "|" . $associations[$lang->value];
				}

				$canEdit    = $user->authorise('core.edit', $component->assetKey . '.' . $itemId);
				$table->load($itemId);

				if (!is_null($table->{$component->fields->created_by}))
				{
					$canEditOwn = $user->authorise('core.edit.own', $component->assetKey . '.' . $itemId) && $table->{$component->fields->created_by} == $user->id;
					$canEdit    = $canEdit || $canEditOwn;
				}

				if (!$canEdit)
				{
					$lang->disable = true;
				}	
			}
			else
			{
				$lang->value .= '|0';
				$canCreate    = $user->authorise('core.create', $component->assetKey);

				if (!$canCreate)
				{
					$lang->disable = true;
				}
			}
			
		}

		$options = array_merge(parent::getOptions(), $existingLanguages);

		return $options;
	}
}
