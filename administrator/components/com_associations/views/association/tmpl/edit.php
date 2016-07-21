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
	function triggerSave() {
		window.frames[1].Joomla.submitbutton('" . $this->associatedView . ".apply');
		return false;
	}
");

$this->app->getDocument()->addScriptDeclaration("
	jQuery(document).ready(function($) {
		$('#toogle-left-panel').on('click', function() {
			$('#left-panel').toggle();
			$('#right-panel').toggleClass('full-width');
		});

		$('.btn-success').attr('onclick','return triggerSave()');

		$('#reference-association').load(function (){
			$('#reference-association').each(function () {
				$(this).contents().find('.chzn-single').css('background', 'transparent');
				$(this).contents().find('.chzn-single').css('background-color', '#eee');
				$(this).contents().find('.controls').css( 'pointer-events', 'none' );
    			$(this).contents().find('input').attr('disabled', 'disabled');
			});
		});

		$('#target-association').load(function (){		
			$(this).contents().find('a[href=#associations]').parent().hide();
		});
	});

	function loadFrame(id) {
		var oldSrc = document.getElementById('target-association').src;
		lastStrIndex = oldSrc.length-1;

		while(oldSrc.charAt(lastStrIndex) != '=') {
			lastStrIndex--;
		}

		newSrc = oldSrc.substring(0, lastStrIndex) + '=' + id;

		document.getElementById('target-association').src = newSrc;
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
<button id="toogle-left-panel">Show/Hide Reference (PoC)</button>

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
</form>