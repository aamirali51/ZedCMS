<?php
if ( class_exists( 'TGMPA_List_Table' ) ) {
	class Soledad_DashPluginsTable extends TGMPA_List_Table {

		public $optional_plugins = [];

		/**
		 * Unlisted plugins only added for installation helper of demo importer.
		 *
		 * @var array
		 */
		public $hidden_plugins = [];

		public function __construct() {
			parent::__construct();

			// Collect optional and hidden plugin ids.
			foreach ( $this->tgmpa->plugins as $plugin ) {
				if ( ! empty( $plugin['optional'] ) ) {
					$this->optional_plugins[] = $plugin['slug'];
				}

				if ( ! empty( $plugin['hidden'] ) ) {
					$this->hidden_plugins[] = $plugin['slug'];
				}
			}
		}

		/**
		 * Extend bulk actions process to account for activations.
		 */
		public function process_bulk_actions() {

			$installed   = false;
			$to_activate = false;

			if ( 'tgmpa-bulk-install' === $this->current_action() && ! empty( $_POST['plugin'] ) ) {

				$plugins = (array) $_POST['plugin'];

				foreach ( $plugins as $plugin ) {
					if ( ! $this->tgmpa->is_plugin_active( $plugin ) ) {
						$to_activate = true;
						break;
					}
				}

				// Install the plugins normally. $_POST will be mutated, so store original.
				$orig_post = $_POST;
				$installed = parent::process_bulk_actions();

				// If the intention is to install for inactive plugins, assume they should be activated.
				if ( $to_activate ) {
					$_REQUEST['action'] = 'tgmpa-bulk-activate';
					$_POST              = $orig_post;
				}
			}

			parent::process_bulk_actions();

			// Plugins had to be activated but nothing was installed.
			if ( ! $installed && $to_activate ) {
				echo '<p><a href="' . esc_url( $this->tgmpa->get_tgmpa_url() ) . '" target="_parent">' . esc_html( $this->tgmpa->strings['return'] ) . '</a></p>';

				return true;
			}
		}

		/**
		 * Add additional categories compared to default and add optional plugins to
		 * 'update' and 'all-registered' context only. Add to 'all' only if there's a
		 * an update and the plugin is already installed.
		 */
		protected function categorize_plugins_to_views() {

			$plugins = array(
				'all-registered' => array(),
				'all'            => array(),
				'install'        => array(),
				'update'         => array(),
				'activate'       => array(),
			);

			foreach ( $this->tgmpa->plugins as $slug => $plugin ) {

				$is_installed = $this->tgmpa->is_plugin_installed( $slug );
				$is_active    = $this->tgmpa->is_plugin_active( $slug );
				$has_update   = $this->tgmpa->does_plugin_have_update( $slug );

				if ( $is_active && false === $has_update ) {
					// No need to display plugins if they are installed, up-to-date and active.
					continue;
				}

				$plugins['all-registered'][ $slug ] = $plugin;

				// Add to all if it's not an optional plugin, or if an optional active plugin has an update.
				if ( empty( $plugin['optional'] ) || ( $is_active && $has_update ) ) {
					$plugins['all'][ $slug ] = $plugin;
				}

				if ( ! $is_installed ) {
					if ( empty( $plugin['optional'] ) ) {
						$plugins['install'][ $slug ] = $plugin;
					}
				} else {
					if ( $is_active && $has_update ) {
						$plugins['update'][ $slug ] = $plugin;
					}

					if ( empty( $plugin['optional'] ) && $this->tgmpa->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}

			return $plugins;
		}

		/**
		 * Gather data; public.
		 */
		public function gather_plugin_data() {
			return $this->_gather_plugin_data();
		}
	}
}

class Soledad_Theme_Admin_DashPlugins {
	public function __construct() {
		add_action( 'tgmpa_after_install_plugins_page', array( $this, 'display' ) );
	}

	public function display( $tgmpa ) {
		$table               = new Soledad_DashPluginsTable;
		$table->view_context = 'all-registered';

		$plugins   = $table->gather_plugin_data();
		$optionals = $table->optional_plugins;
		$hidden    = $table->hidden_plugins;

		// Only optional and non-hidden plugins here.
		$plugins = array_filter( $plugins, function ( $plugin ) use ( $optionals, $hidden ) {
			return ! in_array( $plugin['slug'], $hidden ) && in_array( $plugin['slug'], $optionals );
		} );

		if ( ! count( $plugins ) ) {
			return;
		}

		arsort( $plugins );

		?>

        <br/>
        <br/>

        <hr/>

        <div class="penci-dash-options-plugins">

            <h3><?php _e( 'Exclusive Add-On Plugins', 'soledad' ); ?></h3>

            <p><?php _e( 'The following plugins are add-on features. Let\'s install it if you need to use an add-on feature.', 'soledad' ); ?></p>

			<?php
			$penci_dismis_notes = get_option( 'penci_dismiss_notices', '' );
			if ( 'yes' != $penci_dismis_notes ):
				?>
                <div class="penci-plugins-notice">
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Text To Speech:</span> <?php _e( 'A plugin to help you converts text into human-like speech. The Plugin uses the latest technology of machine learning and artificial intelligence to play a high-quality human voice. The Plugin basis is the Google Cloud Platform.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Data Migrator:</span> <?php esc_html_e( 'A plugin to help you migration data from other WordPress Themes into Soledad Theme. Supports WordPress Themes: Newspaper, Jnews, Jannah, Sahifa, Newsmag, Publisher, SmartMag, Bimber, Solopine\'s Themes', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Social Feed:</span> <?php esc_html_e( 'A plugin to help you connect to some socials media ( like Twitter ) to show the Feed of those socials media.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Slider:</span> <?php esc_html_e( 'A plugin to help you build a custom slider does not based on Posts when you use Customize to config the homepage. If you are using Elementor or WPBakery - you do not need to use this plugin.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Soledad AMP:</span> <?php esc_html_e( 'Exclusive AMP plugin from PenciDesign. It automatically adds Accelerated Mobile Pages (Google AMP Project) functionality to your WordPress site. AMP makes your website faster for Mobile visitors.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Frontend Submission:</span> <?php esc_html_e( 'Frontend submit article for Soledad WordPress Theme', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Paywall:</span> <?php esc_html_e( 'Member subscription for reading posts in Soledad Theme - WooCommerce or GetPaid plugin required.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Pay Writer:</span> <?php esc_html_e( 'Provide authors payment and donation for the post they made. easily configure how much author can earn for a post by payment option', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci RSS Aggregator:</span> <?php esc_html_e( 'The most powerful WordPress RSS aggregator, helping you curate content, autoblog, import and display unlimited RSS feeds within a few minutes', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Podcast:</span> <?php esc_html_e( 'This plugin enables you to develop a top-notch podcast website with a wide range of features.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci AI SmartContent Creator:</span> <?php esc_html_e( 'This plugin can assist you in creating articles and blog posts, as well as generating AI-based images. Additionally, it can suggest topic ideas and optimize keywords to save you time and improve the quality of your content. It is a must-have tool for content creation.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Bookmark & Follow:</span> <?php esc_html_e( 'Penci Bookmark & Follow plugin allows your visitors to follow changes on your site for particular post, page, authors etc.', 'tgmpa' ); ?>
                    </p>
					<p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Finance:</span> <?php esc_html_e( 'Display finance stock or cryptocurrency data.', 'tgmpa' ); ?>
                    </p>
					<p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Sport:</span> <?php esc_html_e( 'Displays scores and rankings for popular sports/leagues.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Smart Crop Thumbnails:</span> <?php esc_html_e( 'A plugin that allows you to select an Interest Point in your images during cropping. This feature helps you manage how your images will be cropped, rather than using the default crop from WordPress.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Live Blog:</span> <?php esc_html_e( 'Covering a conference, sports event, breaking news or other quickly developing events? You want your readers to be updated as quickly as possible. The best way to do that is by providing them with a liveblog.', 'tgmpa' ); ?>
                    </p>
                    <p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Google Analytics Views:</span> <?php esc_html_e( 'Sync pageview data from Google Analytics to your WordPress Database, enabling you to sort posts, view pageview data in the WordPress Dashboard, and output pageviews to your visitors.', 'tgmpa' ); ?>
                    </p>
					<p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Mobile Template:</span> <?php esc_html_e( 'Create separate Page Content and Templates for desktop and mobile.', 'tgmpa' ); ?>
                    </p>
					<p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Filter Everything:</span> <?php esc_html_e( 'Filters everything in WordPress & WooCommerce: Products, any Post types, by Any Criteria. Compatible with WPML, ACF and others popular. Supports AJAX', 'tgmpa' ); ?>
                    </p>
					<p class="penci-dplugins"><span
                                class="penci-notice-head">Penci Player Rankings:</span> <?php esc_html_e( 'This plugin helps you easily create ranking tables for players across different fields. It supports search, filtering based on various criteria, and displays detailed information for each player.', 'tgmpa' ); ?>
                    </p>
                    <div class="dis-missnote">
                        <p><?php esc_html_e( 'Understand it?', 'tgmpa' ); ?> <a
                                    href="?page=tgmpa-install-plugins&penci-dismis=penci_dismiss_notices"><?php esc_html_e( 'Click here to hide this note.', 'tgmpa' ); ?></a>
                        </p>
                    </div>
                </div>
			<?php endif; ?>

            <table class="wp-list-table widefat fixed">
                <thead>
                <tr>
                    <th class="manage-column column-plugin column-primary">Plugin</th>
                    <th class="manage-column column-source">Source</th>
                    <th scope="col" id="type" class="manage-column column-type">Type</th>
                    <th scope="col" id="status" class="manage-column column-status">Status</th>
                </tr>
                </thead>

				<?php foreach ( $plugins as $plugin ): ?>

                    <tr>
                        <td class="plugin column-plugin has-row-actions column-primary"><?php
							echo $table->column_plugin( $plugin ); // phpcs:ignore WordPress.Security.EscapeOutput -- Safe from TGMPA_List_Table
							?></td>
                        <td class="source column-source"><?php echo esc_html( $plugin['source'] ); ?></td>
                        <td class="type column-type">Optional</td>
                        <td class="status column-status"><?php
							echo esc_html( $plugin['status'] );

							if ( strstr( $plugin['status'], 'Update' ) ) {
								echo '<hr />';
								echo $table->column_version( $plugin );  // phpcs:ignore WordPress.Security.EscapeOutput -- Safe from TGMPA_List_Table
							}
							?></td>
                    </tr>

				<?php endforeach; ?>
            </table>

        </div>
		<?php
	}
}

new Soledad_Theme_Admin_DashPlugins();