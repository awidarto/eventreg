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

	public function post_process(){
		$data = Input::get();


		$regnumber = $data['regnumber'];
		$stationnumber = $data['stationnumber'];
		$activity = $data['activity'];

		$countcar =  strlen($regnumber);

		if($stationnumber=='' || $regnumber == ''){
			return Redirect::to('reader')->with('notify_error','Error! Registration number and Station number cannot be null');	
		}else if($countcar<5 && $countcar>12){
			return Redirect::to('reader')->with('notify_error','Error! Wrong format Registration number');	

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
				

			}else if($role == 'A'){

				$type = $splitting[1].$splitting[2].$splitting[3];
				$dinner = $splitting[4].$splitting[5];
				$sequence = $splitting[6].$splitting[7].$splitting[8].$splitting[9].$splitting[10].$splitting[11];


				$reg_number[0] = $role;
				$reg_number[1] = $type;
				$reg_number[2] = $dinner;
				$reg_number[3] = $sequence;

				$registratonnumberall = implode('-',$reg_number);

				$attendee = new Official();
				
			}else{
				return Redirect::to('reader')->with('notify_error','Error! Wrong format Registration number');	
			}
			$att = $attendee->get(array('registrationnumber'=>$registratonnumberall));				

			if(isset($att)){

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
					$datatoreader['participant_number'] = $registratonnumberall;
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
				return Redirect::to('reader')->with('notify_error','Error! This participant cannot find in our database');			
			}


			
			



			
		}
	}

}