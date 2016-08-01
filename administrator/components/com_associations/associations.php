<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_associations'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$input     = JFactory::getApplication()->input;
$component = $input->get('component', '', 'string');
$splitcpnt = explode('.', $component);
$component = $splitcpnt[0];

// Check if user has permission to access the component
if ($component != '' && !JFactory::getUser()->authorise('core.manage', $component))
{
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

JLoader::register('AssociationsHelper', __DIR__ . '/helpers/associations.php');

$controller = JControllerLegacy::getInstance('Associations');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
