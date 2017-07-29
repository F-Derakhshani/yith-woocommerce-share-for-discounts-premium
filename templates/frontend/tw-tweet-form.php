<div class="ywsfd-twitter-form-wrapper">

	<p class="form-row form-row-wide ywsfd-validate-required">
		<label for="ywsfd_tweet_text"><?php _e( 'Your Tweet', 'yith-woocommerce-share-for-discounts' ); ?>
			<span class="required">*</span>
		</label>
		<textarea class="input-text input-textarea" name="ywsfd_tweet_text" id="ywsfd_tweet_text" rows="2" cols="5"><?php echo $social_params['sharing']['message'] . ' - ' . $social_params['sharing']['url'] . $social_params['sharing']['twitter_username'] ?></textarea>
		<span class="ywsfd-char-count"><?php _e( 'Remaining characters', 'yith-woocommerce-share-for-discounts' ); ?>: <span>160</span></span>
	</p>

	<p class="form-row form-row-wide">

		<input type="button" class="button ywsfd-cancel-btn ywsfd-cancel-tweet" name="ywsfd_cancel_tweet" value="<?php _e( 'Cancel', 'yith-woocommerce-share-for-discounts' ); ?>" />
		<input type="button" class="button ywsfd-send-btn ywsfd-send-tweet" name="ywsfd_send_tweet" value="<?php _e( 'Tweet', 'yith-woocommerce-share-for-discounts' ); ?>" />

	</p>

	<div class="clear"></div>

</div>