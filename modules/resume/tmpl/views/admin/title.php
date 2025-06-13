<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param WP JOB PORTAL
* @param Resume Detail
*/
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(3);
?>
<div class="wpjobportal-resume-cnt-wrp">
    <div class="wpjobportal-resume-middle-wrp">
        <?php if(isset($listing_fields['jobtype'])){ ?>
            <div class="wpjobportal-resume-data">
                <span class="wpjobportal-resume-job-type" style="background-color: <?php echo esc_attr($resume->color); ?>" >
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->jobtypetitle)); ?>
                </span>
            </div>
        <?php } ?>
        <div class="wpjobportal-resume-data">
            <span class="wpjobportal-resume-name">
                <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid=".$resume->id));?>">
                    <?php
                    // since application title is not required showing name in its place
                    echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->application_title));
                    if($resume->application_title !=''){
                        echo '&nbsp;(';
                    }
                    echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->first_name));
                    if($resume->last_name != ''){
                        echo '&nbsp;'. esc_html(wpjobportal::wpjobportal_getVariableValue($resume->last_name));
                    }
                    if($resume->application_title !=''){
                        echo ')';
                    }
                     ?>
                </a>
            </span>
            <?php
                if ($resume->status == 0) {
                    echo '<span class="wpjobportal-item-status pending">' . esc_html(__('Pending', 'wp-job-portal')) . '</span>';
                } elseif ($resume->status == 1) {
                    echo '<span class="wpjobportal-item-status approved">' . esc_html(__('Approved', 'wp-job-portal')) . '</span>';
                } elseif ($resume->status == -1) {
                    echo '<span class="wpjobportal-item-status rejected">' . esc_html(__('Rejected', 'wp-job-portal')) . '</span>';
                } elseif ($resume->status == 3) {
                    echo '<span class="wpjobportal-item-status rejected">' . esc_html(__('Pending Payment', 'wp-job-portal')) . '</span>';
                }
            ?>
        </div>
        <?php if(isset($listing_fields['job_category'])){ ?>
            <div class="wpjobportal-resume-data wpjobportal-resume-catgry">
                <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->cat_title)); ?>
            </div>
        <?php }?>
        <div class="wpjobportal-resume-data">
            <?php if(isset($listing_fields['salaryfixed'])){ ?>
                <div class="wpjobportal-resume-data-text">
                    <span class="wpjobportal-resume-data-title">
                        <?php
                            // if(!isset(wpjobportal::$_data['fields']['salaryfixed'])){
                            //     wpjobportal::$_data['fields']['salaryfixed'] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('salaryfixed',3);
                            // }
                            echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['salaryfixed'])) . ': ';
                        ?>
                    </span>
                    <span class="wpjobportal-resume-data-value">
                        <?php // was showing label twice
                            echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->salaryfixed));
                        ?>
                    </span>
                </div>
            <?php } ?>
            <?php
            if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                if(isset($listing_fields['employer'])) {
                ?>

                    <div class="wpjobportal-resume-data-text">
                        <span class="wpjobportal-resume-data-title">
                            <?php echo esc_html(__('Total Experience', 'wp-job-portal')) . ' : '; ?>
                        </span>
                        <span class="wpjobportal-resume-data-value">
                            <?php echo esc_html(wpjobportal::$_common->getTotalExp($resume->resumeid)); ?>
                        </span>
                    </div>
                <?php
                }
                if(isset($listing_fields['address_city'])) { ?>
                        <div class="wpjobportal-resume-data-text">
                            <span class="wpjobportal-resume-data-title">
                                <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['address_city'])) . ' : '; ?>
                            </span>
                            <span class="wpjobportal-resume-data-value">
                                <?php echo esc_html(WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($resume->city)); ?>
                            </span>
                        </div>
                <?php }
            }
            ?>
            <?php if(isset($listing_fields['job_category'])) { ?>
                <div class="wpjobportal-resume-data-text">
                    <span class="wpjobportal-resume-data-title">
                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['job_category'])) . ' : '; ?>
                    </span>
                    <span class="wpjobportal-resume-data-value">
                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->cat_title)); ?>
                    </span>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


