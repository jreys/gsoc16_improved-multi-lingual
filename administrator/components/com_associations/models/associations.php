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
 * Methods supporting a list of article records.
 *
 * @since  __DEPLOY_VERSION__
 */
class AssociationsModelAssociations extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since  __DEPLOY_VERSION__
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'a.name',
				'a.ordering',
				'language_title',
				'association',
				'associationlanguage',
				'associationcomponent',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = 'a.id', $direction = 'desc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
		$this->setState('associationlanguage', $this->getUserStateFromRequest($this->context . '.associationlanguage', 'associationlanguage', '', 'string'));
		$this->setState('associationcomponent', $this->getUserStateFromRequest($this->context . '.associationcomponent', 'associationcomponent', '', 'string'));

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('associationlanguage');
		$id .= ':' . $this->getState('associationcomponent');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db        = $this->getDbo();
		$query     = $db->getQuery(true);
		$user      = JFactory::getUser();
		$app       = JFactory::getApplication();
		$assoc     = JLanguageAssociations::isEnabled();
		$component = $this->getState('associationcomponent');

		// If it's not a category
		if (!strpos($component, '|'))
		{
			$componentSplit     = explode('.', $component);
			$componentModelPath = JPATH_ADMINISTRATOR . '/components/' . $componentSplit[0]	
				. '/models/' . $componentSplit[1] . '.php';
			$componentModel     = file_get_contents($componentModelPath);

			if ($position = strpos($componentModel, 'getAssociations'))
			{
				// Searching for , '#__table' , after getAssociations(
				$start = strpos($componentModel, ',', $position) + 2;
				$end = strpos($componentModel, ',', $start) - 1;
				
				if ($componentSplit[0] == 'com_menus')
				{
					$table = '#__menu';
				}
				else 
				{
					$table = str_replace("'", "", substr($componentModel, $start, $end - $start));
				}
				
				$columns = $db->getTableColumns($table);

				if (!isset($columns['title']))
				{
					$title = 'a.name';
				}
				else
				{
					$title = 'a.title';
				}

				$query->select('a.id, ' . $title . ' AS title, a.language');
				
				$query->from($db->quoteName($table, 'a'));

				// Join over the language
				$query->select('l.title AS language_title, l.image AS language_image')
					->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

				// Join over the associations.

				if ($assoc)
				{
					$query->select('COUNT(' . $db->quoteName('asso2.id') . ') > 1 as ' . $db->quoteName('association'))
						->join(
							'LEFT',
							$db->quoteName('#__associations', 'asso') . ' ON ' . $db->quoteName('asso.id') . ' = ' . $db->quoteName('a.id')
							. ' AND ' . $db->quoteName('asso.context') . ' = ' . $db->quote($componentSplit[0] . '.item')
						)
						->join(
							'LEFT',
							$db->quoteName('#__associations', 'asso2') . ' ON ' . $db->quoteName('asso2.key') . ' = ' . $db->quoteName('asso.key')
						)
						->group(
							$db->quoteName(
								array(
									'a.id',
									$title,
									'a.language'
								)
							)
						);
				}

				if ($componentSplit[0] == 'com_menus')
				{
					// Exclude the root category.
					$query->where('a.id > 1')
						->where('a.client_id = 0');
				}

				// Filter on the language.
				if ($language = $this->getState('associationlanguage'))
				{
					$query->where($db->quoteName('a.language') . ' = ' . $db->quote($language));
				}
			}
		}
		// If it's a category
		elseif (strpos($component, '|'))
		{
			$componentSplit = explode('|', $component);
			$extension = $componentSplit[1];

			// Select the required fields from the table.
			$query->select('a.id, a.title, a.language');
			$query->from('#__categories AS a');

			// Join over the language
			$query->select('l.title AS language_title, l.image AS language_image')
				->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

			// Join over the users for the checked out user.
			$query->select('uc.name AS editor')
				->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

			// Join over the asset groups.
			$query->select('ag.title AS access_level')
				->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

			// Join over the users for the author.
			$query->select('ua.name AS author_name')
				->join('LEFT', '#__users AS ua ON ua.id = a.created_user_id');

			if ($assoc)
			{
				$query->select('COUNT(asso2.id)>1 as association')
					->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_categories.item'))
					->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
					->group('a.id, l.title, uc.name, ag.title, ua.name');
			}

			$query->where('a.extension = ' . $db->quote($extension));

			// Filter on the language.
			if ($language = $this->getState('associationlanguage'))
			{
				$query->where($db->quoteName('a.language') . ' = ' . $db->quote($language));
			}
			
		}

		return $query;
	}

}
