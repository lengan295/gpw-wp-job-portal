<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

add_filter('product_type_selector', 'wpjobportal_packages_add_product_type');

function wpjobportal_packages_add_product_type($types) {
    $types['wpjobportal_packages'] = esc_html(__('WP JOB PORTAL Package', 'wp-job-portal'));
    # Per Listing types
    $types['wpjobportal_perlisting'] = esc_html(__('WP JOB PORTAL Perlisting', 'wp-job-portal'));
    return $types;
}

add_action('plugins_loaded', 'wpjobportal_packages_create_custom_product_type');

function wpjobportal_packages_create_custom_product_type() {
    if (!class_exists('WC_Product')) {
        return;
    }
    class WC_Product_Wpjobportal_packages extends WC_Product {

        public $product_type = '';
        public function __construct($product) {
            $this->product_type = 'wpjobportal_packages';
            parent::__construct($product);
            // add additional functions here
        }
    }

    class WC_Product_Wpjobportal_perlisting extends WC_Product {

        public function __construct($product) {
            $this->product_type = 'wpjobportal_perlisting';
            parent::__construct($product);
            // add additional functions here
        }
    }
    /*
    // declare the product class
    class WC_Product_WPJobPortaljobs extends WC_Product {

        public function __construct($product) {
            $this->product_type = 'wpjobportal_packages,wpjobportal_perlisting';
            parent::__construct($product);
            // add additional functions here
        }

    }
    */

}

// add the settings under ‘General’ sub-menu
add_action('woocommerce_product_options_general_product_data', 'wpjobportal_packages_add_custom_settings');

function wpjobportal_packages_add_custom_settings() {
    global $woocommerce, $post;
    echo '<div id="wpjobportal_packages_custom_product_option" class="options_group">';

    // get all packages packs
    $query = "SELECT id,title,price FROM `" . wpjobportal::$_db->prefix . "wj_portal_packages` WHERE status = 1";
    $result = wpjobportal::$_db->get_results($query);
	$wpjobportal_packagepack_fieldvalue = get_post_meta($post->ID, 'wpjobportal_packagepack_field', true);

    //parse the packages packs
    if ($result && is_array($result)) {
        $options = array('' => esc_html(__('Select Package', 'wp-job-portal')));
        $fielddata = '';
        $i = 0;
         foreach ($result AS $pack) {
            $options[$pack->id] = $pack->title;
            if($i != 0) {
                $fielddata .= '|';
            }
            $fielddata .= $pack->id . ':' . $pack->price;
            $i++;
        }
    }

    // Create a number field, for example for UPC
	$value = "";
	if($wpjobportal_packagepack_fieldvalue)
		$value = $wpjobportal_packagepack_fieldvalue;
    woocommerce_wp_select(
            array(
                'id' => 'wpjobportal_packagepack_field',
                'label' => esc_html(__('Package combo', 'woocommerce')),
                'placeholder' => '',
                'desc_tip' => 'true',
                'description' => esc_html(__('Select packages pack so that user can purchase them.', 'woocommerce')),
                'type' => 'number',
                'options' => $options,
                'value' => $value,
                'custom_attributes' => array('fielddata' => $fielddata)
    ));



    echo '</div>';
        wp_register_script( 'wpjobportal-inline-handle', '' );
        wp_enqueue_script( 'wpjobportal-inline-handle' );
        $inline_js_script = '
        jQuery(document).ready(function(){
                jQuery( ".options_group.pricing" ).addClass( "show_if_wpjobportal_packages" ).show();

                jQuery("#product-type").change(function(){
                    var value = jQuery(this).val();
                    if(value == "wpjobportal_packages"){
                        jQuery("div#wpjobportal_packages_custom_product_option").show();
                        var selectedvalue = jQuery("select#wpjobportal_packagepack_field").val();
                        var value = jQuery("select#wpjobportal_packagepack_field").attr("fielddata");
                        var array = value.split("|");
                        for(i=0; i < array.length; i++){
                            var valarray = array[i].split(":");
                            var index = valarray[0];
                            var ans = valarray[1].split(",");
                            if(selectedvalue == index){
                                jQuery("input#_regular_price").val(ans[0]).attr("readonly","true");
                                if(ans[1] != 0){
                                    jQuery("input#_sale_price").val(ans[1]).attr("readonly","true");
                                }else{
                                    jQuery("input#_sale_price").attr("readonly","true");
                                }
                            }
                        }
                    }else{
                        jQuery("div#wpjobportal_packages_custom_product_option").hide();
                        jQuery("input#_regular_price").removeAttr("readonly");
                        jQuery("input#_sale_price").removeAttr("readonly");
                    }
                });
                jQuery("#product-type").change();
                jQuery("select#wpjobportal_packagepack_field").change(function(){
                    var selectedvalue = jQuery(this).val();
                    var value = jQuery(this).attr("fielddata");
                    var array = value.split("|");
                    for(i=0; i < array.length; i++){
                        var valarray = array[i].split(":");
                        var index = valarray[0];
                        var ans = valarray[1].split(",");
                        if(selectedvalue == index){
                            jQuery("input#_regular_price").val(ans[0]).attr("readonly","true");
                            if(ans[1] != 0){
                                jQuery("input#_sale_price").val(ans[1]).attr("readonly","true");
                            }else{
                                jQuery("input#_sale_price").val("").attr("readonly","true");
                            }
                        }
                    }
                });
            });
    ';
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
}

