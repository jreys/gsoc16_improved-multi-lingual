<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->app->getDocument()->addScriptDeclaration("
	function triggerSave(frame) {
		if (frame == 'reference') {
			window.frames[0].Joomla.submitbutton('" . $this->associatedView . ".apply');
		}
		if (frame == 'target') {
			window.frames[1].Joomla.submitbutton('" . $this->associatedView . ".apply');

			//Will only execute this AFTER save to get the ID in case it's a new item
			jQuery('#target-association').load(function () {
				target = jQuery('#reference-association').contents();
				selectedLang = jQuery('#jform_itemlanguage').val();
				split = selectedLang.split('|');
				langAssociation = split[0].replace('-','_');
				langID = jQuery(this).contents().find('#jform_id').val();
				target.find('#jform_associations_' + langAssociation + '_id').val(langID);

				//Updating language selector when a new item is saved
				if (split[1] == '0') {
					jQuery('#jform_itemlanguage option').each(function() {
						if(jQuery(this).val() == selectedLang) {
							jQuery(this).val(split[0] + '|' + langID);    
						}
					});
				}

				if (langID != '0') {
					if (!jQuery('#target-id').val()) {
						jQuery('#target-id').val(langID);
					}
					else {
						jQuery('#target-id').val(jQuery('#target-id').val()+','+langID);
					}
				}
			});
		}
		return false;
	}
");

$this->app->getDocument()->addScriptDeclaration("
	jQuery(document).ready(function($) {
		$('#toogle-left-panel').on('click', function() {
			$('#left-panel').toggle();
			$('#right-panel').toggleClass('full-width');
		});

		selectedLang = $('#jform_itemlanguage').val();

		if (selectedLang == '')
		{
			document.getElementById('target-association').src = '';
		}

		//Save button action, replacing the fault Joomla.submitbutton() with custom function
		$('.btn-success').attr('onclick', function(){
			var frame = $(this).attr('onclick');

			frame = frame.substring(frame.lastIndexOf('(')+1,frame.lastIndexOf(')'));
			return 'return triggerSave(' + frame + ')';
		});

		$('#reference-association').load(function (){
			//Disable reference fields, commented out just to keep the code for ACL
			$('#reference-association').each(function () {
				//$(this).contents().find('.chzn-single').css('background', 'transparent');
				//$(this).contents().find('.chzn-single').css('background-color', '#eee');
				//$(this).contents().find('.controls').css( 'pointer-events', 'none' );
				//$(this).contents().find('input').attr('disabled', 'disabled');
				$(this).contents().find('#jform_language_chzn').css( 'pointer-events', 'none' );
				$(this).contents().find('#jform_language_chzn').find('.chzn-single').css('background', 'transparent');
				$(this).contents().find('#jform_language_chzn').find('.chzn-single').css('background-color', '#eee');
				$('#toolbar-copy').children().first().attr('onclick', 'return copyRefToTarget()');

				$(this).contents().find('#associations .controls').css( 'pointer-events', 'auto' );
			});
		});

		$('#target-association').load(function () {
			target = $(this);

			//Hide associations tab	
			target.contents().find('a[href=#associations]').parent().hide();

			langAssociation = '" . str_replace('-', '_', $this->referenceLanguage) . "';
			langID = " . $this->referenceID . ";
			target.contents().find('#jform_associations_' + langAssociation + '_id').val(langID);

			split = selectedLang.split('|');

			if (typeof split[1] !== 'undefined') {
				selectedLang = split[0];

				//Adding checkin IDs to an hidden input
				if (split[1] != '0') {
					if (!$('#target-id').val()) {
						$('#target-id').val(split[1]);
					}
					else {
						$('#target-id').val($('#target-id').val()+','+split[1]);
					}
				}

				//Auto-picking the selected language on the switcher
				target.contents().find('#jform_language option[value='+selectedLang+']').attr('selected','selected');
				target.contents().find('#jform_language').chosen();
				target.contents().find('#jform_language').trigger('liszt:updated');
				target.contents().find('#jform_language').parent().find('div:gt(2)').remove();
			}

			//Storing existing associations on target
			$('#jform_itemlanguage option').each(function()
			{
				split = $(this).val().split('|');
				if (typeof split[1] !== 'undefined' && split[1] !== '0') {
					langAssociation = split[0].replace('-','_');
					langID = split[1];
					target.contents().find('#jform_associations_' + langAssociation + '_id').val(langID);
				}
			});

			//Disabling language selector on the target
			$(this).contents().find('#jform_language_chzn').css( 'pointer-events', 'none' );
			$(this).contents().find('#jform_language_chzn').find('.chzn-single').css('background', 'transparent');
			$(this).contents().find('#jform_language_chzn').find('.chzn-single').css('background-color', '#eee');
		});

	});

	function loadFrame(id) {
		split = id.split('|');
		id = split[1];

		newSrc = '" . $this->targetLink . "' + id;

		selectedLang = jQuery('#jform_itemlanguage').val();

		if (selectedLang == '')
		{
			document.getElementById('target-association').src = '';
		}
		else
		{
			document.getElementById('target-association').src = newSrc;
		}
	}

	function copyRefToTarget() {
		var ref = document.getElementById('reference-association').contentWindow.document.getElementsByName('checkbox');
		jQuery('#reference-association').contents().find('input').each(function () {
			//console.log(jQuery('#target-association').contents().find(this.id).text());
			id = '#'+this.id;
			jQuery('#target-association').contents().find(id).val((jQuery(this).val()));
		});
		return false;
	}
");

$this->app->getDocument()->addStyleDeclaration('

	.sidebyside .outer-panel {
		float: left;
		width: 50%;
	}
	.sidebyside #left-panel .inner-panel {
		border-right: 1px solid #999999 !important;
	}
	.sidebyside #left-panel .inner-panel {
		padding-right: 10px;
	}
	.sidebyside #right-panel .inner-panel {
		padding-left: 10px;
	}
	.sidebyside .full-width {
		float: none !important;
		width: 100% !important;
	}
	.sidebyside .full-width .inner-panel {
		padding-left: 0 !important;
	}
	.sidebyside iframe {
		width: 100%;
		height: 1500px;
		border: 0 !important;
	}

	.reference-side h3 {
		float: left;
		width: 50%;
	}
