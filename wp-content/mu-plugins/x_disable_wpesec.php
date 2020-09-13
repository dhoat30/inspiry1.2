<?php
add_action( 'widgets_init', 'wpe_remove_encourage_tls', 0 );
function wpe_remove_encourage_tls() {
remove_action( 'init', 'wpesec_encourage_tls' );
}