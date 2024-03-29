<?php
/*
 * Xibo - Digital Signage - http://www.xibo.org.uk
 * Copyright (C) 2006-2013 Daniel Garner
 *
 * This file is part of Xibo.
 *
 * Xibo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version. 
 *
 * Xibo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Xibo.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Theme variables:
 * 	buttons = An array containing the media buttons
 */
defined('XIBO') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");
?>
<form class="form-horizontal" id="<?php echo Theme::Get('form_upload_id'); ?>" method="post" action="<?php echo Theme::Get('form_upload_action'); ?>" enctype="multipart/form-data" target="fileupload">
	<fieldset>
        <?php echo Theme::Get('form_upload_meta'); ?>
        <div class="control-group">
			<label class="control-label" for="media_file" accesskey="n" title="<?php echo Theme::Translate('Select the file to upload'); ?>"><?php echo Theme::Translate('File'); ?></label>
			<div class="controls">
				<input name="media_file" type="file" id="media_file" tabindex="1" onchange="fileFormSubmit();this.form.submit();" />
			</div>
		</div>
	</fieldset>	
</form>
<div id="uploadProgress" class="well" style="display:none">
    <span><?php echo Theme::Translate('You may fill in the form while your file is uploading.'); ?></span>
</div>
<form class="XiboForm form-horizontal" id="<?php echo Theme::Get('form_id'); ?>" method="post" action="<?php echo Theme::Get('form_action'); ?>">
    <?php echo Theme::Get('form_meta'); ?>
    <div class="control-group">
		<label class="control-label" for="name" accesskey="n" title="<?php echo Theme::Translate('The Name of this item - Leave blank to use the file name'); ?>"><?php echo Theme::Translate('Name'); ?></label>
		<div class="controls">
			<input name="name" type="text" id="name" value="<?php echo Theme::Get('name'); ?>" tabindex="2" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="duration" accesskey="n" title="<?php echo Theme::Translate('The duration in seconds this image should be displayed (may be overridden on each layout)'); ?>"><?php echo Theme::Translate('Duration'); ?></label>
		<div class="controls">
			<input name="duration" type="text" id="duration" tabindex="3" value="<?php echo Theme::Get('duration'); ?>" <?php echo Theme::Get('is_duration_field_enabled'); ?> />
		</div>
	</div>
	<?php if (Theme::Get('is_assignable')) { ?>
	<div class="control-group">
		<div class="controls">
			<label class="checkbox" for="replaceInLayouts" accesskey="n"><?php echo Theme::Translate('Update this media in all layouts it is assigned to. Note: It will only be replaced in layouts you have permission to edit.'); ?>
				<input type="checkbox" id="replaceInLayouts" name="replaceInLayouts" <?php echo Theme::Get('is_replace_field_checked'); ?> />
			</label>
		</div>
	</div>
	<?php } ?>
	<div class="well">
		<?php echo Theme::Get('valid_extensions'); ?>
	</div>
</form>
<div style="display:none">
	<iframe name="fileupload" width="1px" height="1px"></iframe>
</div>