<?php

class Exhibition_Controller extends Base_Controller {

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/

	public $restful = true;

	public function __construct(){

		$this->crumb = new Breadcrumb();

		date_default_timezone_set('Asia/Jakarta');
	}


	public function get_index(){

		$this->crumb->add('exhibition','Visitor Registration');

		$form = new Formly();
		$form->framework = 'zurb';

		$exhibitor = new Exhibitor();

		$golfcount = $exhibitor->count(array('golf'=>'Yes'));

		return View::make('exhibition.new')
					->with('form',$form)
					->with('golfcount',$golfcount)
					->with('crumb',$this->crumb)
					->with('title','Convention Registration');

	}



	public function get_payment($type){

		if(!Auth::exhibitor()){
			return Redirect::to('/');
		}

		$this->crumb->add('exhibition/payment/'.$type,ucfirst($type).' Payment Confirmation');

		$att = new Exhibitor();

		//print_r(Auth::exhibitor());

		$confirm = new Confirmation();

		$confirmdata = $confirm->get(array('type'=>$type,'id'=>Auth::exhibitor()->id));

		$_id = new MongoId(Auth::exhibitor()->id);

		$exhibitor = $att->get(array('_id'=>$_id));

		if(is_null($confirmdata) || count($confirmdata) < 0 || !isset($confirmdata) || !is_array($confirmdata)){

		}else{

			$exhibitor = array_merge($exhibitor,$confirmdata);

		}

		$form = new Formly($exhibitor);

		$golfcount = $att->count(array('golf'=>'Yes','golfPaymentStatus'=>'paid'));

		$form->framework = 'zurb';

		return View::make('exhibition.payment')
					->with('form',$form)
					->with('type',$type)
					->with('user',$exhibitor)
					->with('crumb',$this->crumb)
					->with('golfcount',$golfcount)
					->with('title',ucfirst($type).' Payment Confirmation');

	}

