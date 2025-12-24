<?php
namespace PenciSoledadElementor\Modules\PenciLoginForm\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciLoginForm extends Base_Widget {

	public function get_name() {
		return 'penci-login-form';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Login/Register Form', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-lock-user';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'facebook', 'social', 'embed', 'page' );
	}

	protected function register_controls() {
		

		// Section layout
		$this->start_controls_section(
			'section_page', array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		
		$this->add_control(
			'app_id', array(
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<span style="color: #888;font-size: 12px;">Please note that when a user is logged in, the registration form will be hidden. And if you select to show "Register" form, you need to go to Dashboard > Settings > General > on "Membership" select "Anyone can register" to make the Register form displays.</span>',
				'content_classes' => 'elementor-descriptor',
			)
		);
		
		$this->add_control(
			'form_style', array(
				'label'   => __( 'Choose Form Type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'login',
				'options' => array(
					'login'    => esc_html__( 'Login', 'soledad' ),
					'register' => esc_html__( 'Register', 'soledad' ),
				)
			)
		);

		$this->end_controls_section();
		$this->register_block_title_section_controls();

		// Design
		$this->start_controls_section(
			'section_design_content',
			array(
				'label' => __( 'Content', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'pformtext_color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-user-login' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pformtext_typo',
				'label'    => __( 'Description Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-user-login',
			)
		);
		$this->add_control(
			'pinput_color',
			array(
				'label'     => __( 'Input Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-register-wrap input[type="text"]'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-register-wrap input[type="email"]'    => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-register-wrap input[type="url"]'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-register-wrap input[type="password"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="text"]'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="email"]'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="url"]'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="password"]'    => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pinput_placeholder_color',
			array(
				'label'     => __( 'Input Placeholder Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} form input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} form input:-moz-placeholder'          => 'color: {{VALUE}};',
					'{{WRAPPER}} form input::-ms-input-placeholder'     => 'color: {{VALUE}};',
					'{{WRAPPER}} form input::placeholder'     => 'color: {{VALUE}}; opacity: 1;',
				),
			)
		);
		$this->add_control(
			'pinput_border_color',
			array(
				'label'     => __( 'Input Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-register-wrap input[type="text"]'     => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-register-wrap input[type="email"]'    => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-register-wrap input[type="url"]'      => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-register-wrap input[type="password"]' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="text"]'        => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="email"]'       => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="url"]'         => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="password"]'    => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pinput_bgcolor',
			array(
				'label'     => __( 'Input Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-register-wrap input[type="text"]'     => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-register-wrap input[type="email"]'    => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-register-wrap input[type="url"]'      => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-register-wrap input[type="password"]' => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-login input[type="text"]'        => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-login input[type="email"]'       => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-login input[type="url"]'         => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-login input[type="password"]'    => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pinput_typo',
				'label'    => __( 'Input Typography', 'soledad' ),
				'selector' => '{{WRAPPER}}  .penci-register-wrap input[type="text"],{{WRAPPER}}  .penci-register-wrap input[type="email"],{{WRAPPER}}  .penci-register-wrap input[type="password"],{{WRAPPER}}  .penci-user-login input[type="text"],{{WRAPPER}}  .penci-user-login input[type="email"],{{WRAPPER}}  .penci-user-login input[type="password"]'
			)
		);
		$this->add_control(
			'psubmitbtn_color',
			array(
				'label'     => __( 'Button Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-register-wrap input[type="submit"],{{WRAPPER}} .penci-user-login input[type="submit"], .penci-user-logged-in .penci-user-action-links a' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'psubmitbtn_bgcolor',
			array(
				'label'     => __( 'Button Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-register-wrap input[type="submit"]'        => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-login input[type="submit"]'           => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-logged-in .penci-user-action-links a' => 'background-color: {{VALUE}};border-color: {{VALUE}};'
				),
			)
		);
		$this->add_control(
			'psubmitbtn_hcolor',
			array(
				'label'     => __( 'Button Hover Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-register-wrap input[type="submit"]:hover'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-login input[type="submit"]:hover'           => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-user-logged-in .penci-user-action-links a:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'psubmitbtn_hbgcolor',
			array(
				'label'     => __( 'Button Background Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-register-wrap input[type="submit"]:hover'        => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-login input[type="submit"]:hover'           => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-user-logged-in .penci-user-action-links a:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};'
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'psubmitbtn_typo',
				'label'    => __( 'Button Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-register-wrap input[type="submit"],{{WRAPPER}} .penci-user-login input[type="submit"],{{WRAPPER}} .penci-user-logged-in .penci-user-action-links a'
			)
		);
		
		$this->add_control(
			'ploginregis_link',
			array(
				'label'     => __( 'Login & Register Links Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-loginform-extra a'        => 'color: {{VALUE}};border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
		
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$form_type = $settings['form_style'];

		$css_class  = 'penci-block-vc penci-login-register';

		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if( ! is_user_logged_in() || ( $form_type != 'register' && is_user_logged_in() ) ){
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
			<div class="penci-block_content">
				<div class="penci-login-wrap penci-user-login clearfix<?php echo( 'login' != $form_type ? ' hidden' : '' ); ?>">
					<?php
					if ( ! is_user_logged_in() ) {
						\Penci_Vc_Helper::_login_form();
					} else {
						$current_user = wp_get_current_user();
						?>
						<div class="penci-user-logged-in">
							<div class="penci-login-header">
								<div class="penci-login-avatar">
									<?php echo get_avatar( $current_user->ID, 85 ); ?>
								</div>
								<p>
									<span class="penci-text-hello"><?php echo penci_get_setting( 'penci_trans_hello_text' ); ?></span>
									<span class="penci-display_name"><?php echo $current_user->display_name; ?></span>
								</p>
							</div>
							<div class="penci-user-action-links">
								<?php
								if ( class_exists( 'bbpress' ) ) {
									$profile_url = bbp_get_user_profile_url( bbp_get_current_user_id() );
								} else {
									$profile_url = get_edit_user_link();
								}
								?>
								<a class="penci-button penci-button-ptofile" href="<?php echo $profile_url; ?>"><?php penci_fawesome_icon('far fa-user-circle'); ?> <?php echo penci_get_setting( 'penci_trans_profile_text' ); ?></a>
								<a class="penci-button penci-button-logout" href="<?php echo wp_logout_url( $current_url ); ?>"><?php penci_fawesome_icon('fas fa-sign-out-alt'); ?> <?php echo penci_get_setting( 'penci_trans_logout_text' ); ?></a>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<?php if( ! is_user_logged_in() && get_option( 'users_can_register' ) ){ ?>
				<div class="penci-register-wrap clearfix<?php echo( 'register' != $form_type ? ' hidden' : '' ); ?>">
					<div class="penci-register-container">
						<form name="form" id="penci-registration-form" class="penci-registration-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post" novalidate="novalidate">
							<input type="hidden" name="_wpnonce" class="penci_form_nonce" value="<?php echo wp_create_nonce( 'register' ); ?>">
							<p class="register-input">
								<input class="penci_first_name penci-input" name="penci_first_name" type="text" placeholder="<?php echo penci_get_setting( 'penci_pregister_first_name' ); ?>"/>
							</p>
							<p class="register-input">
								<input class="penci_last_name penci-input" name="penci_last_name" type="text" placeholder="<?php echo penci_get_setting( 'penci_pregister_last_name' ); ?>"/>
							</p>
							<p class="register-input">
								<input class="penci_user_name penci-input" name="penci_user_name" type="text" placeholder="<?php echo penci_get_setting( 'penci_pregister_user_name' ); ?>"/>
							</p>
							<p class="register-input">
								<input class="penci_user_email penci-input" name="penci_user_email" type="email" placeholder="<?php echo penci_get_setting( 'penci_pregister_user_email' ); ?>"/>
							</p>
							<p class="register-input">
								<input class="penci_user_pass penci-input" name="penci_user_pass" type="password" placeholder="<?php echo penci_get_setting( 'penci_pregister_user_pass' ); ?>"/>
							</p>
							<p class="register-input">
								<input class="penci_user_pass_confirm penci-input" name="penci_user_pass_confirm" type="password" placeholder="<?php echo penci_get_setting( 'penci_pregister_pass_confirm' ); ?>"/>
							</p>
							<?php do_action( 'register_form' ); ?>
							<p class="register-input">
								<input type="submit" name="penci_submit" class="button" value="<?php echo penci_get_setting( 'penci_pregister_button_submit' ); ?>"/>
							</p>
						</form>
						<?php
						echo '<div class="penci-loginform-extra"><a class="penci-user-login-here" href="' . esc_url( wp_login_url() ) . '">' . penci_get_setting( 'penci_pregister_label_registration' ) . '</a></div>';
						?>
					</div>
				</div>
				<?php } ?>
				<div class="penci-loading-icon"><?php penci_fawesome_icon('fas fa-spinner fa-pulse fa-3x fa-fw'); ?></div>
			</div>
		</div>
		<?php
		}
	}
}
