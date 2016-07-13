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
 * Component Controller
 *
 * @since  __DEPLOY_VERSION__
 */
class AssociationsController extends JControllerLegacy
{
	/**
	 * @var     string  The default view.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $default_view = 'associations';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController     This object to support chaining.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view   = $this->input->get('view', 'associations');
		$layout = $this->input->get('layout', 'associations');
		$id     = $this->input->getInt('id');

		return parent::display();
	}
}