	public function post_payment($type = 'convention'){

		$data = Input::get();

	    $rules = array(
	        $type.'transferdate' => 'required',
	        $type.'totalpayment' => 'required',
	        $type.'fromaccountname' => 'required',
	        $type.'fromaccnumber' => 'required',
	        $type.'frombank' => 'required',
	        'docupload' => 'required',
	    );

	    $type = $data['type'];

	    $validation = Validator::make($input = Input::all(), $rules);

	    if($validation->fails()){

	    	return Redirect::to('payment/'.$type)->with_errors($validation)->with_input(Input::all());

	    }else{

			$data = Input::get();

			unset($data['repass']);
			unset($data['csrf_token']);

			$data[$type.'transferdate'] = new MongoDate(strtotime($data[$type.'transferdate']." 00:00:00"));

			$data['createdDate'] = new MongoDate();
			$data['lastUpdate'] = new MongoDate();

			$confirm = new Confirmation();


			// uploaded receipt
			$docupload = Input::file('docupload');
			$docupload[$type.'DocUploadTime'] = new MongoDate();

			$fileExt = File::extension( $docupload['name']);

			$docName = $type.'PaymentProof.'.$fileExt;

			$data[$type.'DocFilename'] = $docName;

			$data[$type.'DocFiledata'] = $docupload;


			if($obj = $confirm->insert($data)){


				if($docupload['name'] != ''){

					$newid = $obj['_id']->__toString();

					$newdir = realpath(Config::get('kickstart.storage')).'/payments/'.$newid;

					Input::upload('docupload',$newdir,$docName);

					$email_attachment = $newdir.'/'.$docName;
				}else{
					$email_attachment = false;
				}


				$exhibitor = new Exhibitor();

				$id = Auth::exhibitor()->id;

				$_id = new MongoId($id);

				$userdata = $exhibitor->get(array('_id'=>$_id));

				$userdata = array_merge($userdata,$data);

				//check first if booth payment selected
				if(isset($data['confirmbooth'])){

					

					$exhibitor->update(array('_id'=>$_id),array('$set'=>array('golfPaymentStatus'=>'pending')));
					$exhibitor->update(array('_id'=>$_id),array('$set'=>array('conventionPaymentStatus'=>'pending')));
					
					
					$userdata[$type.'transferdate'] = date('d-m-Y',$userdata[$type.'transferdate']->sec);
					$data['confirmbooth'] = 'yes';

					$userdata['address'] = $userdata['address_1'].'<br />'.$userdata['address_2'];

					$body = View::make('email.regpayment')
						->with('type',$type)
						->with('confirmAll','yes')
						->with('data',$userdata)
						->render();

					if($email_attachment == false){
						Message::to($userdata['email'])
						    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->cc(Config::get('eventreg.reg_finance_email'), Config::get('eventreg.reg_finance_name'))
						    ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->subject('Convention & Golf Payment Confirmation – '.$userdata['registrationnumber'])
						    ->body( $body )
						    ->html(true)
						    ->send();
					}else{
						Message::to($userdata['email'])
						    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->cc(Config::get('eventreg.reg_finance_email'), Config::get('eventreg.reg_finance_name'))
						    ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->subject('Convention & Golf Payment Confirmation – '.$userdata['registrationnumber'])
						    ->body( $body )
						    ->html(true)
						    ->attach($email_attachment)
						    ->send();
					}

				}else{
					$exhibitor->update(array('_id'=>$_id),array('$set'=>array($type.'PaymentStatus'=>'pending')));
				
					$userdata[$type.'transferdate'] = date('d-m-Y',$userdata[$type.'transferdate']->sec);

					$userdata['address'] = $userdata['address_1'].'<br />'.$userdata['address_2'];

					$body = View::make('email.regpayment')
						->with('type',$type)
						->with('data',$userdata)
						->render();

					if($email_attachment == false){
						Message::to($userdata['email'])
						    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->cc(Config::get('eventreg.reg_finance_email'), Config::get('eventreg.reg_finance_name'))
						    ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->subject(ucfirst($type).' Payment Confirmation – '.$userdata['registrationnumber'])
						    ->body( $body )
						    ->html(true)
						    ->send();
					}else{
						Message::to($userdata['email'])
						    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->cc(Config::get('eventreg.reg_finance_email'), Config::get('eventreg.reg_finance_name'))
						    ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
						    ->subject(ucfirst($type).' Payment Confirmation – '.$userdata['registrationnumber'])
						    ->body( $body )
						    ->html(true)
						    ->attach($email_attachment)
						    ->send();
					}
				}
					



		    	return Redirect::to('paymentsubmitted')->with('notify_success',Config::get('site.payment_success'));
			}else{
		    	return Redirect::to('exhibition')->with('notify_success',Config::get('site.payment_failed'));
			}
		}

	}

	public function get_paymentsubmitted(){

		$this->crumb->add('exhibition','Exhibition');

		$form = new Formly();
		return View::make('exhibition.paymentsubmitted')
					->with('form',$form)
					->with('crumb',$this->crumb)
					->with('title','Thank you for your payment confirmation!');

	}

	public function get_login(){

		$this->crumb->add('exhibition','Exhibition');

		$form = new Formly();
		return View::make('exhibition.login')
					->with('form',$form)
					->with('crumb',$this->crumb)
					->with('title','Login Form');

	}

	public function get_operationalform(){
		if(!Auth::exhibitor()){
			return Redirect::to('exhibition/login');
		}
		$this->crumb->add('exhibition','Operational Form');

		$form = new Formly();
		return View::make('exhibition.operationalform')
					->with('form',$form)
					->with('crumb',$this->crumb)
					->with('title','Operational Form');

	}


