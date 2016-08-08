<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports a modal item picker.
 *
 * @since  __DEPLOY_VERSION__
 */
class JFormFieldModalAssociation extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   __DEPLOY_VERSION__
	 */
	protected $type = 'Modal_Association';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getInput()
	{
		// The active item id field.
		$value = (int) $this->value > 0 ? (int) $this->value : '';

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectAssociation_' . $this->id . '(id) {';
		$script[] = '       target = document.getElementById("target-association");';
		$script[] = '		document.getElementById("target-association").src=target.getAttribute("data-editurl") + "&task=" + target.getAttribute("data-item") + ".edit" + "&id=" + id';
		$script[] = '		jQuery("#associationSelect' . $this->id . 'Modal").modal("hide");';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html = array();

		$linkAssociations = 'index.php?option=com_associations&amp;view=associations&amp;layout=modal&amp;tmpl=component'
			. '&amp;function=jSelectAssociation_' . $this->id;

		$linkAssociations .= '&amp;language=en-GB';

		$urlSelect = $linkAssociations . '&amp;' . JSession::getFormToken() . '=1';

		// Select custom association button
		$html[] = '<a'
			. ' id="select-change"'
			. ' class="btn hasTooltip"'
			. ' data-toggle="modal"'
			. ' data-select="' . JText::_('COM_ASSOCIATIONS_SELECT_TARGET') . '"'
			. ' data-change="' . JText::_('COM_ASSOCIATIONS_CHANGE_TARGET') . '"'
			. ' role="button"'
			. ' href="#associationSelect' . $this->id . 'Modal">'
			. '<span class="icon-refresh"></span>'
			. '<span id="select-change-text"></span>'
			. '</a>';

		// Select custom association modal
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			'associationSelect' . $this->id . 'Modal',
			array(
				'title'       => JText::_('COM_ASSOCIATIONS_SELECT_TARGET'),
				'url'         => $urlSelect,
				'height'      => '400px',
				'width'       => '800px',
				'bodyHeight'  => '70',
				'modalWidth'  => '80',
				'footer'      => '<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
						. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>',
			)
		);

		$html[] = '<input type="hidden" id="' . $this->id . '_id" name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
}