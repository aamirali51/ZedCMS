<?php
$options = [];

$options[] = array(
    'id'       => 'penci_textshare_header_01',
    'label'    => __('General Settings', 'soledad'),
    'type'     => 'soledad-fw-header',
);

$options[] = array(
    'id'       => 'penci_textshare_enable',
    'default'  => false,
    'sanitize' => 'penci_sanitize_checkbox_field',
    'label'    => __('Enable Share Selection Text  ', 'soledad'),
    'type'     => 'soledad-fw-toggle',
);

$options[] = array(
    'id'       => 'penci_textshare_services',
    'default'  => 'facebook,twitter',
    'sanitize' => 'penci_sanitize_checkbox_field',
    'label'    => __('Enable Share Services  ', 'soledad'),
    'type'     => 'soledad-fw-multi-check',
    'choices' => [
        'facebook' => ['name'=>__('Facebook', 'soledad'),'value'=>'facebook'],
        'twitter' => ['name'=>__('Twitter', 'soledad'),'value'=>'facebook'],
        'likedin' => ['name'=>__('LikedIn', 'soledad'),'value'=>'likedin'],
        'whatsapp' => ['name'=>__('Whatsapp', 'soledad'),'value'=>'whatsapp'],
        'telegram' => ['name'=>__('Telegram', 'soledad'),'value'=>'telegram'],
        'copy'     => ['name'=>__('Copy Button', 'soledad'),'value'=>'copy'],
    ],
);

$options[] = array(
    'label'       => esc_attr__( 'Facebook App ID', 'soledad' ),
    'id'          => 'penci_fbappid',
    'type'        => 'soledad-fw-text',
    'default'     => '',
    'sanitize'    => 'sanitize_text_field',
    'description' => __( 'When you use "Facebook" share text, it required an Facebook APP ID to make it works. You can <a target="_blank" href="https://developers.facebook.com/apps">get it here</a>.', 'soledad' ),
);

$options[] = array(
    'id'       => 'penci_textshare_header_02',
    'label'    => __('Colors', 'soledad'),
    'type'     => 'soledad-fw-header',
);

$options[] = array(
    'id'       => 'penci_textshare_bgcolor',
    'label'    => __('Popup Background Color', 'soledad'),
    'type'     => 'soledad-fw-color',
);

$options[] = array(
    'id'       => 'penci_textshare_txtcolor',
    'label'    => __('Popup Text Color', 'soledad'),
    'type'     => 'soledad-fw-color',
);

$options[] = array(
    'id'       => 'penci_textshare_selection_bgcolor',
    'label'    => __('Selection Text Background Color', 'soledad'),
    'type'     => 'soledad-fw-color',
);

$options[] = array(
    'id'       => 'penci_textshare_selection_txtcolor',
    'label'    => __('Selection Text Color', 'soledad'),
    'type'     => 'soledad-fw-color',
);


return $options;