');
?>
<button id="toogle-left-panel" class="btn btn-small">Show/Hide Reference (PoC)</button>

<form action="<?php echo JRoute::_(JFactory::getURI()->toString()); ?>"
 method="post" name="adminForm" id="adminForm" class="form-validate">

<div class="sidebyside">
	<div class="outer-panel" id="left-panel">
		<div class="inner-panel">
			<h3><?php echo JText::_('COM_ASSOCIATIONS_REFERENCE_ITEM'); ?></h3>
			<iframe id="reference-association" src="<?php echo JRoute::_($this->link); ?>" class="reference-association"
				name="<?php echo JText::_('COM_ASSOCIATIONS_TITLE_MODAL'); ?>" height="100%" width="400px" scrolling="no">
			</iframe>
		</div>
	</div>
	<div class="outer-panel" id="right-panel">
		<div class="inner-panel">
			<div class="reference-side">
				<h3><?php echo JText::_('COM_ASSOCIATIONS_ASSOCIATED_ITEM'); ?></h3>
				<?php echo $this->form->getInput('itemlanguage'); ?>
			</div>
			<iframe id="target-association" name="target-association" 
				src="<?php echo JRoute::_($this->targetLink); ?>" 
				name="<?php echo JText::_('COM_ASSOCIATIONS_TITLE_MODAL'); ?>" height="100%" width="400px" scrolling="no"></iframe>
		</div>
	</div>
</div>
<input type="hidden" name="task" value=""/>
<input id="target-id" type="hidden" name="target-id" value=""/>
</form>