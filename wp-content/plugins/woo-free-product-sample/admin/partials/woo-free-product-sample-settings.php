<div class="wrap wfps_settings">
    <div id="icon-options-general" class="icon32"></div>
    <h1><?php echo WFPS_PLUGIN_NAME; ?></h1>
    <?php settings_errors(); ?>
    <?php 
        $status 		 = $this->get_license_status();      
        $activation_info = get_option( $this->_activation ); ?>
    <div id="poststuff">
        <div class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable" style="position:relative">
                    <div class="wfps_settings_outer_left">
                        <div class="postbox">
                            <div class="wfps_inside">
								<form method="post" action="options.php" novalidate="novalidate">
									<?php
										settings_fields( $this->_optionGroup );
										$setting_options = wp_parse_args( get_option($this->_optionName), $this->_defaultOptions );
									?>
									<table class="form-table">
										<tbody>

											<?php
												foreach( $settings as  $key => $value ) :
											?>
											
											<tr <?php if( isset( $value['position'] ) ) { echo  $value['style']; } ?>>
												<th scope="row">
													<label for="<?php echo $value['name']; ?>">
														<?php echo $value['label']; ?>	
													</label>
												</th>
												<td>	
													<?php 
														$file_name = isset( $value['type'] ) ? $value['type'] : 'text';							
														
														if( $file_name ) {
															include WFPS_ADMIN_DIR_PATH . 'includes/fields/'. $file_name .'.php';
														}
														if( isset( $value['description'] ) ) {
													?>
														<div class="woo-free-product-sample-form-desc"><?php echo $value['description']; ?></div>
													<?php } ?>
												</td>
											</tr>
																			
											<?php endforeach; ?>							
																							
											<?php do_settings_fields( $this->_optionGroup, 'default' ); ?>
										</tbody>
									</table>    
									<?php do_settings_sections($this->_optionGroup, 'default'); ?>
									<?php submit_button(); ?>
								</form>
                            </div>
                            <!-- .inside -->

                        </div>
                        <!-- .postbox -->
                        
                        <?php if ( $status == false || $status !== 'valid' ) : ?>
                        <div class="premium clearfix">
                            <div class="premium_left premium_left_free premium_left_free_button">
                                <h1><?php esc_html_e( 'Upgrade to', 'woo-free-product-sample' ); ?> <br><?php esc_html_e( 'Premium Now!', 'woo-free-product-sample' ); ?></h1>
                               <div class="price">
                                   <span><?php esc_html_e( 'For only', 'woo-free-product-sample' ); ?> <b><?php esc_html_e( '$29.00', 'woo-free-product-sample' ); ?></b> <?php esc_html_e( 'per site', 'woo-free-product-sample' ); ?></span>
                               </div>
                                <div class="tagline">
                                    <?php esc_html_e( 'Elevate Your WooCommerce Stores', 'woo-free-product-sample' ); ?> <br> <?php esc_html_e( 'with our
                                    light, fast and feature-rich plugins.', 'woo-free-product-sample' ); ?>
                                </div>
                                <div>
                                    <a href="https://www.thewpnext.com/downloads/free-product-sample-for-woocommerce/" target="_blank"><?php esc_html_e( 'Upgrade Now', 'woo-free-product-sample' ); ?></a>
                                </div>
                            </div>
                            <div class="premium_right">
                                <div class="outer">
                                    <h4><?php esc_html_e( 'Premium Features', 'woo-free-product-sample' ); ?></h4>
                                    <ul>
                                        <li>
                                            <b><?php esc_html_e( 'Display the button for global.', 'woo-free-product-sample' ); ?></b>
                                        </li>
                                        <li>
                                            <b><?php esc_html_e( 'Conditional logic', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Exclude products capability if don’t need.', 'woo-free-product-sample' ); ?>
                                        </li>
                                        <li>
                                            <b><?php esc_html_e( 'Sample price', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Set custom price for sample products.', 'woo-free-product-sample' ); ?>
                                        </li>
                                        <li>
                                            <b><?php esc_html_e( 'Custom shipping class & tax rules', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Set shipping class & tax rule for the sample products.', 'woo-free-product-sample' ); ?>
                                        </li>
                                        <li>
                                            <b><?php esc_html_e( 'Custom SKU', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Set custom SKU for sample products.', 'woo-free-product-sample' ); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="sidebar">
                        <?php if ( $status == false || $status !== 'valid' ) : ?>
                        <div class="sidebar_top">
                            <h1><?php esc_html_e( 'Upgrade to', 'woo-free-product-sample' ); ?> <br><?php esc_html_e( 'Premium Now!', 'woo-free-product-sample' ); ?></h1>
                            <div class="price_side">
                            <?php esc_html_e( 'For only', 'woo-free-product-sample' ); ?><b> <?php esc_html_e( '$29', 'woo-free-product-sample' ); ?> </b><?php esc_html_e( 'per site', 'woo-free-product-sample' ); ?>
                            </div>
                            <div class="tagline_side"><?php esc_html_e( 'Elevate Your WooCommerce Stores', 'woo-free-product-sample' ); ?> <br> <?php esc_html_e( 'with our
                                    light, fast and feature-rich plugins.', 'woo-free-product-sample' ); ?>
                            </div>
                            <div>
                                <a href="https://www.thewpnext.com/downloads/free-product-sample-for-woocommerce/" target="_blank"><?php esc_html_e( 'Upgrade Now', 'woo-free-product-sample' ); ?></a>
                            </div>

                        </div>

                        <div class="sidebar_bottom">
                            <ul>
                                <li>
                                    <b><?php esc_html_e( 'Display the button for global.', 'woo-free-product-sample' ); ?></b>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Conditional logic', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Exclude products capability if don’t need.', 'woo-free-product-sample' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Sample price', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Set custom price for sample products.', 'woo-free-product-sample' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Custom shipping class & tax rules', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Set shipping class & tax rule for the sample products.', 'woo-free-product-sample' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Custom SKU', 'woo-free-product-sample' ); ?>:</b> <?php esc_html_e( 'Set custom SKU for sample products.', 'woo-free-product-sample' ); ?>
                                </li>                                
                            </ul>
                        </div>
                        <?php endif; ?>
                        <div class="support">
                            <h3><?php esc_html_e( 'Dedicated Support Team', 'woo-free-product-sample' ); ?></h3>
                            <p><?php esc_html_e( 'We are available round the clock for any support.', 'woo-free-product-sample' ); ?></p>
                            <?php echo apply_filters( 'request_support_ticket', '' ); ?>                            
                        </div>

                    </div>

                </div>
                <!-- .meta-box-sortables .ui-sortable -->

            </div>
            <!-- post-body-content -->


            <!-- #postbox-container-1 .postbox-container -->

        </div>
        <!-- #post-body .metabox-holder .columns-2 -->
        <div id="post-body" class="metabox-holder columns-2"></div>

        <br class="clear">
    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->