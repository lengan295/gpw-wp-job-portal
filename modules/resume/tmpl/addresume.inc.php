<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('tinymcewpjobportal.js', site_url('wp-includes/js/tinymce/tinymce.min.js'));
wp_enqueue_script('jquery-ui-datepicker');
//wp_enqueue_script('multi-files-selector', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/multi-files-selector.js');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$mappingservice = wpjobportal::$_config->getConfigValue('mappingservice');
wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');

if($mappingservice == "gmap"){
    wp_register_script ( 'wpjp-google-map-js', $protocol . 'maps.googleapis.com/maps/api/js?key=' . wpjobportal::$_configuration['google_map_api_key'] );
    wp_enqueue_script ( 'wpjp-google-map-js' );
}elseif ($mappingservice == "osm") {
    wp_register_script ( 'wpjp-osm-map-js', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/js/ol.min.js' );
    wp_enqueue_script( 'wpjp-osm-map-js' );
    wp_enqueue_style( 'wpjp-osm-map-css', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/css/ol.min.css' );
}
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
$resume = isset(wpjobportal::$_data[0]) ? isset(wpjobportal::$_data[0]) : null;
?>
<style type="text/css">
.ui-datepicker{
    float: left;
}
</style>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
       var maindivoffsettop = 0;
        var currenttop = 0;
        jQuery(document).ready(function () {
            jQuery('input.jstokeninputcity').each(function(){
                var jsparent = jQuery(this).parent();
                var cityid = jQuery(jsparent).find('input.jscityid').val();
                var cityname = jQuery(jsparent).find('input.jscityname').val();
                var datafor = jQuery(this).attr('data-for');
                datafor = datafor.split('_');
                getTokenInputResume(datafor, cityid, cityname);
                try { tinymce.execCommand('mceAddEditor', true, 'resume'); } catch (e){console.log(e); }
            });
            //More option
            jQuery('body').delegate('span.wpjobportal-resume-moreoptiontitle', 'click', function(e){
                e.preventDefault();
                var img = jQuery(this).find('img');
                if (jQuery('div.wpjobportal-resume-moreoption').is(':hidden')) {
                    var srcimg = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/resume/up.png';
                } else{
                    var srcimg = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/resume/down.png';
                }
                jQuery('div.wpjobportal-resume-moreoption').toggle();
                jQuery(img).attr('src', srcimg);
            });
            //Personal files select
        jQuery('body').delegate('span.clickablefiles', 'click', function(e){
            jQuery('input#resumefiles').click();
            jQuery('input#resumefiles').change(function(){
                var srcimage = jQuery('img.rs_photo');
                var files = this.files;
                for (i = 0; i < files.length; i++){
                    var fileext = files[i].name.split('.').pop();
                    var filesize = (files[i].size / 1024);
                    var allowedExt = \"". wpjobportal::$_config->getConfigurationByConfigName('document_file_type')."\";
                    var allowedSize = \"". wpjobportal::$_config->getConfigurationByConfigName('document_file_size')."\";
                    var maxFiles = ". wpjobportal::$_config->getConfigurationByConfigName('document_max_files').";
                    allowedExt = allowedExt.split(',');
                    // check if the file is already inserted or not
                    var alreadyinserted = 0;
                    if (resumefiles.length > 0){
                        for (m = 0; m < resumefiles.length; m++){
                            var aobj = resumefiles[m];
                            if (aobj.file.name == files[i].name){
                                if (aobj.file.size == files[i].size){
                                    if (aobj.file.type == files[i].type){
                                        alreadyinserted = 1;
                                    }
                                }
                            }
                        }
                    }
                    if (alreadyinserted == 0){
                        canupload = 0;
                        reason = '';
                        fileext = fileext.toLowerCase();
                        if (maxFiles > k ){
                            if (allowedSize > filesize){
                                if (jQuery.inArray(fileext, allowedExt) != - 1){
                                    canupload = 1;
                                    k++;
                                } else{
                                    reason = \"". esc_html(__('File extension mismatch', 'wp-job-portal'))."\";
                                }
                            } else{
                                reason = \"". esc_html(__('File size exceeds limit', 'wp-job-portal'))."\";
                            }
                        } else{
                            reason = \"".esc_html(__('Maximum files selected', 'wp-job-portal'))."\";
                        }

                        resumefiles.push({'canupload': canupload, 'reason': reason, 'file':files[i]});
                    }
                    console.log('alreadyinserted = ' + alreadyinserted + ' value of k ' + k);
                }
                showResumeFilesArrayPopup();
            });
        });
        jQuery('body').delegate('img#wjportal-form-delete-image', 'click', function(e){
            jQuery('.wjportal-form-image-wrp').hide();
            jQuery('input#photo').val('').clone(true);
            jQuery('span.wjportal-form-upload-btn-wrp-txt').text('');
            var id =  jQuery('input[name=id]').val();
            // var id =  jQuery('#id').val();
            removeLogo(id);
        });


        });
        function getTokenInputResume(datafor, cityid, cityname) {
            var citylink = \"". wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'city', 'task'=>'getaddressdatabycityname', 'action'=>'wpjobportaltask', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))."\";
            var inputfor = datafor[0];
            var sectionid = datafor[1];
            var city = jQuery('#' + inputfor + 'cityforedit_'+sectionid).val();
            if (city != '') {
                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput(citylink, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__('Type In A Search Term', 'wp-job-portal'))."\",
                    noResultsText: \"". esc_html(__('No Results', 'wp-job-portal'))."\",
                    searchingText: \"". esc_html(__('Searching', 'wp-job-portal'))."\",
                    tokenLimit: 1,
                    prePopulate: [{id: cityid, name: cityname}],";
                    
                    $newtyped_cities = wpjobportal::$_config->getConfigurationByConfigName('newtyped_cities');
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
                                if (item.id > 0){return; }
                                if (item.name.search(',') == - 1) {
                                    var input = jQuery('tester').text();
                                    alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                                    jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                    return false;
                                } else{
                                    var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function(data){
                                        if (data){
                                            try {
                                                var value = jQuery.parseJSON(data);
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('add', {id: value.id, name: value.name});
                                            }
                                            catch (err) {
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                                //jQuery('#' + fieldname).tokenInput('remove', item);
                                                alert(data);
                                            }
                                        }
                                    });
                                }
                            }";
                    }
                    $inline_js_script .= "
                        });
            }else{
                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput(citylink, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__('Type In A Search Term', 'wp-job-portal'))."\",
                    noResultsText: \"". esc_html(__('No Results', 'wp-job-portal'))."\",
                    searchingText: \"". esc_html(__('Searching', 'wp-job-portal'))."\",
                    tokenLimit: 1,";
                    $newtyped_cities = wpjobportal::$_config->getConfigurationByConfigName('newtyped_cities');
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
                                showdiv(sectionid);
                                loadGmap(sectionid,item.latitude,item.longitude);
                                return;
                            }
                            if (item.name.search(',') == - 1) {
                                var input = jQuery('tester').text();
                                alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                return false;
                            } else{
                                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function(data){
                                    if (data){
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                            jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('add', {id: value.id, name: value.name});
                                        }catch (err) {
                                         jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                         alert(data);
                                     }
                                 }
                             });
                            }
                        }";
                    }
                    $inline_js_script .= "
                    });

            }

        }


        function removeLogo(id) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'deleteResumeLogo', resumeid: id, '_wpnonce':'". esc_attr(wp_create_nonce("delete-resume-logo"))."'}, function (data) {
                if (data) {
                    jQuery('#resume_logo_deleted').val('1');
                } else {
                    jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error Deleting Logo', 'wp-job-portal'))."\");
                }
            });
        }

        function disablefields(sectionid){
          if(jQuery('#employer_current_status'+sectionid).is(':checked')){
                var crudDate=\"". gmdate('m/d/Y')."\";
                jQuery('#resto_date'+sectionid).hide();
                jQuery('#employer_current_status'+sectionid).val(1);
                jQuery('#employer_to_date4'+sectionid).val(crudDate);
            }else if(jQuery('#employer_current_status'+sectionid).is(':not(:checked)')){
                jQuery('#resto_date'+sectionid).show();
                jQuery('#employer_current_status'+sectionid).val(0);
                jQuery('#employer_to_date4'+sectionid).val('');
            }else{

            }
        }
        function showResumeSection( btn , sec_name){
            var path = 'div#jssection_'+sec_name;
            var obj = jQuery(path).find('.jssection_hide').first();
            var islast = jQuery(path).find('.jssection_hide').next().hasClass('jssection_hide');
            // now enable this section
            jQuery(obj).removeClass('jssection_hide');
            jQuery(obj).find('input.jsdeletethissection').val(0);
            if(!islast){
                jQuery(btn).remove();
            }
            // set required values
            jQuery(obj).find('[data-myrequired]').each(function(){
                var classname = jQuery(this).attr('data-myrequired');
                jQuery(this).addClass(classname);
                jQuery(this).attr('data-validation',classname);
            });
        }

        function deleteThisSection(obj){
            jQuery(obj).hide();
            // custom code
            var main = jQuery(obj).parent();
            jQuery(main).find('[data-validation]').each(function(){
                var classname = jQuery(this).attr('data-myrequired');
                jQuery(this).removeClass(classname);
                jQuery(this).attr('data-validation','');
            });
            main.find('input.jsdeletethissection').val(1);
            main.find('div.jsundo').addClass('jsundodiv');
            main.find('div.jsundo').show();
        }

        function undoThisSection(obj){
            var main = jQuery(obj).parent();
            jQuery(main).find('[data-myrequired]').each(function(){
                var classname = jQuery(this).attr('data-myrequired');
                jQuery(this).addClass(classname);
                jQuery(this).attr('data-validation',classname);
            });
            main.hide();
            main.removeClass('jsundodiv');
            main.parent().find('input.jsdeletethissection').val(0);
            main.parent().find('img.jsdeleteimage').show();
        }

        function showdiv(sectionid) {
            document.getElementById('outermapdiv_'+sectionid).style.display = 'inline-block';
            document.getElementById('map_'+sectionid).style.visibility = 'visible';
            document.getElementById('map_'+sectionid).style.display = '';
        }
        function hidediv(sectionid) {
            document.getElementById('outermapdiv_'+sectionid).style.display = 'none';
            document.getElementById('map_'+sectionid).style.visibility = 'hidden';
            document.getElementById('map_'+sectionid).style.display = 'hidden';
        }";
        wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

        $inline_js_script = "
        function loadMap( sectionid ) {
            ";
            if($mappingservice == "gmap"){ 
                    $inline_js_script .= "loadGmap(sectionid);";
            }elseif ($mappingservice == "osm") { 
                $inline_js_script .= "
                    //Current Bite Storage Purpose
                    loadOsmMap(sectionid);";
           }
           $inline_js_script .= "
        }


        function loadGmap(sectionid,city_latitude = '',city_longitude = ''){ // to handle the case of showing city marker on token input
            if(city_latitude == '' && city_longitude ==''){
                var default_latitude = \"". wpjobportal::$_configuration['default_latitude']."\";
                var default_longitude = \"". wpjobportal::$_configuration['default_longitude']."\";
            }else{
                var default_latitude = city_latitude;
                var default_longitude = city_longitude;
            }

            var latitude       = document.getElementById('latitude_'+sectionid).value;
            var longitude = document.getElementById('longitude_'+sectionid).value;
            var marker_flag = 0;
            if ((latitude != '') && (longitude != '')) {
                default_latitude = latitude;
                default_longitude = longitude;
                marker_flag = 1;
            }
            var latlng = new google.maps.LatLng(default_latitude, default_longitude);
            zoom = 10;
            var myOptions = {
                zoom: zoom,
                center: latlng,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById('map_container_'+sectionid), myOptions);
            var lastmarker = new google.maps.Marker({
                postiion: latlng,
            });
            var marker = new google.maps.Marker({
                position: latlng,
            });
            if(marker_flag == 1){
                marker.setMap(map);
            }

            lastmarker = marker;
            document.getElementById('latitude_'+sectionid).value = marker.position.lat();
            document.getElementById('longitude_'+sectionid).value = marker.position.lng();
            google.maps.event.addListener(map, 'click', function (e) {
                var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': latLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (lastmarker != ''){
                            lastmarker.setMap(null);
                        }
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                        });
                        marker.setMap(map);
                        lastmarker = marker;
                        document.getElementById('latitude_'+sectionid).value = marker.position.lat();
                        document.getElementById('longitude_'+sectionid).value = marker.position.lng();

                    } else {
                        alert(\"Geocode was not successful for the following reason: \" + status);
                    }
                });
            });
        }



        function loadOsmMap(sectionid){
             /*Altering Map Jquery For The sake 22.00.19*/
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
                    var default_latitude = parseFloat(\"". wpjobportal::$_configuration['default_latitude']."\");
                    var default_longitude = parseFloat(\"". wpjobportal::$_configuration['default_longitude']."\");
                    var latitude =  document.getElementById('latitude_'+sectionid).value;
                    var longitude = document.getElementById('longitude_'+sectionid).value;
                    if ((latitude != '') && (longitude != '')) {
                        default_latitude = parseFloat(latitude);
                        default_longitude = parseFloat(longitude);
                    }
                    lmap.map = new ol.Map({
                        target: 'map_container_'+sectionid,
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
                    });
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
                        document.getElementById('latitude_'+sectionid).value = latlang[1];
                        document.getElementById('longitude_'+sectionid).value = latlang[0];
                        ";
                    }
                    $inline_js_script .= "
                 }
             };
              lmap.init();
             var latitude = document.getElementById('latitude_'+sectionid).value;
             var longitude = document.getElementById('longitude_'+sectionid).value;
             ";
             if($mappingservice == "osm"){
                $inline_js_script .= "
                if(latitude != '' && longitude != ''){
                    lmap.addMarker([longitude,latitude]);
                }";
            }
            $inline_js_script .= "
        }


        //jQuery(document).ready(function(){});
        function getVisible() {
            if(jQuery('div#wpjobportal-wrapper').length){
                var div = jQuery('div#wpjobportal-wrapper');
            }else if(jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap').length){
                var div = jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap');
            }
            var maxheight = jQuery(div).outerHeight();
            var divheight = jQuery('div.wp-job-portal-resume-apply-now-visitor').height();
            var scrolltop = jQuery(document).scrollTop();
            tagheight = currenttop + scrolltop - divheight;
            if(tagheight > maxheight){
                tagheight = maxheight - divheight - 15;
            }
            jQuery('div.wp-job-portal-resume-apply-now-visitor').css('top',tagheight+'px');
        }
        function cancelJobApplyVisitor(){
            var result = confirm(\"". esc_html(__("Are you sure to cancel job apply",'wp-job-portal'))."\");
            if(result == true){
                jQuery.post(ajaxurl,{action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: 'canceljobapplyasvisitor', '_wpnonce':'". esc_attr(wp_create_nonce("cancel-jobapply-as-visitor"))."'},function(data){
                    if(data){
                        window.location = data;
                    }
                });
            }
        }
        function JobApplyVisitor(){
            var resumeid = jQuery('#resume_temp').val();
            if(resumeid == -1){
                alert(\"". esc_html(__("Please first save the resume then apply",'wp-job-portal'))."\");
            }else{
                jQuery.post(ajaxurl,{action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: 'visitorapplyjob', '_wpnonce':'". esc_attr(wp_create_nonce("visitor-apply-job"))."'},function(data){
                   if(data){
                       window.location = data;
                   }
               });
            }
        }

        jQuery(document).ready(function(){
            if(jQuery('div#wpjobportal-wrapper').length){
                maindivoffsettop = jQuery('div#wpjobportal-wrapper').offset().top;
            }else if(jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap').length){
                maindivoffsettop = jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap').offset().top;
            }

          currenttop = jQuery(window).height() - maindivoffsettop;
            currenttop = currenttop - 12;
            jQuery('div.wp-job-portal-resume-apply-now-visitor').css('top',currenttop+'px');
            jQuery(window).on('scroll resize', getVisible);
        });



        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
        var resumefiles = [];";
         if (isset(wpjobportal::$_data[0]['file_section']) && is_array(wpjobportal::$_data[0]['file_section'])) { 
            $k = COUNT(wpjobportal::$_data[0]['file_section']); 
        } else { 
            $k= 0; 
        }
        $inline_js_script .= "var k = ".$k.";
        var formvalidcheck = true;
        //Show resumefiles in the popup
        function showResumeFilesArrayPopup(){
            jQuery('div#resumefileswrapper span.livefiles').html('');
            jQuery('span#resume-files-selected').html('');
            jQuery('div.error_msg').remove();
            for (i = 0; i < resumefiles.length; i++){
                var obj = resumefiles[i];
                var objHTML = '';
                // var objHTML = '<div class=\"resumefileselected';
                // if (obj.canupload == 0){
                //     objHTML += ' errormsg ';
                // }
                // objHTML += '\"><span class=\"filename\">' + obj.file.name + '</span><span class=\"filesize\">( ' + (obj.file.size / 1024) + ' KB )</span>';
                // objHTML += '<button onclick=\"removeFileByIndex(' + i + ');\">". esc_html(__("Remove", 'wp-job-portal'))."</button>';
                // objHTML += '</div>';
                if (obj.canupload == 0){
                    objHTML = '<div class=\"error_msg\"><b>". esc_html(__("Error", 'wp-job-portal')).":</b> ' + obj.reason + '</div>';
                }
                //jQuery('span#resume-files-selected').after(objHTML);
                jQuery('#resumefileswrapper').after(objHTML);
                // append in main resume form
                if (obj.canupload == 1){
                    var mHTML = '<a href=\"javascript:void(0);\" onclick=\"removeFileByIndex(' + i + ');\" class=\"file\"><span class=\"filename\">' + obj.file.name + '</span><span class=\"fileext\"> </span><img class=\"filedownload\" src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/resume/cancel.png\" /></a>';
                    jQuery('div#resumefileswrapper span.livefiles').append(mHTML);
                }
            }
        }


        function addValidateCustom(){
            config = {
                onError: function(){
                    formvalidcheck = false;
                    console.log('Form invalid data not correct');
                }
            };
            jQuery.validate(config);
        }

        //Delete resume file stored in db
        function deleteResumeFile(id){
            var confirmDelete = confirm(\"". esc_html(__("Confirm to delete resume file", 'wp-job-portal')).' ?'."\");
            if (confirmDelete == false) {
                return false;
            }
            jQuery.post(ajaxurl, {wpjobportalme:'resume', action:'wpjobportal_ajax', task:'removeResumeFileById', id:id, '_wpnonce':'". esc_attr(wp_create_nonce("remove-resume-file-by-id"))."'}, function (data){
                if (data){
                    jQuery('a#file_' + id).remove();
                    k--;
                }
            });
        }
        //Common section add
        jQuery('body').delegate('a.add', 'click', function(e){
            e.preventDefault();
            var anchor = jQuery(this);
            var parentDiv = jQuery(this).before();
            var section = jQuery(this).attr('data-section');
            var resumeid = jQuery('input#resume_temp').val();
            if (!resumeid.trim()){
                alert(\"". esc_html(__("Please first save resume personal section then add any other section",'wp-job-portal'))."\");
                return false;
            }
            jQuery('div#resume-wating').show();
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-section-ajax"))."'}, function(data){
                jQuery(parentDiv).after(data);
                jQuery(anchor).remove();
                addDatePicker();
                addValidateCustom();
                jQuery('div#resume-wating').hide();
            });
        });
        function removeFileByIndex(index){
            if (resumefiles.indexOf(index) == - 1){
                resumefiles.splice(index, 1);
                k--;
                showResumeFilesArrayPopup();
            }
            return false;
        }

        function addDatePicker(){
            jQuery('.custom_date').datepicker({dateFormat: '". $js_scriptdateformat."'});
        }
        jQuery(document).ready(function () {
            addDatePicker();
            jQuery('div#black_wrapper_jobapply,div#warn-message span.close-warnmessage,div#resume-files-popup-wrapper span.close-resume-files').click(function () {
                jQuery('div#warn-message').fadeOut();
                jQuery('div#black_wrapper_jobapply').fadeOut();
                jQuery('div#resume-files-popup-wrapper').fadeOut();
            });
            //More option
            jQuery('body').delegate('span.resume-moreoptiontitle', 'click', function(e){
                e.preventDefault();
                var img = jQuery(this).find('img');
                if (jQuery('div.resume-moreoption').is(':hidden')) {
                    var srcimg = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/resume/up.png';
                } else{
                    var srcimg = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/resume/down.png';
                }
                jQuery('div.resume-moreoption').toggle();
                jQuery(img).attr('src', srcimg);
            });
            //Resume select file
            jQuery('body').delegate('span.resume-selectfiles', 'click', function(e){
                e.preventDefault();
                jQuery('div#black_wrapper_jobapply').show();
                jQuery('div#resume-files-popup-wrapper').fadeIn();
                showResumeFilesArrayPopup();
            });
            //Common section edit
            jQuery('body').delegate('div.section_wrapper a.edit', 'click', function(e){
                jQuery('div#resume-wating').show();
                e.preventDefault();
                var div = jQuery(this).parent().parent();
                var section = jQuery(div).attr('data-section');
                var sectionid = jQuery(div).attr('data-sectionid');
                var resumeid = jQuery('input#resume_temp').val();
                jQuery(\"a[data-section='' + section + '']\").remove();
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'getResumeSectionAjax', section: section, sectionid:sectionid, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-section-ajax"))."'}, function(data){
                    jQuery(div).html(data);
                    addDatePicker();
                    addValidateCustom();
                    jQuery('div#resume-wating').hide();
                });
            });
            //Common section delete
            jQuery('body').delegate('div.section_wrapper a.delete', 'click', function(e){
                e.preventDefault();
                var confirmDelete = confirm(\"". esc_html(__("Are you sure to delete", 'wp-job-portal')).' ?'."\");
                if (confirmDelete == false) {
                    return false;
                }
                jQuery('div#resume-wating').show();
                var div = jQuery(this).parent().parent();
                var section = jQuery(div).attr('data-section');
                var sectionid = jQuery(div).attr('data-sectionid');
                var resumeid = jQuery('input#resume_temp').val();
                jQuery(\"a[data-section='' + section + '']\").remove();
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'deleteResumeSectionAjax', section: section, sectionid:sectionid, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("delete-resume-section-ajax"))."'}, function(data){
                    var object = jQuery.parseJSON(data);
                    if (object.result == 1){
                        jQuery(div).html(object.msg);
                    } else{
                        jQuery(div).prepend(object.msg);
                    }
                    if (object.canadd == 1){
                        jQuery(div).after(object.anchor);
                    }
                    jQuery('div#resume-wating').hide();
                });
            });
            //Personal section edit
            jQuery('body').delegate('a.personal_section_edit', 'click', function(e){
                jQuery('div#resume-wating').show();
                e.preventDefault();
                var div = jQuery('div#resume-wrapper');
                var section = 'personal';
                var resumeid = jQuery('input#resume_temp').val();
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-section-ajax"))."'}, function(data){
                    jQuery(div).find('div.resume-top-section').remove();
                    jQuery(div).find('div.resume-section-title.personal').remove();
                    jQuery(div).find('div[data-section=\"personal\"]').remove();
                    jQuery(div).prepend(data);
                    addDatePicker();
                    addValidateCustom();
                    jQuery('div#resume-wating').hide();
                });
            });
            //Skill section edit
            jQuery('body').delegate('a.skilledit', 'click', function(e){
                e.preventDefault();
                var div = jQuery(this).parent().next('div[data-section=\"skills\"]');
                var section = 'skills';
                var resumeid = jQuery('input#resume_temp').val();
                if (!resumeid.trim()){
                    alert(\"". esc_html(__("Please first save resume personal section then add any other section",'wp-job-portal'))."\");
                    return false;
                }
                jQuery('div#resume-wating').show();
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-section-ajax"))."'}, function(data){
                    jQuery(div).html(data);
                    addValidateCustom();
                    jQuery('div#resume-wating').hide();
                });
            });
            //Resume section edit
            jQuery('body').delegate('a.resumeedit', 'click', function(e){
                e.preventDefault();
                var div = jQuery(this).parent().next('div[data-section=\"resume\"]');
                var section = 'resume';
                var resumeid = jQuery('input#resume_temp').val();
                if (!resumeid.trim()){
                    alert(\"". esc_html(__("Please first save resume personal section then add any other section",'wp-job-portal'))."\");
                    return false;
                }
                jQuery('div#resume-wating').show();
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-section-ajax"))."'}, function(data){
                    jQuery(div).html(data);
                    try { tinymce.execCommand('mceAddEditor', true, 'resume'); } catch (e){console.log(e); }
                    addValidateCustom();
                    jQuery('div#resume-wating').hide();
                    /*
                     //init quicktags
                     quicktags({id : 'resume'});
                     //init tinymce
                     tinymce.init(tinyMCEPreInit.mceInit['resume']);
                     /*
                     tinymce.init({skin:'wordpress'});
                     tinyMCE.execCommand('mceAddEditor', true, 'resume');
                     tinyMCE.execCommand('mceAddControl', true, 'resume');
                     /*
                     tinyMCE.execCommand('mceRemoveEditor', true, 'resume');
                     tinyMCE.init({
                     skin : 'wordpress',
                     mode : 'exact',
                     elements : 'resumeeditor'
                     });
                     tinyMCE.execCommand('mceAddEditor', false, 'resume');
                     tinyMCE.execCommand('mceAddControl', true, 'resume');
                     */
                 });
            });
            //Personal Edit photo live change
             jQuery('input#photo').change(function(){
                    var srcimage = jQuery('img.rs_photo');
                    readURL(this, srcimage);
                });
            addValidateCustom();
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

    $inline_js_script = "
    function readURL(input, srcimage) {
        if (input.files && input.files[0]) {
            var fileext = input.files[0].name.split('.').pop();
            var filesize = (input.files[0].size / 1024);
            var allowedsize = \"". wpjobportal::$_config->getConfigurationByConfigName('resume_photofilesize')."\";
            var allowedExt = \"". wpjobportal::$_config->getConfigurationByConfigName('image_file_type')."\";
            allowedExt = allowedExt.split(',');
            if (jQuery.inArray(fileext, allowedExt) != - 1){
                if (allowedsize > filesize){
                    var reader = new FileReader();
                    reader.onload = function (e) {
                    jQuery(srcimage).attr('src', e.target.result);
                    jQuery('.wjportal-form-image-wrp').show();
                    jQuery('.wjportal-form-upload-btn-wrp-txt').html(input.files[0].name);
                    jQuery('img#wjportal-form-delete-image').on('click',function(){
                        jQuery('.wjportal-form-image-wrp').hide();
                        jQuery('input#photo').val('').clone(true);
                        jQuery('span.wjportal-form-upload-btn-wrp-txt').text('');
                    });
                   }
                    reader.readAsDataURL(input.files[0]);
                } else{
                    jQuery('input#photo').replaceWith(jQuery('input#photo').val('').clone(true));
                    alert(\"". esc_html(__("File size is greater then allowed file size", 'wp-job-portal'))."\");
                }
            } else{
                jQuery('input#photo').replaceWith(jQuery('input#photo').val('').clone(true));
                alert(\"". esc_html(__("File ext. is mismatched", 'wp-job-portal'))."\");
            }
        }
    }
    function submitresume(){
        var formvalid = jQuery('form.has-validation-callback').isValid({
            onfocusout: false,
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    validator.errorList[0].element.focus();
                }
            }
        });
        if(formvalid == false){
            return;
        }
        var test = true;
        jQuery('form#resumeform :input[type=email]').each(function(){
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
            if(termsandcondtions == 1){
                if(!jQuery('input[name=\"termsconditions\"]').is(':checked')){
                    alert(common.terms_conditions);
                    event.preventDefault();
                    return false;
                }
            }
            jQuery('div#resume-wating').show();
            var resume = '';
            if(tinyMCE.editors.length > 0){
                var resume = tinyMCE.activeEditor.getContent();
            }
            jQuery('input#resume_edit_val').val(resume);
            jQuery('#resumeform').submit();
        }
    }


        function cancelresumesection1(section, sectionid){
            jQuery('div#resume-wating').show();
            var resumeid = jQuery('input#resume_temp').val();
            var params = {};
            params['action'] = 'wpjobportal_ajax';
            params['wpjobportalme'] = 'resume';
            params['task'] = 'cancelResumeSectionAjax';
            params['section'] = section;
            params['sectionid'] = sectionid;
            params['resumeid'] = resumeid;
            formdata.append('_wpnonce', '". esc_attr(wp_create_nonce("cancel-resume-section-ajax"))."');
            jQuery.post(ajaxurl, params, function(data){
                if (section == 'resume'){
                    tinyMCE.remove();
                }
                var object = jQuery.parseJSON(data);
                if (section != 'resume'){
                    if(section != 'skills'){
                        if(section != 'personal'){
                            if (object.canadd == 1){
                                jQuery('div[data-section=\"' + section + '\"][data-sectionid=\"' + sectionid + '\"]').after(object.anchor);
                            }
                        }
                    }
                }
                jQuery('div[data-section=\"' + section + '\"][data-sectionid=\"' + sectionid + '\"]').replaceWith(object.html);
                if (section == 'addresses'){
                    var htmlobject = jQuery.parseHTML(object.html);
                    var id = jQuery(htmlobject).find('div.map').attr('id');
                    if (document.getElementById('script_' + id) != 'undefined'){
                        if(document.getElementById('script_' + id) != null){
                            eval(document.getElementById('script_' + id).innerHTML);
                        }
                    }
                }
                jQuery('div#resume-wating').hide();
            });
        }

        function getTokenInput(fieldname, fieldeditname) {
            var citylink = \"". esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'city', 'task'=>'getaddressdatabycityname', 'action'=>'wpjobportaltask', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())))."\";

            var city = jQuery('#' + fieldeditname).val();
            alert(city);
            if (city != '') {
                city = jQuery.parseJSON(city);
                jQuery('#' + fieldname).tokenInput(citylink, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__('Type In A Search Term', 'wp-job-portal'))."\",
                    noResultsText: \"". esc_html(__('No Results', 'wp-job-portal'))."\",
                    searchingText: \"". esc_html(__('Searching', 'wp-job-portal'))."\",
                    tokenLimit: 1,
                    prePopulate: [{id:city.id, name:city.name}],";
                    $newtyped_cities = wpjobportal::$_config->getConfigurationByConfigName('newtyped_cities');
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
                        showdiv(sectionid);
                        loadGmap(sectionid,item.latitude,item.longitude);
                        return;
                    }
                    if (item.name.search(',') == - 1) {
                        var input = jQuery('tester').text();
                        alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                        jQuery('#' + fieldname).tokenInput('remove', item);
                        return false;
                    } else{
                        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                        jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function(data){
                            if (data){
                                try {
                                    var value = jQuery.parseJSON(data);
                                    jQuery('#' + fieldname).tokenInput('remove', item);
                                    jQuery('#' + fieldname).tokenInput('add', {id: value.id, name: value.name});
                                }
                                catch (err) {
                                    jQuery('#' + fieldname).tokenInput('remove', item);
                                    alert(data);
                                }
                            }
                        });
                    }
                }";
                }
                 $inline_js_script .= "
            });
            } else {
                jQuery('#' + fieldname).tokenInput(citylink, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__('Type In A Search Term', 'wp-job-portal'))."\",
                    noResultsText: \"". esc_html(__('No Results', 'wp-job-portal'))."\",
                    searchingText: \"". esc_html(__('Searching', 'wp-job-portal'))."\",
                    tokenLimit: 1,";
                    $newtyped_cities = wpjobportal::$_config->getConfigurationByConfigName('newtyped_cities');
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
                            if (item.id > 0){return; }
                            if (item.name.search(',') == - 1) {
                                var input = jQuery('tester').text();
                                alert (\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                                jQuery('#' + fieldname).tokenInput('remove', item);
                                return false;
                            } else{
                                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php'))."\";
                                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function(data){
                                    if (data){
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('#' + fieldname).tokenInput('remove', item);
                                            jQuery('#' + fieldname).tokenInput('add', {id: value.id, name: value.name});
                                        }
                                        catch (err) {
                                            jQuery('#' + fieldname).tokenInput('remove', item);
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        }";
                    }
                    $inline_js_script .= "
                    });
            }

        }

        function initialize(lat, lang, div) {
            var myLatlng = new google.maps.LatLng(lat, lang);
            var myOptions = {
                zoom: 8,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(document.getElementById(div), myOptions);
            var marker = new google.maps.Marker({
                map: map,
                position: myLatlng
            });
        }

        function initializeEdit(lat, lang, div) {
            var myLatlng = new google.maps.LatLng(lat, lang);
            var myOptions = {
                zoom: 8,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(document.getElementById(div), myOptions);
            var marker = new google.maps.Marker({
                map: map,
                position: myLatlng
            });
            var lastmarker = marker;
            google.maps.event.addListener(map, 'click', function (e) {
                var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': latLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (lastmarker != '')
                            lastmarker.setMap(null);
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                        });
                        marker.setMap(map);
                        lastmarker = marker;
                        document.getElementById('latitude_' + div).value = marker.position.lat();
                        document.getElementById('longitude_' + div).value = marker.position.lng();
                    } else {
                        alert(\"". esc_html(__("Geocode was not successful for the following reason", 'wp-job-portal')).": \" + status);
                    }
                });
            });
        }

        jQuery(document).ready(function(){
            var print_link = document.getElementById('print-link');
            if (print_link) {
                ";
                $resumeid = isset(wpjobportal::$_data[0]["personal_section"]->id) ? wpjobportal::$_data[0]["personal_section"]->id : 0; 
                $inline_js_script .= "
                var href = \"". esc_url_raw(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'printresume', 'wpjobportalid'=>$resumeid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())))."\";
                print_link.addEventListener('click', function (event) {
                    print = window.open(href, 'print_win', 'width=1024, height=800, scrollbars=yes');
                    event.preventDefault();
                }, false);
            }
        });

        function getTokenInputTags(multitags) {
            var tagArray = \"". esc_url_raw(admin_url('admin.php?page=wpjobportal_tag&tagfor=2&action=wpjobportaltask&task=gettagsbytagname'))."\";
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
                        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php'))."\";
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


        function dateValidator(sectionid){
            var from = jQuery('#employer_from_date4'+sectionid).val();
            var to = jQuery('#employer_to_date4'+sectionid).val();
            if(Date.parse(from) > Date.parse(to)){
               alert(\"To Date Must Be Greater than From Date!\");
               jQuery('#employer_to_date4'+sectionid).val('');
               jQuery('#employer_to_date4'+sectionid).focus();
            }
        }

        function onSubmit(token) {
            document.getElementById('resumeform').submit();
            // submitresume();
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

    if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $config_array = wpjobportal::$_config->getConfigByFor('captcha');
        if($config_array['captcha_selection'] == 1 && $config_array['recaptcha_privatekey'] ){
            wp_enqueue_script('wpjobportal-repaptcha-script-resume', $protocol . 'www.google.com/recaptcha/api.js');
        }
    }
?>
