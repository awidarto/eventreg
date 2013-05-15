<?php

class Reader_Controller extends Base_Controller {

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

	public $crumb;


	public function __construct(){
		//$this->crumb = new Breadcrumb();
		//$this->crumb->add('reader','Dashboard');

		date_default_timezone_set('Asia/Jakarta');
		//$this->filter('before','auth');
	}

	
	public function get_index(){
		$form = new Formly();

		if (Session::has('stationselected') && Session::has('activityselected')){
			return View::make('reader.home')->with('form',$form);
		}else{
			return Redirect::to('reader/selectstation')->with('notify_error','You need to select the station & activity first');
		}
	}

	public function get_selectstation(){
		$form = new Formly();

		return View::make('reader.selectstation')
		->with('form',$form)
		;
	}

	public function post_poststation(){
		$data = Input::get();

		$stationnumber = $data['stationnumber'];
		$activity = $data['activity'];

		Session::put('stationselected', $stationnumber);
		Session::put('activityselected', $activity);
		return Redirect::to('reader')->with('notify_success','Succesfully store station number, you may scan the barcode now');
	}


	public function get_report()
	{
		$form = new Formly();

		if(isset($_GET['activityselected'])){
			$activity = $_GET['activityselected'];
			
		}else{
			$activity = 'opening';
		}

		$reader = new Reader();

		$q = array('activity'=>$activity,'$or'=>array(
			array('role'=>'COM'),
			array('role'=>'BOD'),
			array('role'=>'ATB'),
			));

		$q2 = array('activity'=>$activity,'$or'=>array(
			array('role'=>'PO'),
			array('role'=>'PD'),
			array('role'=>'SD'),
			array('role'=>'SO'),
			array('role'=>'PTC'),
			));

		$q3 = array('activity'=>$activity,'$or'=>array(
			array('role'=>'VIP'),
			array('role'=>'VVIP'),
			));

		$stat['all'] = $reader->count(array('activity'=>$activity));
		$stat['commitee'] = $reader->count($q,array(),array(),array());
		$stat['attendee'] = $reader->count($q2,array(),array(),array());
		$stat['vip'] = $reader->count($q3,array(),array(),array());
		
		$stat['other'] = $stat['all']-($stat['commitee']+$stat['attendee']+$stat['vip']);

		

		return View::make('reader.report')
			->with('stat',$stat)
			->with('sessionselected',$activity)
			->with('urlredirect',URL::to('reader/report'))
			->with('title','Scan Report')
			->with('form',$form)
			->with('crumb',$this->crumb);
	}

