<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sforwoo_admin_settings_setup() {
    // Add the menu item and page
    $page_title = 'Simpact';
    $menu_title = 'Simpact';
    $capability = 'manage_options';
    $slug       = 'SIMPACT-settings';
    $callback   = 'sforwoo_admin_settings_page';
    $icon       = 'dashicons-yes';
    $position   = 60;
    add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
}
add_action('admin_menu', 'sforwoo_admin_settings_setup');

function sforwoo_admin_settings_page(){
    global $sforwoo_active_tab;
    $sforwoo_active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'description';
    ?>
    <div  style= "float:left; margin-top: 20px; margin-bottom: 20px; margin-left:20px; width:100%;">
        <div class = "sforwoo_logodash" style= "width:85px; float:left;">
            <img src="<?php echo plugins_url( 'images/my-logo.png', __FILE__ ); ?>" style="height:80px;width:80px"/>
        </div>
        <div class = "sforwoo_header" style= "width:90%; float:left;">
            <h1> Simpact, </h1>
            <h2>One click at a time</h2>
            <?php settings_errors(); ?>
        </div>
    </div>
    <div>
        <h2 class="nav-tab-wrapper">
    <?php
        do_action( 'sforwoo_settings_tab' );
    ?>
        </h2>
    </div>
    <?php
        do_action( 'sforwoo_settings_content' );
}

add_action( 'sforwoo_settings_tab', 'sforwoo_welcome_tab', 1 );
function sforwoo_welcome_tab() {
	global $sforwoo_active_tab; ?>
	<a class="nav-tab <?php echo $sforwoo_active_tab == 'description' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=SIMPACT-settings&tab=description' ); ?>"><?php _e( 'Description', 'SIMPACT' ); ?> </a>
    <?php
}

add_action( 'sforwoo_settings_content', 'sforwoo_welcome_render_options_page' );
function sforwoo_welcome_render_options_page() {
	global $sforwoo_active_tab;
	if ( '' || 'description' != $sforwoo_active_tab ) {
        return;
    }
    ?>
    <h3><?php _e( 'Instructions', 'SIMPACT' ); ?></h3>
        <div class = "informative">
            Here you will find the instructions to start with ecommerce fundraising on your website. Follow these three simple steps below to make Simpact work on your website. <br><br>
            <i>
            <b>i.</b> Register with us by contacting us through our website  www.simpact.co. And we will grant you access by providing you with a password (API-Key).<br>
            <b>ii.</b> Now fill in your API-key. Go to Dashboard -> Simpact -> Configurator -> Select Simpact -> Fill in API-key.<br>
            <b>iii.</b> To test if the plugin is setup correctly, you should make one small (test) donation, this can be as little as 1 cent. You have 24 hours to send us a donation, otherwise your api-key will become invalid. <br>         You can contact us to help you make a test donation! (If you have a Paypal Gateway, use paypal for the test donation.) <br><br>
            </i>
            You are done! Simpact will now appear on your checkout page. Thank you for following these steps and enjoy the plugin.
            <br>If you want you can customize the widget under the customize tab. <br>
            Take into account that the plugin does not work with cheque-, BACS- and COD-gateway.  <br> And with any gateways that defaults to “on hold” or “pending payment” instead of “processing”.
        </div>
    <h3><?php _e( 'It is 100% Free', 'SIMPACT' ); ?></h3>
        <div class = "informative">
        First things first, what will this cost you? This plugin is a 100% free of use. <br>
        With us, you will give your customers the option to donate to charity. We developed this plugin for you. In addition, we will do all the organizational and administrative work for you.<br>
        Altogether, this plugin enables your webshop to take part in Ecommerce Fundraising for free.
        </div>
    <h3><?php _e( 'Benefits for your Company', 'SIMPACT' ); ?></h3>
        <div class = "informative">
        We are here to help your webshop, improve your image and create goodwill for your customers. <br>
        With this charity option in your checkout, some people will lean more towards your brand, be happier with their purchase and will be more likely to come back to you to buy again.<br>
        All because the positive brand association you create right before people checkout.
        </div>
    <?php
}

add_action( 'sforwoo_settings_tab', 'sforwoo_another_tab' );
function sforwoo_another_tab() {
    global $sforwoo_active_tab;
    ?>
	<a class="nav-tab <?php echo $sforwoo_active_tab == 'customize' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=SIMPACT-settings&tab=customize' ); ?>"><?php _e( 'Configurator', 'SIMPACT' ); ?> </a>
	<?php
}

add_action( 'sforwoo_settings_content', 'sforwoo_another_render_options_page' );

function sforwoo_another_render_options_page() {
	global $sforwoo_active_tab;
	if ( 'customize' != $sforwoo_active_tab ) {
        return;
    }
	?>
	<h3><?php _e( 'THE SIMPACT PLUGIN CONFIGURATOR', 'SIMPACT' ); ?></h3>
        <div class="wrap">
            <form method="post" action="options.php">
    <?php
                settings_fields( 'SIMPACT-settings' );
                do_settings_sections( 'SIMPACT-settings' );
                submit_button();
    ?>
            </form>
        </div>

    <?php
}

