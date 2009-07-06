<?php
/**
 * Controller for admin section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @author Kevin Coffman <kwc@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2008, University of Michigan
 */

class Admin extends Controller {

	public function __construct()
	{
		parent::Controller();

		$this->freakauth_light->check('admin');

		$this->load->model('school');
		$this->load->model('curriculum');
		$this->load->model('subject');
		$this->load->model('course');
		$this->load->model('ocw_user');
	}

	public function index() { $this->home(); }

	/**
    * Display admin dashboard 
    *
    * @access  public
    * @return  void
    */
	public function home()
	{
		$data = array('title'=>'Admin', 'section'=>'home'); 
   	$this->layout->buildPage('admin/index', $data);
	}

	/**
    * manage users in the system
    *
    * @access  public
		* @params  string action 	what to do
		* @params  string type 		which type of users to manage
		* @params  int 		uid 	  user id	
    * @return  void
    */
	public function users($action='view', $type='instructor', $uid='')
	{
			$roles = array('admin'=>'Administrator','dscribe1'=>'dscribe1',
									   'dscribe2'=>'dscribe2','instructor'=>'Instructor');

			// add user:
			if ($action=='add_user') {
					$data = array('title'=>'Admin: Manage Users &rqauo; Add Users',
												'defuser'=>$type, 'roles' => $roles,'name'=>'',
												'role'=>'','user_name'=>'','email'=>'');

					// user wants to add a new user
					if (isset($_POST['submit'])) {
							$files = (isset($_FILES['profile'])) ? $_FILES : '';
			 				$r = $this->ocw_user->add_user($_POST,$files);

			 				if ($r===true) { $r = 'User added'; }

			 				flashMsg($r);
       				redirect('admin/users/add_user/'.$type, 'location');
					}

					// show add user form
					$this->load->view(property('app_views_path').'/admin/users/_add_user',$data); 

			} elseif ($action=='remove_user') {
							$r = $this->ocw_user->remove_user($uid);
							$r = ($r===true) ? 'Removed user' : $r;
			 				flashMsg($r);
       				redirect('admin/users/view/'.$type, 'location');

			} elseif ($action=='editprofile') {
					// user wants to upaate an existing user's profile
					if (isset($_POST['submit'])) {
							$task = $_POST['task'];
							unset($_POST['task']); unset($_POST['submit']);

							$files = (isset($_FILES['profile'])) ? $_FILES : '';
							$r = ($task=='update') 
								 ?  $this->ocw_user->update_profile($uid,$_POST['profile'],$files)
								 :  $this->ocw_user->add_profile($uid,$_POST['profile'],$files);

			 				$r = ($r===true) ? (($task=='update') ? 'User profile updated': 'User profile added') : $r;
			 				flashMsg($r);
       				redirect('admin/users/editprofile/'.$type.'/'.$uid, 'location');
					}

					$data = array();
					$data['uid'] = $uid;
					$data['roles'] = $roles;
					$data['defuser'] = $type;
  				$data['profile'] = $this->ocw_user->profile($uid);
					$this->load->view(property('app_views_path').'/admin/users/_edit_profile',$data); 

			} elseif ($action=='editinfo') {
					// user wants to upaate an existing user's information
					if (isset($_POST['submit'])) {
							unset($_POST['submit']);
			 				$r = $this->ocw_user->update_user($uid,$_POST);

			 				if ($r===true) { $r = 'User updated'; }

			 				flashMsg($r);
       				redirect('admin/users/editinfo/'.$type.'/'.$uid, 'location');
					}

					$data = array();
					$data['roles'] = $roles;
					$data['defuser'] = $type;
  				$data['user'] = $this->ocw_user->get_user_by_id($uid);
					$this->load->view(property('app_views_path').'/admin/users/_edit_info',$data); 

			// edit user course information & dscribe1/dscribe2 relationships
			} elseif ($action=='edit') {
  				$u = $this->ocw_user->get_user_by_id($uid);

					if ($u !== false) {

							if (isset($_POST['submit'])) {
									$task = $_POST['task'];
									unset($_POST['task']); unset($_POST['submit']);

									// assign a user to a course
									if ($task=='addcourse') {
											if (isset($_POST['cid']) && is_numeric($_POST['cid'])) {
													if ($this->ocw_user->has_role($uid, $_POST['cid'], $u['role'])) {
															$r = "Error: User is already a(n) {$u['role']} for this course.";
													} else {
															$d = array('user_id'=>$uid,'role'=>$u['role'],'course_id'=>$_POST['cid']);
															$r = $this->course->add_user($d);	
													}
											} else {
													$r = 'Error: Please pick a valid course.';
											}
											if ($r===true) { $r = 'User has been assigned course'; }

									// unassign a user to a course
									} elseif ($task=='removecourse') {
											if (isset($_POST['cid']) && is_numeric($_POST['cid'])) {
													if ($this->ocw_user->has_role($uid, $_POST['cid'], $u['role'])) {
															$r = $this->course->remove_user($_POST['cid'],$uid,$u['role']);	
													} else {
															$r = "Error: User is not  a(n) {$u['role']} for this course.";
													}
											} else {
													$r = 'Error: Please pick a valid course.';
											}
											if ($r===true) { $r = 'User has been unassigned from course'; }

									// assign a dscribe1 to a dscribe2
									} elseif ($task=='assigndscribe1') {
											if (isset($_POST['dsid']) && is_numeric($_POST['dsid'])) {
															$r = $this->ocw_user->set_relationship($_POST['dsid'],$uid);	
											} else {
													$r = 'Error: Please pick a valid dscribe1.';
											}
											if ($r===true) { $r = 'dScribe1 has been assigned'; }
											
									// unassign a dscribe1 to a dscribe2
									} elseif ($task=='unassigndscribe1') {
											if (isset($_POST['dsid']) && is_numeric($_POST['dsid'])) {
															$r = $this->ocw_user->set_relationship($_POST['dsid'],$uid,'unassign');	
											} else {
													$r = 'Error: Please pick a valid dscribe1.';
											}
											if ($r===true) { $r = 'dScribe1 has been unassigned'; }
									}

			 						flashMsg($r);
       						redirect('admin/users/edit/'.$type.'/'.$uid, 'location');
							}

							$courses = $this->course->get_courses(getUserProperty('id'),'admin');	

							// get all courses
							$select_box = '<select id="cid" name="cid" width="200px">';
							$select_box .= '<option value="none">Choose a course</option>';
							foreach($courses as $school => $curriculum) {
											$select_box .= '<optgroup label="'.$school.'">';
        							foreach($curriculum as $crse) {
          										foreach($crse as $c) {
                  										$select_box .= '<option value="'.$c['id'].'">'.
                                  		$c['number'].' '.$c['title'].'</option>';
          										}
    									}
    									$select_box .= '</optgroup>';
							}
							$select_box .= '</select>';

							
							$data = array('title'=>'Admin: Manage Users &raquo; '.$u['name'],'user'=>$u,
														'section'=>'users','tab'=>$type);	
							$data['defuser'] = $type;
							$data['select_courses'] = $select_box;

							if ($u['role'] == 'dscribe2') {
									// get all dscribe1s
									$data['dscribes'] = $this->ocw_user->get_users_by_relationship($uid, 'dscribe1');
									$all_dscribes = $this->ocw_user->getUsers('id, email, name, user_name',null,'role="dscribe1"'); 
									$ds_select_box = '';
									if ($all_dscribes != null) {
				    					$ds_select_box = '<select id="dsid" name="dsid" width="200px">';
				    					$ds_select_box .= '<option value="none">Choose a dScribe...</option>';
				    					foreach($all_dscribes as $d) {
													if (is_array($data['dscribes'])) {
															if (!in_array($d['id'], $data['dscribes'])) {
				                					$ds_select_box .= '<option value="'.$d['id'].'">'.
				                              $d['name'].' ('.$d['user_name'].')</option>';
															}
													} else {
				                					$ds_select_box .= '<option value="'.$d['id'].'">'.
				                              $d['name'].' ('.$d['user_name'].')</option>';
													}
				    					}
				    					$ds_select_box .= '</select>';
									}
									$data['select_dscribes'] = $ds_select_box;
							}

							//$data['courses'] = $this->ocw_user->get_courses($uid);
							$data['courses'] = $this->course->get_courses($uid,getUserPropertyFromId($uid, 'role'));
							$this->layout->buildPage('admin/users/_edit_'.$u['role'],$data); 
					} else {
			 				flashMsg('This user does not exist...');
       				redirect('admin/users/view/'.$type, 'location');
					}	

			} else {
			
					$users = $this->ocw_user->getUsers();
					$num_inst = $num_d1 = $num_d2 = $num_admin = 0;
		
					// get user counts and arrange users by roles	
					if (!is_null($users)) {
							$tmp = array();
							foreach($users as $user) {
											$tmp[$user['role']][] = $user; 
											switch($user['role']) {
													case 'instructor': $num_inst++; break;
													case 'dscribe1': $num_d1++; break;
													case 'dscribe2': $num_d2++; break;
													case 'admin': $num_admin++; break;
											}
							}
							$users = $tmp;
					}
		
					$data = array(
										'title'=>'Admin: Manage Users',
										'section' => 'users',
										'tab' => $type,
										'users' => $users,
										'inst_title' => "Instructors ($num_inst)",
										'd1_title' => "dScribe1 ($num_d1)",
										'd2_title' => "dScribe2 ($num_d2)",
										'admin_title' => "Administrators ($num_admin)",
									); 
		   		$this->layout->buildPage('admin/users/index', $data);
		}
	}

