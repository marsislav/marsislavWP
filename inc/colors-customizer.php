<?php
/**
 * Colors, Backgrounds, Border Radius & Layout Customizer
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
 * Sanitize helpers
 * ============================================================ */

function marsislav_sanitize_hex_color_blank( $color ) {
    if ( '' === $color ) return '';
    return sanitize_hex_color( $color );
}

function marsislav_sanitize_bg_type( $val ) {
    return in_array( $val, array( 'solid', 'gradient', 'image', 'transparent' ), true ) ? $val : 'solid';
}

function marsislav_sanitize_gradient_dir( $val ) {
    $valid = array(
        'to bottom', 'to top', 'to right', 'to left',
        'to bottom right', 'to bottom left', 'to top right', 'to top left',
        '45deg', '135deg', '225deg', '315deg',
    );
    return in_array( $val, $valid, true ) ? $val : 'to bottom';
}

function marsislav_sanitize_bg_repeat( $val ) {
    $valid = array( 'no-repeat', 'repeat', 'repeat-x', 'repeat-y' );
    return in_array( $val, $valid, true ) ? $val : 'no-repeat';
}

function marsislav_sanitize_bg_size( $val ) {
    $valid = array( 'cover', 'contain', 'auto' );
    return in_array( $val, $valid, true ) ? $val : 'cover';
}

function marsislav_sanitize_opacity( $val ) {
    $val = floatval( $val );
    return ( $val >= 0 && $val <= 100 ) ? $val : 100;
}

function marsislav_sanitize_border_radius( $val ) {
    return min( absint( $val ), 100 );
}

function marsislav_sanitize_anim_style( $val ) {
    $valid = array( 'fade-up', 'fade', 'fade-left', 'zoom' );
    return in_array( $val, $valid, true ) ? $val : 'fade-up';
}

/* ============================================================
 * Register all settings
 * ============================================================ */

