
<input class="<?php echo esc_attr( $value['class'] ); ?>" id="<?php echo $value['name']; ?>" type="text" name="<?php echo $this->_optionName."[".$value['name']."]"; ?>" value="<?php echo isset( $setting_options[$value['name']] ) ? $setting_options[$value['name']] : ''; ?>" placeholder="<?php echo $value['placeholder']; ?>">
