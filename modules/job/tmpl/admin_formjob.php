<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
    wp_enqueue_script('jquery-ui-datepicker');
     if ( !WPJOBPORTALincluder::getTemplate('templates/admin/header', array('module' => 'job')) ) {
        return;
    }
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');

    $config = wpjobportal::$_configuration;
    if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
        $dash = '/';
    } else {
        $dash = '-';
    }
    $dateformat = $config['date_format'];
    $firstdash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, 0);
    $firstvalue = wpjobportalphplib::wpJP_substr($dateformat, 0, $firstdash);
    $firstdash = $firstdash + 1;
    $seconddash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, $firstdash);
    $secondvalue = wpjobportalphplib::wpJP_substr($dateformat, $firstdash, $seconddash - $firstdash);
    $seconddash = $seconddash + 1;
    $thirdvalue = wpjobportalphplib::wpJP_substr($dateformat, $seconddash, wpjobportalphplib::wpJP_strlen($dateformat) - $seconddash);
    $js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
    $js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
    $js_scriptdateformat = wpjobportalphplib::wpJP_str_replace('Y', 'yy', $js_scriptdateformat);
    ?>
<style>
    div#map_container{
    width: 100%;
    height:<?php echo esc_attr(wpjobportal::$_configuration['mapheight']) . 'px'; ?>;
    }
