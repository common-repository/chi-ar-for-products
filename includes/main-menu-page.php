<?php
defined('CHI_AR_VERSION') or die;
/**
 * custom option and settings
 */
function chiar_settings_init() {
    // register a new setting for "chiar" page
    register_setting( 'chiar', 'chiar_options' );

    // register a new section in the "chiar" page
    add_settings_section(
        'chiar_section_acc',
        __( 'Account and Subscription', 'chiar' ),
        null,
        'chiar'
    );

    // register a new section in the "chiar" page
    add_settings_section(
        'chiar_section_glyph',
        __( '3D&AR implementation', 'chiar' ),
        null,
        'chiar'
    );

    // register a new field in the "chiar_section_developers" section, inside the "chiar" page
    add_settings_field(
        'chiar_field_token', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Token', 'chiar' ),
        'chiar_field_token_cb',
        'chiar',
        'chiar_section_acc',
        [
            'label_for' => 'chiar_field_token',
            'class' => '',
            'chiar_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'chiar_field_glyphcheck', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'AR icon type', 'chiar' ),
        'chiar_field_glyphcheck_cb',
        'chiar',
        'chiar_section_glyph',
        [
            'label_for' => 'chiar_field_glyphcheck',
            'class' => '',
            'chiar_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'chiar_field_3dcheck', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( '360° View', 'chiar' ),
        'chiar_field_3dcheck_cb',
        'chiar',
        'chiar_section_glyph',
        [
            'label_for' => 'chiar_field_3dcheck',
            'class' => '',
            'chiar_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'chiar_field_image_action', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Show on main image click', 'chiar' ),
        'chiar_field_image_action_cb',
        'chiar',
        'chiar_section_glyph',
        [
            'label_for' => 'chiar_field_image_action',
            'class' => '',
            'chiar_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'chiar_field_glyphv', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Vertical icon location', 'chiar' ),
        'chiar_field_glyphv_cb',
        'chiar',
        'chiar_section_glyph',
        [
            'label_for' => 'chiar_field_glyphv',
            'class' => '',
            'chiar_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'chiar_field_glyphh', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Horizontal icon location', 'chiar' ),
        'chiar_field_glyphh_cb',
        'chiar',
        'chiar_section_glyph',
        [
            'label_for' => 'chiar_field_glyphh',
            'class' => '',
            'chiar_custom_data' => 'custom',
        ]
    );
}

/**
 * register our chiar_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'chiar_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

function chiar_field_token_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'chiar_options' );
    // output the field
    ?>
    <input type="text"
           id="<?php echo esc_attr( $args['label_for'] ); ?>"
           data-custom="<?php echo esc_attr( $args['chiar_custom_data'] ); ?>"
           name="chiar_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo $options[$args['label_for']];?>"
           style="width:100%;"
    >
    <?php
}

function chiar_field_glyphv_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'chiar_options' );
    //$state=$options['chiar_field_glyphcheck']==''?'disabled':'';
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['chiar_custom_data'] ); ?>"
            name="chiar_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            <?// = $state; ?>
    >
        <option value="top" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'top', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Top', 'chiar' ); ?>
        </option>
        <option value="bottom" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'bottom', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Bottom', 'chiar' ); ?>
        </option>
    </select>
    <?php
}

function chiar_field_glyphh_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'chiar_options' );
    //$state=$options['chiar_field_glyphcheck']==''?'disabled':'';
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['chiar_custom_data'] ); ?>"
            name="chiar_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            <?// = $state; ?>
    >
        <option value="left" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'left', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Left', 'chiar' ); ?>
        </option>
        <option value="right" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'right', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Right', 'chiar' ); ?>
        </option>
    </select>
    <?php
}

function chiar_field_glyphcheck_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'chiar_options' );
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['chiar_custom_data'] ); ?>"
            name="chiar_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
        <?// = $state; ?>
    >
        <option value="glyph&image" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'glyph&image', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Icon & Image', 'chiar' ); ?>
        </option>
        <option value="glyph" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'glyph', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Icon', 'chiar' ); ?>
        </option>
        <option value="image" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'image', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Entire image', 'chiar' ); ?>
        </option>
    </select>
    <?php
}

function chiar_field_3dcheck_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'chiar_options' );
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['chiar_custom_data'] ); ?>"
            name="chiar_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
        <?// = $state; ?>
    >
        <option value="product&popup" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'product&popup', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Product page & popup', 'chiar' ); ?>
        </option>
        <option value="product" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'product', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Product page', 'chiar' ); ?>
        </option>
        <option value="popup" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'popup', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Popup', 'chiar' ); ?>
        </option>
        <option value="none" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'none', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'None', 'chiar' ); ?>
        </option>
    </select>
    <?php
}

function chiar_field_image_action_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'chiar_options' );
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['chiar_custom_data'] ); ?>"
            name="chiar_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
        <?// = $state; ?>
    >
        <option value="ar" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'ar', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'AR', 'chiar' ); ?>
        </option>
        <option value="3d" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '3d', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '3D View', 'chiar' ); ?>
        </option>
        <option value="none" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'none', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'None', 'chiar' ); ?>
        </option>
    </select>
    <?php
}

function chiar_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'chiar_messages', 'chiar_message', __( 'Settings Saved', 'chiar' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'chiar_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "chiar"
            settings_fields( 'chiar' );
            // output setting sections and their fields
            // (sections are registered for "chiar", each field is registered to a specific section)
            do_settings_sections( 'chiar' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

function chiar_options_page() {
    add_menu_page(
        'AR & 360° View plugin by CHOOSE AR',
        'CHOOSE AR',
        'manage_options',
        'chiar',
        'chiar_options_page_html',
        CHI_AR_URL . '/assets/img/menu-icon.png',
        59
    );
}
add_action( 'admin_menu', 'chiar_options_page' );