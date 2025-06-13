<?php
if (!defined('ABSPATH'))    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');
?>

<style>
.ui-datepicker{
    float: left;
}
</style>
<?php
$dateformat = wpjobportal::$_configuration['date_format'];
if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
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
<?php
        $mappingservice = wpjobportal::$_config->getConfigValue('mappingservice');
        if($mappingservice == 'gmap'){
            # Google
            $filekey = WPJOBPORTALincluder::getJSModel('common')->getGoogleMapApiAddress();
            //echo $filekey;
            wp_enqueue_script( 'jp-google-map', $filekey, array(), '1.1.1', false );
        }elseif($mappingservice == 'osm'){
            # Open Street
            wp_enqueue_script( 'wpjp-osm-js', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/js/ol.min.js' );
            wp_enqueue_style( 'wpjp-osm-css', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/css/ol.min.css' );
        }

    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        var map = null;
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

<?php

$mapfield = null;
if(isset(wpjobportal::$_data[2]))
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
            div#map{width: 100%;
                height: 100%;
            }
            div#map_container{
                height:<?php echo esc_attr(wpjobportal::$_configuration['mapheight']) . 'px'; ?>;
                width:100%;}
        </style>
        <?php
        $job = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
        ?>
        <?php
            $inline_js_script = "
                var latlang_marker_array = [];
                if (typeof google === 'object' && typeof google.maps === 'object') {
                    var bound = new google.maps.LatLngBounds();
                }
                        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                        function loadMap() { ";
                           if($mappingservice == 'gmap'){ 
                            $inline_js_script .= "
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
                                }); ";
                            } 
                            $inline_js_script .= "
                        }

                function checkmapcooridnate() {
                    var latitude = document.getElementById('latitude').value;
                    var longitude = document.getElementById('longitude').value;
                    var radius = document.getElementById('radius').value;
                    if (latitude != '') {
                        if (longitude != '') {
                            if (radius != '') {
                            this.form.submit();
                            } else {
                                alert(\"". esc_html(__("Please enter the coordinate radius", 'wp-job-portal'))."\");
                                    return false;
                            }
                        }
                    }
                }
                function addMarker(latlang,cityid){
                    var markers = null;
                    if(!map){
                         return false;
                     }
                    if (cityid === undefined) {
                        cityid = 0;
                    }";
                    if($mappingservice == 'gmap'){ 
                        $inline_js_script .= "
                            var marker = new google.maps.Marker({
                                position: latlang,
                                map: map,
                                draggable: true,
                            });
                            marker.setMap(map);
                            map.setCenter(latlang);

                            // this array is for newly added city whoose marker may need to be removed.
                            latlang_marker_array[latlang] = marker;
                            //..

                            marker.addListener('dblclick', function() {
                                deleteMarker(marker);
                            });
                            if(document.getElementById('latitude').value == ''){
                                document.getElementById('latitude').value = marker.position.lat();
                            }else{
                                // to handle the case of edit form duplicating latitude field data
                                // checking if current marker latitude already exsists in the field and not adding those again
                                var latitude_value = marker.position.lat();
                                var latitude_field_value = document.getElementById('latitude').value;

                                if (latitude_field_value.indexOf(latitude_value) !== -1) {
                                    //console.log('Substring found!');
                                } else {
                                    //console.log('Substring not found!');
                                    document.getElementById('latitude').value += ',' + marker.position.lat();
                                }
                            }
                            if(document.getElementById('longitude').value == ''){
                                document.getElementById('longitude').value = marker.position.lng();
                            }else{
                                // to handle the case of edit form duplicating longitude field data
                                // checking if current marker longitude already exsists in the field and not adding those again
                                var longitude_value = marker.position.lng();
                                var longitude_field_value = document.getElementById('longitude').value;

                                if (longitude_field_value.indexOf(longitude_value) !== -1) {
                                    //console.log('Substring found!');
                                } else {
                                    //console.log('Substring not found!');
                                    document.getElementById('longitude').value += ',' + marker.position.lng();
                                }
                            }

                            // cityid is to identify the marker neds to be removed.
                            if(cityid != 0){
                                marker.cityid = cityid;
                                markers.push(marker);
                            }

                            bound.extend(marker.getPosition());
                            map.fitBounds(bound); ";
                    }
                    $inline_js_script .= "
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
                    if( t_item.latitude != undefined){
                        if( t_item.latitude != ''){
                            if(t_item.latitude != 0){
                                var markerLatlng = new google.maps.LatLng(t_item.latitude, t_item.longitude);
                                deleteMarker(latlang_marker_array[markerLatlng]);
                                markers.splice(markerLatlng, 1);
                            }
                        }
                    }
                }
                            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        ?>
    <?php } ?>
