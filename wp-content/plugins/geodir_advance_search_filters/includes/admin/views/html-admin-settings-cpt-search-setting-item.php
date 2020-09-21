<?php
/**
 * Admin custom field search form
 *
 * @since 2.0.0
 *
 * @package GeoDirectory
 */

?>
<li class="dd-item" data-id="1" id="setName_<?php echo $field->id;?>">
	<div class="dd-form">
		<i class="fas fa-caret-down" aria-hidden="true" onclick="gd_tabs_item_settings(this);"></i>
		<div class="dd-handle">
			<?php echo $field_icon; ?> 
			<?php if ( $field->field_type == 'fieldset' ) { ?>
			<?php echo __( 'Fieldset:', 'geodiradvancesearch' ) . ' ' . $field->frontend_title; ?>
			<?php } else { ?>
			<?php echo !empty( $field->admin_title ) ? $field->admin_title : $field->frontend_title; ?>
			<?php } ?>
			<span class="dd-key" title="<?php _e('Open/Close','geodiradvancesearch');?>"><?php echo $field->field_type . ' (' . $field->htmlvar_name . ')'; ?></span>
		</div>
		<div class="dd-setting <?php echo 'dd-type-'.esc_attr($field->field_type);?>">
			<input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>"/>
			<input type="hidden" name="post_type" id="post_type" value="<?php echo $field->post_type; ?>"/>
			<input type="hidden" name="field_type" id="field_type" value="<?php echo $field->field_type; ?>"/>
			<input type="hidden" name="field_id" id="field_id" value="<?php echo $field->id; ?>"/>
			<input type="hidden" name="data_type" id="data_type" value="<?php echo $field->data_type; ?>"/>
			<input type="hidden" name="input_type" id="input_type" value="<?php echo $field->input_type; ?>"/>
			<input type="hidden" name="htmlvar_name" id="htmlvar_name" value="<?php echo $field->htmlvar_name; ?>"/>
			<input type="hidden" name="admin_title" id="admin_title" value="<?php echo esc_attr( $field->admin_title ); ?>"/>
			<input type="hidden" name="search_condition" id="search_condition" value="<?php echo esc_attr( $field->search_condition ); ?>"/>

			<?php do_action( "geodir_search_cfa_hidden_fields", $field->field_type, $field, $cf ); ?>
	
			<?php 
			if ( apply_filters( 'geodir_advance_search_field_in_main_search_bar', false, $field, $cf ) ) { 
				$main_search = ! empty( $field->main_search ) ? true : false;
				$main_search_priority = empty( $field->main_search_priority ) && $field->main_search_priority != '0' ? 15 : (int) $field->main_search_priority;
			?>
				<?php do_action( "geodir_search_cfa_before_main_search", $field->field_type, $field, $cf ); ?>
				<p class="dd-setting-name gd-advanced-setting" data-gdat-display-switch-set="gdat-main_search_priority<?php echo $key; ?>">
					<label for="gd_main_search<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'This will show the filed in the main search bar as a select input, it will no longer show in the advanced search dropdown.', 'geodiradvancesearch' ));
						_e( 'Show in main search bar?','geodiradvancesearch' ) ?>
					</label>
					<input type="checkbox" name="main_search" id="gd_main_search<?php echo $key; ?>" value="1" <?php checked( $main_search, true ); ?> onclick="gd_show_hide_radio(this,'show','gda-show gdat-main_search_priority<?php echo $key; ?>');"/>
				</p>
				<p class="dd-setting-name gd-advanced-setting gda-show gdat-main_search_priority<?php echo $key; ?>" <?php echo ( empty( $main_search ) ? 'style="display:none"' : '' ); ?>>
					<label for="gd_main_search_priority<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Where in the search bar you want it to be placed (recommended 15). CPT input: 10, Search input:20, Near input:30.', 'geodiradvancesearch' ));
						_e( 'Search bar priority','geodiradvancesearch' ) ?>
					</label>
					<input type="number" name="main_search_priority" id="gd_main_search_priority<?php echo $key; ?>" value="<?php echo $main_search_priority; ?>" lang="EN"/>
				</p>
			<?php } ?>

			<?php if ( $field->field_type == 'categories' || $field->field_type == 'select' || $field->field_type == 'radio' || $field->field_type == 'multiselect' ) { ?>
				<?php do_action( "geodir_search_cfa_before_input_type", $field->field_type, $field, $cf ); ?>
				<p class="dd-setting-name gd-advanced-setting">
					<label for="gd_input_type<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Select custom field type.', 'geodiradvancesearch' ));
						_e( 'Field Input Type', 'geodiradvancesearch' ) ?> 
					</label>
					<select name="input_type" id="gd_input_type<?php echo $key; ?>" onchange="geodir_adv_search_input_type_changed(this,'<?php echo $key; ?>');">
						<option value="SELECT" <?php selected( 'SELECT', $field->input_type ); ?>><?php _e( 'SELECT', 'geodiradvancesearch' ); ?></option>
						<option value="CHECK" <?php selected( 'CHECK', $field->input_type ); ?>><?php _e( 'CHECK', 'geodiradvancesearch' ); ?></option>
						<option value="RADIO" <?php selected( 'RADIO', $field->input_type ); ?>><?php _e( 'RADIO', 'geodiradvancesearch' ); ?></option>
						<option value="LINK" <?php selected( 'LINK', $field->input_type ); ?>><?php _e( 'LINK', 'geodiradvancesearch' ); ?></option>
					</select>
				</p>
			<?php } else if ( ( $field->data_type == 'INT' || $field->data_type == 'FLOAT' ) && $field->field_type != 'fieldset' ) { ?>
				<?php if ( $field->htmlvar_name != 'distance' ) { ?>
					<?php do_action( "geodir_search_cfa_before_data_type_change", $field->field_type, $field, $cf ); ?>
					<p class="dd-setting-name gd-advanced-setting">
						<label for="gd_data_type_change<?php echo $key; ?>">
							<?php
							echo geodir_help_tip( __( 'Select custom field type.', 'geodiradvancesearch' ));
							_e( 'Field Data Type', 'geodiradvancesearch' ) ?> 
						</label>
						<select name="data_type_change" id="data_type_change" onchange="geodir_adv_search_type_changed(this,'<?php echo $key; ?>');">
							<option value="SELECT" <?php selected( 'SELECT', $field->search_condition ); ?>><?php _e( 'Range in SELECT', 'geodiradvancesearch' ); ?></option>
							<option value="LINK" <?php selected( 'LINK', $field->search_condition ); ?>><?php _e( 'Range in LINK', 'geodiradvancesearch' ); ?></option>
							<option value="TEXT" <?php selected( 'SINGLE', $field->search_condition ); ?> <?php selected( 'FROM', $field->search_condition ); ?>><?php _e( 'Range in TEXT', 'geodiradvancesearch' ); ?></option>
						</select>
					</p>
				<?php } ?>

				<?php do_action( "geodir_search_cfa_before_search_condition_select", $field->field_type, $field, $cf ); ?>
				<p class="dd-setting-name gd-advanced-setting gd-search-condition-select-row" <?php echo ( ! in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
					<label for="gd_search_condition_select<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Select searching type.', 'geodiradvancesearch' ));
						_e( 'Searching Type', 'geodiradvancesearch' ) ?> 
					</label>
					<select name="search_condition_select" id="search_condition_select" onchange="geodir_adv_search_range_changed(this,'<?php echo $key; ?>');">
						<option value="SINGLE" <?php selected( 'SINGLE', $field->search_condition ); ?>><?php _e( 'Range single', 'geodiradvancesearch' ); ?></option>
						<option value="FROM" <?php selected( 'FROM', $field->search_condition ); ?>><?php _e( 'Range from', 'geodiradvancesearch' ); ?></option>
					</select>
				</p>

				<?php if ( $field->htmlvar_name != 'distance' ) { ?>
					<?php do_action( "geodir_search_cfa_before_range_min", $field->field_type, $field, $cf ); ?>
					<p class="dd-setting-name gd-advanced-setting gd-range-min-row" <?php echo ( in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
						<label for="gd_range_min<?php echo $key; ?>">
							<?php
							echo geodir_help_tip( __( 'Starting search range.', 'geodiradvancesearch' ));
							_e( 'Starting Search Range', 'geodiradvancesearch' ); ?>
						</label>
						<input type="number" name="range_min" id="gd_range_min<?php echo $key; ?>" value="<?php echo esc_attr( $field->range_min ) ?>" lang="EN"/>
					</p>
				<?php } ?>

				<?php do_action( "geodir_search_cfa_before_range_max", $field->field_type, $field, $cf ); ?>
				<p class="dd-setting-name gd-advanced-setting gd-range-max-row" <?php echo ( in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
					<label for="gd_range_max<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Enter the maximum radius of the search zone you want to create, for example if you want your visitors to search any listing within 50 miles or kilometers from the current location, then you would enter 50.', 'geodiradvancesearch' ));
						_e( 'Maximum Search Range', 'geodiradvancesearch' ); ?>
					</label>
					<input type="number" name="range_max" min="1" id="gd_range_max<?php echo $key; ?>" value="<?php echo esc_attr( $field->range_max ) ?>" lang="EN"/>
				</p>

				<?php do_action( "geodir_search_cfa_before_range_step", $field->field_type, $field, $cf ); ?>
				<?php $range_step_attr = $field->htmlvar_name != 'distance' ? 'onkeyup="geodir_adv_search_difference(this);" onchange="geodir_adv_search_difference(this);"' : ''; ?>
				<p class="dd-setting-name gd-advanced-setting gd-range-step-row" <?php echo ( in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
					<label for="gd_range_step<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Here you decide how many different search radii you make available to your visitors. If you enter a fifth of the Maximum Search Range, there will be 5 options; if you enter half of the Maximum Search Range, then there will be 2 options.', 'geodiradvancesearch' ));
						_e( 'Difference in Search Range', 'geodiradvancesearch' ); ?>
					</label>
					<input type="number" name="range_step" min="1" id="gd_range_step<?php echo $key; ?>" value="<?php echo esc_attr( $field->range_step ) ?>" <?php echo $range_step_attr; ?> lang="EN"/>
					<span class="gd-range-mode-row" style="display:<?php echo ( ! empty( $field->range_step ) && $field->range_step == 1 ? 'block' : 'none' ); ?>"> 
						<input type="checkbox" name="range_mode"value="1" <?php selected( true , ! empty( $field->range_mode ) ); ?>/> <?php _e( 'You want to searching with single range', 'geodiradvancesearch' ); ?>
					</span>
				</p>

				<?php if ( $field->htmlvar_name != 'distance' ) { ?>
					<?php do_action( "geodir_search_cfa_before_range_start", $field->field_type, $field, $cf ); ?>
					<p class="dd-setting-name gd-advanced-setting gd-range-start-row" <?php echo ( in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
						<label for="gd_range_start<?php echo $key; ?>">
							<?php
							echo geodir_help_tip( __( 'First search range.', 'geodiradvancesearch' ) );
							_e( 'First Search Range', 'geodiradvancesearch'); ?>
						</label>
						<input type="number" name="range_start" id="gd_range_start<?php echo $key; ?>" value="<?php echo esc_attr( $field->range_start ) ?>" lang="EN"/>
					</p>

					<?php do_action( "geodir_search_cfa_before_range_from_title", $field->field_type, $field, $cf ); ?>
					<p class="dd-setting-name gd-advanced-setting gd-range-from-title-row" <?php echo ( in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
						<label for="gd_range_from_title<?php echo $key; ?>">
							<?php
							echo geodir_help_tip( __( 'First search range text.', 'geodiradvancesearch' ) );
							_e( 'First Search Range Text', 'geodiradvancesearch'); ?>
						</label>
						<input type="text" name="range_from_title" id="gd_range_from_title<?php echo $key; ?>" value="<?php echo esc_attr( $field->range_from_title ) ?>" placeholder="<?php esc_attr_e( 'Less than', 'geodiradvancesearch' ); ?>"/>
					</p>

					<?php do_action( "geodir_search_cfa_before_range_to_title", $field->field_type, $field, $cf ); ?>
					<p class="dd-setting-name gd-advanced-setting gd-range-to-title-row" <?php echo ( in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
						<label for="gd_range_to_title<?php echo $key; ?>">
							<?php
							echo geodir_help_tip( __( 'Last search range text.', 'geodiradvancesearch' ) );
							_e( 'Last Search Range Text', 'geodiradvancesearch'); ?>
						</label>
						<input type="text" name="range_to_title" id="gd_range_to_title<?php echo $key; ?>" value="<?php echo esc_attr( $field->range_to_title ) ?>" placeholder="<?php esc_attr_e( 'More than', 'geodiradvancesearch' ); ?>"/>
					</p>
				<?php } ?>
			<?php } else if ( $field->input_type == 'DATE' ) { ?>
				<?php do_action( "geodir_search_cfa_before_search_condition_select", $field->field_type, $field, $cf ); ?>
				<p class="dd-setting-name gd-advanced-setting" <?php echo ( ! in_array( $field->search_condition, array( 'SINGLE', 'FROM' ) ) ? 'style="display:none"' : '' ); ?>>
					<label for="gd_search_condition_select<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Select searching type.', 'geodiradvancesearch' ));
						_e( 'Searching Type', 'geodiradvancesearch' ) ?> 
					</label>
					<select name="search_condition_select" id="search_condition_select" onchange="geodir_adv_search_range_changed(this,'<?php echo $key; ?>');">
						<option value="SINGLE" <?php selected( 'SINGLE', $field->search_condition ); ?>><?php _e( 'Range in single', 'geodiradvancesearch' ); ?></option>
						<option value="FROM" <?php selected( 'FROM', $field->search_condition ); ?>><?php _e( 'Range in from', 'geodiradvancesearch' ); ?></option>
					</select>
				</p>
			<?php } ?>

			<?php do_action( "geodir_search_cfa_before_range_expand", $field->field_type, $field, $cf ); ?>
			<p class="dd-setting-name gd-advanced-setting gd-range-expand-row" <?php echo ( $field->search_condition == 'LINK' || $field->input_type == 'LINK' || $field->input_type == 'CHECK' || $field->htmlvar_name == 'distance' ? '' : 'style="display:none"' ); ?>>
				<label for="gd_range_expand<?php echo $key; ?>">
					<?php
					echo geodir_help_tip( __( 'If you leave this blank, then all options as per Difference in Search Range will be shown. Entering a  number lower than the number of options as per Difference in Search Range will only show that lower number of options, and will add a More link to expand the options so all will be shown.', 'geodiradvancesearch' ));
					_e( 'Expand Search Range', 'geodiradvancesearch' ) ?> 
				</label>
				<input type="number" name="range_expand" width="35px" min="1" id="gd_range_expand<?php echo $key; ?>" value="<?php echo esc_attr( $field->range_expand ); ?>" lang="EN"/>
				<label for="gd_expand_search<?php echo $key; ?>">
                    <input type="checkbox" name="expand_search" id="gd_expand_search<?php echo $key; ?>" value="1" <?php checked( true, ! empty( $field->expand_search ) ); ?> style="display:inline-block;"/> <?php _e( 'Please check to expand search range', 'geodiradvancesearch' ); ?>
				</label>
			</p>

			<?php 
			if ( $field->htmlvar_name == 'distance' ) {
				$search_is_sort = ! empty( $field->extra_fields['is_sort'] ) ? true : false;
				$search_asc = ! empty( $field->extra_fields['asc'] ) ? true : false;
                $search_asc_title = ! empty( $field->extra_fields['asc_title'] ) ? $field->extra_fields['asc_title'] : '';
                $search_desc = ! empty( $field->extra_fields['desc'] ) ? true : false;
                $search_desc_title = ! empty( $field->extra_fields['desc_title'] ) ? $field->extra_fields['desc_title'] : '';
			?>
				<p class="dd-setting-name gd-advanced-setting">
					<label for="gd_geodir_distance_sorting<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Select if you want to show option in distance sort.', 'geodiradvancesearch' ));
						_e( 'Show distance sorting', 'geodiradvancesearch' ) ?> 
					</label>
					<input type="checkbox" name="geodir_distance_sorting" id="gd_geodir_distance_sorting<?php echo $key; ?>" value="1" <?php checked( true, $search_is_sort ); ?>/>
				</p>
	
				<p class="dd-setting-name gd-advanced-setting gd-search-asc-row" <?php echo ( $search_is_sort ? '' : 'style="display:none"' ); ?>>
					<label for="gd_search_asc<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Select if you want to show option in distance sort.', 'geodiradvancesearch' ));
						_e( 'Select Nearest', 'geodiradvancesearch' ) ?> 
					</label>
					<span>
						<input type="checkbox" name="search_asc" id="gd_search_asc<?php echo $key; ?>" value="1" <?php checked( true, $search_asc ); ?> style="display:inline-block;"/> 
						<input type="text" name="search_asc_title" id="gd_search_asc_title<?php echo $key; ?>" value="<?php echo esc_attr( $search_asc_title ) ?>" placeholder="<?php esc_attr_e( 'Ascending title', 'geodiradvancesearch' ); ?>" style="width:75%"/> <i class="fas fa-arrow-up" aria-hidden="true"></i>
					</span>
				</p>

				<p class="dd-setting-name gd-advanced-setting gd-search-desc-row" <?php echo ( $search_is_sort ? '' : 'style="display:none"' ); ?>>
					<label for="gd_search_desc<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Select if you want to show option in distance sort.', 'geodiradvancesearch' ));
						_e( 'Select Farthest', 'geodiradvancesearch' ) ?> 
					</label>
					<span>
						<input type="checkbox" name="search_desc" id="gd_search_desc<?php echo $key; ?>" value="1" <?php checked( true, $search_desc ); ?> style="display:inline-block;"/> 
						<input type="text" name="search_desc_title" id="gd_search_desc_title<?php echo $key; ?>" value="<?php echo esc_attr( $search_desc_title ) ?>" placeholder="<?php esc_attr_e( 'Descending title', 'geodiradvancesearch' ); ?>" style="width:75%"/> <i class="fas fa-arrow-down" aria-hidden="true"></i>
					</span>
				</p>
			<?php } ?>

			<?php if ( $field->field_type == 'categories' || $field->field_type == 'multiselect' || $field->field_type == 'select' ) { 
				$search_operator = ! empty( $field->extra_fields['search_operator'] ) ? $field->extra_fields['search_operator'] : 'AND';
			?>
				<?php do_action( "geodir_search_cfa_before_search_operator", $field->field_type, $field, $cf ); ?>
				<p class="dd-setting-name gd-advanced-setting gd-search-operator-row" <?php echo ( $field->input_type == 'CHECK' ? '' : 'style="display:none"' ); ?>>
					<label for="gd_search_operator<?php echo $key; ?>">
						<?php
						echo geodir_help_tip( __( 'Works with Checkbox type only. )  If AND is selected then the listing must contain all the selected options, if OR is selected then the listing must contain 1 selected item.', 'geodiradvancesearch' ));
						_e( 'Search Operator', 'geodiradvancesearch' ) ?> 
					</label>
					<select name="search_operator" id="gd_search_operator<?php echo $key; ?>">
						<option value="AND" <?php selected( 'AND', $search_operator ); ?>><?php _e( 'AND', 'geodiradvancesearch' ); ?></option>
						<option value="OR" <?php selected( 'OR', $search_operator ); ?>><?php _e( 'OR', 'geodiradvancesearch' ); ?></option>
					</select>
				</p>
			<?php } ?>

			<?php do_action( "geodir_search_cfa_before_frontend_title", $field->field_type, $field, $cf ); ?>
			<p class="dd-setting-name gd-advanced-setting">
				<label for="gd_frontend_title<?php echo $key; ?>">
					<?php
					echo geodir_help_tip( __( 'Search field frontend title.', 'geodiradvancesearch' ) );
					_e( 'Frontend title', 'geodiradvancesearch'); ?>
				</label>
				<input type="text" name="frontend_title" id="gd_frontend_title<?php echo $key; ?>" value="<?php echo esc_attr( $field->frontend_title ) ?>"/>
			</p>

			<?php do_action( "geodir_search_cfa_before_description", $field->field_type, $field, $cf ); ?>
			<p class="dd-setting-name gd-advanced-setting">
				<label for="gd_description<?php echo $key; ?>">
					<?php
					echo geodir_help_tip( __( 'Search field frontend description.', 'geodiradvancesearch' ) );
					_e( 'Description', 'geodiradvancesearch' ); ?>
				</label>
				<input type="text" name="description" id="gd_description<?php echo $key; ?>" value="<?php echo esc_attr( $field->description ) ?>"/>
			</p>

			<?php do_action( "geodir_search_cfa_before_save_button", $field->field_type, $field, $cf ); ?>

			<p class="gd-tab-actions">
				<a class="item-delete submitdelete deletion" id="delete-16" href="javascript:void(0);" onclick="geodir_adv_search_delete_field(this);"><?php _e("Remove", "geodiradvancesearch");?></a> <input type="button" class="button button-primary" name="save" id="save" value="<?php _e("Save", "geodiradvancesearch");?>" onclick="geodir_adv_search_save_field(this);return false;">
			</p>
		</div>
	</div>
</li>