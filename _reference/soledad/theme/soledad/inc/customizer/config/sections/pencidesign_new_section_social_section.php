<?php
$options         = array();
$options[]       = array(
	'default'  => 'https://www.facebook.com/PenciDesign',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Facebook', 'soledad' ),
	'id'       => 'penci_facebook',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => 'https://x.com/PenciDesign',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Twitter (X)', 'soledad' ),
	'id'       => 'penci_twitter',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Instagram', 'soledad' ),
	'id'       => 'penci_instagram',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Pinterest', 'soledad' ),
	'id'       => 'penci_pinterest',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'LinkedIn', 'soledad' ),
	'id'       => 'penci_linkedin',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Flickr', 'soledad' ),
	'id'       => 'penci_flickr',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Behance', 'soledad' ),
	'id'       => 'penci_behance',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Tumblr', 'soledad' ),
	'id'       => 'penci_tumblr',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Youtube', 'soledad' ),
	'id'       => 'penci_youtube',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'label'       => __( 'Email', 'soledad' ),
	'description' => __( 'If you want to click email icon to link to your mail, please fill: mailto:yourmail@hostmail. Change yourmail@hostmail.com to your mail. You also can fill your contact link page here', 'soledad' ),
	'id'          => 'penci_email_me',
	'type'        => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'VK', 'soledad' ),
	'id'       => 'penci_vk',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Bloglovin', 'soledad' ),
	'id'       => 'penci_bloglovin',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Vine', 'soledad' ),
	'id'       => 'penci_vine',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Soundcloud', 'soledad' ),
	'id'       => 'penci_soundcloud',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Snapchat', 'soledad' ),
	'id'       => 'penci_snapchat',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Spotify', 'soledad' ),
	'id'       => 'penci_spotify',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Github', 'soledad' ),
	'id'       => 'penci_github',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Stack Overflow', 'soledad' ),
	'id'       => 'penci_stack',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Twitch', 'soledad' ),
	'id'       => 'penci_twitch',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Steam', 'soledad' ),
	'id'       => 'penci_steam',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Vimeo', 'soledad' ),
	'id'       => 'penci_vimeo',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'XING', 'soledad' ),
	'id'       => 'penci_xing',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Whatsapp', 'soledad' ),
	'id'       => 'penci_whatsapp',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Telegram', 'soledad' ),
	'id'       => 'penci_telegram',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Reddit', 'soledad' ),
	'id'       => 'penci_reddit',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Ok', 'soledad' ),
	'id'       => 'penci_ok',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( '500px', 'soledad' ),
	'id'       => 'penci_500px',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'StumbleUpon', 'soledad' ),
	'id'       => 'penci_stumbleupon',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Wechat', 'soledad' ),
	'id'       => 'penci_wechat',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Weibo', 'soledad' ),
	'id'       => 'penci_weibo',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'LINE', 'soledad' ),
	'id'       => 'penci_line',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Viber', 'soledad' ),
	'id'       => 'penci_viber',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Discord', 'soledad' ),
	'id'       => 'penci_discord',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'RSS Link', 'soledad' ),
	'id'       => 'penci_rss',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Slack', 'soledad' ),
	'id'       => 'penci_slack',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Mixcloud', 'soledad' ),
	'id'       => 'penci_mixcloud',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Goodreads', 'soledad' ),
	'id'       => 'penci_goodreads',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Trip Advisor', 'soledad' ),
	'id'       => 'penci_tripadvisor',
	'type'     => 'soledad-fw-text',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Tik tok', 'soledad' ),
	'id'       => 'penci_tiktok',
	'type'     => 'soledad-fw-text',
);
$newsocial_array = array(
	'Dailymotion',
	'Blogger',
	'Delicious',
	'Deviantart',
	'Digg',
	'Evernote',
	'Forrst',
	'Grooveshark',
	'Lastfm',
	'Myspace',
	'Paypal',
	'Skype',
	'Window',
	'WordPress',
	'Yahoo',
	'Yandex',
	'Threads',
	'Bluesky',
	'Yelp',
);
foreach ( $newsocial_array as $social ) {
	$social_setting = 'penci_' . strtolower( $social );
	$options[]      = array(
		'default'  => '',
		'sanitize' => 'sanitize_text_field',
		'label'    => $social,
		'id'       => $social_setting,
		'type'     => 'soledad-fw-text',
	);
}
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Douban', 'soledad' ),
	'id'       => 'penci_douban',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'QQ', 'soledad' ),
	'id'       => 'penci_qq',
	'type'     => 'soledad-fw-text',
);

return $options;
