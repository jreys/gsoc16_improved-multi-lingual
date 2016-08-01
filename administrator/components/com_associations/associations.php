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
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

$input     = JFactory::getApplication()->input;
$component = $input->get('component', '', 'string');
$splitcpnt = explode('.', $component);
$component = $splitcpnt[0];
JLoader::register('AssociationsHelper', __DIR__ . '/helpers/associations.php');

// Check if user has permission to access the component
if ($component = JFactory::getApplication()->input->get('component', '', 'string'))
{
	$cp = AssociationsHelper::getComponentProperties($component);

	if (!$cp->associations->support)
	{
		throw new Exception(JText::_('COM_ASSOCIATIONS_COMPONENT_NOT_SUPPORTED') . " " . $cp->realcomponent, 404);
	}

	if (!JFactory::getUser()->authorise('core.manage', $cp->realcomponent))
	{
		throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
	}
}

JLoader::register('AssociationsHelper', __DIR__ . '/helpers/associations.php');

$controller = JControllerLegacy::getInstance('Associations');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