add_action('woocommerce_process_product_meta', 'wpjobportal_packages_save_custom_settings');

function wpjobportal_packages_save_custom_settings($post_id) {
    // save wpjobportal_packagepack_field
    $wpjobportal_packagepack_field = wpjobportal::wpjobportal_sanitizeData(WPJOBPORTALrequest::getVar('wpjobportal_packagepack_field','post') );
    if (!empty($wpjobportal_packagepack_field))
        update_post_meta($post_id, 'wpjobportal_packagepack_field', esc_attr($wpjobportal_packagepack_field));
}

function wpjobportal_payment_complete_by_wc($order_id){
    $order = new WC_Order($order_id);
	if($order->status != 'completed'){// to avoint duplication of record in purchase history
       return;
	}
    $user_id = $order->user_id;
    // getting the product detail **
    $items = $order->get_items();
    $packagepackids = array();
    foreach ($items as $item) {
        for($i=1;$i<=$item->get_quantity();$i++){
            $product_name = $item['name'];
            $product_id = $item['product_id'];
            $product_variation_id = $item['variation_id'];
            $packageid = get_post_meta($product_id, 'wpjobportal_packagepack_field', true);
            if(!empty($packageid)) {
                $package = WPJOBPORTALincluder::getJSTable('package');
                $package->load($packageid);
                $arr = array();
                $arr['uid']                    = $user_id;
                $arr['id']                     = $packageid;
                $arr['currencyid']             = $package->currencyid;
                $arr['amount']                 = $item->get_product()->get_price();
                $arr['paymethod']              = 'Woocommerce - ' . $order->get_payment_method_title();
                $arr['payer_email']            = $order->get_billing_email();
                $arr['payer_name']             = $order->get_billing_first_name().' '.$order->get_billing_last_name();
                $arr['payer_transactionumber'] = $order->get_id();
                $arr['payer_address']          = $order->get_billing_city() . ', ' . $order->get_billing_state() . ', ' . $order->get_billing_country();
                $arr['payer_contactnumber']    = $order->get_billing_phone();
                WPJOBPORTALincluder::getJSModel('purchasehistory')->storeUserPackage($packageid, $arr);
            }
        }
    }
}

$perlisting = array();

 //Type Module Perlisting
add_action('woocommerce_product_options_general_product_data', 'wpjobportal_perlisting_add_custom_settings');

