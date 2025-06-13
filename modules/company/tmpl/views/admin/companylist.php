<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param company Details 
*/
?>
<div id="company_<?php echo esc_attr($company->id); ?>" class="wpjobportal-company-list">
	<div id="item-data">
		<div class="wpjobportal-company-list-top-wrp">
			<?php
			    /**
			    * @param Feature Company Label 
			    */
			    do_action('wpjobportal_addons_lable_admin_company',$company);
			?>
	        <span id="selector_<?php echo esc_attr($company->id); ?>" class="selector">
	        	<input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($company->id); ?>);" class="wpjobportal-cb" id="wpjobportal-cb" name="wpjobportal-cb[]" value="<?php echo esc_attr($company->id); ?>" />
	        </span>
	    	<?php
				WPJOBPORTALincluder::getTemplate('company/views/admin/logo',array(
					'company' => $company,
					'layout' => $layout,
					'wpdir' => $wpdir
				));

				WPJOBPORTALincluder::getTemplate('company/views/admin/detail',array(
					'company' => $company
				));
			?>
		</div>
		<div class="wpjobportal-company-list-btm-wrp">
			<?php
				WPJOBPORTALincluder::getTemplate('company/views/admin/control',array(
					'company' => $company,
					'control' => $control,
					'arr' => $arr
				));
			?>
		</div>
	</div>
</div>
