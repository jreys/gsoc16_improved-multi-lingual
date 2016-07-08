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
JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_contact/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');

$app    = JFactory::getApplication();
$user   = JFactory::getUser();
$userId = $user->get('id');

$input = $app->input;

$assoc = JLanguageAssociations::isEnabled();

$app->getDocument()->addScriptDeclaration("
	jQuery( document ).ready(function() {
		function isValid() {
			if (jQuery('#component').val() != '' && jQuery('#ref-language').val() != '') {
				return true;
			}
			return false;
		}

		//jQuery('#filter-submit').attr('disabled', true);

		jQuery('#component').change(function() {
	  		if(isValid()) {
	  			jQuery('#filter-submit').attr('disabled', false);
	  		}
	  		else jQuery('#filter-submit').attr('disabled', true);
		});

		jQuery('#ref-language').change(function() {
	  		if(isValid()) {
	  			jQuery('#filter-submit').attr('disabled', false);
	  		}
	  		else jQuery('#filter-submit').attr('disabled', true);
		});
	});
");
?>

<form action="<?php echo JRoute::_('index.php?option=com_associations&view=associations'); ?>" method="post" name="adminForm" id="adminForm">

	<div id="j-main-container">

	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

	<div class="js-stools clearfix">
		<div class="clearfix">
			<?php if ($assoc && $input->get('layout') != 'modal') : ?>
				<button id="filter-submit" class="btn btn-primary" type="submit" title="<?php echo JText::_('MOD_MULTILANGSTATUS'); ?>">
					<span class="icon-list "> </span> 
					Select
				</button>
			<?php endif; ?>
		</div>
	</div>

	<?php if (!empty($this->items)) : ?>
		<table class="table table-striped" id="contactList">
			<thead>
				<tr>
					<th class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th width="15%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
					</th>
					<?php if ($assoc) : ?>
					<th width="5%" class="nowrap hidden-phone hidden-tablet">
						<?php echo JHtml::_('searchtools.sort', 'COM_ASSOCIATIONS_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
					</th>
					<?php endif;?>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php //echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			/**
				@todo ACL Check
			*/
			$n = count($this->items);
			foreach ($this->items as $i => $item) :
				$canCreate  = $user->authorise('core.create',     'com_menus.menu.' . $menutypeid);
				$canEdit    = $user->authorise('core.edit',       'com_menus.menu.' . $menutypeid);
				$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->get('id')|| $item->checked_out == 0;
				$canChange  = $user->authorise('core.edit.state', 'com_menus.menu.' . $menutypeid) && $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_associations&view=association&layout=edit&acomponent=com_contact&aview=contact&id=' . (int) $item->id); ?>"><?php echo $this->escape($item->title); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->title); ?>
							<?php endif; ?>
						</div>
					</td>
					<td class="small hidden-phone">
						<?php if ($item->language == '*') : ?>
							<?php echo JText::alt('JALL', 'language'); ?>
						<?php else : ?>
							<?php echo $item->language_title ? JHtml::_('image', 'mod_languages/' . $item->language_image . '.gif', $item->language_title, array('title' => $item->language_title), true) . '&nbsp;' . $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
						<?php endif; ?>
					</td>
					<?php if ($assoc) : ?>
					<td class="hidden-phone hidden-tablet">
						<?php if ($item->association) : ?>
							<?php echo JHtml::_('contact.association', $item->id); ?>
						<?php endif; ?>
					</td>
					<?php endif;?>
					<td class="hidden-phone">
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	<?php endif; ?>
	
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
