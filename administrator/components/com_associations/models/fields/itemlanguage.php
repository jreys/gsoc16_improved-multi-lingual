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

		$input         = JFactory::getApplication()->input;
		$component     = AssociationsHelper::getComponentProperties($input->get('component', '', 'string'));
		$referenceId   = $input->get('id', 0, 'int');
		$realView      = !is_null($component->extension) ? $component->extension : $component->item;

		JLoader::register($component->associations->gethelper->class, $component->associations->gethelper->file);

		// Get item associations given ID and item type
		$associations      = call_user_func(
				array(
					$component->associations->gethelper->class, 
					$component->associations->gethelper->method), 
					$referenceId, $realView
			);

		// Get reference language.
		$table            = clone $component->table;
		$table->load($referenceId);
		$referenceLang    = $table->{$component->fields->language};
		$user             = JFactory::getUser();
		$canCreate        = $user->authorise('core.create', $component->realcomponent);
		$canManageCheckin = $user->authorise('core.manage', 'com_checkin');

		$existingLanguages = JHtml::_('contentlanguage.existing', false, true);

		// Each option has the format "<lang>|<id>", example: "en-GB|1"
		foreach ($existingLanguages as $key => $lang)
		{
			// If is equal to reference language
			if ($lang->value == $referenceLang)
			{
				unset($existingLanguages[$key]);
			}

			// If association exists in this language
			if (isset($associations[$lang->value]))
			{
				// If it's a menu, there are some strings needed to be removed
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

				// Check if user can edit item
				$canEdit    = $user->authorise('core.edit', $component->assetKey . '.' . $itemId);

				$table->load($itemId);
				if (!is_null($table->{$component->fields->created_by}))
				{
					// Check if user created this item
					$canEditOwn = $user->authorise('core.edit.own', $component->assetKey . '.' . $itemId) && $table->{$component->fields->created_by} == $user->id;
					$canEdit    = $canEdit || $canEditOwn;
				}

				// Check if user can check-in item
				$canCheckin = !isset($table->{$component->fields->checked_out}) 
					|| $canManageCheckin 
					|| $table->{$component->fields->checked_out} == $user->id 
					|| $table->{$component->fields->checked_out} == 0;

				// If this fails, disable language picking for the user
				if (!($canEdit && $canCheckin))
				{
					$lang->disable = true;
				}	
			}
			else
			{
				// New item, id = 0 and disabled if user is not allowed to create new items
				$lang->value  .= '|0';
				$lang->disable = !$canCreate;
			}
			
		}

		$options = array_merge(parent::getOptions(), $existingLanguages);

		return $options;
	}
}
