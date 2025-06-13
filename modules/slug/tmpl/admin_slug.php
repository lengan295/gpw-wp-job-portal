<?php
if (!defined('ABSPATH'))
die('Restricted Access');
wp_enqueue_script('wpjobportal-res-tables', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/responsivetable.js');
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;"></div>
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <?php
            $msgkey = WPJOBPORTALincluder::getJSModel('slug')->getMessagekey();
            WPJOBPORTALMessages::getLayoutMessage($msgkey);
        ?>
        <!-- top bar -->
        <div id="wpjobportal-wrapper-top">
            <div id="wpjobportal-wrapper-top-left">
                <div id="wpjobportal-breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('dashboard','wp-job-portal')); ?>">
                                <?php echo esc_html(__('Dashboard','wp-job-portal')); ?>
                            </a>
                        </li>
                        <li><?php echo esc_html(__('Slug','wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-help-btn" class="wpjobportal-help-btn">
                    <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/help.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>
        </div>
        <!-- top head -->
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'slug' , 'layouts' => 'slug')); ?>
        <?php
            wp_register_script( 'wpjobportal-inline-handle', '' );
            wp_enqueue_script( 'wpjobportal-inline-handle' );

            $inline_js_script = "
                var slug_for_edit = 0;
                jQuery(document).ready(function () {
                jQuery('div#full_background').click(function () {
                  closePopup();
                   });
                });

                function resetFrom() {// Resest Form
                jQuery('input#slug').val('');
                jQuery('form#wpjobportalform').submit();
                }

                function showPopupAndSetValues(id,slug) {//Showing PopUp
                slug = jQuery('td#td_'+id).html();
                slug_for_edit = id;
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'slug', task: 'getOptionsForEditSlug',id:id ,slug:slug , '_wpnonce':'". esc_attr(wp_create_nonce("get-options-for-edit-slug"))."'}, function (data) {
                    if (data) {
                        var d = jQuery.parseJSON(data);
                        jQuery('div#full_background').css('display', 'block');
                        jQuery('div#popup_main').html(d);
                        jQuery('div#popup_main').slideDown('slow');
                    }
                });
                }
                function closePopup() {// Close PopUp
                jQuery('div#popup_main').slideUp('slow');
                setTimeout(function () {
                jQuery('div#full_background').hide();
                jQuery('div#popup_main').html('');
                }, 700);
                }
                function getFieldValue() {
                var slugvalue = jQuery('#slugedit').val();
                jQuery('input#'+slug_for_edit).val(slugvalue);
                jQuery('td#td_'+slug_for_edit).html(slugvalue);
                closePopup();
                }
            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        ?>

        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- filter form -->
            <form class="wpjobportal-filter-form slug-configform" name="wpjobportalform" id="conwpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_slug&task=savehomeprefix")); ?>">
                <?php echo wp_kses(WPJOBPORTALformfield::text('prefix', esc_html(wpjobportal::$_configuration['home_slug_prefix']), array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Home Slug','wp-job-portal')).' '. esc_html(__('Prefix', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Save', 'wp-job-portal')), array('class' => 'button wpjobportal-form-search-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_slug_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <div class="wpjobportal-form-help-text">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/view-job-information.png" />
                    <?php echo esc_html(__('This prefix will be added to slug incase of homepage links','wp-job-portal'))?>
                </div>
            </form>
            <!-- filter form -->
            <form class="wpjobportal-filter-form slug-configform" name="wpjobportalform" id="conwpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_slug&task=saveprefix")); ?>">
                <?php echo wp_kses(WPJOBPORTALformfield::text('prefix', esc_html(wpjobportal::$_configuration['slug_prefix']), array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Slug','wp-job-portal')).' '. esc_html(__('Prefix', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Save', 'wp-job-portal')), array('class' => 'button wpjobportal-form-search-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_slug_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <div class="wpjobportal-form-help-text">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/view-job-information.png" />
                    <?php echo esc_html(__('This prefix will be added to slug incase of conflict','wp-job-portal'))?>
                </div>
            </form>
            <!-- filter form -->
            <form class="wpjobportal-filter-form" name="wpjobportalform" id="wpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_slug")); ?>">
                <?php echo wp_kses(WPJOBPORTALformfield::text('slug', esc_html(wpjobportal::$_data['slug']), array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Search By Slug', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Search', 'wp-job-portal')), array('class' => 'button wpjobportal-form-search-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::button('reset', esc_html(__('Reset', 'wp-job-portal')), array('class' => 'button wpjobportal-form-reset-btn', 'onclick' => 'resetFrom();')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </form>
            <?php
                if (!empty(wpjobportal::$_data[0])) {
                    ?>
                    <form id="wpjobportal-list-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_slug&task=saveSlug")); ?>">
                        <table id="wpjobportal-table" class="wpjobportal-table">
                            <thead>
                                <tr>
                                    <th class="wpjobportal-text-left">
                                        <?php echo esc_html(__('Slug List', 'wp-job-portal')); ?>
                                    </th>
                                    <th class="wpjobportal-text-left">
                                        <?php echo esc_html(__('Description', 'wp-job-portal')); ?>
                                    </th>
                                    <th>
                                        <?php echo esc_html(__('Action', 'wp-job-portal')); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $pagenum = WPJOBPORTALrequest::getVar('pagenum', 'get', 1);
                                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                                    foreach (wpjobportal::$_data[0] as $row){
                                        ?>
                                        <tr>
                                            <td class="wpjobportal-text-left" id="<?php echo 'td_'.esc_attr($row->id);?>">
                                                <?php echo esc_html($row->slug);?>
                                            </td>
                                            <td class="wpjobportal-text-left">
                                                <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($row->description));?>
                                            </td>
                                            <td>
                                                <a class="wpjobportal-table-act-btn" href="#" onclick="showPopupAndSetValues(<?php echo esc_js($row->id); ?>)" title="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
                                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/edit.png" alt="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
                                                </a>
                                            </td>
                                        </tr>
                                        <?php echo wp_kses(WPJOBPORTALformfield::hidden(esc_html($row->id), esc_html($row->slug)),WPJOBPORTAL_ALLOWED_TAGS);?>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <!-- Hidden Fields -->
                        <div class="wpjobportal-filter-form-action-wrp">
                            <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Save', 'wp-job-portal')), array('class' => 'button savebutton wpjobportal-form-act-btn wpjobportal-form-act-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <div class="wpjobportal-form-act-msg">
                                <?php echo  esc_html(__('This button will only save slugs on current page','wp-job-portal')); ?> !
                            </div>
                        </div>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('task', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('pagenum', ($pagenum > 1) ? esc_html($pagenum) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_slug_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </form>
                    <?php
                    if (wpjobportal::$_data[1]) {
                        echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(wpjobportal::$_data[1]) . '</div></div>';
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    $link[] = array(
                            'link' => 'admin.php?page=wpjobportal_slug&wpjobportallt=formcareerlevels',
                        );
                    WPJOBPORTALlayout::getNoRecordFound($msg, $link);
                }
            ?>
        </div>
    </div>
</div>
