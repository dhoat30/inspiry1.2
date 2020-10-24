<?php

// Check GeoDir_Lists_List class exists or not.
if( ! class_exists( 'GeoDir_Lists_List' ) ) {

    /**
     * GeoDir_Lists_Lists Class for the list output.
     *
     * @since 2.0.0
     *
     * Class GeoDir_Lists_List
     */
    class GeoDir_Lists_List{

        /**
         * Constructor.
         *
         * @since 2.0.0
         *
         * GeoDir_Lists_List constructor.
         */
        public function __construct() {

            add_filter( 'the_content', array( $this, 'list_post_content' ) );

            add_action('geodir_lists_loop_actions', array( $this, 'loop_actions' ) );

            // author page permalinks
            add_filter( 'init', array( $this, 'author_rewrite_rules' ) );
            

        }

        /**
         * Add author page pretty urls.
         *
         * @since 2.0.0
         *
         * @param array $rules Rules.
         *
         * @return array $rules.
         */
        public function author_rewrite_rules( ){
            global $wp_rewrite;

            $post_type = 'gd_list';
            $pt = GeoDir_Lists_CPT::post_type_args();
            $cpt_slug = $pt['rewrite']['slug'];

            // main rule
            $regex = "^".$wp_rewrite->author_base."/([^/]+)/$cpt_slug/?$";
            $redirect = 'index.php?author_name=$matches[1]&post_type='.$post_type;
            add_rewrite_rule($regex,$redirect,'top');

            // paged rule
            $regex = "^".$wp_rewrite->author_base."/([^/]+)/$cpt_slug/page/?([0-9]{1,})/?$";
            $redirect = 'index.php?author_name=$matches[1]&post_type='.$post_type.'&paged=$matches[2]';
            add_rewrite_rule($regex,$redirect,'top');


        }

        public function loop_actions() {
            global $post;


            if ( geodir_design_style() ) {
                $this->loop_actions_aui();
                return;
            }

            $user_id = get_current_user_id();
            $is_author = $user_id && ! empty( $post->post_author ) && $post->post_author == $user_id ? true : false;
            $real_post_status = geodir_lists_get_real_post_status( $post->ID );

            if ( $is_author && $real_post_status && $real_post_status == 'private' ) {
                ?>
                <div class="clearfix gd-lists-loop-info">
                    <span class="gd-lists-list-private">
                    <?php echo '<i class="fas fa-user-secret" title="" aria-hidden="true"></i> '.sprintf( __( "Non-public %s (you can still share a direct link with your friends)", 'gd-lists' ), geodir_lists_name_singular() );?>
                    </span>
                </div>
            <?php }

            if ( $is_author ) {
            ?>
            <div class="clearfix gd-lists-loop-author-actions">
                <span class="gd-lists-author-action-edit">
                    <a href="javascript:void(0);" onclick="gd_list_edit_list_dialog(<?php echo absint($post->ID);?>)"><?php echo sprintf( __( "%s Edit %s", 'gd-lists' ), '<i class="fas fa-edit" aria-hidden="true"></i>', geodir_lists_name_singular() ) ?></a>
                </span>
                <span class="gd-lists-author-action-delete">
                    <a href="javascript:void(0);" onclick="gd_list_delete_list(<?php echo absint($post->ID);?>)"><?php echo sprintf( __( "%s Delete %s", 'gd-lists' ), '<i class="fas fa-trash-alt" aria-hidden="true"></i>', geodir_lists_name_singular() ) ?></a>
                </span>
            </div>
            <?php
            }
        }

		public function loop_actions_aui() {
			global $post;

			$user_id = get_current_user_id();
			$is_author = $user_id && ! empty( $post->post_author ) && $post->post_author == $user_id ? true : false;
			$real_post_status = geodir_lists_get_real_post_status( $post->ID );

			if ( $is_author ) {
			?>
			<div class="card border-0 gd-lists-loop-author-actions">
				<div class="card-body p-0 pb-3">
					<div class="card-title text-right">
						<div class="card-toolbar">
							<?php
							echo aui()->button(
								array(
									'type' => 'a',
									'href' => 'javascript:void(0);',
									'class' => 'btn btn-sm btn-outline-primary ml-2 gd-lists-author-action-edit',
									'icon' => 'fas fa-edit mr-1',
									'content' => wp_sprintf( __( "Edit %s", 'gd-lists' ), geodir_lists_name_singular() ),
									'no_wrap' => true,
									'onclick' => 'gd_list_edit_list_dialog(' . absint( $post->ID ) .');'
								)
							);

							echo aui()->button(
								array(
									'type' => 'a',
									'href' => 'javascript:void(0);',
									'class' => 'btn btn-sm btn-outline-danger ml-2 gd-lists-author-action-delete',
									'icon' => 'fas fa-trash-alt mr-1',
									'content' => wp_sprintf( __( "Delete %s", 'gd-lists' ), geodir_lists_name_singular() ),
									'no_wrap' => true,
									'onclick' => 'gd_list_delete_list(' . (int) $post->ID . ');'
								)
							);
							?>
						</div>
					</div> 
				</div>
			</div>
			<?php
			}

			if ( $is_author && $real_post_status && $real_post_status == 'private' ) {
			?>
			<div class="alert alert-info gd-lists-loop-info mb-4" role="alert">
				<span class="gd-lists-list-private"><?php echo '<i class="fas fa-user-secret mr-1" aria-hidden="true"></i>' . wp_sprintf( __( "Non-public %s (you can still share a direct link with your friends)", 'gd-lists' ), geodir_lists_name_singular() ); ?></span>
			</div>
			<?php 
			}
		}

        /**
         * Method are use to added links and update and listing content.
         *
         * @since 2.0.0
         *
         * @param string $post_content Get selected post Post_content.
         * @return string $post_content
         */
        public function list_post_content( $post_content ) {

            global $post;

            if (!$post) {
                return $post_content;
            }

            $post_type = !empty( $post->post_type ) ? $post->post_type :'';

            if ( 'gd_list' !== $post_type ) {
                return $post_content;
            }

            if( is_single() && 'gd_list' === $post_type ) {
                $post_content = "[gd_list_loop_actions]" . $post_content;
                $post_content .= "[gd_list_loop layout=\"2\" mt=4 mb=4]"; //@todo we shoudl change this to a page template for easy user editing
            }

            return $post_content;

        }

        


    }

    new GeoDir_Lists_List();
}