	public function post_operationalform(){

		//print_r(Session::get('permission'));

	   

		$data = Input::get();

		$exhibitor = new Exhibitor();
		
    	if (isset($data['programdate1']) && $data['programdate1']!='') {$data['programdate1'] = new MongoDate(strtotime($data['programdate1']." 00:00:00")); }
		if (isset($data['programdate2']) && $data['programdate2']!='') {$data['programdate2'] = new MongoDate(strtotime($data['programdate2']." 00:00:00")); }
		if (isset($data['programdate3']) && $data['programdate3']!='') {$data['programdate3'] = new MongoDate(strtotime($data['programdate3']." 00:00:00")); }
		if (isset($data['programdate4']) && $data['programdate4']!='') {$data['programdate4'] = new MongoDate(strtotime($data['programdate4']." 00:00:00")); }
		if (isset($data['programdate5']) && $data['programdate5']!='') {$data['programdate5'] = new MongoDate(strtotime($data['programdate5']." 00:00:00")); }
		if (isset($data['programdate6']) && $data['programdate6']!='') {$data['programdate6'] = new MongoDate(strtotime($data['programdate6']." 00:00:00")); }

		if (isset ($data['cocktaildate1'])&& $data['cocktaildate1']!='') { $data['cocktaildate1'] = new MongoDate(strtotime($data['cocktaildate1']." 00:00:00")); }
		if (isset ($data['cocktaildate2'])&& $data['programdate2']!='') { $data['cocktaildate2'] = new MongoDate(strtotime($data['cocktaildate2']." 00:00:00")); }
		if (isset ($data['cocktaildate3'])&& $data['programdate3']!='') { $data['cocktaildate3'] = new MongoDate(strtotime($data['cocktaildate3']." 00:00:00")); }
		if (isset ($data['cocktaildate4'])&& $data['programdate4']!='') { $data['cocktaildate4'] = new MongoDate(strtotime($data['cocktaildate4']." 00:00:00")); }

		unset($data['csrf_token']);

		$userid = Auth::exhibitor()->id;

		$_id = new MongoId($userid);

		$userdata = $exhibitor->get(array('_id'=>$_id));
		$data['userid'] = $userdata['_id']->__toString();

		$data['createdDate'] = new MongoDate();
		$data['lastUpdate'] = new MongoDate();

		
		

		$exhibitor = new Exhibitor();

		$submitdata = new Operationalform();

		if($obj = $submitdata->insert($data)){

			$exhibitor->update(array('_id'=>$_id),array('$set'=>array('formstatus'=>'submitted')));
			
			//Event::fire('exhibitor.createformadmin',array($obj['_id'],$passwordRandom));
			
	    	return Redirect::to('exhibition/formsubmitted')->with('notify_success',Config::get('site.register_success'));
		}else{
	    	return Redirect::to('exhibition/formsubmitted')->with('notify_success',Config::get('site.register_failed'));
		}

	    

		
	}

	public function get_editform(){

		//$this->crumb->add('user/edit','Edit',false);
		$formoperational = new Operationalform();

		$id = Auth::exhibitor()->id;

		//$id = new MongoId($id);

		$user_form = $formoperational->get(array('userid'=>$id));

		if (isset($user_form['programdate1']) && $user_form['programdate1']!='') {$user_form['programdate1'] = date('d-m-Y', $user_form['programdate1']->sec); }
		if (isset($user_form['programdate2']) && $user_form['programdate2']!='') {$user_form['programdate2'] = date('d-m-Y', $user_form['programdate2']->sec); }
		if (isset($user_form['programdate3']) && $user_form['programdate3']!='') {$user_form['programdate3'] = date('d-m-Y', $user_form['programdate3']->sec); }
		if (isset($user_form['programdate4']) && $user_form['programdate4']!='') {$user_form['programdate4'] = date('d-m-Y', $user_form['programdate4']->sec); }
		if (isset($user_form['programdate5']) && $user_form['programdate5']!='') {$user_form['programdate5'] = date('d-m-Y', $user_form['programdate5']->sec); }
		if (isset($user_form['programdate6']) && $user_form['programdate6']!='') {$user_form['programdate6'] = date('d-m-Y', $user_form['programdate6']->sec); }

		if (isset ($user_form['cocktaildate1'])&& $user_form['cocktaildate1']!='') { $user_form['cocktaildate1'] = date('d-m-Y', $user_form['cocktaildate1']->sec);; }
		if (isset ($user_form['cocktaildate2'])&& $user_form['programdate2']!='') { $user_form['cocktaildate2']  = date('d-m-Y', $user_form['cocktaildate2']->sec);; }
		if (isset ($user_form['cocktaildate3'])&& $user_form['programdate3']!='') { $user_form['cocktaildate3']  = date('d-m-Y', $user_form['cocktaildate3']->sec);; }
		if (isset ($user_form['cocktaildate4'])&& $user_form['programdate4']!='') { $user_form['cocktaildate4']  = date('d-m-Y', $user_form['cocktaildate4']->sec);; }


		$form = Formly::make($user_form);

		$form->framework = 'zurb';

		return View::make('exhibition.editform')
					->with('data',$user_form)
					->with('form',$form)
					->with('crumb',$this->crumb)
					->with('title','Edit My Profile');

	}


