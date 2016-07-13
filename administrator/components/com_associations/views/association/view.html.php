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
 * View class for a list of articles.
 *
 * @since  __DEPLOY_VERSION__
 */
class AssociationsViewAssociation extends JViewLegacy
{
	/**
	 * An array of items
	 *
	 * @var  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  JPagination
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		AssociationsHelper::loadLanguageFiles();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		/*
		* @todo Review later
		*/
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		else
		{
			// In article associations modal we need to remove language filter if forcing a language.
			// We also need to change the category filter to show show categories with All or the forced language.
			if ($forcedLanguage = JFactory::getApplication()->input->get('forcedLanguage', '', 'CMD'))
			{
				// If the language is forced we can't allow to select the language, so transform the language selector filter into an hidden field.
				$languageXml = new SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
				$this->filterForm->setField($languageXml, 'filter', true);

				// Also, unset the active language filter so the search tools is not open by default with this filter.
				unset($this->activeFilters['language']);

				// One last changes needed is to change the category filter to just show categories with All language or with the forced language.
				$this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
			}
		}

		$app = JFactory::getApplication();
		$input = $app->input;
		$assoc = JLanguageAssociations::isEnabled();

		$app->getDocument()->addScriptDeclaration("
			function iframeRef( frameRef ) {
			return frameRef.contentWindow
				? frameRef.contentWindow.document
				: frameRef.contentDocument
			}

			function triggerSave() {
				var inside = iframeRef( document.getElementById('target-association') );
				inside.getElementById('applyBtn').click();
				return false;
			}
		");

		$app->getDocument()->addStyleDeclaration('
			.sidebyside .outer-panel {
				float: left;
				width: 50%;
			}
			.sidebyside .left-panel {
				border-right: 1px solid #999999 !important;
			}
			.sidebyside .right-panel {
				border-left: 1px solid #999999 !important;
			}
			.sidebyside .inner-panel {
				padding: 10px;
			}
			.sidebyside iframe {
				width: 100%;
				height: 1500px;
				border: 0 !important;
		}
		');

		$referenceId = $input->get('id', '0');
		$associatedComponent = $input->get('acomponent', '');
		$associatedView = $input->get('aview', '');

		$this->link = "";

		if ($associatedComponent == 'com_categories')
		{
			// If it's categories
			$this->link = 'index.php?option=' . $associatedComponent . '&task=category.edit&layout=modal&tmpl=component&id='
				. $referenceId . '&extension=' . $associatedView;
		}
		elseif ($associatedComponent == 'com_menus')
		{
			// If it's a menu item
			$this->link = 'index.php?option=com_menus&view=item&layout=modal&task=item.edit&tmpl=component&id=' . $referenceId;
		}
		else {
			// Any other case
			$this->link = 'index.php?option=' . $associatedComponent . '&view=' . $associatedView
			. '&layout=modal&tmpl=component&task=' . $associatedView . '.edit&id=' . $referenceId;
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		$jinput = JFactory::getApplication()->input;
		$associatedView = $jinput->get('aview', '');
		JToolbarHelper::title(JText::_('COM_ASSOCIATIONS_HEADER_EDIT'), 'contract');

		JToolbarHelper::apply($associatedView . '.apply');
		JToolbarHelper::save($associatedView . '.save');
		JToolbarHelper::save2new($associatedView . '.save2new');
		JToolbarHelper::cancel($associatedView . '.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::help('JGLOBAL_HELP');

		JHtmlSidebar::setAction('index.php?option=com_associations');
	}
}