function wpjobportal_perlisting_add_custom_settings() {
    global $woocommerce, $post;
   $perlisting = array(
    (object) array('id' => 'company_price_perlisting', 'text' => esc_html(__('Company', 'wp-job-portal')),),
    (object) array('id' => 'company_feature_price_perlisting', 'text' => esc_html(__('Feature Company', 'wp-job-portal'))),
    (object) array('id' => 'job_currency_price_perlisting', 'text' => esc_html(__('Add Job', 'wp-job-portal'))),
    (object) array('id' => 'jobs_feature_price_perlisting', 'text' => esc_html(__('Featuer Job', 'wp-job-portal'))),
    (object) array('id' => 'job_resume_price_perlisting', 'text' => esc_html(__('Add Resume', 'wp-job-portal'))),
    (object) array('id' => 'job_featureresume_price_perlisting', 'text' => esc_html(__('Feature Resume', 'wp-job-portal'))),
    (object) array('id' => 'job_department_price_perlisting', 'text' => esc_html(__('Add Department', 'wp-job-portal'))),
    (object) array('id' => 'job_resumesavesearch_price_perlisting', 'text' => esc_html(__('Resume Save Search', 'wp-job-portal'))),
    (object) array('id' => 'job_jobalert_price_perlisting', 'text' => esc_html(__('Job Alert Time ', 'wp-job-portal'))),
    (object) array('id' => 'job_viewcompanycontact_price_perlisting', 'text' => esc_html(__('View Company Contact Detail ', 'wp-job-portal'))),
    (object) array('id' => 'job_viewresumecontact_price_perlisting', 'text' => esc_html(__('View Resume Contact Detail', 'wp-job-portal'))),
    (object) array('id' => 'job_jobapply_price_perlisting', 'text' => esc_html(__('Job Apply', 'wp-job-portal'))),
    (object) array('id' => 'job_coverletter_price_perlisting', 'text' => esc_html(__('Add Cover Letter', 'wp-job-portal')))

    );



    echo '<div id="wpjobportal_perlisting_custom_product_option" class="options_group">';
    $options = array('' => esc_html(__('Select Package', 'wp-job-portal')));
        $i = 0;
        $fielddata = '';
        foreach ($perlisting as $key => $value) {
            $options[$value->id] = $value->text;
            if($i != 0) {
                $fielddata .= '|';
            }
            $fielddata .= esc_html($value->id). ':' . esc_html(wpjobportal::$_config->getConfigValue($value->id));
            $i++;
        }
    # Perlisting Module Package Selection
    woocommerce_wp_select(
            array(
                'id' => 'wpjobportal_perlistingpack_field',
                'label' => esc_html(__('Package combo', 'woocommerce')),
                'placeholder' => '',
                'desc_tip' => 'true',
                'description' => esc_html(__('Select packages pack so that user can purchase them.', 'woocommerce')),
                'type' => 'number',
                'options' => $options,
                'custom_attributes' => array('fielddata' => $fielddata)
    ));
    echo '</div>';
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );
    $inline_js_script = '
            jQuery(document).ready(function(){
                jQuery( ".options_group.pricing" ).addClass( "show_if_wpjobportal_perlisting" ).show();

                jQuery("#product-type").change(function(){
                    var value = jQuery(this).val();
                    if(value == "wpjobportal_perlisting"){
                        jQuery("div#wpjobportal_perlisting_custom_product_option").show();
                        var selectedvalue = jQuery("select#wpjobportal_perlistingpack_field").val();
                        var value = jQuery("select#wpjobportal_perlistingpack_field").attr("fielddata");
                        var array = value.split("|");
                        for(i=0; i < array.length; i++){
                            var valarray = array[i].split(":");
                            var index = valarray[0];
                            var ans = valarray[1].split(",");
                            if(selectedvalue == index){
                                jQuery("input#_regular_price").val(ans[0]).attr("readonly","true");
                                if(ans[1] != 0){
                                    jQuery("input#_sale_price").val(ans[1]).attr("readonly","true");
                                }else{
                                    jQuery("input#_sale_price").attr("readonly","true");
                                }
                            }
                        }
                    }else{
                        jQuery("div#wpjobportal_perlisting_custom_product_option").hide();
                        jQuery("input#_regular_price").removeAttr("readonly");
                        jQuery("input#_sale_price").removeAttr("readonly");
                    }
                });
                jQuery("#product-type").change();
                jQuery("select#wpjobportal_perlistingpack_field").change(function(){
                    var selectedvalue = jQuery(this).val();
                    var value = jQuery(this).attr("fielddata");
                    var array = value.split("|");
                    for(i=0; i < array.length; i++){
                        var valarray = array[i].split(":");
                        var index = valarray[0];
                        var ans = valarray[1].split(",");
                        if(selectedvalue == index){
                            jQuery("input#_regular_price").val(ans[0]).attr("readonly","true");
                            if(ans[1] != 0){
                                jQuery("input#_sale_price").val(ans[1]).attr("readonly","true");
                            }else{
                                jQuery("input#_sale_price").val("").attr("readonly","true");
                            }
                        }
                    }
                });
            });
    ';
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
}

