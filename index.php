<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details
 *
 * @package    block
 * @subpackage 3d usersprofile
 * @copyright  2013 eabyas <eabyas.in>
 * @author     Niranjan Reddy <niranjan@eabyas.in>  
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
?>

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->

 
<link rel="stylesheet" href="css/idangerous.swiper.css">
 
<link rel="stylesheet" href="css/idangerous.swiper.3dflow.css">

<script src="js/libs/jquery-1.7.1.min.js"></script>
 
<script src="js/idangerous.swiper-1.8.min.js"></script>
 
<script src="js/idangerous.swiper.3dflow-1.2.js"></script>

<style>.appstore.swiper-container{padding:30px 0;}.appstore.swiper-container,.appstore .swiper-slide{width:auto;height:161px;}.appstore .swiper-slide{background-size:cover;background-repeat:no-repeat;background-position:center;border-radius:5px;border-bottom:1px solid #555;
}.real-reflection{-webkit-box-reflect:below 1px -webkit-linear-gradient(bottom,rgba(0,0,0,0.5) 0px,rgba(0,0,0,0) 20px);}.appstore .reflection{width:100%;height:15px;border-radius:3px 3px 0 0;position:absolute;left:0;bottom:-17px;background-image:-webkit-gradient(linear,left top,left bottom,from(rgba(0,0,0,0.3)),to(rgba(0,0,0,0)));background-image:-webkit-linear-gradient(top,rgba(0,0,0,0.3),rgba(0,0,0,0));background-image:-moz-linear-gradient(top,rgba(0,0,0,0.3),rgba(0,0,0,0));background-image:-o-linear-gradient(top,rgba(0,0,0,0.3),rgba(0,0,0,0));background-image:linear-gradient(to bottom,rgba(0,0,0,0.3),rgba(0,0,0,0));
}.appstore .swiper-slide a{position:absolute;left:0;top:0;width:100%;height:100%;z-index:1}.anyhtml.swiper-container{padding:30px 0;}.anyhtml.swiper-container,.anyhtml .swiper-slide{width:auto;height:161px;}.anyhtml .swiper-slide{background:#eee;text-align:center;font-size:25px;color:#222;line-height:161px;font-weight:bold;}.td7.swiper-container,.td7 .swiper-slide,.td72.swiper-container,.td72 .swiper-slide{width:auto;height:100%;}.td8.swiper-container,.td8 .swiper-slide{width:auto;height:210px;}.td8.swiper-container{padding:0;}.td8 .swiper-slide{background:#fff;line-height:70px;font-size:18px;}.swiper-scrollbar{width:100%;height:8px;margin:20px 0;}</style>
<script>
/* 3D Flow demos */
$(function(){
	
	$(".swiper-wrapper").mousemove(function(event) {
	 $(".swiper-slide").each(function (index){
  var x=this.style.zIndex;;
 
if(x ==1) {
//alert(x);
//alert("Test this : " + $(this).attr('desc'));
	 var msg=$(this).attr('desc');
	// alert(msg);
	  $("#desc").html("<div>" + msg + "</div>");
}
});
});
	//Stretched Up
	$('.td2').swiper({
		slidesPerSlide:3,
		loop:true,
		//Enable 3D Flow
		tdFlow: {
			rotate : 30,
			stretch :10,
			depth: 150,
			modifier : 1,
			shadows:true
		}
	})

})
</script>

<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->libdir.'/filelib.php');
global $USER,$PAGE;
$contextid=optional_param('contextid',0,PARAM_INT);
 $PAGE->set_url('/blocks/usersprofile/index.php?'.$contextid);
//check the context level of the user and check weather the user is login to the system or not
if ($contextid) {
        $context = context::instance_by_id($contextid, MUST_EXIST);
        if ($context->contextlevel != CONTEXT_COURSE) {
            print_error('invalidcontext');
        }
        $course = $DB->get_record('course', array('id'=>$context->instanceid), '*', MUST_EXIST);
    } else {
        $course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
        $context = context_course::instance($course->id, MUST_EXIST);
    }
    // not needed anymore
  //  unset($contextid);
    unset($courseid);

    require_login($course);

    $systemcontext = context_system::instance();
    $isfrontpage = ($course->id == SITEID);

    $frontpagectx = context_course::instance(SITEID);

//$PAGE->set_url('/block/usersprofile/index.php');
// if ($isfrontpage) {
//     //   $PAGE->set_pagelayout('admin');
//        
//    } else {
  $PAGE->set_pagelayout('incourse');
       
  //  }
//Header and the navigation bar
$PAGE->set_heading($SITE->fullname);
$PAGE->navbar->add(get_string('usersprofile', 'block_usersprofile'));
//echo $OUTPUT->header();
echo $OUTPUT->header();
//Heading of the page
echo $OUTPUT->heading(get_string('usersprofile', 'block_usersprofile'));
  list($ccselect, $ccjoin) = context_instance_preload_sql('u.id', CONTEXT_USER, 'ctx');
 $joins[] = $ccjoin;
 $from = implode("\n", $joins);
 if ($isfrontpage) {
       $sql="SELECT u.* FROM {user} u JOIN (SELECT DISTINCT eu1_u.id FROM {user} eu1_u WHERE eu1_u.deleted = 0 AND eu1_u.id >1) e ON e.id = u.id  $from  ";
      
 }
 else {
   $sql="SELECT u.*,ra.roleid FROM {user} u,{role_assignments} ra where ra.contextid={$contextid} AND ra.userid=u.id";
  
 }

$userlist=$DB->get_recordset_sql($sql);;

echo '<div class="swiper-container appstore td2">
<div class="swiper-wrapper">';


foreach($userlist as $users){
    
    $details = '<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">First Name: </label>'.$users->firstname .'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Last Name: </label>'.$users->lastname .'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Email: </label>'.$users->email .'</div>';
  //  $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Role: </label>'.$users->roleid .'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Department: </label>'.$users->department .'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Country: </label>'.get_string($users->country, 'countries') .'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">City: </label>'.$users->city.'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Address: </label>'.$users->address .'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Phone No1: </label>'.$users->phone1 .'</div>';
    $details .='<div style="text-align:left;padding-left:80px;padding-top:10px"><label style="font-weight:bold">Phone No2: </label>'.$users->phone2 .'</div>';


//print_object($users);
$user_picture=new user_picture($users);
$src=$user_picture->get_url($PAGE);


echo '<div class="swiper-slide real-reflection" style="background-image:url(pix/gradiant.jpg);height:650px !important">
<div style="text-align:center;"><img src="'.$src.'" height="55px" width="55px" style="padding:10px;"alt="users Profile"></div> '.$details.'
<br/><div style="text-align:center;"> <a  style="color:#076bd0" href="'.$CFG->wwwroot.'/message/index.php?id='.$users->id.'" target="_blank">'.get_string('messageselectadd').'</a>
 </div>
</div>';

}
echo '</div>
</div>
<div style="height:200px;"> </div>';
echo '<div id="page-footer"> <p class="helplink">';
echo page_doc_link(get_string('moodledocslink'));
echo '</p> ';
echo $OUTPUT->login_info();
echo $OUTPUT->home_link();
echo $OUTPUT->standard_footer_html();
echo '</div><div class="clearfix"></div></div>';

?>