	public function post_editform(){

		//print_r(Session::get('permission'));


		$data = Input::get();

		$id = new MongoId($data['id']);
		$data['lastUpdate'] = new MongoDate();
		
		
		unset($data['csrf_token']);
		unset($data['id']);

		$operationalform = new Operationalform();
		
		if (isset($data['programdate1']) && $data['programdate1']!='') {$data['programdate1'] = new MongoDate(strtotime($data['programdate1']." 00:00:00")); }
		if (isset($data['programdate2']) && $data['programdate2']!='') {$data['programdate2'] = new MongoDate(strtotime($data['programdate2']." 00:00:00")); }
		if (isset($data['programdate3']) && $data['programdate3']!='') {$data['programdate3'] = new MongoDate(strtotime($data['programdate3']." 00:00:00")); }
		if (isset($data['programdate4']) && $data['programdate4']!='') {$data['programdate4'] = new MongoDate(strtotime($data['programdate4']." 00:00:00")); }
		if (isset($data['programdate5']) && $data['programdate5']!='') {$data['programdate5'] = new MongoDate(strtotime($data['programdate5']." 00:00:00")); }
		if (isset($data['programdate6']) && $data['programdate6']!='') {$data['programdate6'] = new MongoDate(strtotime($data['programdate6']." 00:00:00")); }

		if (isset ($data['cocktaildate1'])&& $data['cocktaildate1']!='') { $data['cocktaildate1'] = new MongoDate(strtotime($data['cocktaildate1']." 00:00:00")); }
		if (isset ($data['cocktaildate2'])&& $data['programdate2']!='') { $data['cocktaildate2'] = new MongoDate(strtotime($data['cocktaildate2']." 00:00:00")); }
		if (isset ($data['cocktaildate3'])&& $data['programdate3']!='') { $data['cocktaildate3'] = new MongoDate(strtotime($data['cocktaildate3']." 00:00:00")); }
		if (isset ($data['cocktaildate4'])&& $data['programdate4']!='') { $data['cocktaildate4'] = new MongoDate(strtotime($data['cocktaildate4']." 00:00:00")); }

		


		if($operationalform->update(array('_id'=>$id),array('$set'=>$data))){

			/*$ex = $user->get(array('_id'=>$id));

			$body = View::make('email.regupdateexhbition')
				->with('data',$ex)
				->render();

			Message::to($data['email'])
			    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
			    ->cc(Config::get('eventreg.reg_dyandra_admin_email'), Config::get('eventreg.reg_dyandra_admin_name'))
			    ->subject('Indonesia Petroleum Association – 37th Convention & Exhibitionion (Profile Updated – '.$data['registrationnumber'].')')
			    ->body( $body )
			    ->html(true)
			    ->send();*/

	    	return Redirect::to('exhibition/formsubmitted')->with('notify_success',Config::get('site.register_success'));

		}else{
	    	return Redirect::to('exhibitor/profile/')->with('notify_success','Exhibitor saving failed');
		}
	}

