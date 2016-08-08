/**
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// TO use later?
//function copyRefToTarget() {
//	var ref = document.getElementById('reference-association').contentWindow.document.getElementsByName('checkbox');
//	jQuery('#reference-association').contents().find('input').each(function () {
//		//console.log(jQuery('#target-association').contents().find(this.id).text());
//		id = '#'+this.id;
//		jQuery('#target-association').contents().find(id).val((jQuery(this).val()));
//	});
//	return false;
//}

jQuery(document).ready(function($) {
	$('#toolbar-target').hide();
	$('#select-change').hide();

	// Save button actions, replacing the default Joomla.submitbutton() with custom function.
	Joomla.submitbutton = function(task)
	{
		// Using close button, normal joomla submit.
		if (task == 'association.cancel')
		{
			Joomla.submitform(task);
		}
		// Saving target or reference, send the save action to the target/reference iframe.
		else
		{
			// We need to re-enable the language field to save.

			$('#' + task + '-association').contents().find('#jform_language').attr('disabled', false);
			window.frames[task + '-association'].Joomla.submitbutton(document.getElementById('adminForm').getAttribute('data-associatedview') + '.apply');
		}

		return false;
	}

	// Preload Joomla loading layer.
	Joomla.loadingLayer('load');

	// Attach behaviour to toggle button.
	$('body').on('click', '#toogle-left-panel', function()
	{
		var referenceHide = this.getAttribute('data-hide-reference');
		var referenceShow = this.getAttribute('data-show-reference');

		if ($(this).text() === referenceHide)
		{
			$(this).text(referenceShow);
		}
		else
		{
			$(this).text(referenceHide);
		}
		
		$('#left-panel').toggle();
		$('#right-panel').toggleClass('full-width');
	});

	// Attach behaviour to language selector change event.
	$('body').on('change', '#jform_itemlanguage', function() {
		var target   = document.getElementById('target-association');
		var selected = $(this).val();

		// Populate the data attributes and load the the edit page in target frame.
		if (selected != '' && typeof selected !== 'undefined')
		{
			target.setAttribute('data-action', selected.split(':')[2]);
			target.setAttribute('data-id', selected.split(':')[1]);
			target.setAttribute('data-language', selected.split(':')[0]);

			// Iframe load start, show Joomla loading layer.
			Joomla.loadingLayer('show');

			// Load the target frame.
			target.src = target.getAttribute('data-editurl') + '&task=' + target.getAttribute('data-item') + '.' + target.getAttribute('data-action') + '&id=' + target.getAttribute('data-id');
		}
		// Reset the data attributes and no item to load.
		else
		{
			$('#toolbar-target').hide();
			$('#select-change').hide();

			target.setAttribute('data-action', '');
			target.setAttribute('data-id', '0');
			target.setAttribute('data-language', '');
			target.src = '';
		}
	});

	// Attach behaviour to reference frame load event.
	$('#reference-association').on('load', function() {

		// Disable language field.
		$(this).contents().find('#jform_language_chzn').remove();
		$(this).contents().find('#jform_language').attr('disabled', true).chosen();

		// Later usage copy function?
		//$('#toolbar-copy').children().first().attr('onclick', 'return copyRefToTarget()');
		//referenceContents.find('#associations .controls').css('pointer-events', 'auto');

		// Iframe load finished, hide Joomla loading layer.
		Joomla.loadingLayer('hide');
	});

	// Attach behaviour to target frame load event.
	$('#target-association').on('load', function() {
		// We need to check if we are not loading a blank iframe.
		if (this.getAttribute('src') != '')
		{
			$('#toolbar-target').show();
			$('#select-change').show();

			var targetLanguage       = this.getAttribute('data-language');
			var targetId             = this.getAttribute('data-id');
			var targetLoadedId       = $(this).contents().find('#jform_id').val() || '0';

			// Hide associations tab.
			$(this).contents().find('a[href=\"#associations\"]').parent().hide();

			// Update language field with the selected language and them disable it.
			$(this).contents().find('#jform_language_chzn').remove();
			$(this).contents().find('#jform_language').val(targetLanguage).change().attr('disabled', true).chosen();

			// If we are creating a new association (before save) we need to add the new association.
			if (targetLoadedId == '0')
			{
				document.getElementById('select-change-text').innerHTML =  document.getElementById('select-change').getAttribute('data-select');
			}
			// If we are editing a association.
			else 
			{
				document.getElementById('select-change-text').innerHTML =  document.getElementById('select-change').getAttribute('data-change');

				// Add the id to list of items to check in on close.
				var currentIdList = document.getElementById('target-id').value;
				var updatedList   = currentIdList == '' ? targetLoadedId : currentIdList + ',' + targetLoadedId;
				document.getElementById('target-id').value = updatedList;

				// If we created a new association (after save).
				if (targetLoadedId != targetId)
				{
					// Refresh the language selector with the new id (used after save).
					$('#jform_itemlanguage option[value^=\"' + targetLanguage + ':' + targetId + ':add\"]').val(targetLanguage + ':' + targetLoadedId + ':edit');

					// Update main frame data-id attribute (used after save).
					this.setAttribute('data-id', targetLoadedId);
					this.setAttribute('data-action', 'edit');

					// Update the reference item associations tab.
					var reference     = document.getElementById('reference-association');
					var languageCode  = targetLanguage.replace(/-/, '_');
					var title         = $(this).contents().find('#jform_title').val()

					// - For modal association selectors.
					$(reference).contents().find('#jform_associations_' + languageCode + '_id').val(targetLoadedId);
					$(reference).contents().find('#jform_associations_' + languageCode + '_name').val(title);

					// - For chosen association selectors (menus).
					$(reference).contents().find('#jform_associations_' + languageCode + '_chzn').remove();
					$(reference).contents().find('#jform_associations_' + languageCode).append('<option value=\"'+ targetLoadedId + '\">' + title + '</option>');
					$(reference).contents().find('#jform_associations_' + languageCode).val(targetLoadedId).change().chosen();

				}
			}

			// Update the target item associations tab.
				var reference     = document.getElementById('reference-association');
				var referenceId   = reference.getAttribute('data-id');
				var languageCode  = reference.getAttribute('data-language').replace(/-/, '_');
				var title         = $(reference).contents().find('#jform_title').val();
				target            = $(this).contents();

				// - For modal association selectors.
				target.find('#jform_associations_' + languageCode + '_id').val(referenceId);
				target.find('#jform_associations_' + languageCode + '_name').val(title);

				// - For chosen association selectors (menus).
				target.find('#jform_associations_' + languageCode + '_chzn').remove();
				chznField = target.find('#jform_associations_' + languageCode);
				chznField.append('<option value=\"'+ referenceId + '\">' + title + '</option>');
				chznField.val(referenceId).change().chosen();

				$('#jform_itemlanguage option').each(function()
				{
					var parse = $(this).val().split(':');

					if (typeof parse[1] !== 'undefined' && parse[1] !== '0')
					{
						// - For modal association selectors.
	 					langAssociation = parse[0].replace(/-/,'_');
	 					target.find('#jform_associations_' + langAssociation + '_id').val(parse[1]);

	 					// - For chosen association selectors (menus).
						target.find('#jform_associations_' + langAssociation + '_chzn').remove();
						chznField = target.find('#jform_associations_' + langAssociation);
						chznField.append('<option value=\"'+ parse[1] + '\"></option>');
						chznField.val(parse[1]).change().chosen();
	 				}
				});

			// Iframe load finished, hide Joomla loading layer.
			Joomla.loadingLayer('hide');
		}
	});
});