add_action('woocommerce_process_product_meta', 'wpjobportal_perlisting_save_custom_settings');

function wpjobportal_perlisting_save_custom_settings($post_id) {
    // save wpjobportal_packagepack_field
    $wpjobportal_perlistingpack_field = wpjobportal::wpjobportal_sanitizeData(WPJOBPORTALrequest::getVar('wpjobportal_perlistingpack_field','post') );
    //echo $wpjobportal_perlistingpack_field;
    if (!empty($wpjobportal_perlistingpack_field))
        update_post_meta($post_id, 'wpjobportal_perlistingpack_field', esc_attr($wpjobportal_perlistingpack_field));
}


# Payment Woo Commerce
function wpjobportal_paymentperlisting_complete_by_wc($order_id){
    $order = new WC_Order($order_id);
    if($order->status != 'completed'){// to avoint duplication of record in purchase history
       return;
    }
    $user_id = $order->user_id;
    // getting the product detail **
    $items = $order->get_items();
    $packagepackids = array();
    $module_function = '';
    foreach ($items as $item) {
        for($i=1;$i<=$item->get_quantity();$i++){
            $product_name = $item['name'];
            $product_id = $item['product_id'];
            $product_variation_id = $item['variation_id'];
            $module = get_post_meta($order_id, '_wpjobporta_billing_perlisting', true);
            $parse = wpjobportalphplib::wpJP_explode('-', $module);
            $moduleid = $parse[1];
            $actionname = $parse[0];
            # switch case
            # Need to change this
           // $currencyid = wpjobportal::$_config->getConfigValue('job_currency_department_perlisting');

            # Defination --OF Currency id For Department And Price
            if(!empty($moduleid)) {
                //$package = WPJOBPORTALincluder::getJSTable('package');
                //$package->load($packageid);
                $arr = array();
                $arr['uid']                    = $user_id;
                $arr['id']                     = $moduleid;
                $arr['currencyid']             = '';
                $arr['amount']                 = $item->get_product()->get_price();
                $arr['price']                  = $item->get_product()->get_price();
                $arr['paymethod']              = 'Woocommerce - ' . $order->get_payment_method_title();
                $arr['payer_email']            = $order->get_billing_email();
                $arr['payer_name']             = $order->get_billing_first_name().' '.$order->get_billing_last_name();
                $arr['payer_transactionumber'] = $order->get_id();
                $arr['payer_address']          = $order->get_billing_city() . ', ' . $order->get_billing_state() . ', ' . $order->get_billing_country();
                $arr['payer_contactnumber']    = $order->get_billing_phone();
                # Same function For Paypal,woocommerce,stripe-->
                WPJOBPORTALincluder::getJSModel('wpjobportal')->storeModule($arr,$actionname);
            }
        }
    }
}
# Fetching module --set up woocommerce
add_action( 'woocommerce_after_order_notes', 'add_custom_checkout_hidden_field' );
function add_custom_checkout_hidden_field( $checkout ) {
        global $woocommerce;
        $moduleid = WPJOBPORTALrequest::getVar('id');
        echo '<div id="user_link_hidden_checkout_field">
                <input type="hidden" class="input-hidden" name="billing_wpjobportal_mid" id="billing_wpjobportal_mid" value="' . esc_attr($moduleid) . '">
        </div>';
   // }
}


