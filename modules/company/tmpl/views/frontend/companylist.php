<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param company 	company object
*/

if (!$company) {
	return;
}
?>
<div class="wjportal-company-list">
    <div class="wjportal-company-list-top-wrp object_<?php echo esc_attr($company->id); ?>" data-boxid="company_<?php echo esc_attr($company->id); ?>">
        <?php
            $html='<div class="wjportal-company-logo">';
            if(empty(wpjobportal::$_data['shortcode_option_hide_company_logo'])){
            	WPJOBPORTALincluder::getTemplate('company/views/frontend/logo', array(
            		'company' => $company,
                    'layout' => 'complogo',
                    'html' => $html,
                    'module' => 'company'

                ));
            }
            $html = "</div>";
            WPJOBPORTALincluder::getTemplate('company/views/frontend/detail', array(
        		'company' => $company,
                'layout' => 'detail',
                'companies_layout' => $layout,
                'module' => 'company'
        	));
    	?>
    </div>
    <?php if($layout != 'companies'){ ?>
        <div class="wjportal-company-list-btm-wrp">
        	<?php
            	WPJOBPORTALincluder::getTemplate('company/views/frontend/control', array(
            		'company' => $company,
                    'layout' => $layout,
                    'module' => 'company'
            	));
        	?>
        </div>
    <?php } ?>
</div>