<?php endif; ?>
<?php

    $inline_js_script = "
        var markers = [];
        function getdepartments(src, val,themecall){
            var themecall = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'departments', task: 'listDepartments', val: val,themecall:themecall, '_wpnonce':'". esc_attr(wp_create_nonce("list-departments"))."'}, function(data){
                if (data){
                    jQuery('#' + src).html(data); //retuen value
                }
                });
        }
        function addMarkerOnMap(location){
            if( location.latitude != undefined && location.latitude != '' && location.latitude != 0){// code is for adding a marker from data base lat lang.
                var latlng = new google.maps.LatLng(String(location.latitude), String(location.longitude));
                if(map != null){
                    //alert('abc');
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
                        //alert(\"". esc_html(__('Something got wrong','wp-job-portal')).":\"+status);
                    }
                });
            }
            return;
        }

         function getTokenInput(multicities) {
            var cityArray = '". esc_url_raw(admin_url("admin.php?page=wpjobportal_city&action=wpjobportaltask&task=getaddressdatabycityname"))."';
            var city = jQuery('#cityforedit').val();
        if (city != '') {
            jQuery('.wpjobportal-job-form-city-field').tokenInput(cityArray, {
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
                            if($mapfield->published == 1 && $mappingservice != 'osm'){
                                $inline_js_script .= "addMarkerOnMap(item);";
                            }
                        endif; 
                        $inline_js_script .= "
                            return;
                        }
                        if (item.name.search(',') == - 1) {
                            var input = jQuery('tester').text();
                            alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                            jQuery('.wpjobportal-job-form-city-field').tokenInput('remove', item);
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
                                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude,wpnoncecheck:common.wp_jm_nonce, '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."' }, function(data){
                                        if (data){
                                            try {
                                                var value = jQuery.parseJSON(data);
                                                jQuery('.wpjobportal-job-form-city-field').tokenInput('remove', item);
                                                jQuery('.wpjobportal-job-form-city-field').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude, longitude:value.longitude});
                                            }
                                            catch (err) {
                                                jQuery('.wpjobportal-job-form-city-field').tokenInput('remove', item);
                                                alert(data);
                                            }
                                        }
                                    });
                                },1500);
                        }
                    },
                    onDelete: function(item){";
                        if($mappingservice != 'osm'){
                            $inline_js_script .= " identifyMarkerForDelete(item);// delete marker associted with this token input value.";
                        }
                         $inline_js_script .= "
                    }";
                }
                $inline_js_script .= "
                });
            } else {
                jQuery('.wpjobportal-job-form-city-field').tokenInput(cityArray, {
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
                            if($mapfield->published == 1  && $mappingservice != 'osm'){
                                $inline_js_script .= "addMarkerOnMap(item);";
                            }
                        endif;
                        $inline_js_script .= "
                            return;
                        }
                        if (item.name.search(',') == - 1) {
                            var input = jQuery('tester').text();
                            alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                            jQuery('.wpjobportal-job-form-city-field').tokenInput('remove', item);
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
                                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude,wpnoncecheck:common.wp_jm_nonce, '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."' }, function(data){
                                        if (data){
                                            try {
                                                var value = jQuery.parseJSON(data);
                                                jQuery('.wpjobportal-job-form-city-field').tokenInput('remove', item);
                                                jQuery('.wpjobportal-job-form-city-field').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude, longitude:value.longitude});
                                            }
                                            catch (err) {
                                                jQuery('.wpjobportal-job-form-city-field').tokenInput('remove', item);
                                                alert(data);
                                            }
                                        }
                                    });
                                },1000);
                        }
                    },
                    onDelete: function(item){";
                        if($mappingservice != 'osm'){

                        $inline_js_script .= " identifyMarkerForDelete(item);// delete marker associted with this token input value.";
                        }
                        $inline_js_script .= "
                    }";
                    }
                    $inline_js_script .= "
                });
            }
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

    $inline_js_script = "
        function getTokenInputTags(multitags) {
            var tagArray = '". esc_url_raw(admin_url("admin.php?page=wpjobportal_tag&tagfor=1&action=wpjobportaltask&task=gettagsbytagname"))."';
            jQuery('#tags').tokenInput(tagArray, {
                theme: 'wpjobportal',
                preventDuplicates: true,
                hintText: \"". esc_html(__('Type In A Search Term', 'wp-job-portal'))."\",
                noResultsText: \"". esc_html(__('No Results', 'wp-job-portal'))."\",
                searchingText: \"". esc_html(__('Searching', 'wp-job-portal'))."\",
                tokenLimit: 5,
                prePopulate: multitags,";
                $newtyped_tags = wpjobportal::$_config->getConfigurationByConfigName('newtyped_tags');
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
             if(isset($mappingservice) && $mappingservice == 'osm'){
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
             addMarker: function(latlang,cityid=''){
                 if(!lmap.map){
                     return false;
                 }";
                 if(isset($mappingservice) && $mappingservice == 'osm'){ 
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
            ";
            $multicities = "var multicities = '';";
            if(isset(wpjobportal::$_data[0]->multicity)) 
                if(!(wpjobportal::$_data[0]->multicity == "[]")) 
                    $multicities = "var multicities = ". wpjobportal::$_data[0]->multicity.";";
            
            $inline_js_script .= $multicities;
            $inline_js_script .= "
                    getTokenInput(multicities);
                    ";
            if(in_array('tag',wpjobportal::$_active_addons)) : 
                $jobtags = "var jobtags = '';";
                if(isset(wpjobportal::$_data[0]->jobtags)) 
                    if(!(wpjobportal::$_data[0]->jobtags == "[]")) 
                        $jobtags = "var jobtags = ". wpjobportal::$_data[0]->jobtags.";";
            
                    $inline_js_script .= $jobtags;
                    $inline_js_script .= "
                    getTokenInputTags(jobtags); ";
            endif;
                if(isset($job) && !empty($job)){
                    if($mappingservice == "osm"){
                        if(isset($job->longitude) && !empty($job->latitude)){ 
                            $inline_js_script .= "lmap.addMarker([parseFloat('".esc_attr($job->longitude)."'),parseFloat('". esc_attr($job->latitude)."')]);";
                       }
                    }
                } 
            $inline_js_script .= "
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
            $('.custom_date').datepicker({dateFormat: '". $js_scriptdateformat."'});
            $.validate();

            jQuery('input#saveasdraft').click(function(){
                jQuery('input#draft').val(1);
                jQuery('.wjportal-form').submit();
            }); ";

            if($mapfield):
                if($mapfield->published == 1){ 
                    $inline_js_script .= "loadMap();";
                }
            endif;
            $inline_js_script .= "
            //Token Input
            jQuery('form#job_form').submit(function (e) {
                var termsandcondtions = jQuery('div.wpjobportal-terms-and-conditions-wrap').attr('data-wpjobportal-terms-and-conditions');
                if(termsandcondtions == 1){
                    if(!jQuery('input[name=\"termsconditions\"]').is(':checked')){
                        alert(common.terms_conditions);
                        return false;
                    }
                }
            });

        });
        function getotherexp(id){
            if (id == 1){
                jQuery('span#experience').show();
                jQuery('span#experienceid').hide();
                jQuery('span#range-one').show();
                jQuery('span#range-exp').hide();
                jQuery('input#isexperienceminimax').val(0);
            }
            if (id == 2){
                jQuery('span#experience').hide();
                jQuery('span#experienceid').show();
                jQuery('span#range-exp').show();
                jQuery('span#range-one').hide();
                jQuery('input#isexperienceminimax').val(1);
            }
        }
        function getotheredu(id){
            if (id == 1){
                jQuery('span#education').show();
                jQuery('span#educationid').hide();
                jQuery('span#range-edu-one').show();
                jQuery('span#range-edu').hide();
                jQuery('input#iseducationminimax').val(0);
            }
            if (id == 2){
                jQuery('span#education').hide();
                jQuery('span#educationid').show();
                jQuery('span#range-edu').show();
                jQuery('span#range-edu-one').hide();
                jQuery('input#iseducationminimax').val(1);
            }
        }

        function submitjobform(){
            var formvalid = jQuery('form#wpjobportal-form').isValid();
            if (formvalid == false) {
                event.preventDefault();
                return;
            }
            var test = true;
            jQuery('form#wpjobportal-form :input[type=email]').each(function(){
                var emailValue = jQuery(this).val();
                if(emailValue.length != 0){
                    var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    test = pattern.test(emailValue);
                    if (test == false) {
                        jQuery(this).css({ 'border-color': 'red'});
                    }
                }
            });
            if (test == false) {
                alert('Email is not of correct Format');
            } else {
                var termsandcondtions = jQuery('div.wpjobportal-terms-and-conditions-wrap').attr('data-wpjobportal-terms-and-conditions');
                // theme has these classes and attributes
                var termsandcondtions2 = jQuery('div.wjportal-terms-and-conditions-wrap').attr('data-wpjobportal-terms-and-conditions');
                if(termsandcondtions == 1 || termsandcondtions2 == 1){
                    if(!jQuery('input[name=\"termsconditions\"]').is(':checked')){
                        alert(common.terms_conditions);
                        event.preventDefault();
                        return false;
                    }
                }
                jQuery('#wpjobportal-form').submit();
            }
        }

    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