	/**
    * manage schools in the system
    *
    * @access  public
    * @return  void
    */
	public function schools($action='view', $sid='')
	{
		if ($action=='add_school') {
			// add a new school
			if (isset($_POST['submit'])) {
				if (isset($_POST['name']) && $_POST['name'] <> '') {
					$description = (isset($_POST['description']) && trim($_POST['description']) <> '') ? trim($_POST['description']) : '';
	 				$r = $this->school->add($_POST['name'], $description);
				} else
					$r = "The school name is required";

	 			if ($r===true)
					$r = "Successfully added '" . $_POST['name'] ."'";

				flashMsg($r);
   			redirect('admin/schools/add_school/', 'location');
			}

			// show add school form
			$data = array('title'=>'Admin: Manage Schools &rqauo; Add a School');
			$this->load->view(property('app_views_path').'/admin/schools/_add_school',$data); 

		} else if ($action=='edit_school') {
			// edit a school
			if (isset($_POST['submit'])) {
				unset($_POST['submit']);
				if (isset($_POST['name']) && trim($_POST['name']) <> '') {
					if (isset($_POST['description']))
						$_POST['description'] = trim($_POST['description']);
 					$r = $this->school->update($sid, $_POST);
				} else
					$r = "The school name is required";

 				if ($r===true)
					$r = "Successfully modified '" . $_POST['name'] ."'";

				flashMsg($r);
				redirect('admin/schools/edit_school/' . $sid, 'location');
			}

			// show edit school form
			$data = array('title'=>'Admin: Manage Schools &rqauo; Edit a School',
										'school' => $this->school->get_school($sid));
			$this->load->view(property('app_views_path').'/admin/schools/_edit_school', $data); 
			
		} else if ($action=='remove_school') {
			// remove a school
			$sname = $this->school->name($sid);
			
			$r = $this->school->remove($sid);
			if ($r === true) { $r = "Successfully deleted '" . $sname . "'"; }
			flashMsg($r);
			redirect('admin/schools/view', 'location');

		} else {
			// view all schools
			$schools = $this->school->get_schools();

			$data = array('title'=>'Admin: Manage Schools ',
										'section'=>'schools',
										'tab'=>'',
										'schools' => $schools);	

   		$this->layout->buildPage('admin/schools/schools', $data);
		}
	}

