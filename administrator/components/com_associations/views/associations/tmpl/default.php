<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');

$input = $app->input;

$assoc = JLanguageAssociations::isEnabled();
?>

<style type="text/css">

		/* Fixed filter field in search bar */
		.js-stools .js-stools-menutype {
			float: left;
			margin-right: 10px;
			min-width: 220px;
		}
		html[dir=rtl] .js-stools .js-stools-menutype {
			float: right;
			margin-left: 10px
			margin-right: 0;
		}
		.js-stools .js-stools-container-bar .js-stools-field-filter .chzn-container {
			padding: 3px 0;
		}
	
  </style>

<form action="<?php echo JRoute::_('index.php?option=com_content&view=associations'); ?>" method="post" name="adminForm" id="adminForm">

	<div id="j-main-container">
		<?php
		// Search tools bar
		//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this), null, array('debug' => false));
		?>

	<div class="js-stools clearfix">
		<div class="clearfix">
			<div class="js-stools-container-bar">

				<div class="js-stools-field-filter js-stools-menutype">
					<select id="component" name="component">
						<option value="*">- Select Component -</option>
						<option value="articles" selected="selected">Articles</option>
					</select>
				</div>

				<div class="js-stools-field-filter js-stools-menutype">
					<select id="category" name="category">
						<option value="*">- Select Category -</option>
						<option value="en-gb" selected="selected">Category (en-gb)</option>
						<option value="uncategorized">Uncategorized</option>
					</select>
				</div>

				<div class="js-stools-field-filter js-stools-menutype">
					<select id="ref-language" name="ref-language">
						<option value="*" selected="selected">- Select Reference Language -</option>
						<option value="eng" >English</option>
						<option value="fr">French</option>
					</select>
				</div>
			<?php if ($assoc && $input->get('layout') != 'modal') : ?>
				<a class="btn btn-primary" data-toggle="modal" role="button" href="index.php?option=com_content&view=article&layout=edit&id=1#articleSelectjform_associations_pt_PTModal" title="title">
					<span class="icon-list "> </span> 
					Select
				</a>
			<?php endif; ?>
		 
		
		</div>
	</div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
