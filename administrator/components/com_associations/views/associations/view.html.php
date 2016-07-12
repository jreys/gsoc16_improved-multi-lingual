<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of articles.
 *
 * @since  __DEPLOY_VERSION__
 */
class AssociationsViewAssociations extends JViewLegacy
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  JPagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;
	
	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$app    = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();
		
		if ($assoc)
		{
			$this->state         = $this->get('State');
			$this->filterForm    = $this->get('FilterForm');
			$this->activeFilters = $this->get('ActiveFilters');

			if (!$this->state->get('associationcomponent') == '' && !$this->state->get('associationlanguage') == '')
			{
				$this->items      = $this->get('Items');
				$this->pagination = $this->get('Pagination');
				$componentFilter  = $this->state->get('associationcomponent');
				$parts            = explode('.', $componentFilter);
				$comp             = $parts[0];
				$assocItem        = $parts[1];

				// Get the value in the Association column
				if ($comp == "com_content")
				{
					$this->assocValue = "contentadministrator.association";
				}
				elseif ($comp == "com_categories")
				{
					$this->assocValue = "categoriesadministrator.association";
				}
				elseif ($comp == "com_menus")
				{
					$this->assocValue = "MenusHtml.Menus.association";
				}
				else
				{
					$this->assocValue = $assocItem . '.association';
				}

				// If it's not a category
				if ($componentFilter != '' && !strpos($componentFilter, '|'))
				{
					$componentSplit = explode('.', $componentFilter);
					$aComponent = $componentSplit[0];
					$aView = $componentSplit[1];
				}
				elseif ($componentFilter != '') {
					$componentSplit = explode('|', $componentFilter);
					$aComponent = 'com_categories';
					$aView = $componentSplit[1];
				}

				if (isset($aComponent) && isset($aView))
				{
					$this->link = 'index.php?option=com_associations&view=association&layout=edit&acomponent='
					. $aComponent . '&aview=' . $aView . '&id=';
				}

			}
			else
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_ASSOCIATIONS_NOTICE_NO_SELECTORS'), 'notice');
			}

			// Check for errors.
			if (count($errors = $this->get('Errors')))
			{
				JError::raiseError(500, implode("\n", $errors));

				return false;
			}

			$this->addToolbar();

			// Will add sidebar if needed $this->sidebar = JHtmlSidebar::render();
			parent::display($tpl);
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_ASSOCIATIONS_ERROR_NO_ASSOC'), 'error');
		}
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolbar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_ASSOCIATIONS_HEADER_SELECT_REFERENCE'), 'contract');
		/* 
		 * @todo Verify later if new/edit/select is really needed
		*/
		// JToolbarHelper::editList('association.edit');

		if ($user->authorise('core.admin', 'com_associations') || $user->authorise('core.options', 'com_associations'))
		{
			JToolbarHelper::preferences('com_associations');
		}

		JToolbarHelper::help('JGLOBAL_HELP');
	}
}
