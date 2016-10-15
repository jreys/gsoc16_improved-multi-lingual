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
 * Contact Associations helper.
 *
 * @since  __DEPLOY_VERSION__
 */
class ContactAssociationsHelper extends JHelperContent
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
			'title'            => 'name',
			'created_time'     => 'created',
			'created_user_id'  => 'created_by',
			'modified_time'    => 'modified',
			'modified_user_id' => 'modified_by',
		);
	}
}