	/**
    * manage curriculum in the system
    *
    * @access  public
    * @return  void
    */
	public function curriculum($action='view', $sid, $currid='')
	{
		if ($action=='add_curriculum') {

			// add a curriculum

			if (isset($_POST['submit'])) {
				if (isset($_POST['name']) && $_POST['name'] <> '') {
					$description = (isset($_POST['description']) && $_POST['description'] <> '') ? $_POST['description'] : '';
	 				$r = $this->curriculum->add($sid, $_POST['name'], $description);
				} else
					$r = "The curriculum name is required";

	 			if ($r===true)
					$r = "Successfully added '" . $_POST['name'] ."'";

				flashMsg($r);
   			redirect('admin/curriculum/add_curriculum/' . $sid, 'location');
			}

			// show add curriculum form
			$data = array('title'=>'Admin: Manage Schools &rqauo; Add a Curriculum',
										'sid' => $sid,
										'sname' => $this->school->name($sid));
			$this->load->view(property('app_views_path').'/admin/schools/_add_curriculum',$data); 

		} else if ($action=='edit_curriculum') {

			// edit a curriculum

			if (isset($_POST['submit'])) {
				unset($_POST['submit']);
				if (isset($_POST['name']) && trim($_POST['name']) <> '') {
					if (isset($_POST['description']))
						$_POST['description'] = trim($_POST['description']);
 					$r = $this->curriculum->update($currid, $_POST);
				} else
					$r = "The curriculum name is required";

 				if ($r===true)
					$r = "Successfully modified '" . $_POST['name'] ."'";

				flashMsg($r);
				redirect('admin/curriculum/edit_curriculum/' . $sid .'/' . $currid, 'location');
			}

			// show edit curriculum form
			$data = array('title'=>'Admin: Manage Schools &rqauo; Edit a Curriculum',
										'sid' => $sid,
										'curr' => $this->curriculum->get_curriculum($currid));
			$this->load->view(property('app_views_path').'/admin/schools/_edit_curriculum', $data);
 
		} else if ($action=='remove_curriculum') {

			// remove a curriculum
			
			$r = $this->curriculum->remove($currid);
			$r = ($r===true) ? 'Removed curriculum' : $r;
			flashMsg($r);
			redirect('admin/curriculum/view/' . $sid, 'location');

		} else {

			// view school's subjects and curriculum

			$sname = $this->school->name($sid);
			$currlist = $this->curriculum->get_curriculum_list($sid);
			$subjlist = $this->subject->get_subjects($sid);
			
			$data = array('title'=>'Admin: Manage Schools ',
										'section'=>'schools',
										'tab'=>'',
										'sid' => $sid,
										'sname' => $sname,
										'currlist' => $currlist,
										'subjlist' => $subjlist );
			$this->layout->buildPage('admin/schools/curriculum', $data);
		}
  }
	