	public function post_process(){
		$data = Input::get();


		$regnumber = $data['regnumber'];
		$stationnumber = $data['stationnumber'];
		$activity = $data['activity'];

		$countcar =  strlen($regnumber);

		if($stationnumber=='' || $regnumber == ''){
			return Redirect::to('reader')->with('notify_error','Error! Registration number and Station number cannot be null');	
		}else if($countcar<5 && $countcar>12){

			//still save in our database
			$readerfind = $reader->get(array('regplain'=>$regnumber,'activity'=>$activity));

			//this user has attend
			if(isset($readerfind)){
				$_idrepeat = $readerfind['_id'];
				$countrecent = $readerfind['countattend']+1;
				if($reader->update(array('_id'=>$_idrepeat),array('$set'=>array('countattend'=>$countrecent,'lastUpdate'=>new MongoDate()))) ){

					return Redirect::to('reader')->with('notify_error','Wrong format Registration number, maybe this badge created in other app, please check manual badge color');	
				}
			}else{
				//drop to database
				$datatoreader['role'] = 'pras';
				$datatoreader['regplain'] = $regnumber;
				$datatoreader['participant_number'] = '';
				$datatoreader['firstname'] = '';
				$datatoreader['lastname'] = '';
				$datatoreader['company'] = '';
				$datatoreader['stationnumber'] = $stationnumber;
				$datatoreader['countattend'] = 1;
				$datatoreader['activity'] = $activity;
				$datatoreader['createdDate'] = new MongoDate();
				$datatoreader['lastUpdate'] = new MongoDate();

				if($obj = $reader->insert($datatoreader)){
					return Redirect::to('reader')->with('notify_error','Wrong format Registration number, maybe this badge created in other app, please check manual badge color');	
				}
			}
			

		}else{
			$reg_number = array();

			$splitting = str_split($regnumber, 1);
			$role = $splitting[0];
			
			$reader = new Reader();
			$datatoreader = array();


			if($role == 'C'){
				
				$type = $splitting[1].$splitting[2];
				$dinner = $splitting[3].$splitting[4];
				$sequence = $splitting[5].$splitting[6].$splitting[7].$splitting[8].$splitting[9].$splitting[10];


				$reg_number[0] = $role;
				$reg_number[1] = $type;
				$reg_number[2] = $dinner;
				$reg_number[3] = $sequence;

				$registratonnumberall = implode('-',$reg_number);


				$attendee = new Attendee();
				

			}else if($role == 'A' || $role == 'O'){

				$type = $splitting[1].$splitting[2].$splitting[3];
				$typespecialstud = $splitting[1].$splitting[2];
				$typespecial4char = $splitting[1].$splitting[2].$splitting[3].$splitting[4];


				$attendee = new Official();
				
				if($typespecialstud=='SD' || $typespecialstud=='VS' || $typespecialstud=='OC'){

					$type = $typespecialstud;
					$dinner = $splitting[3].$splitting[4];
					$sequence = $splitting[5].$splitting[6].$splitting[7].$splitting[8].$splitting[9].$splitting[10];

				}elseif ($typespecial4char == 'VVIP') {

					$type = $typespecial4char;
					
					$dinner = '';
					$sequence = $splitting[5].$splitting[6].$splitting[7].$splitting[8].$splitting[9].$splitting[10];
					$attendee = new Visitor();
				

				}else{

					$type = $type;
					if($type =='VIP' || $type =='MDA'){
						$attendee = new Visitor();	
					}
					
					$dinner = $splitting[4].$splitting[5];
					$sequence = $splitting[6].$splitting[7].$splitting[8].$splitting[9].$splitting[10].$splitting[11];
					
				}


				if($typespecial4char == 'VVIP'){
					$reg_number[0] = $role;
					$reg_number[1] = $type;
					$reg_number[2] = $sequence;
				}else{
					$reg_number[0] = $role;
					$reg_number[1] = $type;
					$reg_number[2] = $dinner;
					$reg_number[3] = $sequence;
				}
				

				$registratonnumberall = implode('-',$reg_number);

				
				
			}else{
				
				//still save in our database
				$readerfind = $reader->get(array('regplain'=>$regnumber,'activity'=>$activity));

				//this user has attend
				if(isset($readerfind)){
					$_idrepeat = $readerfind['_id'];
					$countrecent = $readerfind['countattend']+1;
					if($reader->update(array('_id'=>$_idrepeat),array('$set'=>array('countattend'=>$countrecent,'lastUpdate'=>new MongoDate()))) ){

						return Redirect::to('reader')->with('notify_error','Wrong format Registration number, maybe this badge created in other app, please check manual badge color');	
					}
				}else{
					//drop to database
					$datatoreader['role'] = 'pras';
					$datatoreader['regplain'] = $regnumber;
					$datatoreader['participant_number'] = '';
					$datatoreader['firstname'] = '';
					$datatoreader['lastname'] = '';
					$datatoreader['company'] = '';
					$datatoreader['stationnumber'] = $stationnumber;
					$datatoreader['countattend'] = 1;
					$datatoreader['activity'] = $activity;
					$datatoreader['createdDate'] = new MongoDate();
					$datatoreader['lastUpdate'] = new MongoDate();

					if($obj = $reader->insert($datatoreader)){
						return Redirect::to('reader')->with('notify_error','Wrong format Registration number, maybe this badge created in other app, please check manual badge color');	
					}
				}
			}


			$att = $attendee->get(array('registrationnumber'=>$registratonnumberall));				

			if(isset($att)){

				//student cannot join
				if(($type == 'SD'&&$role!='C') || $type == 'VS' || $type == 'OC'){
					return Redirect::to('reader')->with('notify_error','Error! This participant cant have access on this area');			
				}
				if(isset($att['firstname'])){
					$firstname = $att['firstname'];
				}else{
					$firstname = '';
				}
				if(isset($att['lastname'])){
					$lastname = $att['lastname'];
				}else{
					$lastname = '';
				}
				if(isset($att['company'])){
					$company = $att['company'];
				}else{
					$company = '';
				}
				$fullname = $firstname.$lastname;
				//first check if this user has attend same activity
				$readerfind = $reader->get(array('regplain'=>$regnumber,'activity'=>$activity));

				//this user has attend
				if(isset($readerfind)){
					$_idrepeat = $readerfind['_id'];
					$countrecent = $readerfind['countattend']+1;
					if($reader->update(array('_id'=>$_idrepeat),array('$set'=>array('countattend'=>$countrecent,'lastUpdate'=>new MongoDate()))) ){
						
						return Redirect::to('reader')->with('notify_success','Hi '.$fullname.', Welcome to IPA 37 Convention & Exhibition');
					}
				}else{
					//drop to database
					$datatoreader['role'] = $type;
					//$datatoreader['usesystem'] = $type;
					$datatoreader['participant_number'] = $registratonnumberall;
					$datatoreader['firstname'] = $firstname;
					$datatoreader['lastname'] = $lastname;
					$datatoreader['company'] = $company;
					$datatoreader['regplain'] = $regnumber;
					$datatoreader['stationnumber'] = $stationnumber;
					$datatoreader['countattend'] = 1;
					$datatoreader['activity'] = $activity;
					$datatoreader['createdDate'] = new MongoDate();
					$datatoreader['lastUpdate'] = new MongoDate();

					if($obj = $reader->insert($datatoreader)){
						return Redirect::to('reader')->with('notify_success','Hi '.$fullname.' Welcome to IPA 37 Convention & Exhibition');
					}
				}
				
				


				return Redirect::to('reader')->with('notify_success','Hi Welcome to IPA 37 Convention & Exhibition'.$registratonnumberall);
			}else{
				return Redirect::to('reader')->with('notify_error','This participant cannot find in our database');			
			}


			
			



			
		}
	}

}