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
	 * @return  void.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function cancel()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_associations&view=associations', false));
	}
}
