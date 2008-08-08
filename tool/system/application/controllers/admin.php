<?php
/**
 * Controller for admin section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Admin extends Controller {

	public function __construct()
	{
		parent::Controller();

		$this->freakauth_light->check('admin');

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
		$data = array('title'=>'Admin'); 
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

							$courses = $this->course->get_courses();	

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

							$data['courses'] = $this->ocw_user->get_courses($uid);
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
    * Display courses dashboard 
    *
    * @access  public
    * @return  void
    */
	public function courses()
	{
		$data = array('title'=>'Admin: Manage Courses'); 
   	$this->layout->buildPage('admin/courses/index', $data);
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