function marsislav_colors_customizer( $wp_customize ) {

    $sec      = 'marsislav_design_section';
    $priority = 10;

    $wp_customize->add_section( $sec, array(
        'title'    => esc_html__( 'Дизайн & Цветове', 'marsislav' ),
        'priority' => 140,
    ) );

    /* ----------------------------------------------------------
     * 0. STICKY HEADER
     * ---------------------------------------------------------- */

    $wp_customize->add_setting( 'header_sticky', array(
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'header_sticky', array(
        'label'    => esc_html__( 'Sticky Header (залепен при скрол)', 'marsislav' ),
        'section'  => $sec,
        'type'     => 'checkbox',
        'priority' => $priority++,
    ) );

    $priority += 3;

    /* ----------------------------------------------------------
     * 1. ФОНОВЕ — всяка зона
     * ---------------------------------------------------------- */

    $areas = array(
        'global'         => esc_html__( 'Глобален фон', 'marsislav' ),
        'header'         => esc_html__( 'Хедър', 'marsislav' ),
        'content'        => esc_html__( 'Основна част', 'marsislav' ),
        'sidebar'        => esc_html__( 'Сайдбар', 'marsislav' ),
        'footer_widgets' => esc_html__( 'Footer Widget Area', 'marsislav' ),
        'footer'         => esc_html__( 'Footer', 'marsislav' ),
        'copyright'      => esc_html__( 'Copyright лента', 'marsislav' ),
    );

    $gradient_choices = array(
        'to bottom'       => esc_html__( 'Отгоре надолу', 'marsislav' ),
        'to top'          => esc_html__( 'Отдолу нагоре', 'marsislav' ),
        'to right'        => esc_html__( 'Отляво надясно', 'marsislav' ),
        'to left'         => esc_html__( 'Отдясно наляво', 'marsislav' ),
        'to bottom right' => esc_html__( 'Диагонал долу-дясно', 'marsislav' ),
        'to bottom left'  => esc_html__( 'Диагонал долу-ляво', 'marsislav' ),
        'to top right'    => esc_html__( 'Диагонал горе-дясно', 'marsislav' ),
        'to top left'     => esc_html__( 'Диагонал горе-ляво', 'marsislav' ),
        '45deg'  => '45 deg',
        '135deg' => '135 deg',
        '225deg' => '225 deg',
        '315deg' => '315 deg',
    );

    foreach ( $areas as $area => $label ) {

        // Тип
        $wp_customize->add_setting( 'bg_' . $area . '_type', array(
            'default'           => 'solid',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'marsislav_sanitize_bg_type',
        ) );
        $wp_customize->add_control( 'bg_' . $area . '_type', array(
            'label'    => $label . ' — ' . esc_html__( 'тип фон', 'marsislav' ),
            'section'  => $sec,
            'type'     => 'select',
            'priority' => $priority++,
            'choices'  => array(
                'solid'       => esc_html__( 'Плътен цвят', 'marsislav' ),
                'gradient'    => esc_html__( 'Градиент', 'marsislav' ),
                'image'       => esc_html__( 'Картинка', 'marsislav' ),
                'transparent' => esc_html__( 'Прозрачен', 'marsislav' ),
            ),
        ) );

        // Цвят
        $wp_customize->add_setting( 'bg_' . $area . '_color', array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_color', array(
            'label'    => '  > ' . esc_html__( 'Цвят', 'marsislav' ),
            'section'  => $sec,
            'priority' => $priority++,
        ) ) );

        // Прозрачност
        $wp_customize->add_setting( 'bg_' . $area . '_opacity', array(
            'default'           => 100,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'marsislav_sanitize_opacity',
        ) );
        $wp_customize->add_control( 'bg_' . $area . '_opacity', array(
            'label'       => '  > ' . esc_html__( 'Прозрачност 0-100%', 'marsislav' ),
            'section'     => $sec,
            'type'        => 'range',
            'priority'    => $priority++,
            'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 1 ),
        ) );

        // Градиент цвят 1
        $wp_customize->add_setting( 'bg_' . $area . '_grad1', array(
            'default'           => '#ffffff',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_grad1', array(
            'label'    => '  > ' . esc_html__( 'Градиент — Цвят 1', 'marsislav' ),
            'section'  => $sec,
            'priority' => $priority++,
        ) ) );

        // Градиент цвят 2
        $wp_customize->add_setting( 'bg_' . $area . '_grad2', array(
            'default'           => '#eeeeee',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_grad2', array(
            'label'    => '  > ' . esc_html__( 'Градиент — Цвят 2', 'marsislav' ),
            'section'  => $sec,
            'priority' => $priority++,
        ) ) );

        // Посока
        $wp_customize->add_setting( 'bg_' . $area . '_grad_dir', array(
            'default'           => 'to bottom',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'marsislav_sanitize_gradient_dir',
        ) );
        $wp_customize->add_control( 'bg_' . $area . '_grad_dir', array(
            'label'    => '  > ' . esc_html__( 'Посока на градиента', 'marsislav' ),
            'section'  => $sec,
            'type'     => 'select',
            'priority' => $priority++,
            'choices'  => $gradient_choices,
        ) );

        // Картинка
        $wp_customize->add_setting( 'bg_' . $area . '_image', array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'bg_' . $area . '_image', array(
            'label'     => '  > ' . esc_html__( 'Картинка за фон', 'marsislav' ),
            'section'   => $sec,
            'mime_type' => 'image',
            'priority'  => $priority++,
        ) ) );

        // Повторение
        $wp_customize->add_setting( 'bg_' . $area . '_repeat', array(
            'default'           => 'no-repeat',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'marsislav_sanitize_bg_repeat',
        ) );
        $wp_customize->add_control( 'bg_' . $area . '_repeat', array(
            'label'    => '  > ' . esc_html__( 'Повторение на картинката', 'marsislav' ),
            'section'  => $sec,
            'type'     => 'select',
            'priority' => $priority++,
            'choices'  => array(
                'no-repeat' => esc_html__( 'Не се повтаря', 'marsislav' ),
                'repeat'    => esc_html__( 'Повтаря се (X и Y)', 'marsislav' ),
                'repeat-x'  => esc_html__( 'Хоризонтално', 'marsislav' ),
                'repeat-y'  => esc_html__( 'Вертикално', 'marsislav' ),
            ),
        ) );

        // Размер
        $wp_customize->add_setting( 'bg_' . $area . '_size', array(
            'default'           => 'cover',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'marsislav_sanitize_bg_size',
        ) );
        $wp_customize->add_control( 'bg_' . $area . '_size', array(
            'label'    => '  > ' . esc_html__( 'Размер на картинката', 'marsislav' ),
            'section'  => $sec,
            'type'     => 'select',
            'priority' => $priority++,
            'choices'  => array(
                'cover'   => esc_html__( 'Cover (запълва)', 'marsislav' ),
                'contain' => esc_html__( 'Contain (вписва)', 'marsislav' ),
                'auto'    => esc_html__( 'Оригинален размер', 'marsislav' ),
            ),
        ) );

        $priority += 3;
    }

    /* ----------------------------------------------------------
     * 2. ЦВЕТОВЕ
     * ---------------------------------------------------------- */

    $color_settings = array(
        'color_body_text'         => array( esc_html__( '[ Цветове ] Основен текст',        'marsislav' ), '#1f2937' ),
        'color_body_link'         => array( esc_html__( '  > Линкове',                      'marsislav' ), '#2563eb' ),
        'color_body_link_hover'   => array( esc_html__( '  > Линкове hover',                'marsislav' ), '#1d4ed8' ),
        'color_nav_link'          => array( esc_html__( '  > Меню линкове',                 'marsislav' ), '#1f2937' ),
        'color_nav_link_hover'    => array( esc_html__( '  > Меню hover',                   'marsislav' ), '#2563eb' ),
        'color_footer_text'       => array( esc_html__( '  > Footer текст',                 'marsislav' ), '#1f2937' ),
        'color_footer_link'       => array( esc_html__( '  > Footer линкове',               'marsislav' ), '#2563eb' ),
        'color_footer_link_hover' => array( esc_html__( '  > Footer линкове hover',         'marsislav' ), '#1d4ed8' ),
        'color_h1'                => array( esc_html__( '  > H1',                           'marsislav' ), '#1f2937' ),
        'color_h2'                => array( esc_html__( '  > H2',                           'marsislav' ), '#1f2937' ),
        'color_h3'                => array( esc_html__( '  > H3',                           'marsislav' ), '#1f2937' ),
        'color_h4'                => array( esc_html__( '  > H4',                           'marsislav' ), '#1f2937' ),
        'color_h5'                => array( esc_html__( '  > H5',                           'marsislav' ), '#1f2937' ),
        'color_h6'                => array( esc_html__( '  > H6',                           'marsislav' ), '#1f2937' ),
    );

    foreach ( $color_settings as $key => $info ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $info[1],
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array(
            'label'    => $info[0],
            'section'  => $sec,
            'priority' => $priority++,
        ) ) );
    }

    $priority += 3;

    /* ----------------------------------------------------------
     * 3. BORDER RADIUS
     * ---------------------------------------------------------- */

    $radius_areas = array(
        'radius_header'         => array( esc_html__( '[ Border Radius ] Хедър',       'marsislav' ), 0  ),
        'radius_content'        => array( esc_html__( '  > Основна част',              'marsislav' ), 0  ),
        'radius_sidebar'        => array( esc_html__( '  > Сайдбар',                  'marsislav' ), 0  ),
        'radius_footer_widgets' => array( esc_html__( '  > Footer Widget Area',        'marsislav' ), 0  ),
        'radius_footer'         => array( esc_html__( '  > Footer',                   'marsislav' ), 0  ),
        'radius_copyright'      => array( esc_html__( '  > Copyright лента',          'marsislav' ), 0  ),
        'radius_buttons'        => array( esc_html__( '  > Бутони',                   'marsislav' ), 4  ),
        'radius_inputs'         => array( esc_html__( '  > Полета (inputs)',           'marsislav' ), 4  ),
        'radius_cards'          => array( esc_html__( '  > Карти / постове',          'marsislav' ), 8  ),
        'radius_images'         => array( esc_html__( '  > Изображения',              'marsislav' ), 0  ),
    );

    foreach ( $radius_areas as $key => $info ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $info[1],
            'transport'         => 'postMessage',
            'sanitize_callback' => 'marsislav_sanitize_border_radius',
        ) );
        $wp_customize->add_control( $key, array(
            'label'       => $info[0] . ' (px)',
            'section'     => $sec,
            'type'        => 'range',
            'priority'    => $priority++,
            'input_attrs' => array( 'min' => 0, 'max' => 60, 'step' => 1 ),
        ) );
    }

    $priority += 3;

    /* BREADCRUMBS */
    $wp_customize->add_setting( 'breadcrumbs_enable', array(
        'default'           => true, 'transport' => 'refresh',
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'breadcrumbs_enable', array(
        'label' => esc_html__( '[ Трохи ] Покажи breadcrumbs', 'marsislav' ),
        'section' => $sec, 'type' => 'checkbox', 'priority' => $priority++,
    ) );
    foreach ( array(
        'breadcrumbs_bg'               => array( esc_html__( '  > Фон', 'marsislav' ),            '#f3f4f6' ),
        'breadcrumbs_text_color'       => array( esc_html__( '  > Текст', 'marsislav' ),           '#6b7280' ),
        'breadcrumbs_link_color'       => array( esc_html__( '  > Линкове', 'marsislav' ),         '#2563eb' ),
        'breadcrumbs_link_hover_color' => array( esc_html__( '  > Линкове hover', 'marsislav' ),   '#1d4ed8' ),
    ) as $key => $info ) {
        $wp_customize->add_setting( $key, array( 'default' => $info[1], 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color' ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $info[0], 'section' => $sec, 'priority' => $priority++ ) ) );
    }

    $priority += 3;

    /* SCROLL TO TOP */
    $wp_customize->add_setting( 'scroll_to_top_enable', array(
        'default'           => true, 'transport' => 'refresh',
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'scroll_to_top_enable', array(
        'label' => esc_html__( '[ Scroll Top ] Бутон "Нагоре"', 'marsislav' ),
        'section' => $sec, 'type' => 'checkbox', 'priority' => $priority++,
    ) );
    foreach ( array(
        'scroll_to_top_bg'       => array( esc_html__( '  > Фон', 'marsislav' ),          '#2563eb' ),
        'scroll_to_top_color'    => array( esc_html__( '  > Икона', 'marsislav' ),         '#ffffff' ),
        'scroll_to_top_bg_hover' => array( esc_html__( '  > Фон при hover', 'marsislav' ), '#1d4ed8' ),
    ) as $key => $info ) {
        $wp_customize->add_setting( $key, array( 'default' => $info[1], 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color' ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $info[0], 'section' => $sec, 'priority' => $priority++ ) ) );
    }

    $priority += 3;

    /* ----------------------------------------------------------
     * 6. DARK MODE
     * ---------------------------------------------------------- */

    $wp_customize->add_setting( 'dark_mode_enable', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'dark_mode_enable', array(
        'label'    => esc_html__( '[ Dark Mode ] Покажи бутон за тъмен режим', 'marsislav' ),
        'section'  => $sec,
        'type'     => 'checkbox',
        'priority' => $priority++,
    ) );

    // Dark mode intensity slider (90-100%)
    $wp_customize->add_setting( 'dark_mode_intensity', array(
        'default'           => 92,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'dark_mode_intensity', array(
        'label'       => esc_html__( '  > Степен на затъмняване (%)', 'marsislav' ),
        'description' => esc_html__( '0% = пълен мрак | 100% = нормално', 'marsislav' ),
        'section'     => $sec,
        'type'        => 'range',
        'priority'    => $priority++,
        'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 5 ),
    ) );


    $priority += 3;

    /* ----------------------------------------------------------
     * 7. SCROLL ANIMATIONS
     * ---------------------------------------------------------- */

    $wp_customize->add_setting( 'scroll_animations_enable', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'scroll_animations_enable', array(
        'label'       => esc_html__( '[ Анимации ] Fade-in при скрол', 'marsislav' ),
        'description' => esc_html__( 'Елементите се появяват плавно при скрол.', 'marsislav' ),
        'section'     => $sec,
        'type'        => 'checkbox',
        'priority'    => $priority++,
    ) );

    $wp_customize->add_setting( 'scroll_animations_style', array(
        'default'           => 'fade-up',
        'transport'         => 'refresh',
        'sanitize_callback' => 'marsislav_sanitize_anim_style',
    ) );
    $wp_customize->add_control( 'scroll_animations_style', array(
        'label'    => esc_html__( '  > Стил на анимацията', 'marsislav' ),
        'section'  => $sec,
        'type'     => 'select',
        'choices'  => array(
            'fade-up'   => esc_html__( 'Fade + нагоре (препоръчително)', 'marsislav' ),
            'fade'      => esc_html__( 'Само fade', 'marsislav' ),
            'fade-left' => esc_html__( 'Fade + от ляво', 'marsislav' ),
            'zoom'      => esc_html__( 'Зуум', 'marsislav' ),
        ),
        'priority' => $priority++,
    ) );

    $wp_customize->add_setting( 'scroll_animations_speed', array(
        'default'           => 500,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'scroll_animations_speed', array(
        'label'       => esc_html__( '  > Скорост (ms)', 'marsislav' ),
        'description' => esc_html__( 'Препоръчително: 400–700ms', 'marsislav' ),
        'section'     => $sec,
        'type'        => 'range',
        'priority'    => $priority++,
        'input_attrs' => array( 'min' => 200, 'max' => 1200, 'step' => 100 ),
    ) );
}
add_action( 'customize_register', 'marsislav_colors_customizer' );


/* ============================================================
 * Dynamic CSS (wp_head)
 * ============================================================ */

function marsislav_get_bg_css( $area ) {
    $type    = (string) get_theme_mod( 'bg_' . $area . '_type',     'solid' );
    $color   = (string) get_theme_mod( 'bg_' . $area . '_color',    '' );
    $opacity = marsislav_sanitize_opacity( get_theme_mod( 'bg_' . $area . '_opacity', 100 ) );
    $grad1   = (string) get_theme_mod( 'bg_' . $area . '_grad1',    '#ffffff' );
    $grad2   = (string) get_theme_mod( 'bg_' . $area . '_grad2',    '#eeeeee' );
    $dir     = (string) get_theme_mod( 'bg_' . $area . '_grad_dir', 'to bottom' );
    $img_id  = absint( get_theme_mod( 'bg_' . $area . '_image',     0 ) );
    $repeat  = (string) get_theme_mod( 'bg_' . $area . '_repeat',   'no-repeat' );
    $size    = (string) get_theme_mod( 'bg_' . $area . '_size',     'cover' );

    $imp = ( 'global' !== $area ) ? ' !important' : '';

    if ( 'transparent' === $type ) {
        return 'background:transparent' . $imp . ';';
    }

    if ( 'solid' === $type && $color ) {
        $hex = sanitize_hex_color( $color );
        if ( $opacity < 100 ) {
            list( $r, $g, $b ) = array_map( 'hexdec', str_split( ltrim( $hex, '#' ), 2 ) );
            return 'background:rgba(' . $r . ',' . $g . ',' . $b . ',' . round( $opacity / 100, 2 ) . ')' . $imp . ';';
        }
        return 'background:' . $hex . $imp . ';';
    }

    if ( 'gradient' === $type ) {
        return 'background:linear-gradient(' . esc_attr( $dir ) . ',' . sanitize_hex_color( $grad1 ) . ',' . sanitize_hex_color( $grad2 ) . ')' . $imp . ';';
    }

    if ( 'image' === $type && $img_id ) {
        $url = wp_get_attachment_url( $img_id );
        if ( $url ) {
            $out  = 'background-image:url(' . esc_url( $url ) . ')' . $imp . ';';
            $out .= 'background-repeat:' . esc_attr( $repeat ) . ';';
            $out .= 'background-size:' . esc_attr( $size ) . ';';
            $out .= 'background-position:center center;';
            if ( $opacity < 100 ) {
                $out .= 'opacity:' . round( $opacity / 100, 2 ) . ';';
            }
            return $out;
        }
    }

    return '';
}

function marsislav_dynamic_css() {
    $css = '';

    // Sticky header
    if ( ! (bool) get_theme_mod( 'header_sticky', true ) ) {
        $css .= 'body #masthead{position:relative !important;top:auto !important;}';
    }

    // Backgrounds
    $area_selectors = array(
        'global'         => 'body',
        'header'         => 'body #masthead',
        'content'        => 'body #primary,body #content',
        'sidebar'        => 'body #secondary',
        'footer_widgets' => 'body #footer-sidebar-area',
        'footer'         => 'body #colophon',
        'copyright'      => 'body #colophon .site-info',
    );

    foreach ( $area_selectors as $area => $selector ) {
        $bg = marsislav_get_bg_css( $area );
        if ( $bg ) {
            $css .= $selector . '{' . $bg . '}';
        }
    }

    // Colors — прилагат се винаги (не само при промяна)
    $color_map = array(
        'color_body_text'         => array( 'body,.site-content',                                        '#1f2937' ),
        'color_body_link'         => array( '.site-content a',                                           '#2563eb' ),
        'color_body_link_hover'   => array( '.site-content a:hover',                                     '#1d4ed8' ),
        'color_nav_link'          => array( '.primary-menu a',                                           '#1f2937' ),
        'color_nav_link_hover'    => array( '.primary-menu a:hover,.primary-menu .current-menu-item>a', '#2563eb' ),
        'color_footer_text'       => array( '#colophon,#colophon .footer-sidebar-area',                 '#1f2937' ),
        'color_footer_link'       => array( '#colophon a',                                              '#2563eb' ),
        'color_footer_link_hover' => array( '#colophon a:hover',                                        '#1d4ed8' ),
        'color_h1'                => array( 'h1', '#1f2937' ),
        'color_h2'                => array( 'h2', '#1f2937' ),
        'color_h3'                => array( 'h3', '#1f2937' ),
        'color_h4'                => array( 'h4', '#1f2937' ),
        'color_h5'                => array( 'h5', '#1f2937' ),
        'color_h6'                => array( 'h6', '#1f2937' ),
    );

    foreach ( $color_map as $key => $map ) {
        $val = sanitize_hex_color( (string) get_theme_mod( $key, $map[1] ) );
        if ( $val ) {
            $css .= $map[0] . '{color:' . $val . ' !important;}';
        }
    }

    // Border radius
    $radius_map = array(
        'radius_header'         => array( 'body #masthead',                                                        0 ),
        'radius_content'        => array( 'body #primary,body #content',                                          0 ),
        'radius_sidebar'        => array( 'body #secondary',                                                      0 ),
        'radius_footer_widgets' => array( 'body #footer-sidebar-area',                                            0 ),
        'radius_footer'         => array( 'body #colophon',                                                       0 ),
        'radius_copyright'      => array( 'body #colophon .site-info',                                            0 ),
        'radius_buttons'        => array( 'a.button,.button,button,input[type="submit"],input[type="button"]',    4 ),
        'radius_inputs'         => array( 'input[type="text"],input[type="email"],input[type="search"],textarea', 4 ),
        'radius_cards'          => array( '.post,.card,.entry,article',                                           8 ),
        'radius_images'         => array( 'img',                                                                  0 ),
    );

    foreach ( $radius_map as $key => $map ) {
        $val = marsislav_sanitize_border_radius( get_theme_mod( $key, $map[1] ) );
        if ( (int) $val !== (int) $map[1] ) {
            $css .= $map[0] . '{border-radius:' . $val . 'px !important;}';
        }
    }

        // Breadcrumbs colors
    $bc_bg    = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_bg',               '#f3f4f6' ) );
    $bc_text  = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_text_color',       '#6b7280' ) );
    $bc_link  = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_link_color',       '#2563eb' ) );
    $bc_hover = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_link_hover_color', '#1d4ed8' ) );
    if ( $bc_bg )    $css .= '.marsislav-breadcrumbs{background:' . $bc_bg . ' !important;}';
    if ( $bc_text )  $css .= '.marsislav-breadcrumbs,.bc-sep,.bc-current{color:' . $bc_text . ' !important;}';
    if ( $bc_link )  $css .= '.marsislav-breadcrumbs .bc-link{color:' . $bc_link . ' !important;}';
    if ( $bc_hover ) $css .= '.marsislav-breadcrumbs .bc-link:hover{color:' . $bc_hover . ' !important;}';

    // Scroll to top colors
    $stt_bg    = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_bg',       '#2563eb' ) );
    $stt_color = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_color',    '#ffffff' ) );
    $stt_hover = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_bg_hover', '#1d4ed8' ) );
    if ( $stt_bg )    $css .= '#marsislav-scroll-top{background:' . $stt_bg . ' !important;}';
    if ( $stt_color ) $css .= '#marsislav-scroll-top svg{stroke:' . $stt_color . ' !important;}';
    if ( $stt_hover ) $css .= '#marsislav-scroll-top:hover{background:' . $stt_hover . ' !important;}';

    // Dark mode — overlay alpha (0 = прозрачен, 1 = пълен мрак)
    $// Dark mode — backgrounds dim, text brightens
    $dm_intensity = absint( get_theme_mod( 'dark_mode_intensity', 30 ) );
    $dm_intensity = max( 0, min( 100, $dm_intensity ) );
    $dm_b = round( $dm_intensity / 100, 2 );
    $css .= ':root{--dm-b:' . $dm_b . ';}';

    // Scroll animation speed CSS variable
    $anim_speed = absint( get_theme_mod( 'scroll_animations_speed', 500 ) );
    $anim_style = marsislav_sanitize_anim_style( (string) get_theme_mod( 'scroll_animations_style', 'fade-up' ) );
    $css .= ':root{--ms-anim-duration:' . $anim_speed . 'ms;--ms-anim-style:' . esc_attr( $anim_style ) . ';}';

    if ( $css ) {
        echo '<style id="marsislav-dynamic-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'marsislav_dynamic_css', 99 );


/* ============================================================
 * Customizer preview JS
 * ============================================================ */

function marsislav_colors_preview_js() {
    wp_enqueue_script(
        'marsislav-customizer-colors',
        get_template_directory_uri() . '/js/customizer-colors.js',
        array( 'customize-preview', 'jquery' ),
        _S_VERSION,
        true
    );

    $areas    = array( 'global', 'header', 'content', 'sidebar', 'footer_widgets', 'footer', 'copyright' );
    $img_urls = array();
    foreach ( $areas as $area ) {
        $img_id            = absint( get_theme_mod( 'bg_' . $area . '_image', 0 ) );
        $img_urls[ $area ] = $img_id ? (string) wp_get_attachment_url( $img_id ) : '';
    }

    wp_localize_script( 'marsislav-customizer-colors', 'marsislavBgData', array(
        'imgUrls' => $img_urls,
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'marsislav_bg_img' ),
    ) );
}
add_action( 'customize_preview_init', 'marsislav_colors_preview_js' );


/* ============================================================
 * AJAX — attachment URL
 * ============================================================ */

function marsislav_ajax_get_attachment_url() {
    check_ajax_referer( 'marsislav_bg_img', 'nonce' );
    $id  = absint( isset( $_GET['id'] ) ? $_GET['id'] : 0 );
    $url = $id ? wp_get_attachment_url( $id ) : '';
    wp_send_json_success( array( 'url' => (string) $url ) );
}
add_action( 'wp_ajax_marsislav_bg_img',        'marsislav_ajax_get_attachment_url' );
add_action( 'wp_ajax_nopriv_marsislav_bg_img', 'marsislav_ajax_get_attachment_url' );
