<div class="ywsfd-linkedin-form-wrapper">

	<p class="form-row form-row-wide ywsfd-validate-required">
		<label for="ywsfd_linkedin_text"><?php _e( 'Your Message', 'yith-woocommerce-share-for-discounts' ); ?>
			<span class="required">*</span>
		</label>
		<textarea class="input-text input-textarea" name="ywsfd_linkedin_text" id="ywsfd_linkedin_text" rows="2" cols="5"><?php echo $social_params['sharing']['message'] ?></textarea>
	</p>

	<p class="form-row form-row-wide">

		<input type="button" class="button ywsfd-cancel-btn ywsfd-cancel-linkedin" name="ywsfd_cancel_linkedin" value="<?php _e( 'Cancel', 'yith-woocommerce-share-for-discounts' ); ?>" />
		<input type="button" class="button ywsfd-send-btn ywsfd-send-linkedin" name="ywsfd_send_linkedin" value="<?php _e( 'Share', 'yith-woocommerce-share-for-discounts' ); ?>" />

	</p>

	<div class="clear"></div>

</div>