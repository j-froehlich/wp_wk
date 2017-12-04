<div id="wiloke-service-wrapper">
	<div class="col-md-6">
		<div class="well bs-component">
			<form method="POST">
				<?php wp_nonce_field('wiloke-update-nonce', 'wiloke-update-action'); ?>
				<fieldset>
					<legend>Wiloke Update</legend>
					<div class="form-group">
					  <label for="token-secret" class="col-lg-2 control-label"><?php esc_html('Access Token', 'wiloke-service'); ?></label>
					  <div class="col-lg-10">
					    <textarea class="form-control" rows="20" id="token-secret" name="wiloke_update[secret_token]"><?php echo esc_textarea($secretToken); ?></textarea>
					    <span class="help-block">Following this tutorial to create one <a href="https://wiloke.net/" target="_blank">https://wiloke.net/</a></span>
					  </div>
					</div>

				    <div class="form-group">
				      <div class="col-lg-10 col-lg-offset-2">
				        <button type="reset" class="btn btn-default ui negative basic button">Cancel</button>
				        <button type="submit" name="submit" id="submit" class="ui primary basic button">Submit</button>
				      </div>
				    </div>

				</fieldset>
			</form>
		</div>
	</div>
</div>