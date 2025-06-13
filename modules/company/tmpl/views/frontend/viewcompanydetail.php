<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
 * @param wp job portal      job object - optional
 * & section wise Company Detail
*/
?>
<?php
     /**
     * @param Upper Section
     * Company Contact Detail
     **/
    WPJOBPORTALincluder::getTemplate('company/views/frontend/title',array(
        'layouts' => 'viewcomp_uppersection',
        'config_array' => $config_array,
        'data_class' => $data_class,
        'module' => $module
    ));
?>
<div class="wjportal-company-wrp">
    <?php
        if (isset(wpjobportal::$_data[2]['logo'])) { ?>
            <?php
                $html='<div class="wjportal-company-logo">';
                    WPJOBPORTALincluder::getTemplate('company/views/frontend/logo',array(
                        'layout' => 'complogo',
                        'html' => $html,
                        'classname' => 'wjportal-company-logo-image',
                        'module' => $module
                ));
        } ?>
    <div class="wjportal-companyinfo-social-links-wrapper" >
        <?php
        foreach (wpjobportal::$_data[2] AS $key => $val) {
            switch ($key) {
                case 'facebook_link':
                    if(isset(wpjobportal::$_data[0]->facebook_link) && wpjobportal::$_data[0]->facebook_link != ''){ ?>
                        <a class="wjportal-companyinfo-social-link" href="<?php echo esc_url(wpjobportal::$_data[0]->facebook_link);?>"><i class="fa fa-facebook"></i></a>
                        <?php
                    }
                break;
                case 'youtube_link':
                    if(isset(wpjobportal::$_data[0]->youtube_link) && wpjobportal::$_data[0]->youtube_link != ''){ ?>
                        <a class="wjportal-companyinfo-social-link" href="<?php echo esc_url(wpjobportal::$_data[0]->youtube_link);?>"><i class="fa fa-youtube"></i></a>
                        <?php
                    }
                break;
                case 'linkedin_link':
                    if(isset(wpjobportal::$_data[0]->linkedin_link) && wpjobportal::$_data[0]->linkedin_link != ''){ ?>
                        <a class="wjportal-companyinfo-social-link" href="<?php echo esc_url(wpjobportal::$_data[0]->linkedin_link);?>"><i class="fa fa-linkedin"></i></a>
                        <?php
                    }
                break;
                case 'twiter_link':
                    if(isset(wpjobportal::$_data[0]->twiter_link) && wpjobportal::$_data[0]->twiter_link != ''){ ?>
                        <a class="wjportal-companyinfo-social-link" href="<?php echo esc_url(wpjobportal::$_data[0]->twiter_link);?>"><i class="fa fa-twitter"></i></a>
                        <?php
                    }
                break;
            }
        } ?>
    </div>

</div>
<?php
    /**
     * @param Middle Section 
     * Company Contact Body Detail
     **/
    WPJOBPORTALincluder::getTemplate('company/views/frontend/detail',array(
        'layout' => 'companydetail',
        'data_class' => $data_class,
        'config_array' => $config_array,
        'module' => $module
    ));
?>
<?php
    /**
     * @param Button Section
     * To view All job's Related Companies
     **/
    WPJOBPORTALincluder::getTemplate('company/views/frontend/control',array(
        'config_array' => $config_array,
        'layout' => 'showalljobs',
        'module' => $module
    ));
?>    
