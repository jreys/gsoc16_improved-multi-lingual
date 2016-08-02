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
 * Association edit controller class.
 *
 * @since  __DEPLOY_VERSION__
 */
class AssociationsControllerAssociation extends JControllerForm
{
	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 *                           (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		$cp = AssociationsHelper::getComponentProperties($this->input->get('component', '', 'string'));

		if (!is_null($cp->fields->checked_out))
		{
			$id    = $this->input->get('id', 0, 'int');
			$table = clone $cp->table;
			$table->load($id);

			// Attempt to check-out the record for editing and redirect.
			if (!in_array($table->{$cp->fields->checked_out}, array(JFactory::getUser()->id, 0)) && !$cp->model->checkout($id))
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $cp->model->getError()), 'error');
				$this->setRedirect(JRoute::_('index.php?option=com_associations&view=associations', false));

				return false;
			}
		}

		return parent::display();
	}

	/**
	 * Method for closing the template.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  void.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$cp = AssociationsHelper::getComponentProperties($this->input->get('component', '', 'string'));

		// Only check in, if component allows to check out.
		if (!is_null($cp->fields->checked_out))
		{
			// Check-in reference id.
			$cp->table->checkin($this->input->get('id', null, 'int'));

			// Check-in all ithe target ids (can be several, one for each language).
			if ($targetsId = $this->input->get('target-id', '', 'string'))
			{
				$targetsId = array_unique(explode(',', $targetsId));

				foreach ($targetsId as $key => $targetId)
				{
					$cp->table->checkin((int) $targetId);
				}
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_associations&view=associations', false));
	}
}
