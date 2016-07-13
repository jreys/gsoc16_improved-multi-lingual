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
 * Associations component helper.
 *
 * @since  __DEPLOY_VERSION__
 */
class AssociationsHelper extends JHelperContent
{
	public static $extension = 'com_associations';
	
	/**
	 * Method to load the language files for the components using associations.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function loadLanguageFiles()
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$lang = JFactory::getLanguage();
		
		$backendComponentsDirectory         = JPATH_ADMINISTRATOR . "/components";
		$frontendComponentsDirectory = JPATH_SITE . "/components";
		$backendComponents           = glob($backendComponentsDirectory . '/*', GLOB_NOSORT | GLOB_ONLYDIR);
		$frontendComponents          = glob($frontendComponentsDirectory . '/*', GLOB_NOSORT | GLOB_ONLYDIR);

		// Keeping only directory name
		for ($i = 0; $i < count($backendComponents); $i++)
		{ 
			$backendComponents[$i] = basename($backendComponents[$i]);
		}

		// Keeping only directory name
		for ($i = 0; $i < count($frontendComponents); $i++)
		{ 
			$frontendComponents[$i] = basename($frontendComponents[$i]);
		}

		foreach ($backendComponents as $key => $value)
		{
			$currentDir = $backendComponentsDirectory . "/" . $value . "/models/";

			if (JFolder::exists($currentDir))
			{
				$componentModel = scandir($currentDir);

				foreach ($componentModel as $key2 => $value2)
				{
					if (JFile::exists($currentDir . $value2))
					{
						$file = file_get_contents($currentDir . $value2);

						if (strpos($file, 'protected $associationsContext'))
						{
							$lang->load($value, JPATH_ADMINISTRATOR, null, false, true)
								|| $lang->load($value, JPATH_ADMINISTRATOR . '/components/' . $value, null, false, true);
						}
					}
				}
			}
		}

		foreach ($frontendComponents as $key => $value)
		{
			if (JFile::exists($frontendComponentsDirectory . "/" . $value . "/helpers/association.php"))
			{
				$file = file_get_contents($frontendComponentsDirectory . "/" . $value . "/helpers/association.php");

				if (strpos($file, 'getCategoryAssociations'))
				{
					$lang->load($value, JPATH_ADMINISTRATOR, null, false, true)
						|| $lang->load($value, JPATH_ADMINISTRATOR . '/components/' . $value, null, false, true);
				}
			}
		}
	}
}
