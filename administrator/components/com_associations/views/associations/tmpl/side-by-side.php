<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; 

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();

?>

<form action="<?php echo JRoute::_('index.php?option=com_content&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_CONTENT_ARTICLE_CONTENT')); ?>
		<div class="row-fluid">
			<p> General </p>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_CONTENT_FIELDSET_PUBLISHING')); ?>
			<div class="row-fluid form-horizontal-desktop">
				<p>Publishing</p>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_CONTENT_FIELDSET_URLS_AND_IMAGES')); ?>
			<div class="row-fluid form-horizontal-desktop">
				<p>Images</p>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if ($assoc && $input->get('layout') != 'modal') : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
				<p>Associations</p>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'editor', JText::_('COM_CONTENT_SLIDER_EDITOR_CONFIG')); ?>
			<p>Editor</p>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_CONTENT_FIELDSET_RULES')); ?>
			<p>Permissions</p>
		<?php echo JHtml::_('bootstrap.endTab'); ?>


		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
		<?php echo JHtml::_('form.token'); ?>


		</div>
</form>
