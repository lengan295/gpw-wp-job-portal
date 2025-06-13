<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param Detail Body
* wpjobportalPopupAdmin
*/
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(1);
?>
<div class="wpjobportal-company-cnt-wrp">
    <div class="wpjobportal-company-middle-wrp">
        <div class="wpjobportal-company-data">
           <a class="wpjobportal-company-name" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_company&wpjobportallt=formcompany&wpjobportalid='.$company->id)); ?>">
                <?php echo esc_html($company->name); ?>
            </a> 
        </div>
        <?php if(isset($listing_fields['description'])) {?>
            <div class="wpjobportal-company-data wpjobportal-company-desc">
                <?php echo isset($company->description) ? wp_kses($company->description, WPJOBPORTAL_ALLOWED_TAGS) : ''; ?>
            </div>
        <?php }?>
        <?php if(isset($listing_fields['city'])) {?>
            <div class="wpjobportal-company-data">
                <div class="wpjobportal-company-data-text">
                    <span class="wpjobportal-company-data-title">
                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['city'])). ' : '; ?>
                    </span>
                    <span class="wpjobportal-company-data-value">
                        <?php echo esc_html(WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($company->city)); ?>
                    </span>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="wpjobportal-company-right-wrp">
        <div class="wpjobportal-company-status">
            <?php
                if ($company->status == 0) {
                    echo '<span class="wpjobportal-company-status-txt pending">' . esc_html(__('Pending', 'wp-job-portal')) . '</span>';
                } elseif ($company->status == 1) {
                    echo '<span class="wpjobportal-company-status-txt approved">' . esc_html(__('Approved', 'wp-job-portal')) . '</span>';
                } elseif ($company->status == -1) {
                    echo '<span class="wpjobportal-company-status-txt rejected">' . esc_html(__('Rejected', 'wp-job-portal')) . '</span>';
                }elseif ($company->status == 3) {
                    echo '<span class="wpjobportal-company-status-txt pending-payment">' . esc_html(__('Pending Payment', 'wp-job-portal')) . '</span>';
                }
            ?> 
        </div>
    </div>
</div>

                    
                        
