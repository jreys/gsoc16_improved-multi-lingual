<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>


<div class="js-stools clearfix">
	<div class="clearfix">
		<div class="js-stools-container-bar">

			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th width="1%" class="center nowrap">
							<a href="#" onclick="return false;" class="js-stools-column-order hasTooltip" data-order="a.state" data-direction="ASC" data-name="Status" title="" data-original-title="<strong>Status</strong><br />Select to sort by this column">
								Status</a>
						</th>
						<th class="title">
							<a href="#" onclick="return false;" class="js-stools-column-order hasTooltip" data-order="a.title" data-direction="ASC" data-name="Title" title="" data-original-title="<strong>Title</strong><br />Select to sort by this column">Title</a>
						</th>
						<th width="10%" class="nowrap">
							<a href="#" onclick="return false;" class="js-stools-column-order hasTooltip" data-order="a.access" data-direction="ASC" data-name="Access" title="" data-original-title="<strong>Access</strong><br />Select to sort by this column">Access</a>
						</th>
						<th width="15%" class="nowrap">
							<a href="#" onclick="return false;" class="js-stools-column-order hasTooltip" data-order="language" data-direction="ASC" data-name="Language" title="" data-original-title="<strong>Language</strong><br />Select to sort by this column">
											Language</a>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<a href="#" onclick="return false;" class="js-stools-column-order hasTooltip" data-order="a.created" data-direction="ASC" data-name="Date" title="" data-original-title="<strong>Date</strong><br />Select to sort by this column">
												Date</a>
						</th>
						<th width="1%" class="nowrap">
							<a href="#" onclick="return false;" class="js-stools-column-order hasTooltip" data-order="a.id" data-direction="ASC" data-name="ID" title="" data-original-title="<strong>ID</strong><br />Select to sort by this column">ID
											<span class="icon-arrow-down-3">
											</span>
							</a>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="6">
							<div class="pagination pagination-toolbar">
								<input type="hidden" name="limitstart" value="0">
							</div>						
						</td>
					</tr>
				</tfoot>
				<tbody>
					<tr class="row0">
						<td class="center">
							<span class="icon-publish"></span>
						</td>
						<td>
							<a href="javascript:void(0);" onclick="if (window.parent) window.parent.jSelectArticle_jform_associations_en_GB('1', 'Article (en-gb)', '8', null, 'index.php?option=com_content&amp;view=article&amp;id=1&amp;catid=8&amp;lang=en-GB&amp;Itemid=101', 'en', null);">
															Article (en-gb)</a>
							<div class="small">Category: Category (en-gb)</div>
						</td>
						<td class="small hidden-phone">Public
						</td>
						<td class="small hidden-phone">
							<img src="../media/mod_languages/images/en.gif" alt="English (UK)" title="English (UK)">&nbsp;English (UK)
						</td>
						<td class="nowrap small hidden-phone">2016-05-24</td>
						<td>1</td>
					</tr>
				</tbody>
			</table>