add_action( 'sforwoo_settings_tab', 'sforwoo_another_tab1' );
function sforwoo_another_tab1() {
    global $sforwoo_active_tab;
    ?>
	<a class="nav-tab <?php echo $sforwoo_active_tab == 'about-us' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=SIMPACT-settings&tab=about-us' ); ?>"><?php _e( 'About us', 'SIMPACT' ); ?> </a>
	<?php
}

add_action( 'sforwoo_settings_content', 'sforwoo_another_render_options_page1' );

function sforwoo_another_render_options_page1() {
	global $sforwoo_active_tab;
	if ( 'about-us' != $sforwoo_active_tab ) {
        return;
    }
    ?>
	<h3><?php _e( 'What We Do', 'SIMPACT' ); ?></h3>
        <div class = "informative">
            Simpact brings customers, webshops and charity projects together to help them give back to our world. <br>
            We offer a simple solution to webshops, which enables them to give their clients the option to donate to charity projects, directly related to their consumption. <br>
            Customers can choose to add a positive impact to their purchase or to reduce their negative impact/pollution, depending on the product.
        </div>
    <h3><?php _e( 'Find Out More', 'SIMPACT' ); ?></h3>
        <div class = "informative">
            The following link will bring you are our website: <br><br>
            <a href="https://simpact.co/" title="Go to the website of Simpact"><b>Visit Simpact</b></a>
        </div>

	<?php
}


add_action( 'admin_init',  'sforwoo_setup_sections' );
function sforwoo_setup_sections() {
    add_settings_section( 
        'our_first_section',
         '',
         'sforwoo_section_callback',
        'SIMPACT-settings'
         );
}

function sforwoo_section_callback( $arguments ) {
    switch( $arguments['id'] ){
        case 'our_first_section':
        echo 'Add your touch to the Simpact Plugin. Choose from simple or extensive design and pick the language of your webshop to create your Simpact widget.   <br>
        You can choose whether you want the simple design that consists of a simple checkbox sentence or the extended design to make you customers more aware of the option to donate.<br>
        Please do not forget to save and see the result in your checkout page. <br><br>';
            break;
    }
}

add_action( 'admin_init', 'sforwoo_setup_fields' );
function sforwoo_setup_fields() {
    $fields = array(
        array(
            'uid'         => 'our_api_field',
            'label'       => 'Access API-Key',
            'section'     => 'our_first_section',
            'type'        => 'text',
            'options'     => false,
            'placeholder' => 'Your API-Key',
            'supplemental'=> 'Simpact only works with a correct API-Key!',
            'default'     => ''
        ),
        array(
            'uid'         => 'our_first_field',
            'label'       => 'Select Language',
            'section'     => 'our_first_section',
            'type'        => 'select',
            'options'     => array(
                'nl'      => 'Dutch',
                'en'      => 'English'
                ),
            'placeholder' => 'Text goes here',
            'supplemental'=> '',
            'default'     => 'Dutch'
        ),
        array(
            'uid'         => 'our_second_field',
            'label'       => 'Select Design',
            'section'     => 'our_first_section',
            'type'        => 'select',
            'options'     => array(
                'simple'  => 'Simple Design'
                ),
            'placeholder' => 'Text goes here',
            'supplemental'=> 'More designs coming soon!',
            'default'     => 'maybe'
        )
    );
    foreach( $fields as $field ) {
        add_settings_field( 
            $field['uid'],
            $field['label'],
            'sforwoo_field_callback',
            'SIMPACT-settings',
            $field['section'],
            $field 
        );
    }
    register_setting(
        'SIMPACT-settings',
        'our_api_field',
        'sforwoo_validate_input'
    );
    register_setting(
        'SIMPACT-settings',
        'our_first_field'
    );
    register_setting(
        'SIMPACT-settings',
        'our_second_field'
    );
}

function sforwoo_validate_input($input){
       // Create our array for storing the validated options
        if(20 == strlen($input)){
        $output = strip_tags( stripslashes($input));
        }
return apply_filters( 'sforwoo_validate_input', $output, $input );
}



    function sforwoo_field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
        if( ! $value ) { // If no value exists
            $value = $arguments['default']; // Set to our default
        }

        // Check which type of field we want
        switch( $arguments['type'] ){
            case 'text': // If it is a text field
               // $value = sanitize_text_field($value);
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', esc_attr($arguments['uid']), esc_attr($arguments['type']), esc_attr($arguments['placeholder']), esc_attr($value) );
                break;
            case 'select': // If it is a select dropdown
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ) {
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', esc_attr($key), esc_attr(selected( $value, $key, false )), esc_attr($label) );
                    }
                    printf( '<select name="%1$s" id="%1$s">%2$s</select>', esc_attr($arguments['uid']), $options_markup);
                }
                break;
        }

        // If there is supplemental text
        if( $supplimental = $arguments['supplemental'] ){
            printf( '<p class="description">%s</p>', esc_attr($supplimental )); // Show it
        }
    }