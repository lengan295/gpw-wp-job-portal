<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param module 		module name - optional
 * module => id
 *layouts => from which layouts
 */
?>
<?php
$html ='';
if ($module) {
	$html.= '<div id="wpjobportal-head">';
		switch ($layouts) {
			case 'controlpanel':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Dashboard', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link orange-bg button" href="admin.php?page=wpjobportal_job&wpjobportallt=formjob" title="'. esc_html(__('add job','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add Job','wp-job-portal')).'
	        			</a>
	        			<a class="wpjobportal-add-link button" href="admin.php?page=wpjobportal_job" title="'. esc_html(__('all jobs','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/all-jobs.png" alt="'. esc_html(__('all jobs','wp-job-portal')).'" />
	        				'. esc_html(__('All Jobs','wp-job-portal')).'
	        			</a>';
			break;
			case 'shortcodes':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Short Codes', 'wp-job-portal')).'</h1>';
			break;
			case 'help':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Help', 'wp-job-portal')).'</h1>';
			break;
			case 'jobtype':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Job Types', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_jobtype&wpjobportallt=formjobtype')).' title="'. esc_html(__('add new job type','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Job Type','wp-job-portal')).'
	        			</a>';
			break;
			case 'slug':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Slug', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='. wp_nonce_url(admin_url('admin.php?page=wpjobportal_slug&task=resetallslugs&action=wpjobportaltask'),'wpjobportal_slug_nonce').' title="'. esc_html(__('reset all','wp-job-portal')).'">
	        				'. esc_html(__('Reset All','wp-job-portal')).'
	        			</a>';
			break;
			case 'stats':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Stats', 'wp-job-portal')).'</h1>';
			break;
			case 'translations':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Translations', 'wp-job-portal')).'</h1>';
			break;
			case 'systemerror':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Error Log', 'wp-job-portal')).'</h1>';
			break;
			case 'shift':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Shifts', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_shift&wpjobportallt=formshift')).' title="'. esc_html(__('add new shift','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Shift','wp-job-portal')).'
	        			</a>';
			break;
			case 'age':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Age', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_age&wpjobportallt=formages')).' title="'. esc_html(__('add new age','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Age','wp-job-portal')).'
	        			</a>';
			break;
			case 'experience':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Experiences', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_experience&wpjobportallt=formexperience')).' title="'. esc_html(__('add new experience','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Experience','wp-job-portal')).'
	        			</a>';
			break;
			case 'currency':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Currency', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_currency&wpjobportallt=formcurrency')).' title="'. esc_html(__('add new currency','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Currency','wp-job-portal')).'
	        			</a>';
			break;
			case 'jobalert':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Job Alert', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_jobalert&wpjobportallt=formjobalert')).' title="'. esc_html(__('add new job alert','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Job Alert','wp-job-portal')).'
	        			</a>';
			break;
			case 'departmentque':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Departments Approval Queue', 'wp-job-portal')) .'</h1>';
			break;
			case 'department':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Departments', 'wp-job-portal')) .'</h1>
			        	<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_departments&wpjobportallt=formdepartment')).' title="'. esc_html(__('add new department','wp-job-portal')).'">
			        		<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
			        		'. esc_html(__('Add New Department','wp-job-portal')).'
		        		</a>';
			break;
			case 'company':
				$html.='<h1 class="wpjobportal-head-text">'.esc_html(__('Companies', 'wp-job-portal')).'</h1>
	    				<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_company&wpjobportallt=formcompany')).' title="'. esc_html(__('add new company','wp-job-portal')).'">
	    					<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	    					'. esc_html(__('Add New Company','wp-job-portal')).'
						</a>';
			break;
			case 'companyque':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Companies Approval Queue', 'wp-job-portal')).'</h1>';
			break;
			case 'joblist':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Jobs', 'wp-job-portal')) .'</h1>
	        			<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_job&wpjobportallt=formjob')).' title="'.esc_html(__('add new job','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'.esc_html(__('Add New Job','wp-job-portal')).'
	    				</a>';
			break;
			case 'jobapply':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Job Applied Resume', 'wp-job-portal')).'</h1>';
			break;
			case 'resume':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Resume', 'wp-job-portal')).'</h1>';
			break;
			case 'resumeque':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Resume Approval Queue', 'wp-job-portal')) .'</h1>';
			break;
			case 'jobapprovalque':
			 	$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Jobs Approval Queue', 'wp-job-portal')) .'</h1>';
			break;
			case 'age':
				$html.='<h1 class="wpjobportal-head-text">'.esc_html(__('Ages', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_age&wpjobportallt=formages')).' title="'. esc_html(__('add new age','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Age','wp-job-portal')).'
	    				</a>';
			break;
			case 'addnewage':
				$msg= isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New','wp-job-portal'));
	    		$html.='<h1 class="wpjobportal-head-text">'. $msg . ' ' . esc_html(__('Age', 'wp-job-portal')).'</h1>';
			break;
			case 'careerlevel':
				$html.= '<h1 class="wpjobportal-head-text">'.esc_html(__('Career Levels', 'wp-job-portal')).'</h1>';
	            $html.='<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_careerlevel&wpjobportallt=formcareerlevels')).' title="'. esc_html(__('add new career level','wp-job-portal')).'">
	            			<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	            			'. esc_html(__('Add New Career Level','wp-job-portal')).'
	        			</a>';
			break;
			case 'addnew':
				$heading = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New','wp-job-portal'));
	    		$html.='<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('Career Levels', 'wp-job-portal')).'</h1>';
			break;
	    	case 'categories':
	    	 	$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Categories', 'wp-job-portal')).'</h1>';
	        	$html.='<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_category&wpjobportallt=formcategory')).' title="'. esc_html(__('add new category','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Category','wp-job-portal')).'
	    				</a>';
			break;
			case 'addcategories':
	    	 	$heading = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
	        	$html.='<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('Category', 'wp-job-portal')).'</h1>';
			break;
			case 'city':
				$html.= '<h1 class="wpjobportal-head-text">'.esc_html(__('Cities', 'wp-job-portal')).'</h1>';
				$html.='<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_city&wpjobportallt=formcity')).' title="'. esc_html(__('add new city','wp-job-portal')).'">
							<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
							'. esc_html(__('Add New City','wp-job-portal')).'
						</a>';
			break;
			case 'cityadd':
				$heading = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
		        $html.='<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('City', 'wp-job-portal')).'</h1>';
			break;
			case 'countries':
	            $html.='<h1 class="wpjobportal-head-text">'.esc_html(__('Countries', 'wp-job-portal')).'</h1>';
	         	$html.='<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_country&wpjobportallt=formcountry')).' title="'. esc_html(__('add new country','wp-job-portal')).'">
	         				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	         				'. esc_html(__('Add New Country','wp-job-portal')).'
	     				</a>';
			break;
			case 'newcountry':
				$heading = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
		        $html.='<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('Country', 'wp-job-portal')).'</h1>';
			break;
			case 'highesteducation':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Educations', 'wp-job-portal')).'</h1>';
	            $html.='<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_highesteducation&wpjobportallt=formhighesteducation')).' title="'. esc_html(__('add new education','wp-job-portal')).'">
	            			<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	            			'. esc_html(__('Add New Education','wp-job-portal')).'
	        			</a>';
			break;
			case 'educationsts':
				$heading = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
		        $html.='<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('Education', 'wp-job-portal')).'</h1>';
			break;
			case 'jobstatus':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Job Status', 'wp-job-portal')).'</h1>
	        			<a class="wpjobportal-add-link button" href="?page=wpjobportal_jobstatus&wpjobportallt=formjobstatus" title="'. esc_html(__('add new job status','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Job Status','wp-job-portal')).'
	        			</a>';
			break;
			case 'jobstatusadd':
				$heading = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
		        $html.='<h1 class="wpjobportal-head-text">'.$heading . ' ' . esc_html(__('Job Status', 'wp-job-portal')).'</h1>';
			break;
			case 'salaryrangetype':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Salary Range Type', 'wp-job-portal')).'</h1>';
	        	$html.='<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_salaryrangetype&wpjobportallt=formsalaryrangetype')).' title="'. esc_html(__('add new salary range type','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Salary Range Type','wp-job-portal')).'
	    				</a>';
			break;
			case 'rangetypeadd':
				$heading = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
	        	$html.='<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('Salary Range Type', 'wp-job-portal')).'</h1>';
			break;
			case 'state':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('States', 'wp-job-portal')).'</h1>';
	    		$html.='<a class="wpjobportal-add-link button" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_state&wpjobportallt=formstate')).' title="'. esc_html(__('add new state','wp-job-portal')).'">
	    					<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	    					'. esc_html(__('Add New State','wp-job-portal')).'
						</a>';
			break;
			case 'jobs':
				$html.='<h1 class="wpjobportal-head-text">'. ($job ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'))) . ' ' . esc_html(__('Job', 'wp-job-portal')).'</h1>';
			break;
			case 'activitylog':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Activity Log', 'wp-job-portal')) .'</h1>';
			break;
			case 'addcompany':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Companies', 'wp-job-portal')).'</h1>
	    				<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_company&wpjobportallt=formcompany')).' title="'. esc_html(__('add new company','wp-job-portal')).'">
	    					<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	    					'. esc_html(__('Add New Company','wp-job-portal')).'
						</a>';
			break;
			case 'comp':
				$html.='<h1 class="wpjobportal-head-text">'.($company ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'))) . ' ' . esc_html(__('Company', 'wp-job-portal')).'</h1>';
	        break;
			case 'viewresume':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('View Resume', 'wp-job-portal')) .'</h1>';
			break;
			case 'formresume':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Resume', 'wp-job-portal')) .'</h1>';
			break;
			case 'users':
				$html.= '<h1 class="wpjobportal-head-text">'. esc_html(__('Users', 'wp-job-portal')) .'</h1>
					    <a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_user&wpjobportallt=assignrole')).' title="'. esc_html(__('assign role', 'wp-job-portal')) .'">
					    	<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
					    	'. esc_html(__('Assign role', 'wp-job-portal')) .'
				    	</a>';
			break;
			case 'assignrole':
				$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Assign Role', 'wp-job-portal')).'</h1>';
	        break;
			case 'userform':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Change Role', 'wp-job-portal')).'</h1>';
	    	break;
			case 'folder':
				$html.='<h1 class="wpjobportal-head-text">'. esc_html(__('Folders', 'wp-job-portal')).'</h1>';
				$html.='<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_folder&wpjobportallt=formfolder')).' title="'. esc_html(__('add new folder','wp-job-portal')).'">
							<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
							'. esc_html(__('Add New Folder','wp-job-portal')).'
						</a>';
	    	break;
			case 'folderstat':
				$heading = (isset($data) && !empty($data)) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
				$html.='<h1 class="wpjobportal-head-text">'. $heading. ' ' . esc_html(__('Folder', 'wp-job-portal')).'</h1>';
			break;
			case 'folder-que':
				$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Folders Approval Queue', 'wp-job-portal')).'</h1>';
	        break;
			case 'tag':
				$html .='<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('Tag', 'wp-job-portal')).'</h1>';
			break;
			case 'tags':
				$html .= '<h1 class="wpjobportal-head-text">'.esc_html(__('Tags', 'wp-job-portal')) .'</h1>
	        			<a class="wpjobportal-add-link button" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_tag&wpjobportallt=formtag')) .' title="'. esc_html(__('add new tag','wp-job-portal')).'">
	        				<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
	        				'. esc_html(__('Add New Tag','wp-job-portal')).'
	    				</a>';
			break;
			case 'message':
				$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Messages', 'wp-job-portal')).'</h1>';
			break;
			case 'messageque':
				$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Messages Approval Queue', 'wp-job-portal')).'</h1>';
			break;
			case 'messagedetail':
				$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Messages', 'wp-job-portal')).'</h1>';
	        break;
			case 'package':
				$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Package','wp-job-portal')).'</h1>';
				$html .= '<a class="wpjobportal-add-link" href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_package&wpjobportallt=formpackage')).' title="'. esc_html(__('add new package','wp-job-portal')).'">
							<img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/plus-icon.png" alt="'. esc_html(__('plus icon','wp-job-portal')).'" />
							'. esc_html(__('Add New Package','wp-job-portal')).'
						</a>';
			break;
			case 'formpackage':
				$html .='<h1 class="wpjobportal-head-text">';
					if($package) $html .= esc_html(__('Edit Package', 'wp-job-portal'));
					else $html .= esc_html(__('Add New Package', 'wp-job-portal'));
					$html .= '</h1>';
			break;
			case 'customfield':
				$heading = isset(wpjobportal::$_data[0]['fieldvalues']) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add', 'wp-job-portal'));
        		$html .= '<h1 class="wpjobportal-head-text">'. $heading . ' ' . esc_html(__('User Field', 'wp-job-portal')).'</h1>';
				break;
			case 'coverletter':
					$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Cover Letters', 'wp-job-portal')).'</h1>';
			break;
			case 'coverletterque':
					$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Cover Letter Approval Queue', 'wp-job-portal')).'</h1>';
			break;
			case 'addonstatus':
					$wpjobportal_addons_auto_update = wpjobportal::$_config->getConfigValue('wpjobportal_addons_auto_update');
					$html .='
						<div class="wpjobportal-head-addons-status-wrp">
							<h1 class="wpjobportal-head-text">'. esc_html(__('Addons Status', 'wp-job-portal')).'</h1>
							<div class="wpjobportal-head-addons-status-update">
								'. esc_html(__('Addon will automatically update to the newest version', 'wp-job-portal'));
								if($wpjobportal_addons_auto_update == 1 ){
									$html .= ' <a href="'.esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_configuration&task=saveautoupdateconfiguration&action=wpjobportaltask&wpjobportal_addons_auto_update=0'),'wpjobportal_configuration_nonce')).'" class="wpjobportal-head-addons-status-update-onbtn">'. esc_html(__('Auto Update: On', 'wp-job-portal')).'</a>';
								}else{
									$html .= ' <a href="'.esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_configuration&task=saveautoupdateconfiguration&action=wpjobportaltask&wpjobportal_addons_auto_update=1'),'wpjobportal_configuration_nonce')).'" class="wpjobportal-head-addons-status-update-offbtn">'. esc_html(__('Auto Update: Off', 'wp-job-portal')).'</a>';
								}

							$html .= '
							</div>
						</div>
					';
			break;
			case 'seooptions':
					$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('SEO', 'wp-job-portal')).'</h1>';
			break;
			case 'loadaddressdata':
					$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Load Address Data', 'wp-job-portal')).'</h1>';
			break;
			case 'importdata':
					$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Import Data', 'wp-job-portal')).'</h1>';
			break;
			case 'importresult':
					$html .='<h1 class="wpjobportal-head-text">'. esc_html(__('Import Data Report', 'wp-job-portal')).'</h1>';
			break;
		}
	$html.=  '</div>';
	echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
}
?>