	public function get_readform(){

		//$this->crumb->add('user/edit','Edit',false);

		$user = new Exhibitor();

		$formData = new Operationalform();

		$id = Auth::exhibitor()->id;


		$data = $formData->get(array('userid'=>$id));

		if (isset($data['programdate1']) && $data['programdate1']!='') {$data['programdate1'] = date('Y-m-d', $data['programdate1']->sec); }
		if (isset($data['programdate2']) && $data['programdate2']!='') {$data['programdate2'] = date('Y-m-d', $data['programdate2']->sec); }
		if (isset($data['programdate3']) && $data['programdate3']!='') {$data['programdate3'] = date('Y-m-d', $data['programdate3']->sec); }
		if (isset($data['programdate4']) && $data['programdate4']!='') {$data['programdate4'] = date('Y-m-d', $data['programdate4']->sec); }
		if (isset($data['programdate5']) && $data['programdate5']!='') {$data['programdate5'] = date('Y-m-d', $data['programdate5']->sec); }
		if (isset($data['programdate6']) && $data['programdate6']!='') {$data['programdate6'] = date('Y-m-d', $data['programdate6']->sec); }

		if (isset ($data['cocktaildate1'])&& $data['cocktaildate1']!='') { $data['cocktaildate1'] = date('Y-m-d', $data['cocktaildate1']->sec);; }
		if (isset ($data['cocktaildate2'])&& $data['programdate2']!='') { $data['cocktaildate2']  = date('Y-m-d', $data['cocktaildate2']->sec);; }
		if (isset ($data['cocktaildate3'])&& $data['programdate3']!='') { $data['cocktaildate3']  = date('Y-m-d', $data['cocktaildate3']->sec);; }
		if (isset ($data['cocktaildate4'])&& $data['programdate4']!='') { $data['cocktaildate4']  = date('Y-m-d', $data['cocktaildate4']->sec);; }

		$form = new Formly();
		//$form = Formly::make($user_profile);

		//$form->framework = 'zurb';

		return View::make('exhibition.readform')
					->with('form',$form)
					->with('data',$data)
					->with('id',$id)
					->with('crumb',$this->crumb)
					->with('title','Operational Form Submission');

	}

	public function get_formsubmitted(){

		$this->crumb->add('exhibition','Exhibition');

		$form = new Formly();
		return View::make('exhibition.formsubmitted')
					->with('form',$form)
					->with('crumb',$this->crumb)
					->with('title','Thank you for your submission!');

	}

	public function get_landing(){

		$this->crumb->add('exhibition','Exhibition');

		return View::make('exhibition.landing')
					->with('crumb',$this->crumb)
					->with('title','');
	}

	public function get_reset(){

		$this->crumb->add('exhibition/reset','Reset Password');

		$form = new Formly();
		return View::make('exhibition.resetpass')
					->with('form',$form)
					->with('crumb',$this->crumb)
					->with('title','Exhibitor Reset Password Form');

	}

	public function post_reset(){

		//print_r(Session::get('permission'));

	    $rules = array(
	        'email' => 'required|email',
	    );

	    $validation = Validator::make($input = Input::all(), $rules);

	    if($validation->fails()){

	    	return Redirect::to('reset')->with_errors($validation)->with_input(Input::all());

	    }else{

			$data = Input::get();

			$newpass = rand_string(8);

			$data['pass'] = Hash::make($newpass);


			unset($data['csrf_token']);

			$data['lastUpdate'] = new MongoDate();

			$user = new Exhibitor();

			$ex = $user->get(array('email'=>$data['email']));

			if(isset($ex['email']) && $ex['email'] == $data['email']){

				if($obj = $user->update(array('email'=>$data['email']),array('$set'=>$data))){

					$userdata = $user->get(array('email'=>$data['email']));


					$body = View::make('email.resetpass')
						->with('data',$data)
						->with('userdata',$userdata)
						->with('newpass',$newpass)
						->render();

					Message::to($data['email'])
					    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
					    ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
					    ->subject('Password Reset - Indonesia Petroleum Association – 37th Convention & Exhibitionion)')
					    ->body( $body )
					    ->html(true)
					    ->send();

			    	return Redirect::to('exhibitor/resetlanding')->with('notify_success',Config::get('site.reset_success'));
				}else{
			    	return Redirect::to('exhibitor/reset')->with('notify_result',Config::get('site.reset_failed'));
				}

			}else{

		    	return Redirect::to('exhibitor/reset')->with('notify_result',Config::get('site.reset_email_not_found'));

			}



	    }


	}