	/**
		* manage subjects in the system
		*
		* @access  public
		* @return  void
		*/
	public function subjects($action, $sid, $subjid='')
	{
		if ($action=='add_subject') {

			// add a subject

			if (isset($_POST['submit'])) {
				$r = "Error adding Subject!";
				if (!isset($_POST['subj_code']) || $_POST['subj_code'] == '') {
					$r = "The subject code is required";
				} else if (!isset($_POST['subj_desc']) || $_POST['subj_desc'] == '') {
					$r = "The subject description is required";
				} else {
	 				$r = $this->subject->add($sid, $_POST['subj_code'], $_POST['subj_desc']);
				}

	 			if ($r===true)
					$r = "Successfully added '" . $_POST['subj_code'] . ":" . $_POST['subj_desc'] . "'";

				flashMsg($r);
   			redirect('admin/subjects/add_subject/' . $sid, 'location');
			}

			// show add subject form
			$data = array('title'=>'Admin: Manage Schools &rqauo; Add a Subject',
										'sid' => $sid,
										'sname' => $this->school->name($sid));
			$this->load->view(property('app_views_path').'/admin/schools/_add_subject',$data); 

		} else if ($action=='edit_subject') {

			// edit a subject

			if (isset($_POST['submit'])) {
				unset($_POST['submit']);
				$r = "Error updating Subject!";
				if (!isset($_POST['subj_code']) || trim($_POST['subj_code']) == '') {
					$r = "The subject code is required";
				} else	if (!isset($_POST['subj_desc']) || trim($_POST['subj_desc']) == '') {
					$r = "The subject description is required";
				} else {
 					$r = $this->subject->update($subjid, $_POST);						
				}

 				if ($r===true)
					$r = "Successfully modified '" . $_POST['subj_code'] . ":" . $_POST['subj_desc'] . "'";

				flashMsg($r);
				redirect('admin/subjects/edit_subject/' . $sid .'/' . $subjid, 'location');
			}

			// show edit subject form
			$data = array('title'=>'Admin: Manage Schools &rqauo; Edit a Subject',
										'sid' => $sid,
										'subj' => $this->subject->get_subject($subjid));
			$this->load->view(property('app_views_path').'/admin/schools/_edit_subject', $data);

		} else if ($action=='remove_subject') {

			// remove a subject

			$r = $this->subject->remove($subjid);
			$r = ($r===true) ? 'Removed subject' : $r;
			flashMsg($r);
			redirect('admin/curriculum/view/' . $sid, 'location');

		}
  }

