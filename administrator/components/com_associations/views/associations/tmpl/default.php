<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');

$user   = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$componentFilter = $this->state->get('associationcomponent');

if ($componentFilter != '')
{
	$parts     = explode('.', $componentFilter);
	$comp      = $parts[0];
	$assocItem = $parts[1];

	JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/' . $comp . '/helpers/html');

	// Get the value in the Association column
	if ($comp == "com_content")
	{
		$assocValue = "contentadministrator.association";
	}
	elseif ($comp == "com_categories")
	{
		$assocValue = "categoriesadministrator.association";
	}
	elseif ($comp == "com_menus")
	{
		$assocValue = "MenusHtml.Menus.association";
	}
	else
	{
		$assocValue = $assocItem . '.association';
	}

	// If it's not a category
	if ($componentFilter != '' && !strpos($componentFilter, '|'))
	{
		$componentSplit = explode('.', $componentFilter);
		$aComponent = $componentSplit[0];
		$aView = $componentSplit[1];
	}
	elseif ($componentFilter != '') {
		$componentSplit = explode('|', $componentFilter);
		$aComponent = 'com_categories';
		$aView = $componentSplit[1];
	}

	if (isset($aComponent) && isset($aView))
	{
		$link = 'index.php?option=com_associations&view=association&layout=edit&acomponent='
		. $aComponent . '&aview=' . $aView . '&id=';
	}
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_associations&view=associations'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">

	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filterButton' => false))); ?>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="table table-striped" id="contactList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?>
					</th>
					<th width="15%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_ASSOCIATIONS_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php $n = count($this->items); ?>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="nowrap has-context">
						<div class="pull-left">
							<a href="<?php echo JRoute::_($link . (int) $item->id); ?>"><?php echo $this->escape($item->title); ?></a>
						</div>
					</td>
					<td class="small hidden-phone">
						<?php echo $item->language_title ? JHtml::_('image', 'mod_languages/' . $item->language_image . '.gif', $item->language_title, array('title' => $item->language_title), true) . '&nbsp;' . $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					</td>
					<td>
						<?php if ($item->association) : ?>
							<?php echo JHtml::_($assocValue, $item->id); ?>
						<?php endif; ?>
					</td>
					<td class="hidden-phone">
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td>
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>

	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
