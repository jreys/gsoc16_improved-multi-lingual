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
 * Newsfeeds Associations helper.
 *
 * @since  __DEPLOY_VERSION__
 */
class NewsfeedAssociationsHelper extends JHelperContent
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
		return true;
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
			'title'            => 'name',
			'alias'            => 'alias',
			'ordering'         => 'ordering',
			'menutype'         => null,
			'level'            => null,
			'catid'            => 'catid',
			'language'         => 'language',
			'access'           => 'access',
			'state'            => 'published',
			'created_user_id'  => 'created_by',
			'checked_out'      => 'checked_out',
		 	'checked_out_time' => 'checked_out_time',
		);
	}
}