# Member
function wpjobportal_woocommerce_payment_complete($order_id) {
    wpjobportal_payment_complete_by_wc($order_id);
}
# Perlisting
function wpjobportal_woocommerce_paymentperlisting_complete($order_id) {
    wpjobportal_paymentperlisting_complete_by_wc($order_id);
}

function wpjobportal_woocommerce_payment_pending($order_id) {
}

function wpjobportal_woocommerce_payment_failed($order_id) {
}

function wpjobportal_woocommerce_payment_hold($order_id) {
}

function wpjobportal_woocommerce_payment_processing($order_id) {
}
# Member
function wpjobportal_woocommerce_payment_completed($order_id) {
    wpjobportal_payment_complete_by_wc($order_id);
}
# Perlisting
function wpjobportal_woocommerce_paymentperlisting_completed($order_id) {
    wpjobportal_paymentperlisting_complete_by_wc($order_id);
}

function wpjobportal_woocommerce_payment_refunded($order_id) {
}

function wpjobportal_woocommerce_payment_cancelled($order_id) {
}

add_action( 'woocommerce_checkout_update_order_meta', 'add_order_delivery_date_to_order' , 10, 1);

function add_order_delivery_date_to_order ( $order_id ) {
    # Billing Module --($module per id)
    $billing_wpjobportal_mid  = WPJOBPORTALrequest::getVar('billing_wpjobportal_mid','post','');
    if (  '' != $billing_wpjobportal_mid ) {
        add_post_meta( $order_id, '_wpjobporta_billing_perlisting',  sanitize_text_field( $billing_wpjobportal_mid ) );
    }
}

add_action( 'wp_footer', function(){
    # Hide return to shop
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );
    $inline_js_script = "
    jQuery(window).load(function() {
        if (jQuery('a.button.wc-backward'))
            jQuery('a.button.wc-backward').hide();
    });";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
});



  add_filter( 'woocommerce_order_item_name', 'custom_orders_items_names', 10, 2 );
  function custom_orders_items_names( $item_name, $item ) {
  // Only in thankyou "Order-received" page
      $id = WPJOBPORTALrequest::getVar('id');
      $name = wpjobportal::$_common->getProductDesc($id);
    //$name = $id;
      if(is_wc_endpoint_url( 'order-received' ))
    # Specific Item Name For a Product
        $item_name = $name;
        return $item_name;
}


add_filter('woocommerce_get_return_url','override_return_url',10,2);

function override_return_url($return_url,$order){
    //create empty array to store url parameters in
    $sku_list = array();
    $id = wpjobportal::wpjobportal_sanitizeData(WPJOBPORTALrequest::getVar('billing_wpjobportal_mid','post') );
    // retrive products in order
    if(isset($order)){
        foreach($order->get_items() as $key => $item){
          $product = wc_get_product($item['product_id']);
          //get sku of each product and insert it in array
          $sku_list['product_'.$item['product_id'] . 'sku'] = $product->get_sku();
        }
        //build query strings out of the SKU array
        $url_extension = http_build_query($sku_list);
        //append our strings to original url
        //append id perlisting moduleid
        $modified_url = $return_url.'&'.$url_extension.'&id='.$id;
        return $modified_url;
    }
}

function cart_variation_description( $title, $cart_item, $cart_item_key ) {
    $item = $cart_item['data'];

    if(!empty($item) && $item->is_type( 'variation' ) ) {
        return $item->get_name();
    } else
        return $title;
}

