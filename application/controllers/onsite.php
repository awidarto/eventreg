<?php

class Onsite_Controller extends Base_Controller {

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
		//$this->crumb->add('onsite','Dashboard');

		date_default_timezone_set('Asia/Jakarta');
		$this->filter('before','auth');
	}

	public function get_index()
	{

		$form = new Formly();

		$select_all = $form->checkbox('select_all','','',false,array('id'=>'select_all'));

		$action_selection = $form->select('action','',Config::get('kickstart.actionselection'));

		$btn_add_to_group = '<span class=" add_to_group" id="add_to_group">'.$action_selection.'</span>';


		$heads = array(
			'#',
			'Reg. Date',
			'Reg. Number',
			'Email',
			'First Name',
			'Last Name',
			'Company',

			//'Reg. Type',
			//'Country',
			//'Conv. Status',
			//'Golf. Status',
			//''
		);

		$searchinput = array(
			false,
			'Reg. Date',
			'Reg Number',
			'Email',
			'First Name',
			'Last Name',
			'Company',
			//false,
			//'Country',
			//false,
			//false,
			//false
		);


		$colclass = array('','span1','span3','span1','span3','span3','span1','span1','span1','','','','','','','','','');

		return View::make('dashboard.onsite')
			->with('title','Master Data')
			->with('newbutton','New Visitor')
			->with('disablesort','0')
			->with('addurl','attendee/add')
			->with('colclass',$colclass)
			->with('searchinput',$searchinput)
			->with('ajaxsource',URL::to('onsite'))
			->with('ajaxvisitorsource',URL::to('onsite/visitor'))

			->with('ajaxdel',URL::to('attendee/del'))
			->with('ajaxpay',URL::to('attendee/paystatus'))
			->with('ajaxpaygolf',URL::to('attendee/paystatusgolf'))
			->with('ajaxpaygolfconvention',URL::to('attendee/paystatusgolfconvention'))
			->with('ajaxresendmail',URL::to('attendee/resendmail'))
			->with('printsource',URL::to('attendee/printbadge'))
			->with('ajaxexhibitorcheck',URL::to('onsite/ajaxexhibitorcheck'))
			->with('form',$form)
			->with('crumb',$this->crumb)
			->with('heads',$heads)
			->with('visitorheads',$heads)
			->nest('row','attendee.rowdetail');

	}

	public function post_index()
	{


		$fields = array(
			'createdDate',
			'registrationnumber',
			'email',
			'firstname',
			'lastname',
			'company',
			//'regtype',
			//'country',
			//'conventionPaymentStatus',
			//'golfPaymentStatus'
		);

		$rel = array('like','like','like','like','like','like','like','like');

		$cond = array('both','both','both','both','both','both','both','both','both');

		$pagestart = Input::get('iDisplayStart');
		$pagelength = Input::get('iDisplayLength');

		$limit = array($pagelength, $pagestart);

		$defsort = 1;
		$defdir = -1;

		$idx = 0;
		$q = array();

		$hilite = array();
		$hilite_replace = array();

		foreach($fields as $field){
			if(Input::get('sSearch_'.$idx))
			{

				$hilite_item = Input::get('sSearch_'.$idx);
				$hilite[] = $hilite_item;
				$hilite_replace[] = '<span class="hilite">'.$hilite_item.'</span>';

				if($rel[$idx] == 'like'){
					if($cond[$idx] == 'both'){
						$q[$field] = new MongoRegex('/'.Input::get('sSearch_'.$idx).'/i');
					}else if($cond[$idx] == 'before'){
						$q[$field] = new MongoRegex('/^'.Input::get('sSearch_'.$idx).'/i');
					}else if($cond[$idx] == 'after'){
						$q[$field] = new MongoRegex('/'.Input::get('sSearch_'.$idx).'$/i');
					}
				}else if($rel[$idx] == 'equ'){
					$q[$field] = Input::get('sSearch_'.$idx);
				}
			}
			$idx++;
		}

		//print_r($q)

		$attendee = new Attendee();
		$visitor = new Visitor();
		$exhibitor = new Exhibitor();
		$official = new Official();

		/* first column is always sequence number, so must be omitted */
		$fidx = Input::get('iSortCol_0');
		if($fidx == 0){
			$fidx = $defsort;
			$sort_col = $fields[$fidx];
			$sort_dir = $defdir;
		}else{
			$fidx = ($fidx > 0)?$fidx - 1:$fidx;
			$sort_col = $fields[$fidx];
			$sort_dir = (Input::get('sSortDir_0') == 'asc')?1:-1;
		}

		$count_all_attendee = $attendee->count();
		$count_all_visitor = $visitor->count();
		$count_all_exhibitor = $exhibitor->count();
		$count_all_official = $official->count();

		$count_all = $count_all_attendee + $count_all_visitor + $count_all_exhibitor + $count_all_official;

		if(count($q) > 0){
			$attendees = $attendee->find($q,array(),array($sort_col=>$sort_dir),$limit);
			$visitors = $visitor->find($q,array(),array($sort_col=>$sort_dir),$limit);
			$exhibitors = $exhibitor->find($q,array(),array($sort_col=>$sort_dir),$limit);
			$officials = $official->find($q,array(),array($sort_col=>$sort_dir),$limit);

			$count_display_all_attendee = $attendee->count($q);
			$count_display_all_visitor = $visitor->count($q);
			$count_display_all_exhibitor = $exhibitor->count($q);
			$count_display_all_official = $official->count($q);
		}else{
			$attendees = $attendee->find(array(),array(),array($sort_col=>$sort_dir),$limit);
			$visitors = $visitor->find(array(),array(),array($sort_col=>$sort_dir),$limit);
			$exhibitors = $exhibitor->find(array(),array(),array($sort_col=>$sort_dir),$limit);
			$officials = $official->find(array(),array(),array($sort_col=>$sort_dir),$limit);

			$count_display_all_attendee = $attendee->count($q);
			$count_display_all_visitor = $visitor->count($q);
			$count_display_all_exhibitor = $exhibitor->count($q);
			$count_display_all_official = $official->count($q);
		}

		$count_display_all = $count_display_all_attendee + $count_display_all_visitor+$count_display_all_exhibitor+$count_display_all_official;
		
		$aadata = array();

		$form = new Formly();

		$messagelog = new Logmessage();

		$counter = 1 + $pagestart;

		if(Auth::user()->role =='cashier'){
			$aadata[] = array('','<strong>Attendees</strong>','','','','','');

			foreach ($attendees as $doc) {

				$extra = $doc;

				$select = $form->checkbox('sel_'.$doc['_id'],'','',false,array('id'=>$doc['_id'],'class'=>'selector'));

				if(isset($doc['conventionPaymentStatus'])){
					if($doc['conventionPaymentStatus'] == 'unpaid'){
						$paymentStatus = '<span class="fontRed fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
					}elseif ($doc['conventionPaymentStatus'] == 'pending') {
						$paymentStatus = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
					}elseif ($doc['conventionPaymentStatus'] == 'cancel') {
						$paymentStatus = '<span class="fontGray fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';

					}else{
						$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
					}
				}else{
					$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
				}

				if(isset($doc['golfPaymentStatus'])){
					if($doc['golfPaymentStatus'] == 'unpaid' && $doc['golf'] == 'Yes'){
						$paymentStatusGolf = '<span class="fontRed fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}elseif ($doc['golfPaymentStatus'] == 'pending') {
						$paymentStatusGolf = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}elseif ($doc['golfPaymentStatus'] == 'cancel') {
						$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}elseif ($doc['golf'] == 'No') {
						$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}else{
						$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}
				}else{
					$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
				}

				if(isset($doc['golf'])){
					if($doc['golf'] == 'Yes'){
						$rowGolfAction = '<a class="icon-"  ><i>&#xe146;</i><span class="paygolf" id="'.$doc['_id'].'" >Golf Status</span>';
					}else{
						$rowGolfAction = '';
					}
				}else{
					$rowGolfAction = '';
				}

				if(isset($doc['golfPaymentStatus']) && isset($doc['conventionPaymentStatus'])){

					if(($doc['golfPaymentStatus'] == 'pending' && $doc['conventionPaymentStatus'] == 'pending') || ($doc['golfPaymentStatus'] == 'unpaid' && $doc['conventionPaymentStatus'] == 'unpaid')){
						$rowBoothAction = '<a class="icon-"  ><i>&#xe1e9;</i><span class="paygolfconvention" id="'.$doc['_id'].'" >Conv & Golf</span>';
					}else{
						$rowBoothAction = '';
					}
				}else{
					$rowGolfAction = '';
				}

				//find message log

				//$rowResendMessage = '';
				//$messagelogs = $messagelog->find(array('user'=>$doc['_id']),array(),array(),array());
				//if(count($messagelogs)>0){

					$rowResendMessage = '<a class="icon-"  ><i>&#xe165;</i><span class="resendmail" id="'.$doc['_id'].'" >Resend Email</span>';
				//}

				$aadata[] = array(
					$counter,
					//$select,
					date('Y-m-d', $doc['createdDate']->sec),
					(isset($doc['registrationnumber']))?'<span class="pop attendee fontRed onsitetableclick" id="'.$doc['_id'].'">'.$doc['registrationnumber'].'</span>':'',
					$doc['email'],
					$doc['firstname'],
					$doc['lastname'],
					$doc['company'],
					$doc['regtype'],
					

					'extra'=>$extra
				);
				$counter++;
			}
		}else{
			$aadata[] = array('','<strong>Attendees</strong>','','','','','');

			foreach ($attendees as $doc) {

				$extra = $doc;

				$select = $form->checkbox('sel_'.$doc['_id'],'','',false,array('id'=>$doc['_id'],'class'=>'selector'));

				if(isset($doc['conventionPaymentStatus'])){
					if($doc['conventionPaymentStatus'] == 'unpaid'){
						$paymentStatus = '<span class="fontRed fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
					}elseif ($doc['conventionPaymentStatus'] == 'pending') {
						$paymentStatus = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
					}elseif ($doc['conventionPaymentStatus'] == 'cancel') {
						$paymentStatus = '<span class="fontGray fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';

					}else{
						$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
					}
				}else{
					$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
				}

				if(isset($doc['golfPaymentStatus'])){
					if($doc['golfPaymentStatus'] == 'unpaid' && $doc['golf'] == 'Yes'){
						$paymentStatusGolf = '<span class="fontRed fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}elseif ($doc['golfPaymentStatus'] == 'pending') {
						$paymentStatusGolf = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}elseif ($doc['golfPaymentStatus'] == 'cancel') {
						$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}elseif ($doc['golf'] == 'No') {
						$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}else{
						$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
					}
				}else{
					$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
				}

				if(isset($doc['golf'])){
					if($doc['golf'] == 'Yes'){
						$rowGolfAction = '<a class="icon-"  ><i>&#xe146;</i><span class="paygolf" id="'.$doc['_id'].'" >Golf Status</span>';
					}else{
						$rowGolfAction = '';
					}
				}else{
					$rowGolfAction = '';
				}

				if(isset($doc['golfPaymentStatus']) && isset($doc['conventionPaymentStatus'])){

					if(($doc['golfPaymentStatus'] == 'pending' && $doc['conventionPaymentStatus'] == 'pending') || ($doc['golfPaymentStatus'] == 'unpaid' && $doc['conventionPaymentStatus'] == 'unpaid')){
						$rowBoothAction = '<a class="icon-"  ><i>&#xe1e9;</i><span class="paygolfconvention" id="'.$doc['_id'].'" >Conv & Golf</span>';
					}else{
						$rowBoothAction = '';
					}
				}else{
					$rowGolfAction = '';
				}

				//find message log

				//$rowResendMessage = '';
				//$messagelogs = $messagelog->find(array('user'=>$doc['_id']),array(),array(),array());
				//if(count($messagelogs)>0){

					$rowResendMessage = '<a class="icon-"  ><i>&#xe165;</i><span class="resendmail" id="'.$doc['_id'].'" >Resend Email</span>';
				//}

				$aadata[] = array(
					$counter,
					//$select,
					date('Y-m-d', $doc['createdDate']->sec),
					(isset($doc['registrationnumber']))?'<span class="pop attendee fontRed onsitetableclick" id="'.$doc['_id'].'">'.$doc['registrationnumber'].'</span>':'',
					$doc['email'],
					$doc['firstname'],
					$doc['lastname'],
					$doc['company'],
					$doc['regtype'],
					

					'extra'=>$extra
				);
				$counter++;
			}

			$aadata[] = array('','<strong>Visitors</strong>','','','','','');

			foreach ($visitors as $doc) {

				$extra = $doc;

				$select = $form->checkbox('sel_'.$doc['_id'],'','',false,array('id'=>$doc['_id'],'class'=>'selector'));

				

				$aadata[] = array(
					$counter,
					//$select,
					date('Y-m-d', $doc['createdDate']->sec),
					(isset($doc['registrationnumber']))?'<span class="pop visitor fontRed onsitetableclick" id="'.$doc['_id'].'">'.$doc['registrationnumber'].'</span>':'',
					$doc['email'],
					$doc['firstname'],
					'',
					$doc['company'],
					'',
					
					'extra'=>$extra
				);
				$counter++;
			}


			$aadata[] = array('','<strong>Exhibitors</strong>','','','','','');

			foreach ($exhibitors as $doc) {

				$extra = $doc;

				$select = $form->checkbox('sel_'.$doc['_id'],'','',false,array('id'=>$doc['_id'],'class'=>'selector'));

				$aadata[] = array(
					$counter,
					//$select,
					date('Y-m-d', $doc['createdDate']->sec),
					(isset($doc['registrationnumber']))?'<span class="pop exhibitorview fontRed onsitetableclick" id="'.$doc['_id'].'">'.$doc['registrationnumber'].'</span>':'',
					$doc['email'],
					'<span class="pop visitor" id="'.$doc['_id'].'">'.$doc['firstname'].'</span>',
					$doc['lastname'],
					$doc['company'],
					'',
					

					'extra'=>$extra
				);
				$counter++;
			}

			$aadata[] = array('','<strong>Officials</strong>','','','','','');

			foreach ($officials as $doc) {

				$extra = $doc;

				$select = $form->checkbox('sel_'.$doc['_id'],'','',false,array('id'=>$doc['_id'],'class'=>'selector'));


				$aadata[] = array(
					$counter,
					//$select,
					date('Y-m-d', $doc['createdDate']->sec),
					(isset($doc['registrationnumber']))?'<span class="pop officialview fontRed onsitetableclick" id="'.$doc['_id'].'">'.$doc['registrationnumber'].'</span>':'',
					$doc['email'],
					'<span class="pop official" id="'.$doc['_id'].'">'.$doc['firstname'].'</span>',
					$doc['lastname'],
					$doc['company'],
					'',
					

					'extra'=>$extra
				);
				$counter++;
			}

		}

		$result = array(
			'sEcho'=> Input::get('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata,
			'qrs'=>$q
		);

		return Response::json($result);
	}


	public function __post_index()
	{


		$fields = array('registrationnumber','createdDate','email','firstname','lastname','company','regtype','country','conventionPaymentStatus','golfPaymentStatus');

		$rel = array('like','like','like','like','like','like','like','like');

		$cond = array('both','both','both','both','both','both','both','both','both');

		$pagestart = Input::get('iDisplayStart');
		$pagelength = Input::get('iDisplayLength');

		$limit = array($pagelength, $pagestart);

		$defsort = 1;
		$defdir = -1;

		$idx = 1;
		$q = array();

		$hilite = array();
		$hilite_replace = array();

		foreach($fields as $field){
			if(Input::get('sSearch_'.$idx))
			{

				$hilite_item = Input::get('sSearch_'.$idx);
				$hilite[] = $hilite_item;
				$hilite_replace[] = '<span class="hilite">'.$hilite_item.'</span>';

				if($rel[$idx] == 'like'){
					if($cond[$idx] == 'both'){
						$q[$field] = new MongoRegex('/'.Input::get('sSearch_'.$idx).'/i');
					}else if($cond[$idx] == 'before'){
						$q[$field] = new MongoRegex('/^'.Input::get('sSearch_'.$idx).'/i');
					}else if($cond[$idx] == 'after'){
						$q[$field] = new MongoRegex('/'.Input::get('sSearch_'.$idx).'$/i');
					}
				}else if($rel[$idx] == 'equ'){
					$q[$field] = Input::get('sSearch_'.$idx);
				}
			}
			$idx++;
		}

		//print_r($q)

		$attendee = new Attendee();

		/* first column is always sequence number, so must be omitted */
		$fidx = Input::get('iSortCol_0');
		if($fidx == 0){
			$fidx = $defsort;
			$sort_col = $fields[$fidx];
			$sort_dir = $defdir;
		}else{
			$fidx = ($fidx > 0)?$fidx - 1:$fidx;
			$sort_col = $fields[$fidx];
			$sort_dir = (Input::get('sSortDir_0') == 'asc')?1:-1;
		}

		$count_all = $attendee->count();

		if(count($q) > 0){
			$attendees = $attendee->find($q,array(),array($sort_col=>$sort_dir),$limit);
			$count_display_all = $attendee->count($q);
		}else{
			$attendees = $attendee->find(array(),array(),array($sort_col=>$sort_dir),$limit);
			$count_display_all = $attendee->count();
		}

		$aadata = array();

		$form = new Formly();

		$messagelog = new Logmessage();

		$counter = 1 + $pagestart;
		foreach ($attendees as $doc) {

			$extra = $doc;

			$select = $form->checkbox('sel_'.$doc['_id'],'','',false,array('id'=>$doc['_id'],'class'=>'selector'));

			if(isset($doc['conventionPaymentStatus'])){
				if($doc['conventionPaymentStatus'] == 'unpaid'){
					$paymentStatus = '<span class="fontRed fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
				}elseif ($doc['conventionPaymentStatus'] == 'pending') {
					$paymentStatus = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
				}elseif ($doc['conventionPaymentStatus'] == 'cancel') {
					$paymentStatus = '<span class="fontGray fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';

				}else{
					$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
				}
			}else{
				$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
			}

			if(isset($doc['golfPaymentStatus'])){
				if($doc['golfPaymentStatus'] == 'unpaid' && $doc['golf'] == 'Yes'){
					$paymentStatusGolf = '<span class="fontRed fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}elseif ($doc['golfPaymentStatus'] == 'pending') {
					$paymentStatusGolf = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}elseif ($doc['golfPaymentStatus'] == 'cancel') {
					$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}elseif ($doc['golf'] == 'No') {
					$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}else{
					$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}
			}else{
				$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
			}

			if(isset($doc['golf'])){
				if($doc['golf'] == 'Yes'){
					$rowGolfAction = '<a class="icon-"  ><i>&#xe146;</i><span class="paygolf" id="'.$doc['_id'].'" >Golf Status</span>';
				}else{
					$rowGolfAction = '';
				}
			}else{
				$rowGolfAction = '';
			}

			if(isset($doc['golfPaymentStatus']) && isset($doc['conventionPaymentStatus'])){

				if(($doc['golfPaymentStatus'] == 'pending' && $doc['conventionPaymentStatus'] == 'pending') || ($doc['golfPaymentStatus'] == 'unpaid' && $doc['conventionPaymentStatus'] == 'unpaid')){
					$rowBoothAction = '<a class="icon-"  ><i>&#xe1e9;</i><span class="paygolfconvention" id="'.$doc['_id'].'" >Conv & Golf</span>';
				}else{
					$rowBoothAction = '';
				}
			}else{
				$rowGolfAction = '';
			}

			//find message log

			//$rowResendMessage = '';
			//$messagelogs = $messagelog->find(array('user'=>$doc['_id']),array(),array(),array());
			//if(count($messagelogs)>0){

				$rowResendMessage = '<a class="icon-"  ><i>&#xe165;</i><span class="resendmail" id="'.$doc['_id'].'" >Resend Email</span>';
			//}

			$aadata[] = array(
				$counter,
				//$select,
				//(isset($doc['registrationnumber']))?$doc['registrationnumber']:'',
				date('Y-m-d', $doc['createdDate']->sec),
				$doc['email'],
				'<span class="expander" id="'.$doc['_id'].'">'.$doc['firstname'].'</span>',
				$doc['lastname'],
				$doc['company'],
				$doc['regtype'],
				//$doc['country'],
				//$paymentStatus,
				//$paymentStatusGolf,
				//$rowBoothAction.

				//'<a class="icon-"  ><i>&#xe1b0;</i><span class="pay" id="'.$doc['_id'].'" >Convention Status</span>'.
				//$rowGolfAction.

				//'<a class="icon-"  ><i>&#xe14c;</i><span class="pbadge" id="'.$doc['_id'].'" >Print Badge</span>'.
				//'<a class="icon-"  href="'.URL::to('attendee/edit/'.$doc['_id']).'"><i>&#xe164;</i><span>Update Profile</span>'.
				//$rowResendMessage.
				//'<a class="action icon-"><i>&#xe001;</i><span class="del" id="'.$doc['_id'].'" >Delete</span>',

				'extra'=>$extra
			);
			$counter++;
		}


		$result = array(
			'sEcho'=> Input::get('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata,
			'qrs'=>$q
		);

		return Response::json($result);
	}


	public function __post_visitor()
	{


		$fields = array('registrationnumber','createdDate','email','firstname','lastname','company','regtype','country','conventionPaymentStatus','golfPaymentStatus');

		$rel = array('like','like','like','like','like','like','like','like');

		$cond = array('both','both','both','both','both','both','both','both','both');

		$pagestart = Input::get('iDisplayStart');
		$pagelength = Input::get('iDisplayLength');

		$limit = array($pagelength, $pagestart);

		$defsort = 1;
		$defdir = -1;

		$idx = 1;
		$q = array();

		$hilite = array();
		$hilite_replace = array();

		foreach($fields as $field){
			if(Input::get('sSearch_'.$idx))
			{

				$hilite_item = Input::get('sSearch_'.$idx);
				$hilite[] = $hilite_item;
				$hilite_replace[] = '<span class="hilite">'.$hilite_item.'</span>';

				if($rel[$idx] == 'like'){
					if($cond[$idx] == 'both'){
						$q[$field] = new MongoRegex('/'.Input::get('sSearch_'.$idx).'/i');
					}else if($cond[$idx] == 'before'){
						$q[$field] = new MongoRegex('/^'.Input::get('sSearch_'.$idx).'/i');
					}else if($cond[$idx] == 'after'){
						$q[$field] = new MongoRegex('/'.Input::get('sSearch_'.$idx).'$/i');
					}
				}else if($rel[$idx] == 'equ'){
					$q[$field] = Input::get('sSearch_'.$idx);
				}
			}
			$idx++;
		}

		//print_r($q)

		$attendee = new Visitor();

		/* first column is always sequence number, so must be omitted */
		$fidx = Input::get('iSortCol_0');
		if($fidx == 0){
			$fidx = $defsort;
			$sort_col = $fields[$fidx];
			$sort_dir = $defdir;
		}else{
			$fidx = ($fidx > 0)?$fidx - 1:$fidx;
			$sort_col = $fields[$fidx];
			$sort_dir = (Input::get('sSortDir_0') == 'asc')?1:-1;
		}

		$count_all = $attendee->count();

		if(count($q) > 0){
			$attendees = $attendee->find($q,array(),array($sort_col=>$sort_dir),$limit);
			$count_display_all = $attendee->count($q);
		}else{
			$attendees = $attendee->find(array(),array(),array($sort_col=>$sort_dir),$limit);
			$count_display_all = $attendee->count();
		}

		$aadata = array();

		$form = new Formly();

		$messagelog = new Logmessage();

		$counter = 1 + $pagestart;
		foreach ($attendees as $doc) {

			$extra = $doc;

			$select = $form->checkbox('sel_'.$doc['_id'],'','',false,array('id'=>$doc['_id'],'class'=>'selector'));

			if(isset($doc['conventionPaymentStatus'])){
				if($doc['conventionPaymentStatus'] == 'unpaid'){
					$paymentStatus = '<span class="fontRed fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
				}elseif ($doc['conventionPaymentStatus'] == 'pending') {
					$paymentStatus = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
				}elseif ($doc['conventionPaymentStatus'] == 'cancel') {
					$paymentStatus = '<span class="fontGray fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';

				}else{
					$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['conventionPaymentStatus'].'</span>';
				}
			}else{
				$paymentStatus = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
			}

			if(isset($doc['golfPaymentStatus'])){
				if($doc['golfPaymentStatus'] == 'unpaid' && $doc['golf'] == 'Yes'){
					$paymentStatusGolf = '<span class="fontRed fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}elseif ($doc['golfPaymentStatus'] == 'pending') {
					$paymentStatusGolf = '<span class="fontOrange fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}elseif ($doc['golfPaymentStatus'] == 'cancel') {
					$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}elseif ($doc['golf'] == 'No') {
					$paymentStatusGolf = '<span class="fontGray fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}else{
					$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['golfPaymentStatus'].'</span>';
				}
			}else{
				$paymentStatusGolf = '<span class="fontGreen fontBold paymentStatusTable">'.$doc['paymentStatus'].'</span>';
			}

			if(isset($doc['golf'])){
				if($doc['golf'] == 'Yes'){
					$rowGolfAction = '<a class="icon-"  ><i>&#xe146;</i><span class="paygolf" id="'.$doc['_id'].'" >Golf Status</span>';
				}else{
					$rowGolfAction = '';
				}
			}else{
				$rowGolfAction = '';
			}

			if(isset($doc['golfPaymentStatus']) && isset($doc['conventionPaymentStatus'])){

				if(($doc['golfPaymentStatus'] == 'pending' && $doc['conventionPaymentStatus'] == 'pending') || ($doc['golfPaymentStatus'] == 'unpaid' && $doc['conventionPaymentStatus'] == 'unpaid')){
					$rowBoothAction = '<a class="icon-"  ><i>&#xe1e9;</i><span class="paygolfconvention" id="'.$doc['_id'].'" >Conv & Golf</span>';
				}else{
					$rowBoothAction = '';
				}
			}else{
				$rowGolfAction = '';
			}

			//find message log

			//$rowResendMessage = '';
			//$messagelogs = $messagelog->find(array('user'=>$doc['_id']),array(),array(),array());
			//if(count($messagelogs)>0){

				$rowResendMessage = '<a class="icon-"  ><i>&#xe165;</i><span class="resendmail" id="'.$doc['_id'].'" >Resend Email</span>';
			//}

			$aadata[] = array(
				$counter,
				//$select,
				//(isset($doc['registrationnumber']))?$doc['registrationnumber']:'',
				date('Y-m-d', $doc['createdDate']->sec),
				$doc['email'],
				'<span class="expander" id="'.$doc['_id'].'">'.$doc['firstname'].'</span>',
				$doc['lastname'],
				$doc['company'],
				$doc['role'],
				//$doc['country'],
				//$paymentStatus,
				//$paymentStatusGolf,
				//$rowBoothAction.

				//'<a class="icon-"  ><i>&#xe1b0;</i><span class="pay" id="'.$doc['_id'].'" >Convention Status</span>'.
				//$rowGolfAction.

				//'<a class="icon-"  ><i>&#xe14c;</i><span class="pbadge" id="'.$doc['_id'].'" >Print Badge</span>'.
				//'<a class="icon-"  href="'.URL::to('attendee/edit/'.$doc['_id']).'"><i>&#xe164;</i><span>Update Profile</span>'.
				//$rowResendMessage.
				//'<a class="action icon-"><i>&#xe001;</i><span class="del" id="'.$doc['_id'].'" >Delete</span>',

				'extra'=>$extra
			);
			$counter++;
		}


		$result = array(
			'sEcho'=> Input::get('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata,
			'qrs'=>$q
		);

		return Response::json($result);
	}


	public function get_addattendee($type = null){

		if(is_null($type)){
			//$this->crumb->add('attendee/add','New Attendee');
		}else{
			$this->crumb = new Breadcrumb();
			$this->crumb->add('attendee/type/'.$type,'Attendee');

			$this->crumb->add('attendee/type/'.$type,depttitle($type));
			$this->crumb->add('attendee/add','New Attendee');
		}

		$attendee = new Attendee();
		$golfcount = $attendee->count(array('golf'=>'Yes'));

		$form = new Formly();
		return View::make('pop.newattendee')
					->with('form',$form)
					->with('golfcount',$golfcount)
					->with('type',$type)
					->with('crumb',$this->crumb)
					->with('title','New Attendee');

	}

	public function post_del(){
		$id = Input::get('id');

		$user = new Document();

		if(is_null($id)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			$id = new MongoId($id);


			if($user->delete(array('_id'=>$id))){
				Event::fire('document.delete',array('id'=>$id,'result'=>'OK'));
				$result = array('status'=>'OK','data'=>'CONTENTDELETED');
			}else{
				Event::fire('document.delete',array('id'=>$id,'result'=>'FAILED'));
				$result = array('status'=>'ERR','data'=>'DELETEFAILED');				
			}
		}

		print json_encode($result);
	}


	/*public function get_add($type = null){

		if(is_null($type)){
			$this->crumb->add('document/add','New Document');
		}else{
			$this->crumb = new Breadcrumb();
			$this->crumb->add('document/type/'.$type,'Document');

			$this->crumb->add('document/type/'.$type,depttitle($type));
			$this->crumb->add('document/add','New Document');
		}


		$form = new Formly();
		return View::make('document.new')
					->with('form',$form)
					->with('type',$type)
					->with('crumb',$this->crumb)
					->with('title','New Document');

	}*/

	public function post_add($type = null){

		//print_r(Session::get('permission'));

		if(is_null($type)){
			$back = 'document';
		}else{
			$back = 'document/type/'.$type;
		}

	    $rules = array(
	        'title'  => 'required|max:50'
	    );

	    $validation = Validator::make($input = Input::all(), $rules);

	    if($validation->fails()){

	    	return Redirect::to('document/add/'.$type)->with_errors($validation)->with_input(Input::all());

	    }else{

			$data = Input::get();
	    	
	    	//print_r($data);

			//pre save transform
			unset($data['csrf_token']);

			$data['effectiveDate'] = new MongoDate(strtotime($data['effectiveDate']." 00:00:00"));
			$data['expiryDate'] = new MongoDate(strtotime($data['expiryDate']." 00:00:00"));

			$data['createdDate'] = new MongoDate();
			$data['lastUpdate'] = new MongoDate();
			$data['creatorName'] = Auth::user()->fullname;
			$data['creatorId'] = Auth::user()->id;


			$sharelist = explode(',', $data['docShare']);
			if(is_array($sharelist)){
				$usr = new User();
				$shd = array();
				foreach($sharelist as $sh){
					$shd[] = array('email'=>$sh);
				}
				$shared_ids = $usr->find(array('$or'=>$shd),array('id'));

				$data['sharedEmails'] = $sharelist ;
				$data['sharedIds'] = array_values($shared_ids) ;
			}

			$approvallist = explode(',', $data['docApprovalRequest']);
			if(is_array($approvallist)){
				$usr = new User();
				$shd = array();
				foreach($approvallist as $sh){
					$appval[] = array('email'=>$sh);
				}
				$approval_ids = $usr->find(array('$or'=>$appval),array('id','fullname'));

				$data['approvalRequestEmails'] = $approvallist ;
				$data['approvalRequestIds'] = array_values($approval_ids) ;
			}
			
			$docupload = Input::file('docupload');

			$docupload['uploadTime'] = new MongoDate();

			$data['docFilename'] = $docupload['name'];

			$data['docFiledata'] = $docupload;

			$data['docFileList'][] = $docupload;

			$data['tags'] = explode(',',$data['docTag']);

			$document = new Document();

			$newobj = $document->insert($data);


			if($newobj){


				if($docupload['name'] != ''){

					$newid = $newobj['_id']->__toString();

					$newdir = realpath(Config::get('kickstart.storage')).'/'.$newid;

					Input::upload('docupload',$newdir,$docupload['name']);
					
				}

				if(count($data['tags']) > 0){
					$tag = new Tag();
					foreach($data['tags'] as $t){
						$tag->update(array('tag'=>$t),array('$inc'=>array('count'=>1)),array('upsert'=>true));
					}
				}

				$sharedto = explode(',',$data['docShare']);

				if(count($sharedto) > 0  && $data['docShare'] != ''){
					foreach($sharedto as $to){
						Event::fire('document.share',array('id'=>$newobj['_id'],'sharer_id'=>Auth::user()->id,'shareto'=>$to));
					}
				}

				$approvalby = explode(',',$data['docApprovalRequest']);

				if(count($approvalby) > 0 && $data['docApprovalRequest'] != ''){
					foreach($approvalby as $to){
						Event::fire('request.approval',array('id'=>$newobj['_id'],'approvalby'=>$to));
					}
				}

				Event::fire('document.create',array('id'=>$newobj['_id'],'result'=>'OK','department'=>Auth::user()->department,'creator'=>Auth::user()->id));

		    	return Redirect::to($back)->with('notify_success','Document saved successfully');
			}else{
				Event::fire('document.create',array('id'=>$id,'result'=>'FAILED'));
		    	return Redirect::to($back)->with('notify_success','Document saving failed');
			}

	    }

		
	}

	public function get_edit($id = null,$type = null){

		if(is_null($type)){
			$this->crumb->add('document/add','Edit',false);
		}else{
			$this->crumb = new Breadcrumb();
			$this->crumb->add('document/type/'.$type,'Document');

			$this->crumb->add('document/type/'.$type,depttitle($type),false);
			$this->crumb->add('document/edit/'.$id,'Edit',false);
		}


		$doc = new Document();

		$id = (is_null($id))?Auth::user()->id:$id;

		$id = new MongoId($id);

		$doc_data = $doc->get(array('_id'=>$id));

		$doc_data['oldTag'] = $doc_data['docTag'];

		$doc_data['effectiveDate'] = date('Y-m-d', $doc_data['effectiveDate']->sec);
		$doc_data['expiryDate'] = date('Y-m-d', $doc_data['expiryDate']->sec);


		if(is_null($type)){
			$this->crumb->add('document/edit/'.$id,$doc_data['title']);
		}else{
			$this->crumb->add('document/edit/'.$id.'/'.$type,$doc_data['title']);
		}

		$form = Formly::make($doc_data);

		return View::make('document.edit')
					->with('doc',$doc_data)
					->with('form',$form)
					->with('type',$type)
					->with('crumb',$this->crumb)
					->with('title','Edit Document');

	}


	public function post_edit($id,$type = null){

		//print_r(Session::get('permission'));

		if(is_null($type)){
			$back = 'document';
		}else{
			$back = 'document/type/'.$type;
		}

	    $rules = array(
	        'title'  => 'required|max:50'
	    );

	    $validation = Validator::make($input = Input::all(), $rules);

	    if($validation->fails()){

	    	return Redirect::to('document/edit/'.$id.'/'.$type)->with_errors($validation)->with_input(Input::all());

	    }else{

			$data = Input::get();
	    	
			$id = new MongoId($data['id']);

			$data['effectiveDate'] = new MongoDate(strtotime($data['effectiveDate']." 00:00:00"));
			$data['expiryDate'] = new MongoDate(strtotime($data['expiryDate']." 00:00:00"));
			$data['lastUpdate'] = new MongoDate();

			unset($data['csrf_token']);

			$docId = $data['id'];
			unset($data['id']);

			$sharelist = explode(',', $data['docShare']);
			if(is_array($sharelist)){
				$usr = new User();
				$shd = array();
				foreach($sharelist as $sh){
					$shd[] = array('email'=>$sh);
				}
				$shared_ids = $usr->find(array('$or'=>$shd),array('id'));

				$data['sharedEmails'] = $sharelist ;
				$data['sharedIds'] = array_values($shared_ids) ;
			}

			$approvallist = explode(',', $data['docApprovalRequest']);
			if(is_array($approvallist)){
				$usr = new User();
				$shd = array();
				foreach($approvallist as $sh){
					$appval[] = array('email'=>$sh);
				}
				$approval_ids = $usr->find(array('$or'=>$appval),array('id','fullname'));

				$data['approvalRequestEmails'] = $approvallist ;
				$data['approvalRequestIds'] = array_values($approval_ids) ;
			}


			$data['tags'] = explode(',',$data['docTag']);

			$doc = new Document();

			//print_r($data);
			$oldtags = explode(',',$data['oldTag']);

			if(count($data['tags']) > 0){
				$tag = new Tag();
				foreach($data['tags'] as $t){
					if(in_array($t, $oldtags)){
						$add = 0;
					}else{
						$add = 1;
					}
					$tag->update(array('tag'=>$t),array('$inc'=>array('count'=>$add)),array('upsert'=>true));
				}
			}

			unset($data['oldTag']);

			// upload new file , additive

			$docupload = Input::file('docupload');

			$withfile = false;

			if($docupload['name'] != ''){

				$docupload['uploadTime'] = new MongoDate();

				$dirname = $docId;

				$dirname = realpath(Config::get('kickstart.storage')).'/'.$dirname;

				$uploadresult = Input::upload('docupload',$dirname,$docupload['name']);

				if($uploadresult){

					$data['docFilename'] = $docupload['name'];

					$data['docFiledata'] = $docupload;

					$withfile = true;

				}

			}

			if($withfile == true){
				$updatequery = array('$set'=>$data,'$push'=>array('docFileList'=>$docupload));
			}else{
				$updatequery = array('$set'=>$data);
			}

			//print_r($data);

			if($doc->update(array('_id'=>$id),$updatequery)){

				Event::fire('document.update',array('id'=>$id,'result'=>'OK'));

				$sharedto = explode(',',$data['docShare']);

				if(count($sharedto) > 0  && $data['docShare'] != ''){
					foreach($sharedto as $to){
						Event::fire('document.share',array('id'=>$id,'sharer_id'=>Auth::user()->id,'shareto'=>$to));
					}
				}

				$approvalby = explode(',',$data['docApprovalRequest']);

				if(count($approvalby) > 0 && $data['docApprovalRequest'] != ''){
					foreach($approvalby as $to){
						Event::fire('request.approval',array('id'=>$id,'approvalby'=>$to));
					}
				}				

		    	return Redirect::to($back)->with('notify_success','Document saved successfully');
			}else{

				Event::fire('document.update',array('id'=>$id,'result'=>'FAILED'));

		    	return Redirect::to($back)->with('notify_success','Document saving failed');
			}

	    }

		
	}


	


	public function get_attendee($id){
		$id = new MongoId($id);

		$document = new Attendee();

		$towords = new Numberwords();

		$doc = $document->get(array('_id'=>$id));

		return View::make('pop.attendeeview')
		->with('ajaxprintbadge',URL::to('onsite/printbadgecount'))
		->with('ajaxpaymentupdateonsite',URL::to('onsite/paymentupdate'))
		->with('ajaxsavebadgenotes',URL::to('onsite/savebadgenotes'))
		->with('towords',$towords)
		->with('profile',$doc);
	}

	public function get_attendee2($id){
		$id = new MongoId($id);

		$document = new Attendee();

		$towords = new Numberwords();

		$doc = $document->get(array('_id'=>$id));

		return View::make('pop.attendeeview2')
		->with('ajaxprintbadge',URL::to('onsite/printbadgecount'))
		->with('towords',$towords)
		->with('profile',$doc);
	}

	public function get_newboothassist(){
		//$id = new MongoId($id);

		//$document = new Attendee();

		//$doc = $document->get(array('_id'=>$id));
		$form = new Formly();

		return View::make('pop.newboothassist')
		->with('ajaxexhibitorcheck',URL::to('onsite/ajaxexhibitorcheck'))
		->with('ajaxonsitefreeadd',URL::to('onsite/freeaddboothasst'))
		->with('form',$form);
		
		//->with('profile',$doc);
	}

	public function post_ajaxexhibitorcheck(){
		$result = array('status'=>'OK','data'=>'DELETEFAILED');
		print json_encode($result);
	}


	public function get_exhibitor($id){
		$_id = new MongoId($id);

		$boothassistant = new Boothassistant();
		$exhibitor = new Exhibitor();
		$formData = new Operationalform();

		$exhibitorprofile = $exhibitor->get(array('_id'=>$_id));
		$boothassistantdata = $boothassistant->get(array('exhibitorid'=>$id));
		$user_form = $formData->get(array('userid'=>$id));
		$booths = new Booth();

		$booth = '';


		if(isset($exhibitorprofile['boothid'])){
			$_boothID = new MongoId($exhibitorprofile['boothid']);
			$booth = $booths->get(array('_id'=>$_boothID));
		}

		return View::make('pop.exhibitorview')
		->with('ajaxprintbadgeexhibitor',URL::to('onsite/printbadgecountexhibitor'))
		->with('data',$user_form)
		->with('booth',$booth)
		->with('boothassistantdata',$boothassistantdata)
		->with('ajaxonsiteBoothAssistant',URL::to('onsite/addboothassistant'))
		->with('ajaxoverrideentryboothass',URL::to('onsite/overideboothassistant'))
		->with('ajaxprintbadge',URL::to('onsite/printbadgecount'))
		->with('id',$id)
		->with('exhibitor',$exhibitorprofile);
	}



	public function post_addboothassistant(){
		
		$exhibitorid = Input::get('exhibitorid');
		$companyname = Input::get('companyname');
		$companypic = Input::get('companypic');
		$companyemail = Input::get('companypicemail');
		$hallname = Input::get('hallname');
		$boothname = Input::get('boothname');
		$type = Input::get('type');
		$typeid = Input::get('typeid');
		$passname = Input::get('passname');

		$boothassistant = new Boothassistant();

		if(is_null($exhibitorid)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			//find first if record for this exhibitor exist

			$datafind = $boothassistant->get(array('exhibitorid'=>$exhibitorid));
			$data['exhibitorid']=$exhibitorid;
			$data['companyname']  = $companyname;
			$data['companypic']  = $companypic;
			$data['companyemail']  = $companyemail;
			$data['hallname']  = $hallname;
			$data['boothname']  = $boothname;

			if($type == 'freepassname'){
				$data['role'] = 'BA1';
			}else{
				$data['role'] = 'BA2';
			}
			$reg_number[0] = 'A';
			$reg_number[1] = $data['role'];
			//$reg_number[2] = '00';

			$seq = new Sequence();

			$rseq = $seq->find_and_modify(array('_id'=>'boothassistant'),array('$inc'=>array('seq'=>1)),array('seq'=>1),array('new'=>true));

			$reg_number[] = str_pad($rseq['seq'], 6, '0',STR_PAD_LEFT);

			$regnumberall = implode('-',$reg_number);
			
			//cannot find data then create new
			if(!isset($datafind)){
				
				if($obj = $boothassistant->insert($data)){
					
					
					if($objs = $boothassistant->update(array('_id'=>$obj['_id']),array('$set'=>array($type.$typeid=>$passname,$type.$typeid.'regnumber'=>$regnumberall,$type.$typeid.'timestamp'=>new MongoDate() ))) ){

						$result = array('status'=>'OK','message'=>'Imported on '.date('d-m-Y'),'importedtime'=>date('d-m-Y'),'regnumber'=>$regnumberall);
					}
				}

			}else{

				$_id = $datafind['_id'];
				
				
				if($objs = $boothassistant->update(array('_id'=>$_id),array('$set'=>array($type.$typeid=>$passname,$type.$typeid.'regnumber'=>$regnumberall,$type.$typeid.'timestamp'=>new MongoDate() ))) ){
											
					$result = array('status'=>'OK','message'=>'Imported on '.date('d-m-Y'),'importedtime'=>date('d-m-Y'),'regnumber'=>$regnumberall);	
				}
				
				
				
				
			}

			
		}

		print json_encode($result);
	}

	public function post_freeaddboothasst(){

		$exhibitorid = Input::get('exhibitorid');
		$exhibitorname = Input::get('companyname');
		$nameboothasst = Input::get('passname');
		$type = Input::get('type');
		
		$data['companyname'] = $exhibitorname;
		$data['exhibitorid'] = $exhibitorid;

		if($type == 'freepassname'){
			$data['role'] = 'BA1';
		}else{
			$data['role'] = 'BA2';
		}	

		$data['name'] = $nameboothasst;
		$data['creator'] = Auth::user()->fullname;
		$data['creatorid'] = Auth::user()->id;
		$data['type'] = $type;

		$reg_number[0] = 'A';
		$reg_number[1] = $data['role'];
		//$reg_number[2] = '00';

		$seq = new Sequence();

		$rseq = $seq->find_and_modify(array('_id'=>'boothassistant'),array('$inc'=>array('seq'=>1)),array('seq'=>1),array('new'=>true));

		$reg_number[] = str_pad($rseq['seq'], 6, '0',STR_PAD_LEFT);

		$regnumberall = implode('-',$reg_number);

		$data['registrationnumber'] = $regnumberall;

		$addboothass = new Boothassistantonsite();

		if($obj = $addboothass->insert($data) ){
			$result = array('status'=>'OK','regnumber'=>$regnumberall);
		}else{
			$result = array('status'=>'ERR');
		}

		print json_encode($result);

	}


	public function post_overideboothassistant(){
		
		//we can considering already have record
		$exhibitorid = Input::get('exhibitorid');
		
		$type = Input::get('type');
		$typeid = Input::get('typeid');
		$typeid = intval($typeid);
		

		$exhibitor = new Exhibitor();

		if(is_null($exhibitorid)){
			$result = array('status'=>'ERR','data'=>'NOID');
		
		}else{

			$_id = new MongoId($exhibitorid);

			if($objs = $exhibitor->update(array('_id'=>$_id),array('$set'=>array('override'.$type=>$typeid))) ){
				$result = array('status'=>'OK');
			}
			

			
		}

		print json_encode($result);
	}


	public function post_editboothassname(){
		$id = Input::get('dataid');
		$name = Input::get('new_value');
		$boothid = Input::get('elementid');
		
		//$displaytax = Input::get('foo');

		$user = new Boothassistant();

		if(is_null($id)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			$_id = new MongoId($id);

			$boothass = $user->get(array('_id'=>$_id));
			$company = $boothass[$boothid.'regnumber'];

			if($obj = $user->update(array('_id'=>$_id),array('$set'=>array($boothid=>$name)))){
				
				$result = $name;
				
			}else{
				
				$result = array('status'=>'ERR','data'=>'DELETEFAILED');
			}
		}

		return $result;
	}

	public function get_visitor($id){
		$id = new MongoId($id);

		$document = new Visitor();

		$doc = $document->get(array('_id'=>$id));

		return View::make('pop.visitorview')
		->with('ajaxprintbadge',URL::to('onsite/printbadgecountvisitor'))
		->with('profile',$doc);
	}

	public function get_official($id){
		$id = new MongoId($id);

		$document = new Official();

		$doc = $document->get(array('_id'=>$id));

		return View::make('pop.officialview')
		//->with('ajaxprintbadge',URL::to('onsite/printbadgecountvisitor'))
		->with('profile',$doc);
	}

	

	public function post_printbadgecount(){
		$id = Input::get('id');
		
		$badgepickupnotes = Input::get('badgepickupnotes');

		$user = new Attendee();

		if(is_null($id)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			$_id = new MongoId($id);

			//find countbadge
			$userdata = $user->get(array('_id'=>$_id));
			if(isset($userdata['printbadge'])){
				$dataprintcount = $userdata['printbadge'];
				$toadd = $dataprintcount+1;
				if($user->update(array('_id'=>$_id),array('$set'=>array('printbadge'=>$toadd,'badgepickupnotes'=>$badgepickupnotes)))){
					$result = array('status'=>'OK','data'=>'DELETEFAILED');
				}else{
					//Event::fire('paymentstatusgolfconvention.update',array('id'=>$id,'result'=>'FAILED'));
					$result = array('status'=>'ERR','data'=>'DELETEFAILED');
				}
			}else{
				if($user->update(array('_id'=>$_id),array('$set'=>array('printbadge'=>1,'badgepickupnotes'=>$badgepickupnotes)))){
					$result = array('status'=>'OK','data'=>'DELETEFAILED');
				}
			}
		}

		print json_encode($result);
	}

	public function post_savebadgenotes(){
		$id = Input::get('id');
		
		$badgepickupnotes = Input::get('badgepickupnotes');

		$user = new Attendee();

		if(is_null($id)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			$_id = new MongoId($id);

			//find countbadge
			$userdata = $user->get(array('_id'=>$_id));
			
			if($user->update(array('_id'=>$_id),array('$set'=>array('badgepickupnotes'=>$badgepickupnotes)))){
				$result = array('status'=>'OK','data'=>'DELETEFAILED');
			}else{
				$result = array('status'=>'ERR','data'=>'DELETEFAILED');
			}
			
		}

		print json_encode($result);
	}
	

	public function post_paymentupdate(){
		$id = Input::get('id');
		$status = Input::get('status');
		$paymentvia = Input::get('paymentvia');
		$currency = Input::get('currency');
		$totalidr = Input::get('totalidr');
		$totalusd = Input::get('totalusd');

		$user = new Attendee();

		if(is_null($id)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			$_id = new MongoId($id);

			//find countbadge
			$userdata = $user->get(array('_id'=>$_id));
			if(isset($userdata['conventionPaymentStatus'])){
				
				if($user->update(array('_id'=>$_id),array('$set'=>array('conventionPaymentStatus'=>$status,'payonsite'=>'yes','payonsite_paymentvia'=>$paymentvia,'payonsite_currency'=>$currency,'payonsite_totalidr'=>$totalidr,'payonsite_totalusd'=>$totalusd,'payonsite_paydate'=>new MongoDate()  )))){
					//also record on cashier db
					$cashierdb = new Cashier;
					$datacashier['regnumber'] = $userdata['registrationnumber'];
					$datacashier['participantname'] = $userdata['firstname'].' '.$userdata['lastname'];
					$datacashier['regtype'] = $userdata['regtype'];
					$datacashier['paymentvia'] = $paymentvia;
					$datacashier['currency'] = $currency;
					$datacashier['totalidr'] = $totalidr;
					$datacashier['totalusd'] = $totalusd;
					$datacashier['paymentdate'] = new MongoDate();
					$datacashier['cashiername'] = Auth::user()->fullname;
					$datacashier['cashierid'] = Auth::user()->id;

					if($obj = $cashierdb->insert($datacashier)){
						$result = array('status'=>'OK','data'=>'DELETEFAILED');
					}
				}else{
					//Event::fire('paymentstatusgolfconvention.update',array('id'=>$id,'result'=>'FAILED'));
					$result = array('status'=>'ERR','data'=>'ERROR WHILE PROCESSING');
				}
			}else{
				
				$result = array('status'=>'ERR','data'=>'DATA NOT FOUND');
			}
		}

		print json_encode($result);
	}





	public function post_printbadgecountexhibitor(){
		
		$exhibitorid = Input::get('exhibitorid');
		$type = Input::get('type');
		
		
		$data = new Boothassistant();

		if(is_null($exhibitorid)){

			$result = array('status'=>'ERR','data'=>'NOID');
		
		}else{

			
			//find exhibitor
			$boothdata = $data->get(array('exhibitorid'=>$exhibitorid));


			if(isset($boothdata[$type.'print'])){
				$dataprintcount = $boothdata[$type.'print'];
				$toadd = $dataprintcount+1;
				if($data->update(array('_id'=>$boothdata['_id']),array('$set'=>array($type.'print'=>$toadd)))){
					$result = array('status'=>'OK','data'=>'DELETEFAILED');
				}else{
					//Event::fire('paymentstatusgolfconvention.update',array('id'=>$id,'result'=>'FAILED'));
					$result = array('status'=>'ERR','data'=>'DELETEFAILED');
				}
			}else{
				$_id = $boothdata['_id'];
				if($data->update(array('_id'=>$boothdata['_id']),array('$set'=>array($type.'print'=>1)))){
					$result = array('status'=>'OK','data'=>'DELETEFAILED');
				}
			}
		}
		
		print json_encode($result);
	}


	public function post_printbadgecountvisitor(){
		$id = Input::get('id');
		

		$user = new Visitor();

		if(is_null($id)){
			$result = array('status'=>'ERR','data'=>'NOID');
		}else{

			$_id = new MongoId($id);

			//find countbadge
			$userdata = $user->get(array('_id'=>$_id));
			if(isset($userdata['printbadge'])){
				$dataprintcount = $userdata['printbadge'];
				$toadd = $dataprintcount+1;
				if($user->update(array('_id'=>$_id),array('$set'=>array('printbadge'=>$toadd)))){
					$result = array('status'=>'OK','data'=>'DELETEFAILED');
				}else{
					//Event::fire('paymentstatusgolfconvention.update',array('id'=>$id,'result'=>'FAILED'));
					$result = array('status'=>'ERR','data'=>'DELETEFAILED');
				}
			}else{
				if($user->update(array('_id'=>$_id),array('$set'=>array('printbadge'=>1)))){
					$result = array('status'=>'OK','data'=>'DELETEFAILED');
				}
			}
		}

		print json_encode($result);
	}




	public function get_report()
	{

		$visitor = new Visitor();
		$official = new Official();
		$attendee = new Attendee();
	    

		$stat['VS'] = $visitor->count(array('role'=>'VS'));

		$stat['VIP'] = $visitor->count(array('role'=>'VIP'));

		$stat['VVIP'] = $visitor->count(array('role'=>'VVIP'));

		$stat['OC'] = $visitor->count(array('role'=>'OC'));

		$stat['media'] = $visitor->count(array('role'=>'MDA'));


		$stat['COM'] = $official->count(array('role'=>'COM'));
		$stat['BOD'] = $official->count(array('role'=>'BOD'));
		$stat['SD'] = $official->count(array('role'=>'SD'));
		$stat['ORG'] = $official->count(array('role'=>'ORG'));
		$stat['SPK'] = $official->count(array('role'=>'SPK'));
		$stat['ATB'] = $official->count(array('role'=>'ATB'));



		$stat['Visitor'] = $visitor->count();
		$stat['official'] = $official->count();

		//Participant stat

		$stat['registeronsite'] = $attendee->count(array('registeronsite'=>'true'));

		$this->crumb = new Breadcrumb();
		$this->crumb->add('','On Site Report');
		
		

		return View::make('onsite.report')
			->with('title','On site Report')
			->with('stat',$stat)
			
			->with('crumb',$this->crumb);
	}	

}