<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal Action
*/

switch ($control) {
	case 'control': ?>
		<div id="item-actions" class="wpjobportal-company-action-wrp">
			<?php
				/**
				* @param Feature Company Admin 
				*/
				do_action('wpjobportal_addons_control_company_admin',$company);
			?>
		    <a class="wpjobportal-company-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_company&wpjobportallt=formcompany&wpjobportalid='.esc_attr($company->id))); ?>" title="<?php echo esc_html(__('Edit', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Edit', 'wp-job-portal')) ?>
		    </a>
		    <a class="wpjobportal-company-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_company&task=remove&action=wpjobportaltask&&callfrom=1&wpjobportal-cb[]='.esc_attr($company->id)),'wpjobportal_company_nonce')); ?>" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
		    	<?php echo esc_html(__('Delete', 'wp-job-portal')); ?>
		    </a>
		    <a class="wpjobportal-company-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_company&task=enforcedelete&action=wpjobportaltask&callfrom=1&id='.esc_attr($company->id)),'wpjobportal_company_nonce')); ?>"onclick='return confirmdelete("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('enforce delete', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Enforce Delete', 'wp-job-portal')) ?>
		    </a>
		    <?php if(in_array('departments', wpjobportal::$_active_addons)): ?>
			    <a class="wpjobportal-company-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_departments&wpjobportallt=departments&companyid='.esc_attr($company->id))); ?>" title="<?php echo esc_html(__('departments', 'wp-job-portal')) ?>">
			    	<?php echo esc_html(__('Departments', 'wp-job-portal')) ?>
			    </a>
		    <?php  endif ;?>
		</div>
		<?php	break;
		case 'que-control': ?>
			<div class="wpjobportal-company-action-wrp">
				<a class="wpjobportal-company-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_company&wpjobportallt=formcompany&wpjobportalid='.esc_attr($company->id))); ?>" title="<?php echo esc_html(__('Edit', 'wp-job-portal')) ?>">
			    	<?php echo esc_html(__('Edit', 'wp-job-portal')) ?>
			    </a>
			    <?php
			        /*$total = count($arr);
			        if ($total == 3) {
			            $objid = 4; //for all
			        } elseif ($total != 1) {
			        }
			        if ($total == 1) {*/
			            if (isset($arr['self'])) {
			                ?>
			                <a class="wpjobportal-company-act-btn" href="admin.php?page=wpjobportal_company&task=approveQueueCompany&id=<?php echo esc_attr($company->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_company_nonce'));?>" title="<?php echo esc_html(__('approve', 'wp-job-portal')); ?>">
			                	<?php echo esc_html(__('Company Approve', 'wp-job-portal')); ?>
			                </a>
			            <?php
			            } if (isset($arr['feature']) && in_array('featuredcompany', wpjobportal::$_active_addons)) { ?>
			                <a class="wpjobportal-company-act-btn" href="admin.php?page=wpjobportal_company&task=approveQueueFeaturedCompany&id=<?php echo esc_attr($company->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_company_nonce'));?>" title="<?php echo esc_html(__('approve', 'wp-job-portal')); ?>">
			                	<?php echo esc_html(__('Feature Approve', 'wp-job-portal')); ?>
			                </a>
		                <?php
                        }
			        /*}
			         else {
			            ?>
			            <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="approveActionPopup('<?php echo esc_js($company->id); ?>');"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/publish-icon.png">  <?php echo esc_html(__('Approve', 'wp-job-portal')); ?>
			                <div id="wpjobportal-queue-actionsbtn" class="jobsqueueapprove_<?php echo esc_attr($company->id); ?>">
			                    <?php if (isset($arr['self'])) { ?>
			                        <a id="wpjobportal-act-row" class="wpjobportal-act-row" href="admin.php?page=wpjobportal_company&task=approveQueueCompany&id=<?php echo esc_attr($company->id); ?>&action=wpjobportaltask">
			                        	<img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/comapny-logo.png">
			                        	<?php echo esc_html(__("Company Approve", 'wp-job-portal')); ?>
			                        </a>
			                    <?php
			                    } ?>
			                </div>
						</div>
				    	<?php
					}
					//END APPROVE SECTION
					if ($total == 1) {*/
					    if (isset($arr['self'])) {
					        ?>
					        <a class="wpjobportal-company-act-btn" href="admin.php?page=wpjobportal_company&task=rejectQueueCompany&id=<?php echo esc_attr($company->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_company_nonce'));?>" title="<?php echo esc_html(__('reject', 'wp-job-portal')); ?>">
					        	<?php echo esc_html(__('Company Reject', 'wp-job-portal')); ?>
					        </a>
					    <?php
					    } if (isset($arr['feature']) && in_array('featuredcompany', wpjobportal::$_active_addons)) {
					        ?>
					        <a class="wpjobportal-company-act-btn" href="admin.php?page=wpjobportal_company&task=rejectQueueFeatureCompany&id=<?php echo esc_attr($company->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_company_nonce'));?>" title="<?php echo esc_html(__('reject', 'wp-job-portal')); ?>">
					        	<?php echo esc_html(__('Feature Reject', 'wp-job-portal')); ?>
					        </a>
					    <?php
					    }
					/*} else {
					    ?>
					    <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="rejectActionPopup('<?php echo esc_js($company->id); ?>');">
					    	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/reject-s.png">
					    	<?php echo esc_html(__('Reject', 'wp-job-portal')); ?>
					        <div id="wpjobportal-queue-actionsbtn" class="jobsqueuereject_<?php echo esc_attr($company->id); ?>">
					            <?php if (isset($arr['self'])) { ?>
					                <a id="wpjobportal-act-row" class="wpjobportal-act-row" href="admin.php?page=wpjobportal_company&task=rejectQueueCompany&id=<?php echo esc_attr($company->id); ?>&action=wpjobportaltask" title="<?php echo esc_html(__("company reject", 'wp-job-portal')); ?>">
					                	<img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/comapny-logo.png">
					                	<?php echo esc_html(__("Company Reject", 'wp-job-portal')); ?>
					                </a>
					            <?php
					            }
								?>
					            <a id="wpjobportal-act-row-all" class="wpjobportal-act-row-all" href="admin.php?page=wpjobportal_company&task=rejectQueueAllCompanies&objid=<?php echo esc_attr($objid); ?>&id=<?php echo esc_attr($company->id); ?>&action=wpjobportaltask" title="<?php echo esc_html(__("all reject", 'wp-job-portal')); ?>">
					            	<img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/select-all.png">
					            	<?php echo esc_html(__("All Reject", 'wp-job-portal')); ?>
					            </a>
					        </div>
					    </div>
						<?php                         
					}*/
		    	?>
				<a class="wpjobportal-company-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_company&task=remove&action=wpjobportaltask&wpjobportal-cb[]='.esc_attr($company->id)),'wpjobportal_company_nonce')); ?>&callfrom=2" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
		            <?php echo esc_html(__('Delete', 'wp-job-portal')); ?>
		        </a>
		        <a class="wpjobportal-company-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_company&task=enforcedelete&action=wpjobportaltask&id='.esc_attr($company->id)),'wpjobportal_company_nonce')); ?>&callfrom=2" onclick='return confirmdelete("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('force delete', 'wp-job-portal')); ?>">
		            <?php echo esc_html(__('Force Delete', 'wp-job-portal')); ?>
		        </a>
			</div>
			<?php
		break;
}
?>

	
