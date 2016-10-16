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
 * Menus Associations helper.
 *
 * @since  __DEPLOY_VERSION__
 */
class ItemAssociationsHelper extends JHelperContent
{
	/**
	 * Check if item supports associations
	 *
	 * @return  boolean
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function hasAssociationsSupport()
	{
		return true;
	}

	/**
	 * Check if item supports category associations
	 *
	 * @return  boolean
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function hasAssociationsCategories()
	{
		return false;
	}

	/**
	 * Mapping of database columns alias.
	 *
	 * @return  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getColumnTableAlias()
	{
		return array(
			'id'               => 'id',
			'title'            => 'title',
			'alias'            => 'alias',
			'ordering'         => 'lft',
			'menutype'         => 'menutype',
			'level'            => 'level',
			'catid'            => null,
			'language'         => 'language',
			'access'           => 'access',
			'state'            => 'published',
			'created_user_id'  => null,
			'checked_out'      => 'checked_out',
		 	'checked_out_time' => 'checked_out_time',
		);
	}
}
