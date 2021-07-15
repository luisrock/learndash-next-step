<?php

$options_array = [
    'trns_everywhere' => [
        'type' => 'checkbox',
        'kind' => '',
        'default' => '',
        'description'=> 'Make this plugin work everywhere on the site for all courses user is enrolled in',
        'label' => '',
        'obs' => '',
        'final' => 'If not checked, the plugin will work only when courses are listed by the [ld_profile] shortcode.',
        'order' => 1,
    ],
];

define("TRNS_OPTIONS_ARRAY", $options_array);
foreach(TRNS_OPTIONS_ARRAY as $op => $vals) {
    define(strtoupper($op),get_option($op));
}

function trns_admin_menu() {
    global $trns_settings_page;
    $trns_settings_page = add_submenu_page(
                            'learndash-lms', //The slug name for the parent menu
                            __( 'Next Step', 'learndash-next-step' ), //Page title
                            __( 'Next Step', 'learndash-next-step' ), //Menu title
                            'manage_options', //capability
                            'learndash-next-step', //menu slug 
                            'trns_admin_page' //function to output the content
                        );
}
add_action( 'admin_menu', 'trns_admin_menu' );


function trns_register_plugin_settings() {
    foreach(TRNS_OPTIONS_ARRAY as $op => $vals) {
        register_setting( 'trns-settings-group', $op );
    } 
}
//call register settings function
add_action( 'admin_init', 'trns_register_plugin_settings' );


function trns_admin_page() {
?>

<div class="trns-head-panel">
    <h1><?php esc_html_e( 'Next Step Plugin', 'learndash-next-step' ); ?></h1>
    <h3><?php esc_html_e( 'Forward your students from the page where the course link is directly to the next step in the course to be completed.', 'learndash-next-step' ); ?></h3>
</div>

<div class="wrap trns-wrap-grid">

    <form method="post" action="options.php">

        <?php settings_fields( 'trns-settings-group' ); ?>
        <?php do_settings_sections( 'trns-settings-group' ); ?>

        <div class="trns-form-fields">

            <div class="trns-settings-title">
                <?php esc_html_e( 'Next Step Plugin - Settings', 'learndash-next-step' ); ?>
            </div>

            <p>
            This plugin works as follows: the link to a course becomes, for the user who is enrolled in it, the link to the next step to be taken towards the completion of the course (be it a lesson, a topic...).
            If the user is not enrolled or has completed the course, the link will not be affected (that is, it will redirect the user to the course page).
            </p>

            <?php foreach(TRNS_OPTIONS_ARRAY as $op => $vals)  { ?>

                <div class="trns-form-fields-label">
                    <?php esc_html_e( $vals['description'], 'learndash-next-step' ); ?>
                    <?php if(!empty($vals['obs'])) { ?>
                        <span>* <?php esc_html_e( $vals['obs'], 'learndash-next-step' ); ?></span>
                    <?php } ?>
                </div>
                <div class="trns-form-fields-group">
                    <?php if($vals['type'] === 'select') { ?>
                        <!-- select -->
                        <div class="trns-form-div-select">
                            <label>
                                <select name="<?php echo ($vals['kind'] === 'multiple') ? esc_attr( $op ) . '[]' : esc_attr( $op ); ?>"
                                        <?php echo esc_attr($vals['kind']); ?>
                                >
                                    <?php if(empty($vals['options'])) {$vals['options'] = $vals['get_options']();} 
                                    foreach($vals['options'] as $pt) { ?>
                                        <option value="<?php echo esc_attr($pt); ?>"
                                        <?php
                                            if( empty(get_option($op)) && $vals['default'] === $pt ) {
                                                echo esc_attr('selected');
                                            } else if( $vals['kind'] === 'multiple' ) {
                                                if( is_array(get_option($op)) && in_array($pt,get_option($op)) ) {
                                                    echo esc_attr('selected');
                                                }
                                            } else {
                                                selected($pt, get_option($op), true);
                                            }
                                        ?>
                                        >     
                                            <?php echo esc_html($pt); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </label>
                        </div>
                    <?php } else if ($vals['type'] === 'text') { ?>
                        <!-- text -->
                        <input type="text" placeholder="<?php echo esc_attr($vals['default']); ?>" class=""
                            value="<?php echo esc_attr( get_option($op) ); ?>"
                            name="<?php echo esc_attr( $op ); ?>">
                    <?php } else if ($vals['type'] === 'textarea') { ?>
                        <!-- textarea -->
                        <textarea class="large-text"
                                  cols="80"
                                  rows="10"
                                  name="<?php echo esc_attr( $op ); ?>"><?php echo esc_html( get_option($op) ); ?></textarea>
                    <?php } else if ($vals['type'] === 'checkbox') { ?>
                        <!-- checkbox -->
                        <div class="trns-form-div-checkbox">
                            <label>
                                <input  class="trns-checkbox" 
                                        type="checkbox" 
                                        name="<?php echo esc_attr( $op ); ?>"
                                        value="1"
                                        <?php checked(1, get_option( $op ), true); ?> 
                                        >
                                <?php if(!empty($vals['label'])) { ?>
                                    <span class="trns-form-fields-style-label">
                                        <?php esc_html_e( $vals['label'], 'trns-grid-button' ); ?>
                                    </span>
                                <?php } ?>
                            </label>
                        </div>                    
                    <?php } ?>

                    <?php if(!empty($vals['final'])) { ?>
                        <span>* <?php esc_html_e($vals['final'], 'learndash-next-step' ); ?></span>
                    <?php } ?>
                </div>
                <hr>
                <?php } //end foreach TRNS_OPTIONS_ARRAY ?>
               

            <?php submit_button(); ?>

            <div style="float:right; margin-bottom:20px">
              Contact Luis Rock, the author, at 
              <a href="mailto:lurockwp@gmail.com">
                lurockwp@gmail.com
              </a>
            </div>

        </div> <!-- end form fields -->
    </form>
</div> <!-- end trns-wrap-grid -->
<?php } ?>