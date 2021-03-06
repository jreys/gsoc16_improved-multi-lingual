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
		$component = $this->input->get('acomponent', '', 'string');
		$view      = $this->input->get('aview', '', 'string');
		$extension = $this->input->get('extension', '', 'string');
		$refID     = $this->input->get('id', '', 'int');
		$targetID  = $this->input->get('target-id', '', 'string');

		$getCP = $extension != '' ? ('com_categories.category|' . $extension) : ($component . '.' . $view);

		$checkOutComponent = AssociationsHelper::getComponentProperties($getCP);

		if (!is_null($checkOutComponent->fields->checked_out))
		{
			$split = array_unique(explode(",", $targetID));

			// Always check-in reference id
			$checkOutComponent->table->checkin($refID);

			if ($targetID != '')
			{
				$checkOutComponent->table->checkin($targetID);
				foreach ($split as $key => $id)
				{
					$checkOutComponent->table->checkin(intval($id));
				}
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_associations&view=associations', false));
	}
}
