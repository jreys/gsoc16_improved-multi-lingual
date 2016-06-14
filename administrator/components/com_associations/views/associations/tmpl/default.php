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

	<div class="js-stools clearfix">
		<div class="clearfix">
			<div class="js-stools-container-bar">

				<div class="js-stools-field-filter js-stools-menutype">
					<select ...>
						<option value="">- Select Item type -</option>
						<optgroup label="Content">
							<option value="com_content.articles">- Articles</option>
							<option value="com_content.categories">- Categories</option>
						</optgroup>
						<optgroup label="Contacts">
							<option value="com_contact.contacts">- Contacts</option>
							<option value="com_contact.categories">- Categories</option>
						</optgroup>
					</select>
				</div>

				<div class="js-stools-field-filter js-stools-menutype">
					<select id="ref-language" name="ref-language">
						<option value="">- Select Reference Language -</option>
						<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', false, true), 'value', 'text'); ?>
					</select>
				</div>
			<?php if ($assoc && $input->get('layout') != 'modal') : ?>
				<a class="btn btn-primary" data-toggle="modal" role="button" href="#associationsModal" title="<?php echo JText::_('MOD_MULTILANGSTATUS'); ?>">
					<span class="icon-list "> </span> 
					Select
				</a>

			<?php echo JHtml::_(
						'bootstrap.renderModal',
						'associationsModal',
						array(
							'title'       => JText::_('COM_ASSOCIATIONS_TITLE_MODAL'),
							'url'         => JRoute::_('index.php?option=com_associations&view=associations&layout=modal&tmpl=component'),
							'height'      => '400px',
							'width'       => '800px',
							'bodyHeight'  => '70',
							'modalWidth'  => '80',
							'footer'      => '<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
									. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>',
						)
					);
			?>
			<?php endif; ?>
		 
		
		</div>
	</div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
