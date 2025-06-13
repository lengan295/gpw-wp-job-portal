<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

// document title (<title> tag value)
// returning any non empty value stops the regular excution of code and prints this value as title
// 99 makes sure that this is executed last to avoid getting it overridden

// add_filter('pre_get_document_title', 'wp_job_portal_document_title', 99);

// function wp_job_portal_document_title($title) {
//     $jobid = isset(wpjobportal::$_data[0]->id) ? wpjobportal::$_data[0]->id : '';
//     if($jobid == ''){
//         return '';
//     }
//     $job_title_options = get_option('wpjobportal_job_document_title_settings');
//     $job_document_title = WPJOBPORTALincluder::getJSModel('job')->makeJobSeoDocumentTitle($job_title_options , $jobid);
//     return $job_document_title;
// }

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_register_script ( 'wpjp-google-map-api', $protocol . 'maps.googleapis.com/maps/api/js?key=' . wpjobportal::$_configuration['google_map_api_key'] );
wp_enqueue_script ( 'wpjp-google-map-api' );
function checkLinks($name) {
    $print = false;
    $configname = $name;

    $config_array = wpjobportal::$_data['config'];
    /*if (!(WPJOBPORTALincluder::getObjectClass('user')->isguest())) {*/
        if ($config_array["$configname"] == 1) {
            $print = true;
        }
    /*}*/
    return $print;
}
$job = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
$mappingservice = wpjobportal::$_config->getConfigValue('mappingservice');
?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function ($) {
            $('div#wjportal-popup-background, img#wjportal-popup-close-btn').click(function () {
                closePopup();
            });
            $('#wpj-jp-popup-background').click(function () {
                closePopup(1);
            });
            // to handle extentsion and size validation & show selected the file name
            $('#resumefiles').change(function () {
                readURL(this);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var fileext = input.files[0].name.split('.').pop();
                var filesize = (input.files[0].size / 1024);
                var allowedsize = ". wpjobportal::$_config->getConfigValue('document_file_size').";
                var allowedExt = '". wpjobportal::$_config->getConfigValue('document_file_type')."';
                allowedExt = allowedExt.split(',');
                if (jQuery.inArray(fileext, allowedExt) != - 1){
                    if (allowedsize > filesize){
                        //alert(input.files[0].name);
                        jQuery('.wjportal-form-upload-btn-wrp-txt').html(input.files[0].name);
                    } else{
                        jQuery('input#resumefiles').replaceWith(jQuery('input#resumefiles').val('').clone(true));
                        alert(\"". esc_html(__("File size is greater then allowed file size", 'wp-job-portal'))."\");
                    }
                } else{
                    jQuery('input#resumefiles').replaceWith(jQuery('input#resumefiles').val('').clone(true));
                    alert(\"". esc_html(__("File ext. is mismatched", 'wp-job-portal'))."\");
                }

            }
        }


        jQuery('body').delegate('i.wpj-jp-popup-close-icon', 'click', function(e){
            closePopup(1);
        });

        jQuery('#wjportal-popup-close-btn, .modal-backdrop').click(function (e) {
            jQuery('div#wjportal-popup-background').hide();
            jQuery('#payment-popup, #package-popup').slideUp('slow');
        });


        jQuery('body').delegate('#wjportal-popup-close-btn, .modal-backdrop', 'click', function(e){
            jQuery('div#wjportal-popup-background').hide();
            jQuery('div').removeClass('modal-backdrop in');
            jQuery('#payment-popup, #package-popup').slideUp('slow');
        });

        function getPackagePopupJobView(jobid,themecall) {
            var themecall = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')). "\";
            var jppage_id = ". wpjobportal::wpjobportal_getPageid() .";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'job', task: 'getPackagePopupJobView', wpjobportalid: jobid, '_wpnonce':'". esc_attr(wp_create_nonce("get-package-popup-job-view"))."',wpjobportalpageid:jppage_id}, function (data) {
                if (data) {
                    if(jQuery('#package-popup').length)
                    jQuery('#package-popup').remove();
                    jQuery('body').append(data);
                    if(null != themecall){
                        jQuery('#' + common.theme_chk_prefix + '-popup-background').show();
                    } else {
                        jQuery('#wjportal-popup-background').show();
                    }
                    jQuery('#package-popup').slideDown('slow');

                } else {
                    jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error Deleting Logo', 'wp-job-portal'))."\");
                }
            });
        }

         function selectPackage(packageid){
            jQuery('.package-div').css('border','1px solid #ccc');
            jQuery('.wjportal-pkg-item, .wpj-jp-pkg-item').removeClass('wjportal-pkg-selected');
            jQuery('#package-div-'+packageid).addClass('wjportal-pkg-selected');
            jQuery('#wpjobportal_packageid').val(packageid);
            jQuery('#jsre_featured_button').removeAttr('disabled');
            jQuery('#jsre_featured_button').attr('selected_pack',packageid);
            jQuery('#jsre_featured_button').removeClass('disabled');
        }

        function closePopup() {
            var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

            var popup_div = '';
            var bkpop_div = '';
            if (null != themecall) {
                popup_div = 'div#' + common.theme_chk_prefix + '-popup,div#package-popup';
                bkpop_div = 'div#' + common.theme_chk_prefix + '-popup-background';
            } else {
                popup_div = 'div#wpjobportal-search-popup,div#wjportal-listpopup,.applynow-closebutton, div.wjportal-popup-wrp, #wpj-jp-popup';
                bkpop_div = 'div#wjportal-popup-background';
            }
            jQuery(popup_div).slideUp('slow');
            setTimeout(function () {
                jQuery(bkpop_div).hide();
            }, 700);
        }
        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
        var map = null;
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php

$mapfield = null;
if(!empty(wpjobportal::$_data[2]))
foreach(wpjobportal::$_data[2] AS $key => $value){
    $value = (array) $value;
    if(in_array('map', $value)){
        $mapfield = $key;
        break;
    }
}
if($mapfield):
    $mapfield = wpjobportal::$_data[2][$mapfield];
    if($mapfield->published == 1){ ?>
        <style>
            div#map{
                width: 100%;
                height: 100%;
            }
            div#map_container{
                height:<?php echo esc_attr(wpjobportal::$_configuration['mapheight']) . 'px'; ?>;
                width:100%;
            }
        </style>
        <input type="hidden" id="longitude" name="longitude" value="<?php echo esc_attr(wpjobportal::$_data[0]->longitude); ?>"/>
        <input type="hidden" id="latitude" name="latitude" value="<?php echo esc_attr(wpjobportal::$_data[0]->latitude); ?>"/>
        <?php  $mappingservice = wpjobportal::$_config->getConfigValue('mappingservice'); ?>
        <?php if($mappingservice == "gmap"){
            // wp_enqueue_script ( 'wpjp-google-map-api' );
        }elseif ($mappingservice == "osm") {
            wp_register_script ( 'wpjp-osm-map-js', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/js/ol.min.js' );
            wp_enqueue_script( 'wpjp-osm-map-js' );
            wp_enqueue_style( 'wpjp-osm-map-css', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/css/ol.min.css' );
        } ?>
        <?php
            $inline_js_script = "
                var bound = new google.maps.LatLngBounds();
                function loadMap() {
                    var default_latitude = document.getElementById('latitude').value;
                    var default_longitude = document.getElementById('longitude').value;
                    default_latitude = default_latitude.split(',');
                    if(default_latitude == '' || default_longitude == '' ){
                        return;
                    }
                    //alert(default_latitude);
                    //loadComponents
                    if(default_latitude instanceof Array){
                        default_longitude = default_longitude.split(',');
                        var latlng = new google.maps.LatLng(default_latitude[0], default_longitude[0]);
                        zoom = 10;
                        var myOptions = {
                            zoom: zoom,
                            center: latlng,
                            scrollwheel: false,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        map = new google.maps.Map(document.getElementById('map_container'), myOptions);
                        for (i = 0; i < default_latitude.length; i++) {
                            var latlng = new google.maps.LatLng(default_latitude[i], default_longitude[i]);
                            addMarker(latlng);
                        }
                    }else{
                        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                        zoom = 10;
                        var myOptions = {
                            zoom: zoom,
                            center: latlng,
                            scrollwheel: false,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        map = new google.maps.Map(document.getElementById('map_container'), myOptions);
                        addMarker(latlng);
                    }

                    google.maps.event.addListener(map, 'click', function (e) {
                        var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                        geocoder = new google.maps.Geocoder();
                        geocoder.geocode({'latLng': latLng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {

                            } else {
                                alert(\"". esc_html(__("Geocode was not successful for the following reason", 'wp-job-portal')).": \" + status);
                            }
                        });
                    });
                }
               function addMarker(latlang){
                    var marker = new google.maps.Marker({
                        position: latlang,
                        map: map,
                        draggable: false,
                    });
                    marker.setMap(map);
                    bound.extend(marker.getPosition());
                    map.fitBounds(bound);
                }
            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        ?>

    <?php } ?>
<?php endif; ?>
<?php
    $inline_js_script = "
        function checkmapcooridnate() {
            ";
            if($mappingservice == 'gmap'){ 
                $inline_js_script .= "
                var latitude = document.getElementById('latitude').value;
                var longitude = document.getElementById('longitude').value;
                var radius = document.getElementById('radius').value;
                if (latitude != '' && longitude != '') {
                    if (radius != '') {
                        this.form.submit();
                    } else {
                        alert(\"". esc_html(__("Please enter the coordinate radius", 'wp-job-portal'))."\");
                        return false;
                    }
                }";
            }
            $inline_js_script .= "
        }
        window.onload = function () {
            if (document.getElementById('jobseeker_fb_comments') != null) {
                var myFrame = document.getElementById('jobseeker_fb_comments');
                if (myFrame != null)
                    myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
            }
            if (document.getElementById('employer_fb_comments') != null) {
                var myFrame = document.getElementById('employer_fb_comments');
                if (myFrame != null)
                    myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
            }
        }

        function loadOsmMap(){
            var lat = parseFloat(document.getElementById('latitude').value);
            var lon = parseFloat(document.getElementById('longitude').value);
            var coordinate = [lon,lat];
                var map = new ol.Map({
                    target: 'map',
                    controls: ol.control.defaults().extend([new ol.control.FullScreen()]),
                    layers: [
                        new ol.layer.Tile({
                            source: new ol.source.OSM()
                        })
                    ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat(coordinate),
                        zoom: 14
                    }),
                });

                 osmAddMarker(map, coordinate);

                   /* var default_latitude = document.getElementById('latitude').value;
                    var default_longitude = document.getElementById('longitude').value;
                    default_latitude = default_latitude.split(',');
                    if(default_latitude == '' || default_longitude == '' ){
                        return;
                    }
                    //loadComponents
                    if(default_latitude instanceof Array){
                        default_longitude = default_longitude.split(',');
                        for (i = 0; i < default_latitude.length; i++) {
                            var latlng = [parseFloat(default_latitude[i]), parseFloat(default_latitude[i])];
                            osmAddMarker(map, latlng);
                        }
                    }*/
            }
            var lmap = {
                     map:null,
                     marker:null,
                     init: function(){
                         this.toggleMap();
                         jQuery('#showmap1').bind('change',lmap.toggleMap);
                     },
                     toggleMap: function(){
                         if(!jQuery('#showmap1').length || jQuery('#showmap1:checked').val()){
                             lmap.showMap();
                         }else{
                             lmap.hideMap();
                         }
                     },
                     showMap: function(){
                         jQuery('#map-latlng-wrap').show();
                         if(!this.map){
                             this.loadMap();
                         }
                     },
                     hideMap: function(){
                         jQuery('#map-latlng-wrap').hide();
                     },
                     loadMap: function(){
                        ";
                     if($mappingservice == 'osm'){ 
                        $inline_js_script .= "
                        var default_latitude = parseFloat(document.getElementById('default_latitude').value);
                        var default_longitude = parseFloat(document.getElementById('default_longitude').value);
                        lmap.map = new ol.Map({
                            target: 'map_container',
                            controls: ol.control.defaults().extend([new ol.control.FullScreen()]),
                            layers: [
                                new ol.layer.Tile({
                                    source: new ol.source.OSM()
                                })
                            ],
                            view: new ol.View({
                                center: ol.proj.fromLonLat([default_longitude,default_latitude]),
                                zoom: 14,
                            }),
                        });
                        lmap.map.addEventListener('click',function(event){
                            lmap.addMarker(ol.proj.transform(event.coordinate, 'EPSG:3857', 'EPSG:4326'));
                        }); ";
                    }
                    $inline_js_script .= "
                     },
                     addMarker: function(latlang){
                         if(!lmap.map){
                             return false;
                         }";
                         if($mappingservice == 'osm'){ 
                            $inline_js_script .= "
                            if(lmap.marker){
                                lmap.map.removeLayer(lmap.marker);
                            }
                            lmap.marker = osmAddMarker(lmap.map, latlang);
                            lmap.map.getView().setCenter(ol.proj.transform(latlang, 'EPSG:4326', 'EPSG:3857'));
                            document.getElementById('latitude').value = latlang[1];
                            document.getElementById('longitude').value = latlang[0];
                            ";
                        }
                        $inline_js_script .= "
                     }
                 };

        jQuery(document).ready(function ($) {
            jQuery('a.btn').click(function () {
                jQuery('a.btn').removeClass('blue');
                jQuery(this).toggleClass('blue');
            });
            /*job apply link start*/
            ";
            if($mapfield && $mappingservice == "gmap") { 
                    $inline_js_script .= "loadMap();";
            }elseif ($mapfield && $mappingservice == "osm") {
                $inline_js_script .= "
                    /*lmap working*/
                   loadOsmMap();
                   /* lmap.init();*/
                   ";
           }

                if(isset($job) && !empty($job)){
                    if($mappingservice == "osm"){
                        $inline_js_script .= "lmap.addMarker([parseFloat('". esc_attr(wpjobportal::$_config->getConfigValue('default_longitude'))."'),parseFloat('". esc_attr(wpjobportal::$_config->getConfigValue('default_latitude'))."')]);";
                }else{ 
                        $inline_js_script .= "lmap.addMarker([parseFloat('". esc_attr($job->longitude)."'),parseFloat('". esc_attr($job->latitude)."')]);";
                }
            }
            $inline_js_script .= "
            if (document.getElementById('jobseeker_fb_comments') != null) {
                var myFrame = document.getElementById('jobseeker_fb_comments');
                if (myFrame != null)
                    myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
            }
            if (document.getElementById('employer_fb_comments') != null) {
                var myFrame = document.getElementById('employer_fb_comments');
                if (myFrame != null)
                    myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
            }
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

<?php
    $inline_js_script = "
                            // Load the SDK Asynchronously
    (function (d) {
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
        ref.parentNode.insertBefore(js, ref);
    }(document));
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
