<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
 /**
 * @param job      job object - optional
 */
?>
<?php
if (!isset($company)) {
	$company[0]=null;
	$comp = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0]:'';
} else if (isset($company)) {
	$comp = $company;
} else {
	$comp='';
}

/**
* @param wp job portal url
* redirection for More than One Company
* # multicompany vs company
**/
if(in_array('multicompany',wpjobportal::$_active_addons)){
    // Mudlue
    $mod = "multicompany";
}else{
    $mod = "company";
}
$published_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingData(1);
switch ($layout) {
	case 'complogo':
        $data_class = (isset(wpjobportal::$_data[2]['logo'])) ? 'two_column' : 'one_column';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
        $logopath = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
		if(isset($published_fields['logo']) && $published_fields['logo'] != ''){
        if ($comp->logofilename) {
            $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
            $wpdir = wp_upload_dir();
            $logopath = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $comp->id . '/logo/' . $comp->logofilename;
            }
            $class = isset($classname) ? $classname : '';
            if ($class == "") {
           	    ?>
           	    <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->aliasid))); ?>">
           	        <?php } ?>
               		<img src="<?php echo esc_url($logopath); ?>" class="<?php echo esc_attr($class);?>" alt="<?php echo esc_html(__('Company logo','wp-job-portal')); ?>" />
                    <?php if ($class=="") { ?>
            	</a>
    	   <?php }
           } ?>
        </div> <!-- logo div close -->
        <div>
            <?php do_action('wpjobportal_addons_social_share_company',$comp,$data_class); ?>
        </div>
        <?php
    break;
}
?>
