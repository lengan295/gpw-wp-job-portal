<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
 * @param job      job object - optional
 * HOOK TO BE USED FOR FEATURE
*/
$config_array = wpjobportal::$_data['config'];
$curdate = date_i18n('Y-m-d');
$module = WPJOBPORTALrequest::getVar('wpjobportalme');
if(isset($module)){
    if($module == "purchasehistory"){
        $check = false;
    }else{
        $check = true;
    }
}
?>
<?php
switch ($layout) {
	case 'showalljobs':
		if ($config_array['comp_viewalljobs']==1 && !empty(wpjobportal::$_data['0'])) {
        	$compalias = wpjobportal::$_data[0]->alias.'-'.wpjobportal::$_data[0]->id;
			?>
           <div class="wjportal-company-btn-wrp">
            	<a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'company'=>$compalias))); ?>" class="wjportal-company-act-btn" title="<?php echo esc_html(__('View all jobs', 'wp-job-portal')); ?>"><?php echo esc_html(__('View All Jobs', 'wp-job-portal')); ?></a>
           </div>
           <?php
            }
	break;
	case 'control':
        $featuredexpiry = date_i18n('Y-m-d', strtotime($company->endfeatureddate));
        ?>
        <div class="wjportal-company-action-wrp">
            <?php 
            if($company->status == 1){ ?>
                <?php
                    if(in_array('multicompany', wpjobportal::$_active_addons)){
                        $layout_mod = "multicompany";
                    }else{
                        $layout_mod = "company";
                    }
                ?>
                <a class="wjportal-company-act-btn" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=> $layout_mod, 'wpjobportallt'=>'addcompany', 'wpjobportalid'=>$company->id))); ?>" title="<?php echo esc_html(__('Edit company','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Edit Company','wp-job-portal')); ?>
                </a>
                <!-- //Specification Addon -->
                <?php do_action('wp_jobportal_credit_for_featurecompany_ajaxpopup',wpjobportal::$_data['config'],$company,$featuredexpiry);  ?>                
                <a class="wjportal-company-act-btn" href="<?php echo esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'task'=>'remove', 'wpjobportal-cb[]'=>$company->id, 'action'=>'wpjobportaltask','wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_company_nonce')); ?>" onclick='return confirmdelete("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('Delete company','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Delete Company','wp-job-portal')); ?>
                </a>
               <?php
                } elseif ($company->status == 0) {
    	           ?>
                    
                        <span class="wjportal-item-act-status wjportal-waiting">
                            <?php echo esc_html(__('Waiting For Approval', 'wp-job-portal')); ?>
                        </span>
                    
    	           <?php
                } elseif ($company->status == -1) {
	            ?>
                    <span class="wjportal-item-act-status wjportal-rejected">
                        <?php echo esc_html(__('Rejected', 'wp-job-portal')); ?>
                    </span>
    	           <?php
                } elseif ($company->status == 3 && in_array('credits',wpjobportal::$_active_addons) && $check) {
                    #Member Lisitng Make Payment
                    ?>
                    <a class="wjportal-company-act-btn" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'addcompany', 'wpjobportalid'=>$company->id))); ?>" title="<?php echo esc_html(__('Edit company','wp-job-portal')); ?>">
                            <?php echo esc_html(__('Edit Company','wp-job-portal')); ?>
                        </a>
                        <?php
                    do_action('wpjobportal_addons_makePayment_for_department',$company,'paycompany');
                    ?>
                    <a class="wjportal-company-act-btn" href="<?php echo esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'task'=>'remove', 'wpjobportal-cb[]'=>$company->id, 'action'=>'wpjobportaltask','wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_company_nonce')); ?>" onclick='return confirmdelete("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('Delete company','wp-job-portal')); ?>
">
                            <?php echo esc_html(__('Delete Company','wp-job-portal')); ?>
                        </a>
                    <?php
                } elseif ($company->status == 3 && in_array('credits',wpjobportal::$_active_addons) && !$check) { ?>
                    <a class="wjportal-company-act-btn" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'mycompanies', 'wpjobportalid'=>$company->id))); ?>"><?php echo esc_html(__('Cancel Payment', 'wp-job-portal')); ?> </a>
                    <button type="button" class="wjportal-company-act-btn" id="proceedPaymentBtn">
                        <?php echo esc_html(__('Proceed To Payment','wp-job-portal')); ?>
                    </button>
                <?php } ?>
        </div>
		<?php
	break;
    case 'payfeatured':
        do_action('wpjobportal_addons_proceedPayment_PerListing',$company->id,'multicompany','mycompanies');
     break;

       case 'paycompany':
       do_action('wpjobportal_addons_proceedPayment_PerListing',$company->id,'multicompany','mycompanies');
        break;
}
?>