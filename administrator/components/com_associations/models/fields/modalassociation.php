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
		$script[] = '		document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '		jQuery("#associationSelect' . $this->id . 'Modal").modal("hide");';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html = array();

		$linkAssociations = 'index.php?option=com_associations&amp;view=associations&amp;layout=modal&amp;tmpl=component'
			. '&amp;function=jSelectAssociation_' . $this->id;

		if (isset($this->element['language']))
		{
			$linkAssociations .= '&amp;forcedLanguage=' . $this->element['language'];
		}

		$urlSelect = $linkAssociations . '&amp;' . JSession::getFormToken() . '=1';

		// Select custom association button
		$html[] = '<a'
			. ' class="btn hasTooltip"'
			. ' data-toggle="modal"'
			. ' role="button"'
			. ' href="#associationSelect' . $this->id . 'Modal"'
			. ' title="' . JHtml::tooltipText('COM_ASSOCIATIONS_SELECT_TARGET') . '">'
			. '<span class="icon-file"></span> ' . JText::_('JSELECT')
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

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   3.4
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_id', parent::getLabel());
	}
}