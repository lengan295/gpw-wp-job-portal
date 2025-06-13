<?php
if (!defined('ABSPATH'))
die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
$msgkey = WPJOBPORTALincluder::getJSModel('job')->getMessagekey();
WPJOBPORTALMessages::getLayoutMessage($msgkey);

//WPJOBPORTALbreadcrumbs::getBreadcrumbs();
//include_once(WPJOBPORTAL_PLUGIN_PATH . 'includes/header.php');
?>
<div class="wjportal-main-wrapper wjportal-clearfix">
    <?php if (wpjobportal::$_error_flag == null) { ?>
        <div class="wjportal-page-header">
            <?php
                WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'jobbycatagory','layout'=>'jobbycatagory'));
            ?>
        </div>
        <div id="wjportal-popup-background"></div>
        <div id="wjportal-listpopup" class="wjportal-popup-wrp wjportal-job-by-catg-popup">
            <div class="wjportal-popup-cnt">
                <img id="wjportal-popup-close-btn" alt="popup cross" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/popup-close.png">
                <div class="wjportal-popup-title">
                    <span class="wjportal-popup-title2"></span>
                </div>
                <div class="wjportal-popup-contentarea"></div>
            </div>
        </div>
        <div id="wpjobportal-wrapper" class="wjportal-by-categories-main-wrp wjportal-job-by-categories-wrp">
            <?php
                $number = 3;
                if(wpjobportal::$_data['config']['categories_colsperrow'] != ''){ // to handle float value in configuration
                    $number = ceil(wpjobportal::$_data['config']['categories_colsperrow']);
                }
                if ($number < 1 || $number > 100) {
                    $number = 3; // by default set to 3
                }
                $width = 100 / $number;
                $count = 0;
                if (isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0])) {
                    foreach (wpjobportal::$_data[0] AS $jobsByCategories) {
                        if (($count % $number) == 0) {
                            if ($count == 0)
                                echo '<div class="wjportal-by-categories-row-wrp">';
                            else
                                echo '</div><div class="wjportal-by-categories-row-wrp">';
                        }
                        ?>
                        <div class="wjportal-by-category-wrp" style="width:<?php echo esc_attr($width); ?>%;" data-id="<?php echo esc_attr($jobsByCategories->aliasid); ?>">
                            <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'category'=>$jobsByCategories->aliasid))); ?>">
                                <div class="wjportal-by-category-item">
                                    <span class="wjportal-by-category-item-title">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($jobsByCategories->cat_title)); ?>
                                    </span>
                                    <?php if(wpjobportal::$_data['config']['categories_numberofjobs'] == 1){ ?>
                                        <span class="wjportal-by-category-item-number"><?php echo '(' . esc_html($jobsByCategories->totaljobs + $jobsByCategories->total_sub_jobs) . ')'; ?></span>
                                    <?php } ?>
                                </div>
                            </a>
                            <?php
                                $config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('category');
                                $subcategory_limit = 3;
                                if($config_array['subcategory_limit'] != ''){ // to handle float value in configuration
                                    $subcategory_limit = ceil($config_array['subcategory_limit']);
                                }
                                if (!empty($jobsByCategories->subcat)) {
                                    $html = '<div class="wjportal-by-sub-catagory" style="display:none;">';
                                    $subcount = 0;
                                    foreach ($jobsByCategories->subcat AS $cat) {
                                        //$link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumes', 'category'=>$cat->aliasid));
                                        $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'category'=>$cat->aliasid));
                                        $html .= '  <div class="wjportal-by-category-wrp" style="width:100%;">
                                                        <a href="' . $link . '">
                                                            <div class="wjportal-by-category-item">
                                                                <span class="wjportal-by-category-item-title">' . wpjobportal::wpjobportal_getVariableValue($cat->cat_title) . '</span>';
                                                                if($config_array['categories_numberofjobs'] == 1){
                                                                    $html .= '<span class="wjportal-by-category-item-number">(' . $cat->totaljobs . ')</span>';
                                                                }
                                        $html .=    '       </div>
                                                        </a>
                                                    </div>';
                                        $subcount++;
                                    }
                                    if ($subcount >= $subcategory_limit) {
                                        $html .= '  <div class="wjportal-by-category-item-btn-wrp">
                                                        <a href="#" class="wjportal-by-category-item-btn" onclick="getPopupAjax(\'' . $jobsByCategories->aliasid . '\', \'' . wpjobportal::wpjobportal_getVariableValue($jobsByCategories->cat_title) . '\');">' . __('Show More', 'wp-job-portal') . '</a>
                                                    </div>';
                                    }
                                    $html .= '</div>';
                                    echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
                                }
                            ?>
                        </div>
                        <?php
                        $count++;
                    }
                    echo '</div>';
                } else {
                    WPJOBPORTALlayout::getNoRecordFound();
                }
            ?>
        </div>
    <?php
    } else {
        if(wpjobportal::$_error_flag_message !=''){
            echo wp_kses_post(wpjobportal::$_error_flag_message);
        }
    }
    ?>
</div>
</div>