	public function get_resetlanding(){

		$this->crumb->add('exhibition/reset','Reset Password');

		return View::make('exhibition.resetlanding')
					->with('crumb',$this->crumb)
					->with('title','Reset Password Success');
	}

	public function get_profile($id = null){

		if(is_null($id)){
			$this->crumb = new Breadcrumb();
		}

		$user = new Exhibitor();

		$id = (is_null($id))?Auth::exhibitor()->id:$id;

		$id = new MongoId($id);

		$user_profile = $user->get(array('_id'=>$id));

		$this->crumb->add('project/profile','Profile',false);
		$this->crumb->add('project/profile',$user_profile['firstname'].' '.$user_profile['lastname']);

		return View::make('exhibition.profile')
			->with('crumb',$this->crumb)
			->with('profile',$user_profile);
	}

	public function get_edit(){

		$this->crumb->add('user/edit','Edit',false);

		$user = new Exhibitor();

		$id = Auth::exhibitor()->id;

		$id = new MongoId($id);

		$user_profile = $user->get(array('_id'=>$id));


		$form = Formly::make($user_profile);

		$form->framework = 'zurb';

		return View::make('exhibition.edit')
					->with('user',$user_profile)
					->with('form',$form)
					->with('crumb',$this->crumb)
					->with('title','Edit My Profile');

	}


	public function post_edit(){

		//print_r(Session::get('permission'));

	    $rules = array(
	    	'position' => 'required',
	        'email' => 'required|email',
	        'company' => 'required',
	        'companyphone' => 'required',
	        'city' => 'required',
	        'zip' => 'required',
	        
	    );

	    $validation = Validator::make($input = Input::all(), $rules);

	    if($validation->fails()){

	    	return Redirect::to('exhibitor/profile/edit')->with_errors($validation)->with_input(Input::all());

	    }else{

			$data = Input::get();

			$id = new MongoId($data['id']);
			$data['lastUpdate'] = new MongoDate();
			$data['role'] = 'EXH';
			
			unset($data['csrf_token']);
			unset($data['id']);

			$user = new Exhibitor();

			if(isset($data['registrationnumber']) && $data['registrationnumber'] != ''){
				$reg_number = explode('-',$data['registrationnumber']);			

				$reg_number[0] = 'E';
				$reg_number[1] = $data['role'];
				$reg_number[2] = '00';


			}else if($data['registrationnumber'] == ''){
				$reg_number = array();
				$seq = new Sequence();
				$rseq = $seq->find_and_modify(array('_id'=>'visitor'),array('$inc'=>array('seq'=>1)),array('seq'=>1),array('new'=>true));

				$reg_number[0] = 'E';
				$reg_number[1] = $data['role'];
				$reg_number[2] = '00';

				$reg_number[3] = str_pad($rseq['seq'], 6, '0',STR_PAD_LEFT);
			}


			$data['registrationnumber'] = implode('-',$reg_number);

			

			if($user->update(array('_id'=>$id),array('$set'=>$data))){

				$ex = $user->get(array('_id'=>$id));

				$body = View::make('email.regupdateexhbition')
					->with('data',$ex)
					->render();

				Message::to($data['email'])
				    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
				    ->cc(Config::get('eventreg.reg_dyandra_admin_email'), Config::get('eventreg.reg_dyandra_admin_name'))
				    ->subject('Indonesia Petroleum Association – 37th Convention & Exhibitionion (Profile Updated – '.$data['registrationnumber'].')')
				    ->body( $body )
				    ->html(true)
				    ->send();

		    	return Redirect::to('exhibitor/profile/')->with('notify_success','Exhibitor saved successfully');

			}else{
		    	return Redirect::to('exhibitor/profile/')->with('notify_success','Exhibitor saving failed');
			}

	    }


	}


}
?>