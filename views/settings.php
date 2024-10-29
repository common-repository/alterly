<div class="wrap">
    <h2><?php echo $this->plugin->displayName; ?> &raquo; <?php _e('Settings', $this->plugin->name); ?></h2>
           
    <?php    
    if (isset($this->message)) {
        ?>
        <div class="updated fade"><p><?php echo $this->message; ?></p></div>  
        <?php
    }
    if (isset($this->errorMessage)) {
        ?>
        <div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>  
        <?php
    }
    ?>
    
    <div id="poststuff">
    	<div id="post-body" class="metabox-holder columns-2">
    		<!-- Content -->
    		<div id="post-body-content">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
	                <div class="postbox">
	                    <h3 class="hndle"><?php _e('Settings', $this->plugin->name); ?></h3>
	                    
	                    <div class="inside">
                            <h4 class="hndle"><?php _e('Instructions', $this->plugin->name); ?></h4>
                            <ol>
                                <li><?php _e('Go to <a href="https://www.alterly.com/" target="_blank">https://www.alterly.com/</a> and sign up for a free account', $this->plugin->name); ?></li>
                                <li><?php _e('Go to your <a href="https://www.alterly.com/en/app/profile/" target="_blank">profile page</a> and copy your Alterly account ID', $this->plugin->name); ?></li>
                                <li><?php _e('Come back here, paste your account ID the box below, click Save and you\'re site will be ready to use with Alterly', $this->plugin->name); ?></li>
                            </ol>
		                    <form action="options-general.php?page=<?php echo $this->plugin->name; ?>" method="post">
                                    <div>
                                        <label for="alterly_account_id"><strong><?php _e('Altery account ID', $this->plugin->name); ?></strong></label>
                                    </div>
		                    		<div>
                                        <input type="text" name="alterly_account_id" id="alterly_account_id" class="regular-text" value="<?php echo $this->settings['alterly_account_id']; ?>" />
                                    </div>

		                    	<?php wp_nonce_field($this->plugin->name, $this->plugin->name.'_nonce'); ?>
		                    	<p>
									<input name="submit" type="submit" name="Submit" class="button button-primary" value="<?php _e('Save', $this->plugin->name); ?>" /> 
								</p>
						    </form>
	                    </div>
	                </div>
	                <!-- /postbox -->
				</div>
				<!-- /normal-sortables -->
    		</div>
    		<!-- /post-body-content -->

            <!-- Sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                &nbsp;
            </div>
    		<!-- /postbox-container -->
    	</div>
	</div>      
</div>