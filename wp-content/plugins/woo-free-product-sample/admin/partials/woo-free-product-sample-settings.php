<div class="wrap wfps_settings">
    <div id="icon-options-general" class="icon32"></div>
    <h1><?php echo WFPS_PLUGIN_NAME; ?></h1>
    <?php settings_errors(); ?>
    <?php 
        $status 		 = $this->get_license_status();      
        $activation_info = get_option( $this->_activation ); ?>
    <div id="poststuff">
        <div class="metabox-holder columns-2">
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
											<?php foreach( $settings as  $key => $value ) : ?>
												
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
                        </div>
                    </div>
                    <div class="sidebar"></div>
                </div>
            </div>
        </div>
        <div id="post-body" class="metabox-holder columns-2"></div>
        <br class="clear">
    </div>
</div> 