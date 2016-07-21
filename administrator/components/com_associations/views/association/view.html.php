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

		$this->app = JFactory::getApplication();

		$this->form  = $this->get('Form');
		$input     = $this->app->input;
		$assoc     = JLanguageAssociations::isEnabled();
		
		$formData = new JInput($input->get('jform', '', 'array'));

		$associatedComponent = $input->get('acomponent', '', 'string');
		$associatedView      = $input->get('aview', '', 'string');

		$options = array(
			'option'    => $associatedComponent,
			'view'      => $associatedView,
			'extension' => '',
			'task'      => $associatedView . '.edit',
			'layout'    => 'edit',
			'tmpl'      => 'component',
			'id'        => $input->get('id', 0, 'int')
		);

		// Special cases for categories.
		if ($associatedComponent === 'com_categories')
		{
			$options['view']      = '';
			$options['task']      = 'category.edit';
			$options['extension'] = $input->get('extension', '', 'string');
		}
		// Reference item edit link.
		$this->link = 'index.php?' . http_build_query($options);
		$options['id'] = $formData->getInt('itemlanguage', 0);
		$this->targetLink = 'index.php?' . http_build_query($options);

		$this->associatedView = $associatedView;

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

		JToolbarHelper::apply('reference', 'COM_ASSOCIATIONS_SAVE_REFERENCE');
		JToolbarHelper::apply('target', 'COM_ASSOCIATIONS_SAVE_TARGET');
		JToolbarHelper::cancel('association.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::help('JGLOBAL_HELP');

		JHtmlSidebar::setAction('index.php?option=com_associations');
	}
}
