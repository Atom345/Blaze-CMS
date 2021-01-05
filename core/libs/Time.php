<?php

/*
-------------------------------------------------
Phoenix CMS | Copyright (c) 2020-2025           
-------------------------------------------------
Author: Phoenix CMS Team
-------------------------------------------------
Phoenix CMS and its corresponding files are all 
licensed under the GPL 3.0 lisence.
--------------------------------------------------
*/

namespace Phoenix\Time;

use Phoenix\Hooks\Hooks;

class Time{
	 
	public static function get_time(){
		$timezone = get_settings_from_key('time_zone');
		$tz_obj = new \DateTimeZone($timezone);
		$today = new \DateTime("now", $tz_obj);
		$today_formatted = $today->format('m-d-y');
		
        $date = new \DateTime("now", new \DateTimeZone($timezone));
		return $date->format('Y-m-d H:i:s');	
		
	}
	
	public static function get_time_difference($created_time){
		
        $str = strtotime($created_time);
        $today = strtotime(date('Y-m-d H:i:s'));

        $time_differnce = $today-$str;
        
        $years = 60*60*24*365;

        $months = 60*60*24*30;

        $days = 60*60*24;

        $hours = 60*60;

        $minutes = 60;

        if(intval($time_differnce/$years) > 1)
        {
            return intval($time_differnce/$years)." years ago";
        }else if(intval($time_differnce/$years) > 0)
        {
            return intval($time_differnce/$years)." year ago";
        }else if(intval($time_differnce/$months) > 1)
        {
            return intval($time_differnce/$months)." months ago";
        }else if(intval(($time_differnce/$months)) > 0)
        {
            return intval(($time_differnce/$months))." month ago";
        }else if(intval(($time_differnce/$days)) > 1)
        {
            return intval(($time_differnce/$days))." days ago";
        }else if (intval(($time_differnce/$days)) > 0) 
        {
            return intval(($time_differnce/$days))." day ago";
        }else if (intval(($time_differnce/$hours)) > 1) 
        {
            return intval(($time_differnce/$hours))." hours ago";
        }else if (intval(($time_differnce/$hours)) > 0) 
        {
            return intval(($time_differnce/$hours))." hour ago";
        }else if (intval(($time_differnce/$minutes)) > 1) 
        {
            return intval(($time_differnce/$minutes))." minutes ago";
        }else if (intval(($time_differnce/$minutes)) > 0) 
        {
            return intval(($time_differnce/$minutes))." minute ago";
        }else if (intval(($time_differnce)) > 1) 
        {
            return intval(($time_differnce))." seconds ago";
        }else
        {
            return "few seconds ago";
        }
  }
	
}

?>