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
		// Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->getCmd('view', 'associations');
		$vFormat = $document->getType();
		$lName   = $this->input->getCmd('layout', 'associations');

		// Get the model and the view
		// $model = $this->getModel($vName);
		$view = $this->getView($vName, $vFormat);

		// Push the model into the view (as default).
		// $view->setModel($model, true);
		$view->setLayout($lName);

		// Push document object into the view.
		$view->document = $document;

		// Display the view
		$view->display();
	}
}
