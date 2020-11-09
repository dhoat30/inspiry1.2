<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class DAE_Subscriber {

    public $id;
    public $meta;
    public $links;
    public $has_used_links;

    /**
     * Retrieve DAE_Suscriber instance.
     * 
     * @param int|string $subscriber Subscriber ID or Subscriber Email
     */
    public static function get_instance( $subscriber ) {

        global $wpdb;
        $table_subscribers = $wpdb->prefix . 'dae_subscribers';
        $table_subscribermeta = $wpdb->prefix . 'dae_subscribermeta';
        $table_links = $wpdb->prefix . 'dae_links';

        $subscriber_id = (int) $subscriber;

        if ( ! $subscriber_id ) {
            
            $subscriber_email = sanitize_email( $subscriber );

            if ( empty( $subscriber_email ) ) {
                return false;
            }

            $subscribermeta_row = $wpdb->get_row( $wpdb->prepare( "SELECT subscriber_id FROM $table_subscribermeta WHERE meta_value = %s LIMIT 1", $subscriber_email ) );

            if ( empty( $subscribermeta_row ) ) {
                return false;
            }

            $subscriber_id = (int) $subscribermeta_row->subscriber_id;

        }

        $subscribermeta = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $table_subscribermeta WHERE subscriber_id = %d", $subscriber_id ) );
        $links = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_links WHERE subscriber_id = %d", $subscriber_id ) );

        if ( empty( $subscribermeta ) ) {
            return false;
        } else {
            return new DAE_Subscriber( $subscriber_id, $subscribermeta, $links );
        }

    }

    /**
     * Constructor
     * 
     * @param int $subscriber_id Subscriber ID
     * @param array $subscribermeta Array of subscriber meta objects
     * @param array $links Array of link objects
     */
    public function __construct( $subscriber_id, $subscribermeta, $links ) {

        $this->id = $subscriber_id;

        foreach ( $subscribermeta as $meta ) {
            $meta_array[ $meta->meta_key ] = $meta->meta_value;
        }

        $this->meta = $meta_array;

        foreach ( $links as $link ) {
            foreach ( get_object_vars( $link ) as $link_key => $link_value ) {

                if ( 'file' != $link_key ) {
                    if ( 'id' == $link_key || 'subscriber_id' == $link_key ) {
                        $array_links[ $link->file ][ $link_key ] = (int) $link_value;
                    } else {
                        $array_links[ $link->file ][ $link_key ] = $link_value;
                    }
                }

                if ( ! isset( $links_used ) && 'link_used' == $link_key && 'used' == $link_value ) {
                    $links_used = true;
                }

            }
        }

        $this->links = empty( $array_links ) ? $links : $array_links;
        $this->has_used_links = empty( $links_used ) ? false : true;

    }

    /**
     * Insert new subscriber.
     * 
     * @param array $subscribermeta Associative array of subscriber meta.
     */
    public static function insert( $subscribermeta ) {

        global $wpdb;
        $table_subscribers = $wpdb->prefix . 'dae_subscribers';
        $table_subscribermeta = $wpdb->prefix . 'dae_subscribermeta';
        
        if ( ! is_array( $subscribermeta ) ) {
            return false;
        }

        $number_rows = $wpdb->insert(
            $table_subscribers,
            array(
                'time' => current_time( 'Y-m-d H:i:s' )
            ),
            array( '%s' )
        );

        if ( false === $number_rows ) {
            return false;
        }
        
        $subscriber_id = $wpdb->insert_id;
        
        foreach ( $subscribermeta as $key => $value ) {
            
            if ( is_numeric( $value ) ) {
                $value_format = '%d';
            } else {
                $value_format = '%s';
            }
            
            $wpdb->insert(
                $table_subscribermeta,
                array(
                    'subscriber_id'	=> $subscriber_id,
                    'meta_key'		=> $key,
                    'meta_value'	=> $value
                ),
                array( '%d', '%s', $value_format )
            );
            
        }

        return $subscriber_id;

    }

    public static function insert_link( $subscriber_id, $form_content, $file ) {

        global $wpdb;
        $table_links = $wpdb->prefix . 'dae_links';

        $subscriber_id = (int) $subscriber_id;

        if ( ! $subscriber_id ) {
            return false;
        }

        $number_rows = $wpdb->insert(
            $table_links,
            array(
                'subscriber_id'	=> $subscriber_id,
                'time'			=> current_time( 'Y-m-d H:i:s' ),
                'ip'			=> mckp_get_client_ip(),
                'form_content'	=> $form_content,
                'file'			=> $file,
                'link_used'		=> 'not used'
            ),
            array( '%d', '%s', '%s', '%s', '%s', '%s' )
        );

        if ( false === $number_rows ) {
            return false;
        } else {
            return $wpdb->insert_id;
        }

    }

    public static function update_subscriber_meta( $subscriber_id, $values ) {

        global $wpdb;
        $table_subscribermeta = $wpdb->prefix . 'dae_subscribermeta';
        $subscribermeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_subscribermeta WHERE subscriber_id = %d", $subscriber_id ) );

        $subscriber_id = (int) $subscriber_id;
        if ( empty( $subscriber_id ) ) {
            return false;
        }

        foreach ( $subscribermeta as $meta ) {
            $meta_array[ $meta->meta_key ] = $meta->meta_value;
        }

        foreach ( $values as $key => $value ) {

            if ( is_numeric( $value ) ) {
                $value_format = '%d';
            } else {
                $value_format = '%s';
            }

            if ( isset( $meta_array[ $key ] ) ) {

                $number_rows = $wpdb->update(
                    $table_subscribermeta,
                    array(
                        'meta_value'	=> $value
                    ),
                    array(
                        'subscriber_id'	=> $subscriber_id,
                        'meta_key'		=> $key
                    ),
                    array( $value_format ),
                    array( '%d', '%s' )
                );

            } else {

                $number_rows = $wpdb->insert(
                    $table_subscribermeta,
                    array(
                        'subscriber_id' => $subscriber_id,
                        'meta_key'      => $key,
                        'meta_value'    => $value
                    ),
                    array( '%d', '%s', $value_format )
                );

            }

            if ( false === $number_rows ) {
                return false;
            }

        }

        return true;

    }

    public static function update_link( $subscriber_id, $file ) {

        global $wpdb;
        $table_links = $wpdb->prefix . 'dae_links';
        
        $subscriber_id = (int) $subscriber_id;
        if ( empty( $subscriber_id ) ) {
            return false;
        }
			
        $number_rows = $wpdb->update(
            $table_links,
            array(
                'link_used'	=> 'used',
                'time_used'	=> current_time( 'Y-m-d H:i:s' ),
                'ip_used'	=> mckp_get_client_ip()
            ),
            array(
                'subscriber_id'	=> $subscriber_id,
                'file'			=> $file
            ),
            array( '%s', '%s', '%s' ),
            array( '%d', '%s' )
        );

        if ( false === $number_rows ) {
            return false;
        } else {
            return true;
        }

    }

    public static function delete_link( $id ) {

        global $wpdb;
        $table_links = $wpdb->prefix . 'dae_links';
        
        $id = (int) $id;
        if ( empty( $id ) ) {
            return false;
        }

        $number_rows = $wpdb->delete(
            $table_links,
            array( 'id' => $id ),
            array( '%d' )
        );

        if ( false === $number_rows ) {
            return false;
        } else {
            return true;
        }

    }

}

?>