# Member
add_action('woocommerce_payment_complete', 'wpjobportal_woocommerce_payment_complete');
add_filter( 'woocommerce_cart_item_name', 'cart_variation_description', 20, 3);
# Per listing
add_action('woocommerce_payment_complete', 'wpjobportal_woocommerce_paymentperlisting_complete');
add_action('woocommerce_order_status_pending', 'wpjobportal_woocommerce_payment_pending');
add_action('woocommerce_order_status_failed', 'wpjobportal_woocommerce_payment_failed');
add_action('woocommerce_order_status_on-hold', 'wpjobportal_woocommerce_payment_hold');
// Note that it's woocommerce_order_status_on-hold, not on_hold.
add_action('woocommerce_order_status_processing', 'wpjobportal_woocommerce_payment_processing');
add_action('woocommerce_order_status_completed', 'wpjobportal_woocommerce_payment_completed');
add_action('woocommerce_order_status_completed', 'wpjobportal_woocommerce_paymentperlisting_completed');
add_action('woocommerce_order_status_refunded', 'wpjobportal_woocommerce_payment_refunded');
add_action('woocommerce_order_status_cancelled', 'wpjobportal_woocommerce_payment_cancelled');

// subscription code

add_action('admin_enqueue_scripts', function(){
    wp_enqueue_script('wpjobportalsubscriptionadminjs',plugins_url('/', __FILE__).'js/wc_admin.js');
});

add_filter('product_type_options', 'wpjobportalsubscription_checkbox');
    function wpjobportalsubscription_checkbox($options){
        $options['is_wpjobportalsubscription'] = array(
            'id'            => '_wpjobportalsubscription',
            'wrapper_class' => 'show_if_subscription',
            'label'         => __('WP Job Portal', 'wp-job-portal'),
            'description'   => __('Check if you want to use WP Job Portal packages', 'wp-job-portal'),
            'default'       => 'no',
        );
        return $options;
    }

add_action('woocommerce_product_options_general_product_data', 'wpjobportalsubscription_packages');    
    function wpjobportalsubscription_packages(){
        global $post;
        
        $query = "SELECT id,title,price FROM `" . wpjobportal::$_db->prefix . "wj_portal_packages` WHERE status = 1";
        $result = wpjobportal::$_db->get_results($query);
        $wpjobportal_packagepack_field_id = '';
        $wpjobportal_packagepack_field_id = esc_attr(get_post_meta($post->ID, 'wpjobportal_packagepack_field', true));
        //parse the packages packs
        if ($result && is_array($result)) {
            $options = '<option value = "">'.esc_html(__('Select Package', 'wp-job-portal'))."</option>";
             foreach ($result AS $pack) {
                if($wpjobportal_packagepack_field_id == $pack->id) $selected = "selected"; else $selected = "";
                $options .= '<option value = "'.$pack->id.'"'.$selected.'>'.esc_html(__($pack->title, 'wp-job-portal'))."</option>";
            }
        }

        ?>
        <div class="options_group show_if_wpjobportalsubscription hidden">
            <p class="form-field show_if_subscription hidden">
                <label><?php echo esc_html(__("WP Job Portal Packages",'wp-job-portal')); ?></label>
                <span class="wrap">
                    <select id="wpjobportal_packagepack_field_subscription" name="wpjobportal_packagepack_field_subscription" class="select short"> <?php echo $options; ?>"</select>
                    <span class="woocommerce-help-tip" data-tip="<?php echo esc_attr(__("Select packages pack so that user can purchase them",'wp-job-portal')); ?>"></span>
                </span>
            </p>
        </div>
        <?php
    }

//add_action( 'woocommerce_process_product_meta_simple', array($this,'save_wpjobportalsubscription_option_fields'  ));
//add_action( 'woocommerce_process_product_meta_variable', array($this,'save_wpjobportalsubscription_option_fields'  ));
add_action( 'woocommerce_process_product_meta_subscription', 'save_wpjobportalsubscription_option_fields'  );
    function save_wpjobportalsubscription_option_fields( $post_id ) {
        $is_wpjobportalsubscription = isset( $_POST['_wpjobportalsubscription'] ) ? 'yes' : 'no';
        $wpjobportal_packagepack_field = $_POST['wpjobportal_packagepack_field_subscription'];
        update_post_meta( $post_id, '_wpjobportalsubscription', $is_wpjobportalsubscription );
        update_post_meta( $post_id, 'is_wpjobportalsubscription', $is_wpjobportalsubscription );
        update_post_meta( $post_id, 'wpjobportal_packagepack_field', $wpjobportal_packagepack_field );
    }

?>