</style>
<?php
    $lists = array();
    $defaultCategory = wpjobportal::$_common->getDefaultValue('categories');
    $defaultJobtype = wpjobportal::$_common->getDefaultValue('jobtypes');
    $defaultJobstatus = wpjobportal::$_common->getDefaultValue('jobstatus');
    $defaultSalaryrangeType = wpjobportal::$_common->getDefaultValue('salaryrangetypes');
    $defaultCareerlevels = wpjobportal::$_common->getDefaultValue('careerlevels');
    $job = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
    ?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        var companycall = 0;
        jQuery(document).ready(function () {
            jQuery('select#companyid').on('change', function() {
                companycall = companycall + 1;
                var companyid = this.value;
                getdepartments('departmentid', companyid);
            });
            jQuery('select#companyid').change();
            jQuery('div#full_background,img#popup_cross').click(function (e) {
                jQuery('div#popup_main').slideUp('slow', function () {
                    jQuery('div#full_background').hide();
                });
            });
        });




        var companycall = 0;
        jQuery(document).ready(function () {
            jQuery('select#companyid').on('change', function() {
                companycall = companycall + 1;
                var companyid = this.value;
                jQuery.post(\"". esc_url_raw(admin_url('admin-ajax.php'))."\",{action:'wpjobportal_ajax',wpjobportalme:'user',task:'getUserIdByCompanyid',companyid:companyid, '_wpnonce':'". esc_attr(wp_create_nonce("get-user-id-by-company-id"))."'},function(data){
                    if(data){
                        jQuery('input.wpjobportal-form-save-btn').attr('credit_userid',data);
                    }
                });
                getdepartments('departmentid', companyid);
            });
            jQuery('select#companyid').change();
            jQuery('div#full_background,img#popup_cross').click(function (e) {
                jQuery('div#popup_main').slideUp('slow', function () {
                    jQuery('div#full_background').hide();
                });
            });
        });


        function selectPackage(packageid){
            jQuery('.package-div').css('border','1px solid #ccc');
            jQuery('#package-div-'+packageid).addClass('pkg-selected');
            jQuery('.pkg-item').removeClass('pkg-selected');
            jQuery('#package-div-'+packageid).addClass('pkg-selected');
            jQuery('#wpjobportal_packageid').val(packageid);
            jQuery('#upakid').val(packageid);
            jQuery('#pkg-disabled-btn').removeAttr('disabled');
            jQuery('.proceed-without-paying').removeClass('disabled-btn');
            if (jQuery('#package-div-'+packageid).hasClass('pkg-selected')) {
                jQuery('.proceed-without-paying').addClass('disabled-btn');
            }
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module' => 'job')); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <!-- top bar -->
        <div id="wpjobportal-wrapper-top">
            <div id="wpjobportal-wrapper-top-left">
                <div id="wpjobportal-breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('dashboard','wp-job-portal')); ?>">
                                <?php echo esc_html(__('Dashboard','wp-job-portal')); ?>
                            </a>
                        </li>
                        <li><?php echo esc_html(__('Add New Job','wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-help-btn" class="wpjobportal-help-btn">
                    <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/help.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>
        </div>
        <!-- top head -->
        <?php  WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'job' , 'layouts' => 'jobs','job' => $job)); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <form id="job_form" class="wpjobportal-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_job&task=savejob")); ?>">
                <?php if (isset(wpjobportal::$_data[0]->msg) AND wpjobportal::$_data[0]->msg != '') { ?>
                    <span class="formMsg">
                        <font color="red">
                            <strong>
                                <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data[0]->msg)); ?>
                            </strong>
                        </font>
                    </span>
                <?php } ?>
                <?php
                    $formfields = WPJOBPORTALincluder::getTemplate('job/form-fields', array(
                        'job' => $job
                    ));
                    foreach ($formfields as $formfield) {
                        WPJOBPORTALincluder::getTemplate('templates/admin/form-field', $formfield);
                    }
                $isque = WPJOBPORTALrequest::getVar('isqueue','get','');
                ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('isqueue', $isque != '' ? 1 : 0),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('id', isset(wpjobportal::$_data[0]->id) ? esc_html(wpjobportal::$_data[0]->id) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('default_longitude', esc_html(wpjobportal::$_configuration['default_longitude'])),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('default_latitude', esc_html(wpjobportal::$_configuration['default_latitude'])),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <input type="hidden" id="edit_longitude" name="edit_longitude" value="<?php echo  isset(wpjobportal::$_data[0]->longitude) ? esc_attr(wpjobportal::$_data[0]->longitude) : ''; ?>"/>
                <input type="hidden" id="edit_latitude" name="edit_latitude" value="<?php echo  isset(wpjobportal::$_data[0]->latitude) ? esc_attr(wpjobportal::$_data[0]->latitude) : ''; ?>"/>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'job_savejob'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('isadmin', '1'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('payment', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('creditid', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('upakid', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('package', 'job'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_job_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php
                    $status = array((object) array('id' => 0, 'text' => esc_html(__('Pending', 'wp-job-portal'))), (object) array('id' => 1, 'text' => esc_html(__('Approved', 'wp-job-portal'))), (object) array('id' => -1, 'text' => esc_html(__('Rejected', 'wp-job-portal'))), (object) array('id' => 3, 'text' => esc_html(__('Pending Payment', 'wp-job-portal'))));
                    WPJOBPORTALincluder::getTemplate('templates/admin/form-field', array(
                        'title' => esc_html(__('Status', 'wp-job-portal')),
                        'content' => WPJOBPORTALformfield::select('status', $status, isset(wpjobportal::$_data[0]->status) ? wpjobportal::$_data[0]->status : 1, esc_html(__('Select Status', 'wp-job-portal')), array('class' => 'inputbox one wpjobportal-form-select-field')))
                    );
                ?>
                <div class="wpjobportal-form-button">
                    <a id="form-cancel-button" class="wpjobportal-form-cancel-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_job')); ?>" title="<?php echo esc_html(__('cancel', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Cancel', 'wp-job-portal')); ?>
                    </a>
                    <?php
                        if (isset(wpjobportal::$_data[0]->id) || (!in_array('credits',wpjobportal::$_active_addons)) ) {
                        echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn')),WPJOBPORTAL_ALLOWED_TAGS);
                    }else{
                        echo wp_kses(WPJOBPORTALformfield::button('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn','credit_userid' => '', 'onclick' => "wpjobportalformpopupAdmin('add_job','job_form');")),WPJOBPORTAL_ALLOWED_TAGS);
                    }
                    ?>
                </div>
            </form>
        </div>
        <!-- Script Pro -->
        <?php
            $mapfield = null;
            foreach(wpjobportal::$_data[2] AS $key => $value){
                $value = (array) $value;
                if(in_array('map', $value)){
                    $mapfield = $key;
                    break;
                }
            }
            $mappingservice = '';// to handle min fields error
            if($mapfield):
                $mapfield = wpjobportal::$_data[2][$mapfield];
                $mappingservice = wpjobportal::$_config->getConfigValue('mappingservice');
                if($mapfield->published == 1){ ?>
                    <style>
                        div#map_container{border:2px solid #fff;}
                    </style>
                    <?php if($mappingservice == "gmap"){ ?>
                        <?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                        wp_register_script ( 'wpjp-google-map-js', $protocol . 'maps.googleapis.com/maps/api/js?key=' . wpjobportal::$_configuration['google_map_api_key'] );
                        wp_enqueue_script ( 'wpjp-google-map-js' );?>
                    <?php } elseif ($mappingservice == "osm") {
                        wp_enqueue_script( 'wpjp-osm-js', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/js/ol.min.js' );
                        wp_enqueue_style( 'wpjp-osm-css', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/css/ol.min.css' );
                    ?>
                    <?php } ?>
                    <?php
                        $inline_js_script = "
                            var map = null;
                            var markers = [];
                            var latlang_marker_array = [];
                            function addMarker(latlang,cityid = 0){
                                var marker = new google.maps.Marker({
                                    position: latlang,
                                    map: map,
                                    draggable: true,
                                });
                                marker.setMap(map);
                                map.setCenter(latlang);
                                // cityid is to identify the marker neds to be removed.
                                if(cityid != 0){
                                    marker.cityid = cityid;
                                    markers.push(marker);
                                }
                                // this array is for newly added city whoose marker may need to be removed.
                                latlang_marker_array[latlang] = marker;
                                //..

                                marker.addListener('dblclick', function() {
                                    deleteMarker(marker);
                                });
                                if(document.getElementById('latitude').value == ''){
                                    document.getElementById('latitude').value = marker.position.lat();
                                }else if(document.getElementById('latitude').value != marker.position.lat()){ // was adding same corrdinates twice
                                    document.getElementById('latitude').value += ',' + marker.position.lat();
                                }
                                if(document.getElementById('longitude').value == ''){
                                    document.getElementById('longitude').value = marker.position.lng();
                                }else if(document.getElementById('longitude').value != marker.position.lng()){ // was adding same corrdinates twice
                                    document.getElementById('longitude').value += ',' + marker.position.lng();
                                }
                            }

                            function deleteMarker(marker){ // this fucntion completely remves markr and thier lat lang values from text field
                                var latitude = document.getElementById('latitude').value;
                                latitude = latitude.replace(','+marker.position.lat(), '');
                                latitude = latitude.replace(marker.position.lat()+',', '');
                                latitude = latitude.replace(marker.position.lat(), '');
                                document.getElementById('latitude').value = latitude;
                                var longitude = document.getElementById('longitude').value;
                                longitude = longitude.replace(','+marker.position.lng(), '');
                                longitude = longitude.replace(marker.position.lng()+',', '');
                                longitude = longitude.replace(marker.position.lng(), '');
                                document.getElementById('longitude').value = longitude;
                                marker.setMap(null);
                                return;
                            }

                            function identifyMarkerForDelete(t_item){// this fucntion identifies the marker assiciated with token input value that has been removed.
                                var id = t_item.id;
                                // this code is when lat lang are added from data base cities
                                for (var i = 0; i < markers.length; i++) {
                                    if (markers[i].cityid == id) {
                                        //Remove the marker from Map
                                        //markers[i].setMap(null);
                                        deleteMarker(markers[i]);
                                        //Remove the marker from array.
                                        markers.splice(i, 1);
                                        return;
                                    }
                                }
                                // this code is for when lat lang belonged to newely added city
                                if( t_item.latitude != undefined && t_item.latitude != '' && t_item.latitude != 0){
                                    var markerLatlng = new google.maps.LatLng(t_item.latitude, t_item.longitude);
                                    deleteMarker(latlang_marker_array[markerLatlng]);
                                    markers.splice(markerLatlng, 1);
                                }
                            }

                            function addMarkerOnMap(location){
                                if( location.latitude != undefined && location.latitude != '' && location.latitude != 0){// code is for adding a marker from data base lat lang.
                                    var latlng = new google.maps.LatLng(String(location.latitude), String(location.longitude));
                                    if(map != null){
                                        addMarker(latlng,location.id);
                                    } else {
                                        //alert(\"". esc_html(__("Something got wrong 1",'wp-job-portal')).":\");
                                    }
                                }else{ // this code for adding a marker from location name. // this code is redundant but leaving it here
                                    var geocoder =  new google.maps.Geocoder();
                                    geocoder.geocode( { 'address': location.name}, function(results, status) {
                                        var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                                        if (status == google.maps.GeocoderStatus.OK) {
                                            if(map != null){
                                                addMarker(latlng,location.id);
                                            }
                                        } else {
                                            
                                        }
                                    });
                                }
                                return;
                            }

                            function loadMap() {
                                var default_latitude = document.getElementById('default_latitude').value;
                                var default_longitude = document.getElementById('default_longitude').value;
                                var latitude = document.getElementById('edit_latitude').value;
                                var longitude = document.getElementById('edit_longitude').value;
                                var isdefaultvalue = true;
                                if (latitude != '' && longitude != '') {
                                    default_latitude = latitude;
                                    default_longitude = longitude;
                                    isdefaultvalue = false;
                                }

                                var latlng = new google.maps.LatLng(document.getElementById('default_latitude').value, document.getElementById('default_longitude').value);
                                zoom = 8;
                                var myOptions = {
                                    zoom: zoom,
                                    center: latlng,
                                    scrollwheel: false,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                map = new google.maps.Map(document.getElementById('map_container'), myOptions);
                                default_latitude = default_latitude.split(',');
                                if(default_latitude instanceof Array){
                                    default_longitude = default_longitude.split(',');
                                    for (i = 0; i < default_latitude.length; i++) {
                                        var latlng = new google.maps.LatLng(default_latitude[i], default_longitude[i]);
                                        if(isdefaultvalue == false)
                                            addMarker(latlng);
                                    }
                                }else{
                                    var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                                    if(isdefaultvalue == false)
                                        addMarker(latlng);
                                }
                                google.maps.event.addListener(map, 'click', function (e) {
                                    var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                                    geocoder = new google.maps.Geocoder();
                                    geocoder.geocode({'latLng': latLng}, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        addMarker(results[0].geometry.location);
                                    } else {
                                        alert(\"". esc_html(__("Geocode was not successful for the following reason", 'wp-job-portal')).": \" + status);
                                    }
                                    });
                                });
                            }
                        ";
                        wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

                    ?>
            <?php } ?>
        <?php endif; ?>
        <?php
            $inline_js_script = "
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
                    loadMap: function(){ ";

                        if($mappingservice == "osm"){ 
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
                            });";
                        }
                        $inline_js_script .= "
                    },
                    addMarker: function(latlang){
                        if(!lmap.map){
                             return false;
                        }";
                        if($mappingservice == "osm"){ 
                            $inline_js_script .= "
                            if(lmap.marker){
                                lmap.map.removeLayer(lmap.marker);
                            }
                            lmap.marker = osmAddMarker(lmap.map, latlang);
                            lmap.map.getView().setCenter(ol.proj.transform(latlang, 'EPSG:4326', 'EPSG:3857'));
                            document.getElementById('latitude').value = latlang[1];
                            document.getElementById('longitude').value = latlang[0]; ";
                        }
                        $inline_js_script .= "
                    }
                 };
                jQuery(document).ready(function ($) {
                    lmap.init();
                    /*Add Mark Up to OSM MAP*/ ";

                    
                        if($mappingservice == 'osm'){
                            if(isset($job) && !empty($job->longitude) && !empty($job->latitude)) {
                                    $inline_js_script .= "lmap.addMarker([parseFloat('". $job->longitude."'),parseFloat('". $job->latitude."')]); ";
                             }
                        } 
                        $inline_js_script .= "
                        /*job apply link start*/
                        if (jQuery('input#jobapplylink1').is(':checked')){
                            jQuery('div#input-text-joblink').show();
                        }
                        jQuery('input#jobapplylink1').click(function(){
                            if (jQuery(this).is(':checked')){
                                jQuery('div#input-text-joblink').show();
                            } else{
                                jQuery('div#input-text-joblink').hide();
                            }
                        });

                        /*job apply link end*/
                        $('.custom_date').datepicker({dateFormat: '". esc_js($js_scriptdateformat)."'});
                            $.validate();
                            //Token Input
                            ";
                            $multicities = "var multicities = '';";
                            if(isset(wpjobportal::$_data[0]->multicity)) 
                                if(!(wpjobportal::$_data[0]->multicity == "[]")) 
                                    $multicities = "var multicities = ". wpjobportal::$_data[0]->multicity.";";
                            
                            $inline_js_script .= $multicities;
                            $inline_js_script .= "
                            getTokenInput(multicities);

                            //tags
                            ";
                            $jobtags = "var jobtags = '';";
                            if(isset(wpjobportal::$_data[0]->jobtags)) 
                                if(!(wpjobportal::$_data[0]->jobtags == "[]")) 
                                    $jobtags = "var jobtags = ". wpjobportal::$_data[0]->jobtags.";";
                            
                            $inline_js_script .= $jobtags;
                            $inline_js_script .= "
                            getTokenInputTags(jobtags);
                            var map_obj = document.getElementById('map_container');
                            ";
                            if($mappingservice == 'gmap'){ 
                                $inline_js_script .= "
                                if (typeof map_obj !== 'undefined' && map_obj !== null) {
                                    window.onload = loadMap();
                                } ";
                            }
                            $inline_js_script .= "
                        });
                        ";
                    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );  
                        

                        $inline_js_script = "                  
                        function getdepartments(src, val){ 
                            ";
                            if(in_array('departments', wpjobportal::$_active_addons)){ 
                                $inline_js_script .= "
                                if(companycall > 1){
                                    var ajaxurl = \"" .esc_html(admin_url('admin-ajax.php')) ."\";
                                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'departments', task: 'listdepartments', val: val, '_wpnonce':'". esc_attr(wp_create_nonce("list-departments"))."'}, function(data){
                                        if (data){
                                            jQuery('#' + src).html(data); //retuen value
                                        }
                                    });
                                } ";
                            }
                            $inline_js_script .= "
                        }

                        function hideShowRange(hideSrc, showSrc, showLink, hideLink, showName, showVal){
                            jQuery('#' + hideSrc).toggle();
                            jQuery('#' + showSrc).toggle();
                            jQuery('#' + showLink).toggle();
                            jQuery('#' + hideLink).toggle();
                        }

                        function getTokenInputTags(multitags) {

                            var tagArray = '". esc_url_raw(admin_url("admin.php?page=wpjobportal_tag&tagfor=1&action=wpjobportaltask&task=gettagsbytagname"))."';
                            jQuery('#tags').tokenInput(tagArray, {
                                theme: 'wpjobportal',
                                preventDuplicates: true,
                                hintText: \"". esc_html(__('Type In A Search Term', 'wp-job-portal')) ."\",
                                noResultsText: \"". esc_html(__('No Results', 'wp-job-portal')). "\",
                                searchingText: \"". esc_html(__('Searching', 'wp-job-portal')) ."\",
                                tokenLimit: 5,
                                prePopulate: multitags, ";
                                $newtyped_tags = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_tags');
                                if ($newtyped_tags == 1) { 
                                    $inline_js_script .= "
                                    onResult: function(item) {
                                        if (jQuery.isEmptyObject(item)){
                                            return [{id: '', name: jQuery('tester').text()}];
                                        } else {
                                            //add the item at the top of the dropdown
                                            item.unshift({id: '', name: jQuery('tester').text()});
                                            return item;
                                        }
                                    },
                                    onAdd: function(item) {
                                        if (item.id != ''){return; }
                                        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                        jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'tag', task: 'saveTokenInputTag', tagdata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-tag"))."'}, function(data){
                                            if (data){
                                            try {
                                                var value = jQuery.parseJSON(data);
                                                jQuery('#tags').tokenInput('remove', item);
                                                jQuery('#tags').tokenInput('add', {id: value.id, name: value.name});
                                            }
                                            catch (err) {
                                                jQuery('#tags').tokenInput('remove', item);
                                                        alert(data);
                                                }
                                            }
                                        });
                                    }";
                                }
                                $inline_js_script .= "
                            });
                        }

            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
                
                $inline_js_script = "
                    function getTokenInput(multicities) {
                        var cityArray = '". esc_url_raw(admin_url("admin.php?page=wpjobportal_city&action=wpjobportaltask&task=getaddressdatabycityname"))."';
                        var city = jQuery('#cityforedit').val();
                        if (city != '') {
                            jQuery('#city').tokenInput(cityArray, {
                                theme: 'wpjobportal',
                                preventDuplicates: true,
                                hintText: \"". esc_html(__("Type In A Search Term", 'wp-job-portal'))."\",
                                noResultsText: \"". esc_html(__("No Results", 'wp-job-portal'))."\",
                                searchingText: \"". esc_html(__("Searching", 'wp-job-portal'))."\",
                                // tokenLimit: 1,
                                prePopulate: multicities,";
                                $newtyped_cities = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                                if ($newtyped_cities == 1) {
                                    $inline_js_script .= "
                                    onResult: function(item) {
                                        if (jQuery.isEmptyObject(item)){
                                            return [{id:0, name: jQuery('tester').text()}];
                                        } else {
                                            //add the item at the top of the dropdown
                                            item.unshift({id:0, name: jQuery('tester').text()});
                                            return item;
                                        }
                                    },
                                    onAdd: function(item) {
                                        if (item.id > 0){";
                                            
                                            if($mapfield):
                                                if($mapfield->published == 1 && $mappingservice != "osm"){
                                                    $inline_js_script .= "addMarkerOnMap(item);";
                                                } 
                                            endif; 
                                            $inline_js_script .= "
                                                return;
                                        }
                                        if (item.name.search(',') == - 1) {
                                            var input = jQuery('tester').text();
                                            alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                                            jQuery('#city').tokenInput('remove', item);
                                            return false;
                                        } else {
                                            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php'))."\";
                                            var location_data =  jQuery('tester').text();
                                                //alert(new_loction_lat);
                                                var n_latitude;
                                                var n_longitude;
                                                var geocoder =  new google.maps.Geocoder();
                                                geocoder.geocode( { 'address': location_data}, function(results, status) {
                                                    if (status == google.maps.GeocoderStatus.OK) {
                                                        n_latitude = results[0].geometry.location.lat();
                                                        n_longitude = results[0].geometry.location.lng();
                                                    } else {
                                                        alert(\"". esc_html(__('Something got wrong','wp-job-portal')).":\"+status);
                                                    }
                                                });
                                                setTimeout(function(){ // timout is required to make sure that lat lang has value.
                                                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude , '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function(data){
                                                        if (data){
                                                            try {
                                                                var value = jQuery.parseJSON(data);
                                                                jQuery('#city').tokenInput('remove', item);
                                                                jQuery('#city').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude, longitude:value.longitude});
                                                            }
                                                            catch (err) {
                                                                jQuery('#city').tokenInput('remove', item);
                                                                alert(data);
                                                            }
                                                        }
                                                    });
                                                },1500);
                                            }
                                    },
                                    onDelete: function(item){ ";
                                        if($mappingservice != "osm"){
                                            $inline_js_script .= "identifyMarkerForDelete(item);// delete marker associted with this token input value.";
                                        }
                                    $inline_js_script .= "
                                        }";
                                }
                                $inline_js_script .= "
                            });
                        } else {
                            jQuery('#city').tokenInput(cityArray, {
                                theme: 'wpjobportal',
                                preventDuplicates: true,
                                hintText: \"". esc_html(__("Type In A Search Term", 'wp-job-portal'))."\",
                                noResultsText: \"". esc_html(__("No Results", 'wp-job-portal'))."\",
                                searchingText: \"". esc_html(__("Searching", 'wp-job-portal'))."\",
                                // tokenLimit: 1,";
                                $newtyped_cities = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                                if ($newtyped_cities == 1) { 
                                    $inline_js_script .= "
                                    onResult: function(item) {
                                        if (jQuery.isEmptyObject(item)){
                                            return [{id:0, name: jQuery('tester').text()}];
                                        } else {
                                            //add the item at the top of the dropdown
                                            item.unshift({id:0, name: jQuery('tester').text()});
                                            return item;
                                        }
                                    },
                                    onAdd: function(item) {
                                        if (item.id > 0){
                                            ";
                                                if($mapfield):
                                                    if($mapfield->published == 1 && $mappingservice != "osm"){
                                                        $inline_js_script .= "addMarkerOnMap(item);";
                                                    }
                                                endif;
                                                $inline_js_script .= "
                                            return;
                                        }
                                        if (item.name.search(',') == - 1) {
                                            var input = jQuery('tester').text();
                                            alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                                            jQuery('#city').tokenInput('remove', item);
                                            return false;
                                        } else{
                                            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                            var location_data =  jQuery('tester').text();
                                                //alert(new_loction_lat);
                                                var n_latitude;
                                                var n_longitude;
                                                var geocoder =  new google.maps.Geocoder();
                                                geocoder.geocode( { 'address': location_data}, function(results, status) {
                                                    if (status == google.maps.GeocoderStatus.OK) {
                                                        n_latitude = results[0].geometry.location.lat();
                                                        n_longitude = results[0].geometry.location.lng();
                                                    } else {
                                                        alert(\"". esc_html(__('Something got wrong','wp-job-portal')).":\"+status);
                                                    }
                                                });
                                                setTimeout(function(){ // timout is required to make sure that lat lang has value.
                                                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude , '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function(data){
                                                        if (data){
                                                            try {
                                                                var value = jQuery.parseJSON(data);
                                                                jQuery('#city').tokenInput('remove', item);
                                                                jQuery('#city').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude, longitude:value.longitude});
                                                            }
                                                            catch (err) {
                                                                jQuery('#city').tokenInput('remove', item);
                                                                alert(data);
                                                            }
                                                        }
                                                    });
                                                },1000);
                                        }
                                    },
                                    onDelete: function(item){
                                        identifyMarkerForDelete(item);// delete marker associted with this token input value.
                                    }";
                                }
                                $inline_js_script .= "
                            });
                        }
                    }
                    ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        
        ?>
 
    </div>
</div>
