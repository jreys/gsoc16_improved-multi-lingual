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

$jinput = JFactory::getApplication()->input;

$referenceId = $jinput->get('id', '0');
$associatedComponent = $jinput->get('acomponent', '');
$associatedView = $jinput->get('aview', '');

?>

<form action="<?php JRoute::_('index.php?option=com_associations&id=' . (int)$id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

<div class="sidebyside">
    <div class="outer-panel">
        <div class="inner-panel left-panel">
            <h3><?php echo JText::_('COM_ASSOCIATIONS_REFERENCE_ITEM'); ?></h3>
            <iframe src="<?php echo JRoute::_('index.php?option='. $associatedComponent . '&view=' . $associatedView . '&layout=modal&tmpl=component&task=' . $associatedView . '.edit&id=' . $referenceId); ?>" name="<?php echo JText::_('COM_ASSOCIATIONS_TITLE_MODAL'); ?>" height="100%" width="400px" scrolling="no">
            </iframe>
        </div>
    </div>
    <div class="outer-panel">
        <div class="inner-panel right-panel">
            <h3><?php echo JText::_('COM_ASSOCIATIONS_ASSOCIATED_ITEM'); ?></h3>
            <iframe src="<?php echo JRoute::_('index.php?option='. $associatedComponent . '&view=' . $associatedView . '&layout=modal&tmpl=component&task=' . $associatedView . '.edit&id=2' ); ?>" name="<?php echo JText::_('COM_ASSOCIATIONS_TITLE_MODAL'); ?>" height="100%" width="400px" scrolling="no"></iframe>
        </div>
    </div>
</div>
</form>
