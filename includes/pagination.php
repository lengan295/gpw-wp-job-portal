<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALpagination {

    static $_limit;
    static $_offset;
    static $_currentpage;

    static function setLimit($limit){
        if(is_numeric($limit))
            self::$_limit = $limit;
    }

    static function getLimit(){
        return (int) self::$_limit;
    }


    static function getPagination($total,$searchlayout = null){
        if(!is_numeric($total)) return false;

        $pagenum = absint(wpjobportal::wpjobportal_sanitizeData(WPJOBPORTALrequest::getVar('pagenum','get',1)));
        if(!self::getLimit()){
            $limit = wpjobportal::$_configuration['pagination_default_page_size'];
            if($limit != ''){
                $limit = ceil(wpjobportal::$_configuration['pagination_default_page_size']);
            }else{
                $limit = 10;
            }
            self::setLimit($limit); // number of rows in page
        }
        self::$_offset = ( $pagenum - 1 ) * self::$_limit;
        self::$_currentpage = $pagenum;
        $num_of_pages = ceil($total / self::$_limit);
        $result = '';
        if(is_admin()){
            $result = paginate_links(array(
                'base' => add_query_arg('pagenum', '%#%'),
                'format' => '',
                'prev_next' => true,
                'prev_text' => esc_html(__('Previous', 'wp-job-portal')),
                'next_text' => esc_html(__('Next', 'wp-job-portal')),
                'total' => $num_of_pages,
                'current' => $pagenum,
                'add_args' => false,
            ));
        }else{
            if(wpjobportal::$theme_chk == 1) {
                $links = paginate_links( array(
                    'type' => 'array',
                    'base' => add_query_arg('pagenum', '%#%'),
                    'format' => '',
                    'prev_next' => true,
                    'prev_text' => esc_html(__('Previous', 'wp-job-portal')),
                    'total' => $num_of_pages,
                    'current' => $pagenum,
                    'next_text' => esc_html(__('Next', 'wp-job-portal')),
                    'add_args' => false,
                ));
                if(!empty($links) && is_array($links)){
                    $result = '<ul class="pagination pagination-lg">';
                    foreach($links AS $link){
                        if(wpjobportalphplib::wpJP_strstr($link, 'current')){
                            $result .= '<li class="active">'.$link.'</li>';
                        }else{
                            $result .= '<li>'.$link.'</li>';
                        }
                    }
                    $result .= '</ul>';
                }
            }else{
                if($searchlayout != null && get_option( 'permalink_structure' ) != ""){
                    $layargs = add_query_arg(array('pagenum'=>'%#%' , 'wpjobportallay'=>$searchlayout));
                }else{
                    $layargs = add_query_arg(array('pagenum'=>'%#%'));
                }
                $result = paginate_links(array(
                            'base' => $layargs,
                            'format' => '',
                            'prev_next' => true,
                            'prev_text' => esc_html(__('Previous', 'wp-job-portal')),
                            'next_text' => esc_html(__('Next', 'wp-job-portal')),
                            'total' => $num_of_pages,
                            'current' => $pagenum,
                            'add_args' => false,
                        ));
           }

        }
        return $result;
    }

    static function isLastOrdering($total, $pagenum) {
        $maxrecord = $pagenum * wpjobportal::$_configuration['pagination_default_page_size'];
        if ($maxrecord >= $total)
            return false;
        else
            return true;
    }

}

?>