	/**
		* manage courses in the system
		*
		* @access  public
		* @return  void
		*/
	public function courses($action='view', $cid='', $school=0, $term=0, $year=0, $dscribe2=0, $dscribe=0)
	{
		$this->load->library('oer_faceted_search');
		
		if ($action == 'remove_course') {

			// remove a course
			$this->course->remove_course($cid);
			
			redirect('admin/courses/view/', 'location');

		} else if ($action == 'manage_users') {

			$r = true;
			if (isset($_POST['remove']) || isset($_POST['add'])) {
				if (isset($_POST['remove'])) {
					unset($_POST['remove']);
				
					$uid = $_POST['rem_id'];
					if ($uid != "none") {
						$role = getUserPropertyFromId($uid, 'role');
						$r = $this->course->remove_user($cid, $uid, $role);
					} else $r = "Select a user";

				} else if (isset($_POST['add'])) {

					unset($_POST['add']);
					$uid = $_POST['add_id'];
					if ($uid != "none") {
						$role = getUserPropertyFromId($uid, 'role');
						$d = array('user_id'=>$uid, 'role'=>$role, 'course_id'=>$cid);
						$r = $this->course->add_user($d);
					} else $r = "Select a user";
				}
			
				// Flash only error messages
				if ($r !== true)
					flashMsg($r);
				redirect('admin/courses/manage_users/' . $cid);
			}
			
			// get list of users (acl entries) already associated with the course
			$cacls = $this->course->get_course_users($cid);

			// get list of all users
			$users = $this->ocw_user->getUsers('id, name, user_name, role');

			// filter out users that are already associated with the course
			$remove_list = array();
			$add_list = array();

			$rem_select_box = '<select id="rem_id" name="rem_id" width="200px">';
			$rem_select_box .= '<option value="none">Remove a user...</option>';
			$add_select_box = '<select id="add_id" name="add_id" width="200px">';
			$add_select_box .= '<option value="none">Add a user...</option>';

			$usertype = array("dscribe1", "dscribe2", "instructor");
			
			// Note that this loops through the list multiple times, but we
			// only go to the DB once (well, once for users and once for acls)
			foreach($usertype as $t) {
				$add_select_box .= '<optgroup label="' . $t . '">';
				$rem_select_box .= '<optgroup label="' . $t . '">';
				if (!is_null($users)) {
					foreach($users as $u) {
						if ($u['role'] != $t) continue;
						$associated = false;
						if (!is_null($cacls)) {
							foreach($cacls as $a) {
								if ($a['user_id'] == $u['id']) {
									$rem_select_box .= '<option value="'. $u['id'] . '">' .
											$u['name'] . ' (' . $u['user_name'] . ')</option>';
									$remove_list[] = $u;
									$associated = true;
									break;
								}
							}
						}
						if ($associated === false) {
							$add_select_box .= '<option value="'. $u['id'] . '">' .
									$u['name'] . ' (' . $u['user_name'] . ')</option>';
							$add_list[] = $u;
						}
					}
				}
				$add_select_box .= '</optgroup>';
				$rem_select_box .= '</optgroup>';
			}
			$data = array('title' => 'Admin: Manage Course Users ',
										'section' => 'courses',
										'tab' => '',
										'rem_box' => $rem_select_box,
										'add_box' => $add_select_box,
										'cid' => $cid,
										'ctitle' => $this->course->course_title($cid));
			$this->load->view(property('app_views_path').'/courses/_manage_course_users', $data);
			
		} else {
			
			// view all courses
			$courses = $this->course->new_get_courses(getUserProperty('id'));
			
			// get faceted search choices
			$facet_options = $this->oer_faceted_search->get_facet_options($courses);
      
      // do filtering based on facet arguments
      $facet_filters = array(
        'school_id' => $school,
        'term' => $term,
        'year' => $year,
        'dscribe2s' => $dscribe2,
        'dscribe1s' => $dscribe
        );
      
      $this->oer_faceted_search->do_course_facet_filtering(
        $courses, $facet_filters);
      
			$data = array('title'=>'Admin: Manage Courses ',
										'section'=>'courses',
										'tab'=>'',
										'courses' => $courses,
										'facet_options' => $facet_options);
										
			$data['defuser'] = '';
      $data["controller_args"] = array('view', '0');
			$this->layout->buildPage('admin/courses/index', $data);

		}
	}

	/**
	 * Display a profile image for a user
	 *
	 * @param int uid userid
	 * @return image
	 */
	public function profileimage($uid)
	{
		 $profile = $this->ocw_user->profile($uid);
     $nopic = ($profile==false || $profile['imagefile']=='') ? true : false;
            
      if ($nopic) {
            $path = property('app_img').'/noprofile.jpg'; 
            $image = imagecreatefromjpeg($path);
            $image_type = 'jpg';
        } else {
            $image = $profile['imagefile']; 
            $image_type = $profile['imagetype'];
        }

        switch (strtolower($image_type))
        {
                case 'jpg':
                case 'jpeg': $content= 'image/jpeg'; break;
                case 'gif':  $content= 'image/gif'; break;
                case 'png': $content= 'image/png'; break;
                case 'tiff': 
                case 'tif':$content= 'image/tiff'; break;
                case 'svg':
                case 'svgz':$content='image/svg+xml';break;
                default: $content='image/jpeg';
        }

        header('content-type: '.$content);
        if ($nopic) {
            imagejpeg($image);  unset($image);
        } else {
            print stripslashes($image);
        }
        exit;
	}
}
?>
