<?php
add_filter( 'theme_mod_penci_featured_slider', '__return_false' );
$user_id      = get_current_user_id();
$account      = PenciUserProfile::getInstance();
$endpoint     = $account->get_endpoint();
$current_page = $account->get_current_page();
unset( $endpoint['editor'] );
get_header(); ?>
	<div class="container container-single-page container-default-page">
		<div id="main" class="penci-main-single-page-default">
			<div class="pcac_page_container">
				<div class="pcac_page_left">
					<div class="pcac_author_info">
						<?php echo get_avatar( get_current_user_id(), '128', '', get_the_author_meta( 'display_name' ), array( 'class' => 'img-rounded' ) ); ?>
						<div class="pcac_author_content">
							<h3 class="pcac_author_name">
								<?php echo esc_html( get_the_author_meta( 'display_name', $user_id ) ); ?>
							</h3>
							<span class="pcac_author_roles">
								<?php echo esc_html( get_the_author_meta( 'email', $user_id ) ); ?>
							</span>
						</div>
						<div class="pcac_account_nav">
							<ul>
								<?php
								foreach ( array_slice( $endpoint, 1 ) as $item ) :
									$class = $current_page == $item['slug'] ? 'penci-nav-user-item active' : 'penci-nav-user-item';
									?>
									<li class="<?php echo esc_attr( $class ); ?>">
										<a href="<?php echo esc_url( penci_home_url_multilang( $endpoint['account']['slug'] . '/' . $item['slug'] ) ); ?>"><?php _e( $item['title'], 'soledad' ); ?></a>
									</li>
								<?php endforeach ?>
							</ul>
							<?php
							if ( has_nav_menu( 'user-menu' ) ) {
								wp_nav_menu(
									array(
										'theme_location' => 'user-menu',
										'container'      => '',
									)
								);
							}
							?>
						</div>
						<?php do_action( 'penci_after_account_nav' ); ?>
					</div>
				</div>
				<div class="pcac_page_right">
					<h1 class="pcac_account_title"><?php do_action( 'penci_account_right_title' ); ?></h1>
					<?php do_action( 'penci_account_main_content' ); ?>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer(); ?>
