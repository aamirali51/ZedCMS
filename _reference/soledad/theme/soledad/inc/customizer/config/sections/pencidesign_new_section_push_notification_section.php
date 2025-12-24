<?php
$options = [];

$options[] = [
	'id'          => 'penci_pushnt_enable',
	'type'        => 'soledad-fw-toggle',
	'label'       => __( 'Enable Push Notification', 'soledad' ),
	'description' => __( 'Enable the option to show the subscribe button below the post content and at the top of the category page.', 'soledad' ),
];

$options_lists = [
	'penci_pushnt_sdesc'      => [
		'label'   => __( 'Subscribe Description', 'soledad' ),
		'default' => __( 'Get real time update about this post category directly on your device, subscribe now.', 'soledad' ),
	],
	'penci_pushnt_btntext'    => [
		'label'   => __( 'Subscribe Button Text', 'soledad' ),
		'default' => __( 'Subscribe', 'soledad' ),
	],
	'penci_pushnt_unbtntext'  => [
		'label'   => __( 'Unsubscribe Button Text', 'soledad' ),
		'default' => __( 'Unsubscribe', 'soledad' ),
	],
	'penci_pushnt_probtntext' => [
		'label'   => __( 'Processing Button Text', 'soledad' ),
		'default' => __( 'Processing ...', 'soledad' ),
	],
];

foreach ( $options_lists as $option_key => $option_data ) {
	$options[] = [
		'id'      => $option_key,
		'type'    => 'soledad-fw-text',
		'label'   => $option_data['label'],
		'default' => $option_data['default'],
	];
}

return $options;