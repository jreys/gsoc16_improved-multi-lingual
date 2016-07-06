<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('groupedlist');
/**
 * A drop down containing all valid HTTP 1.1 response codes.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_redirect
 * @since       __DEPLOY_VERSION__
 */
class JFormFieldAssociatedComponent extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $type = 'AssociatedComponent';

	protected $articles = array(
		'com_content.articles' => 'Articles',
		'com_content.categories' => 'Categories',
	);
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getGroups()
	{
		$options = array();
		foreach ($this->articles as $key => $value)
		{
			$options[] = JHtml::_('select.option', $key, $value);
		}
		$options1 = array();
		$options1['Content'] = $options;
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getGroups(), $options1);

		$componentsDirectory = getcwd() . "/components";
		$components = scandir($componentsDirectory);

		foreach ($components as $key => $value)
		{
			$currentDir = $componentsDirectory . "/" . $value . "/models/";
			$componentModel = scandir($currentDir);
			foreach ($componentModel as $key2 => $value2)
			{
				if (is_file($currentDir . $value2))
				{
					if(exec('grep '.escapeshellarg("JLanguageAssociations::isEnabled()").' '.$currentDir . $value2))
					{
        				print_r($value . " has associations <br>");
    				}
    				break;
				}
			}
		}

		//print_r($components);

		return $options;
	}
}