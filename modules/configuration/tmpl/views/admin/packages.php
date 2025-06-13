<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="listjobs">
    <h3 class="js-job-configuration-heading-main"><?php echo esc_html(__('Listing Style', 'wp-job-portal')); ?></h3>
    <div class="left">
        <?php if($theme_chk == 0){ ?>
            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                <div class="js-col-xs-12  js-job-configuration-title"><?php echo esc_html(__('Search icon position', 'wp-job-portal')); ?></div>
                <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::select('searchjobtag', $searchjobtag, wpjobportal::$_data[0]['searchjobtag']),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo esc_html(__('Postion for search icon on jobs listing page.', 'wp-job-portal')); ?></small></div>
            </div>
        <?php } ?>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12  js-job-configuration-title"><?php echo esc_html(__('Show featured jobs', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::select('showfeaturedjobsinlistjobs', $yesno, wpjobportal::$_data[0]['showfeaturedjobsinlistjobs']),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo esc_html(__('Show featured jobs in jobs lising page', 'wp-job-portal')); ?></small></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12  js-job-configuration-title"><?php echo esc_html(__('Show Total Number Of jobs', 'wp-job-portal')); ?></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::select('show_total_number_of_jobs', $yesno, wpjobportal::$_data[0]['show_total_number_of_jobs']),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo esc_html(__('Show total number of jobs in jobs lising page', 'wp-job-portal')); ?></small></div>
        </div>
    </div>
    <div class="right">
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12  js-job-configuration-title"><?php echo esc_html(__('Number of featured jobs', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('nooffeaturedjobsinlisting', wpjobportal::$_data[0]['nooffeaturedjobsinlisting'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo esc_html(__('Number of featured job show per scroll', 'wp-job-portal')); ?></small></div>
        </div>
        <?php if($theme_chk == 0){ ?>
            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                <div class="js-col-xs-12  js-job-configuration-title"><?php echo esc_html(__('Show labels in jobs listing', 'wp-job-portal')); ?></div>
                <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::select('labelinlisting', $yesno, wpjobportal::$_data[0]['labelinlisting']),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo esc_html(__('Show labels in jobs listings, my jobs etc', 'wp-job-portal')); ?></small></div>
            </div>
        <?php } ?>
    </div>
    <h3 class="js-job-configuration-heading-main"><?php echo esc_html(__('Indeed Jobs', 'wp-job-portal')); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
    <div class="left">
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Show Indeed jobs on jobs listings', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::select('indeedjob_enabled', $yesno, wpjobportal::$_data[0]['indeedjob_enabled']),WPJOBPORTAL_ALLOWED_TAGS); ?><div><small><?php echo esc_html(__('Show company logo with job feeds', 'wp-job-portal')); ?></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('API key', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('indeedjob_apikey', wpjobportal::$_data[0]['indeedjob_apikey'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?><div><small></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Number of jobs before showing indeed jobs', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('indeedjob_showafter', wpjobportal::$_data[0]['indeedjob_showafter'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('How many plugin jobs show before indeed jobs', 'wp-job-portal')); ?></small></div></div>
        </div>
        <!-- <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Number of Indeed jobs per page', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('indeedjob_jobperrequest', wpjobportal::$_data[0]['indeedjob_jobperrequest'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('Number of Indeed Jobs per scroll', 'wp-job-portal')); ?></small></div></div>
        </div> -->
    </div>
    <div class="right">
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Categories', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('indeedjob_category', wpjobportal::$_data[0]['indeedjob_category'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('Comma separated list of categories i.e Accounting, Management etc', 'wp-job-portal')); ?></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Location', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('indeedjob_location', wpjobportal::$_data[0]['indeedjob_location'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('Location format must be country, state, city', 'wp-job-portal')); ?></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Job types', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('indeedjob_jobtype', wpjobportal::$_data[0]['indeedjob_jobtype'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('Comma separated list of job types i.e full time, part time etc', 'wp-job-portal')); ?></small></div></div>
        </div>
    </div>
    <h3 class="js-job-configuration-heading-main"><?php echo esc_html(__('Career Builder', 'wp-job-portal')); ?><font style="color:#fff;font-size:22px;margin:0px 5px;">*</font></h3>
    <div class="left">
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Show Career Builder jobs in job listings', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::select('careerbuilder_enabled', $yesno, wpjobportal::$_data[0]['careerbuilder_enabled']),WPJOBPORTAL_ALLOWED_TAGS); ?><div><small><?php echo esc_html(__('Use rss categories with our job categories', 'wp-job-portal')); ?></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('API key', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('careerbuilder_developerkey', wpjobportal::$_data[0]['careerbuilder_developerkey'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Number of jobs before showing Career Builder jobs', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('careerbuilder_showafter', wpjobportal::$_data[0]['careerbuilder_showafter'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('How many plugin jobs show before career builder jobs', 'wp-job-portal')); ?></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Number of Career Builder jobs per page', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('careerbuilder_jobperrequest', wpjobportal::$_data[0]['careerbuilder_jobperrequest'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('Number of Career Builder jobs per scroll', 'wp-job-portal')); ?></small></div></div>
        </div>
    </div>
    <div class="right">
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Country Code', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('careerbuilder_countrycode', wpjobportal::$_data[0]['careerbuilder_countrycode'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('Comma separated list of country codes', 'wp-job-portal')); ?></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Categories', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('careerbuilder_category', wpjobportal::$_data[0]['careerbuilder_category'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div><small><?php echo esc_html(__('Comma separated list of categories i.e Accounting, Management etc', 'wp-job-portal')); ?></small></div></div>
        </div>
        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
            <div class="js-col-xs-12 js-col-md-2 js-job-configuration-title"><?php echo esc_html(__('Job Types', 'wp-job-portal')); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(WPJOBPORTALformfield::text('careerbuilder_emptype', wpjobportal::$_data[0]['careerbuilder_emptype'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <div>
                    <small><?php echo esc_html(__('Comma separated list of job types i.e JTFT, JTPT, JTFP, JTCT, JTIN ', 'wp-job-portal')); ?></small><br/>
                    <small>JTFT : <?php echo esc_html(__('Full-time', 'wp-job-portal')); ?></small><br/>
                    <small>JTPT : <?php echo esc_html(__('Part-time', 'wp-job-portal')); ?></small><br/>
                    <small>JTFP : <?php echo esc_html(__('Full-time/part-time', 'wp-job-portal')); ?></small><br/>
                    <small>JTCT : <?php echo esc_html(__('Contractant', 'wp-job-portal')); ?></small><br/>
                    <small>JTIN : <?php echo esc_html(__('Stagiair', 'wp-job-portal')); ?></small><br/>
                </div>
            </div>
        </div>
    </div>
</div>