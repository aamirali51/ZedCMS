<?php
add_action('widgets_init', 'penci_videoplaylist_widget');

function penci_videoplaylist_widget()
{
    register_widget('Penci_Video_Playlist_Widget');
}

if (!class_exists('Penci_Video_Playlist_Widget')) {
    class Penci_Video_Playlist_Widget extends WP_Widget
    {

        /**
         * Widget setup.
         */
        function __construct()
        {
            $widget_ops = array(
                'classname' => 'penci_videoplaylist_widget',
                'description' => esc_html__('Video playlist block', 'soledad')
            );
            $control_ops = array('id_base' => 'penci_videoplaylist_widget');

            global $wp_version;
            if (4.3 > $wp_version) {
                $this->WP_Widget('penci_videoplaylist_widget', penci_get_theme_name('.Soledad', true) . esc_html__('Penci Video Playlist', 'soledad'), $widget_ops, $control_ops);
            } else {
                parent::__construct('penci_videoplaylist_widget', penci_get_theme_name('.Soledad', true) . esc_html__('Penci Video Playlist', 'soledad'), $widget_ops, $control_ops);
            }
        }

        public function widget($args, $instance)
        {
            if (!isset($args['widget_id'])) {
                $args['widget_id'] = $this->id;
            }

            $title = isset($instance['title']) ? $instance['title'] : '';
            $title = apply_filters('widget_title', $title);

            $defaults = array(
                'title' => esc_html__('Video Playlist', 'soledad'),
                'video_type' => '',
                'yplaylist_id' => '',
                'yplaylist_limit' => 5,
                'penci_block_width' => 3,
                'videos_list' => '',
                'hide_duration' => '',
                'hide_order_number' => '',
                'video_list_title' => '',
                'video_title_length' => 10,
                'block_id' => rand(1000, 100000),
            );

            $settings = wp_parse_args((array)$instance, $defaults);

            if ((!$settings['videos_list'] && $settings['video_type'] == 'custom') || ($settings['video_type'] == 'youtube_playlist' && !$settings['yplaylist_id'])) {
                return;
            }
            ?>
            <?php echo $args['before_widget']; ?>
            <?php
            if ($title && !$settings['video_list_title']) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            $css_class = 'penci-block-vc penci-video_playlist';
            $css_class .= ' pencisc-column-1';
            ?>
            <div class="<?php echo esc_attr($css_class); ?>">
                <div class="penci-block_content">
                    <?php

                    if (!get_theme_mod('penci_youtube_api_key') && preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $settings['videos_list'], $matches)) {
                        echo 'The Soledad theme can retrieve YouTube video information without requiring an API key. However, for greater stability, please go to Customize > General > Extra Options > YouTube API Key and enter an api key :)';
                    }

                    $cache_name = 'penci-shortcode-playlist-' . $settings['video_type'] . '-' . $settings['block_id'];
                    $cache_key = 'penci-shortcode-playlist-key' . $settings['video_type'] . '-' . $settings['block_id'];

                    $videos = preg_split('/\r\n|[\r\n]/', $settings['videos_list']);
                    $videos_list = get_transient($cache_name);
                    $videos_list_key = get_transient($cache_key);
                    $rand_video_list = rand(1000, 100000);


                    if ($settings['video_type'] != 'youtube_playlist' && (empty($videos_list) || $settings['videos_list'] != $videos_list_key)) {
                        $videos_list = \Penci_Video_List::get_video_infos($videos);
                        set_transient($cache_name, $videos_list, 18000);
                        set_transient($cache_key, $settings['videos_list'], 18000);
                    }

                    if ($settings['video_type'] == 'youtube_playlist' && (empty($videos_list) || $settings['yplaylist_id'] != $videos_list_key)) {
                        $videos_list = \Penci_Video_List::get_playlist_videos_ids($settings['yplaylist_id'], $settings['yplaylist_limit']);
                        set_transient($cache_name, $videos_list, 18000);
                        set_transient($cache_key, $settings['videos_list'], 18000);
                    }

                    $videos_count = is_array($videos_list) ? count((array)$videos_list) : 0;


                    if (!empty($videos_list)): ?>
                        <div class="penci-video-play">
                            <?php foreach ((array)$videos_list as $key => $video): ?>
                                <?php
                                if ($key > 0) {
                                    continue;
                                }
                                ?>
                                <div class="fluid-width-video-wrapper">
                                    <iframe class="penci-video-frame"
                                            id="video-<?php echo esc_attr($rand_video_list) ?>-1"
                                            src="<?php echo esc_attr($video['id']) ?>" width="339"
                                            height="191"></iframe>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="penci-video-nav">
                            <?php if ($title && $settings['video_list_title']): ?>
                                <div class="penci-playlist-title">
                                    <div class="playlist-title-icon"><?php penci_fawesome_icon('fas fa-play'); ?></span></div>
                                    <h2><?php echo $title; ?></h2>
                                    <span class="penci-videos-number">
								<span class="penci-video-playing">1</span> /
								<span class="penci-video-total"><?php echo($videos_count) ?></span>
										<?php
                                        if (function_exists('penci_get_tran_setting')) {
                                            echo penci_get_tran_setting('penci_social_video_text');
                                        } else {
                                            esc_html_e('Videos', 'soledad');
                                        }
                                        ?>
								</span>
                                </div>
                            <?php endif; ?>
                            <?php
                            $class_nav = (!empty($settings['title']) && $settings['video_list_title']) ? ' playlist-has-title' : '';
                            $class_nav .= $videos_count > 3 ? ' penci-custom-scroll' : '';

                            $direction = is_rtl() ? ' dir="rtl"' : '';
                            ?>
                            <div class="penci-video-playlist-nav<?php echo esc_attr($class_nav); ?>"<?php echo($direction); ?>>
                                <?php
                                $video_number = 0;
                                foreach ($videos_list as $video):
                                    $video_number++;
                                    ?>
                                    <a data-name="video-<?php echo esc_attr($rand_video_list . '-' . $video_number) ?>"
                                       data-src="<?php echo esc_attr($video['id']) ?>"
                                       class="penci-video-playlist-item penci-video-playlist-item-<?php echo esc_attr($video_number); ?>">
							<span class="penci-media-obj">
								<span class="penci-mobj-img">
									<?php if (!$settings['hide_order_number']): ?>
                                        <span class="playlist-panel-item penci-video-number"><?php echo esc_attr($video_number) ?></span>
                                        <span class="playlist-panel-item penci-video-play-icon"><?php penci_fawesome_icon('fas fa-play'); ?></span>
                                        <span class="playlist-panel-item penci-video-paused-icon"><?php penci_fawesome_icon('fas fa-pause'); ?></span>
                                    <?php
                                    endif;
                                    $dis_lazy = get_theme_mod('penci_disable_lazyload_layout');
                                    if ($dis_lazy) {
                                        $class_lazy = ' penci-disable-lazy';
                                        $data_src = 'style="background-image: url(' . esc_url($video['thumb']) . ');"';
                                    } else {
                                        $class_lazy = ' penci-lazy';
                                        $data_src = 'data-bgset="' . esc_url($video['thumb']) . '"';
                                    }

                                    printf('<span class="penci-image-holder penci-video-thumbnail%s" %s><span class="screen-reader-text">%s</span></span>', $class_lazy, $data_src, esc_html__('Thumbnail youtube', 'soledad'));
                                    ?>
								</span>
								<span class="penci-mobj-body">
									<span class="penci-video-title"
                                          title="<?php echo esc_attr($video['title']); ?>"><?php echo wp_trim_words($video['title'], $settings['video_title_length'], '...'); ?></span>
									<?php if (!$settings['hide_duration']): ?>
                                        <span class="penci-video-duration"><?php echo esc_attr($video['duration']) ?></span>
                                    <?php endif; ?>
								</span>
							</span>
                                    </a>
                                <?php endforeach;
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            echo $args['after_widget'];
        }

        /**
         * Update the widget settings.
         */
        function update($new_instance, $old_instance)
        {
            $instance = $old_instance;

            $data_instance = $this->soledad_widget_defaults();

            foreach ($data_instance as $data => $value) {
                $instance[$data] = !empty($new_instance[$data]) ? $new_instance[$data] : '';
            }

            return $instance;
        }

        public function soledad_widget_defaults()
        {
            $defaults = array(
                'title' => esc_html__('Video Playlist', 'soledad'),
                'video_type' => '',
                'yplaylist_id' => '',
                'yplaylist_limit' => 5,
                'videos_list' => '',
                'hide_duration' => '',
                'hide_order_number' => '',
                'video_list_title' => '',
                'video_title_length' => 10,
                'block_id' => rand(1000, 100000)
            );

            return $defaults;
        }

        function form($instance)
        {
            $defaults = $this->soledad_widget_defaults();
            $instance = wp_parse_args((array)$instance, $defaults);

            $instance_title = $instance['title'] ? str_replace('"', '&quot;', $instance['title']) : '';
            $video_title_length = isset($instance['video_title_length']) ? absint($instance['video_title_length']) : 10;
            $hide_duration = isset($instance['hide_duration']) ? (bool)$instance['hide_duration'] : false;
            $hide_order_number = isset($instance['hide_order_number']) ? (bool)$instance['hide_order_number'] : false;
            $video_list_title = isset($instance['video_list_title']) ? (bool)$instance['video_list_title'] : false;
            ?>

            <p class="penci-field-item ">
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Block title:</label>
                <input id="<?php echo esc_attr($this->get_field_id('title')); ?>" class="widefat" type="text"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                       value="<?php echo $instance_title; ?>">
                <span class="penci-widget-desc">A title for this block, if you leave it blank the block will not have a title</span>
            </p>
            <p class="penci-field-item ">
                <label for="<?php echo esc_attr($this->get_field_id('video_type')); ?>">Video Type:</label>
                <select id="<?php echo esc_attr($this->get_field_id('video_type')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('video_type')); ?>"
                        class="widefat categories"
                        style="width:100%;">
                    <option value='' <?php if ('' == $instance['video_type']) {
                        echo 'selected="selected"';
                    } ?>>Custom
                    </option>
                    <option value='youtube_playlist' <?php if ('youtube_playlist' == $instance['video_type']) {
                        echo 'selected="selected"';
                    } ?>>Youtube Playlist
                    </option>
                </select>
            </p>
            <p class="penci-field-item ">
                <label for="<?php echo esc_attr($this->get_field_id('yplaylist_id')); ?>">Youtube Playlist
                    ID</label>
                <input type="text" id="<?php echo esc_attr($this->get_field_id('yplaylist_id')); ?>" class="widefat"
                       name="<?php echo esc_attr($this->get_field_name('yplaylist_id')); ?>"
                       value="<?php echo $instance['yplaylist_id']; ?>">
                <span class="penci-widget-desc">Enter Youtube Playlist ID or Playlist URL.</span>
            </p>
            <p class="penci-field-item ">
                <label for="<?php echo esc_attr($this->get_field_id('yplaylist_limit')); ?>">Youtube Playlist
                    Maxium Items:</label>
                <input type="number" id="<?php echo esc_attr($this->get_field_id('yplaylist_limit')); ?>"
                       class="widefat"
                       name="<?php echo esc_attr($this->get_field_name('yplaylist_limit')); ?>"
                       value="<?php echo $instance['yplaylist_limit']; ?>">
            </p>
            <p class="penci-field-item ">
                <label for="<?php echo esc_attr($this->get_field_id('videos_list')); ?>">Custom Videos List</label>
                <textarea id="<?php echo esc_attr($this->get_field_id('videos_list')); ?>" class="widefat"
                          name="<?php echo esc_attr($this->get_field_name('videos_list')); ?>"><?php echo $instance['videos_list']; ?></textarea>
                <span class="penci-widget-desc">Enter each video url in a seprated line. Supports: YouTube and Vimeo videos only.<br><span
                            style="color: red;font-weight: bold;">Note Important</span>: If  you use video come from youtube, please go to Customize &gt; General Options &gt; YouTube API Key and enter an api key.</span>
            </p>
            <p class="penci-field-item penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12">
                <label for="widget-penci-widget__videos_playlist-2-heading_meta_settings">Extra settings</label>
            </p>
            <p class="penci-field-item vc_col-sm-6">
                <label for="<?php echo esc_attr($this->get_field_id('video_list_title')); ?>">Use Video PlayList
                    Title</label>
                <input class="penci-checkbox" id="<?php echo esc_attr($this->get_field_id('video_list_title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('video_list_title')); ?>"
                       type="checkbox"<?php checked($video_list_title); ?>>
            </p>
            <p class="penci-field-item vc_col-sm-6">
                <label for="<?php echo esc_attr($this->get_field_id('hide_duration')); ?>">Hide video
                    duration</label>
                <input class="penci-checkbox" id="<?php echo esc_attr($this->get_field_id('hide_duration')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('hide_duration')); ?>"
                       type="checkbox"<?php checked($hide_duration); ?>>
            </p>
            <p class="penci-field-item vc_col-sm-6">
                <label for="<?php echo esc_attr($this->get_field_id('hide_order_number')); ?>">Hide video order
                    number</label>
                <input class="penci-checkbox" id="<?php echo esc_attr($this->get_field_id('hide_order_number')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('hide_order_number')); ?>"
                       type="checkbox"<?php checked($hide_order_number); ?>>
            </p>
            <p class="penci-field-item ">
                <label for="<?php echo esc_attr($this->get_field_id('video_title_length')); ?>">Custom Title
                    Length:</label>
                <input id="<?php echo esc_attr($this->get_field_id('video_title_length')); ?>" class="widefat"
                       type="text" name="<?php echo esc_attr($this->get_field_name('video_title_length')); ?>"
                       value="<?php echo $video_title_length; ?>">
            </p>
            <p class="penci-field-item ">
                <label for="<?php echo esc_attr($this->get_field_id('block_id')); ?>">Unique ID for Save & Clear
                    Caching</label>
                <input id="<?php echo esc_attr($this->get_field_id('block_id')); ?>" class="widefat" type="text"
                       name="<?php echo esc_attr($this->get_field_name('block_id')); ?>"
                       value="<?php echo sanitize_text_field($instance['block_id']); ?>">
            </p>
            <?php
        }
    }
}
?>