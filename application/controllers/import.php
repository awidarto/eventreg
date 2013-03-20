<?php

class Import_Controller extends Base_Controller {

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
		$this->crumb = new Breadcrumb();

		date_default_timezone_set('Asia/Jakarta');
		$this->filter('before','auth');

		$this->crumb->add('import','Import Data');
	}

	public function get_index()
	{

		$form = new Formly();

		return View::make('import.import')
			->with('title','Import Data')
			->with('form',$form)
			->with('crumb',$this->crumb);
	}

	public function get_exhibitor($exid)
	{
		$this->crumb = new Breadcrumb();
		$this->crumb->add('exhibitor','Exhibitor');
		$this->crumb->add('import','Import Worker Data');

		$ex = new Exhibitor();

		$_id = new MongoId($exid);

		$exhibitor = $ex->get(array('_id'=>$_id));

		$exhibitor['exhibitor_id'] = $exid;

		$form = new Formly($exhibitor);

		return View::make('import.eximport')
			->with('title','Import Exhibitor Data')
			->with('exid',$exid)
			->with('form',$form)
			->with('crumb',$this->crumb);
	}

	public function get_preview($id,$type = null)
	{

		$this->crumb->add('import/preview','Preview');

		if($type == 'exhibitor'){
			$import = new Import();

			$_importid = new MongoId($id);

			$exhibitor = $import->get(array('_id'=>$_importid));

			$form = new Formly($exhibitor);

			$this->crumb->add('import/preview/'.$id.'/'.$type,'Exhibitor\'s Worker');

		}else{

			$exhibitor = false;

			$form = new Formly();

			$this->crumb->add('import/preview/'.$id,'Attendee');

		}

		$imp = new Importcache();

		$ihead = $imp->get(array('cache_id'=>$id, 'cache_head'=>true));

		$heads = array();

		//$colclass = array('span3','span3','span3','span1','span1','span1','','','','','','','');

		$colclass = array();

		$cnt = 0;

		$searchinput = array();


		$select_all = $form->checkbox('select_all','','',false,array('id'=>'select_all'));

		$override_all = $form->checkbox('override_all','','',false,array('id'=>'override_all'));

		if(is_null($type)){
			$valid_heads = 'eventreg.valid_heads';
			$valid_heads_select = 'eventreg.valid_head_selects';
		}else{
			$valid_heads = 'eventreg.'.$type.'_valid_heads';
			$valid_heads_select = 'eventreg.'.$type.'_valid_head_selects';
		}

		foreach ($ihead['head_labels'] as $h) {

			$hidden_head = $form->hidden('mapped_'.$cnt,$h);

			$heads[$cnt] = $h.$hidden_head;

			$searchinput[$cnt] = $form->select('map_'.$cnt,'',Config::get($valid_heads_select),$h);
			if(!in_array($h, Config::get($valid_heads))){
				$heads[$cnt] = '<span class="invalidhead">'.$heads[$cnt].'</span>';

			}else{

			}

			$cnt++;
		}



		$head_count = count($heads);

		$colclass = array_merge(array('',''),$colclass);

		$searchinput = array_merge(array($select_all,$override_all),$searchinput);

		$heads = array_merge(array('Select','Override'),$heads);

		if(is_null($type)){
			$ajaxsource = URL::to('import/loader/'.$id);
			$commiturl = 'import/commit/'.$id;
			$disablesort = '0,5,6';
		}else{
			$ajaxsource = URL::to('import/loader/'.$id.'/'.$type);
			$commiturl = 'import/commit/'.$id.'/'.$type;
			$disablesort = '0,1';
		}

		return View::make('tables.import')
			->with('title','Data Preview')
			->with('newbutton','Commit Import')
			->with('disablesort',$disablesort)
			->with('addurl','')
			->with('commiturl',$commiturl)
			->with('importid',$id)
			->with('reimporturl','import')
			->with('form',$form)
			->with('head_count',$head_count)
			->with('colclass',$colclass)
			->with('searchinput',$searchinput)
			->with('ajaxsource',$ajaxsource)
			->with('ajaxdel',URL::to('attendee/del'))
			->with('crumb',$this->crumb)
			->with('heads',$heads)
			->with('type',$type)
			->with('exhibitor',$exhibitor)
			->nest('row','attendee.rowdetail');
	}

	public function post_loader($id,$type = null)
	{

		$imp = new Importcache();

		$ihead = $imp->get(array('cache_id'=>$id, 'cache_head'=>true));

		$fields = $ihead['head_labels'];


		//$fields = array('registrationnumber','firstname','lastname','email','company','position','mobile','companyphone','companyfax','createdDate','lastUpdate');

		$rel = array('like','like','like','like','like','like','like','like','like','like');

		$cond = array('both','both','both','both','both','both','both','both','both','both');

		$pagestart = Input::get('iDisplayStart');
		$pagelength = Input::get('iDisplayLength');

		$limit = array($pagelength, $pagestart);

		$defsort = 1;
		$defdir = -1;

		$idx = 0;
		$q = array('cache_head'=>false,'cache_id'=>$id,'cache_commit'=>false);

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

		$attendee = new Importcache();

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

		if(is_null($type)){
			$attending = new Attendee();

			$email_arrays = array();

			foreach($attendees as $e){
				$email_arrays[] = array('email'=>$e['email']);
			}

			//print_r($email_arrays);

			$email_check = $attending->find(array('$or'=>$email_arrays),array('email'=>1,'_id'=>-1));

			$email_arrays = array();

			foreach($email_check as $ec){
				$email_arrays[] = $ec['email'];
			}

		}else{
			if($type == 'exhibitor'){
				$attending = new Official();
			}

			$email_arrays = array();

			foreach($attendees as $e){
				$email_arrays[] = array('firstname'=>$e['first_name']);
			}

			//print_r($email_arrays);
			if(!empty($email_arrays)){
				$email_check = $attending->find(array('$or'=>$email_arrays),array('firstname'=>1,'_id'=>-1));
				$email_arrays = array();

				foreach($email_check as $ec){
					$email_arrays[] = $ec['firstname'];
				}

			}else{
				$email_arrays = array();
			}


		}


		//print_r($email_arrays);


		$aadata = array();

		$form = new Formly();

		$counter = 1 + $pagestart;

		foreach ($attendees as $doc) {

			$extra = $doc;


			$adata = array();

			for($i = 0; $i < count($fields); $i++){

				if(in_array($doc[$fields[$i]], $email_arrays)){
					$adata[$i] = '<span class="duplicateemail">'.$doc[$fields[$i]].'</spam>';
				}else{
					$adata[$i] = $doc[$fields[$i]];
				}

			}

			//print_r($adata);


			$select = $form->checkbox('sel[]','',$doc['_id'],false,array('id'=>$doc['_id'],'class'=>'selector'));

			if(is_null($type)){
				$compindex = 'email';
			}else{
				if($type == 'exhibitor'){
					$compindex = 'first_name';
				}
			}

			if(in_array($doc[$compindex], $email_arrays)){
				$override = $form->checkbox('over[]','',$doc['_id'],'',array('id'=>'over_'.$doc['_id'],'class'=>'overselector'));
				$exist = $form->hidden('existing[]',$doc['_id']);
			}else{
				$override = '';
				$exist = '';
			}

			$adata = array_merge(array($select,$override.''.$exist),$adata);

			$adata['extra'] = $extra;

			$aadata[] = $adata;

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

	public function post_commit($importid){

		$conventionrate = Config::get('eventreg.conventionrate');
		$golfrate = Config::get('eventreg.golffee');
		$dateA = date('Y-m-d G:i'); 
		$earlybirddate = Config::get('eventreg.earlybirdconventiondate'); 

		$data = Input::all();

		$type = (is_null($data['type']))?'attendee':$data['type'];

		$importsession = new Import();

		$_imid = new MongoId($importid);

		$pic = $importsession->get(array('_id'=>$_imid));

		if(isset($data['sel'])){

			$commitedobj = array();

			$idvals = array();

			foreach ($data['sel'] as $idval) {
				$_id = new MongoId($idval);
				$idvals[] = array('_id'=>$_id);
			}

			$icache = new Importcache();

			$commitobj = $icache->find(array('$or'=>$idvals));

			//print_r($commitobj);
			if($type == 'attendee'){
				$attendee = new Attendee();
				$i2o = Config::get('eventreg.attendee_map');

			}elseif($type == 'exhibitor'){
				$attendee = new Worker();
				$i2o = Config::get('eventreg.exhibitor_map');
			}

			//print_r($i2o);

			$commit_count = 0;

			foreach($commitobj as $comobj){
				//print_r($comobj);

				$tocommit = Config::get('eventreg.'.$type.'_template');


				for($i = 0; $i < $data['head_count']; $i++ ){

					$okey = $data['map_'.$i];
					if(isset($i2o[$okey])){
						$tocommit[$i2o[$okey]] = $comobj[$okey];
						//print $okey.' --> '.$i2o[$okey]."<br />\r\n";
					}
				}

				// import and group identifier
				$tocommit['cache_id'] = $comobj['cache_id'];
				$tocommit['cache_obj'] = $comobj['_id'];

				if($type == 'attendee'){
					$tocommit['groupName'] = $comobj['groupName'];
					$tocommit['groupId'] = $comobj['groupId'];
				}else if($type == 'exhibitor'){
					$tocommit['exhibitor_id'] = $data['exhibitor_id'];
				}

				if(isset($data['over'])){
					if( in_array($comobj['_id']->__toString(), $data['over'])){
						$override = true;
					}else{
						$override = false;
					}
				}else{
					$override = false;
				}


				if(isset($data['existing'])){
					if( in_array($comobj['_id']->__toString(), $data['existing'])){
						$existing = true;
					}else{
						$existing = false;
					}
				}else{
					$existing = false;
				}

				//print_r($tocommit);
				//print 'override -> '.$override."\r\n";


				if($override == true){


					if($type == 'attendee'){
						
						$attobj = $attendee->get(array('email'=>$tocommit['email']));

						$tocommit['lastUpdate'] = new MongoDate();
						$tocommit['role'] = 'attendee';

						if(isset($tocommit['conventionPaymentStatus'])){
							$tocommit['conventionPaymentStatus'] = $attobj['conventionPaymentStatus'];
						}

						if(isset($tocommit['golfPaymentStatus'])){
							$tocommit['golfPaymentStatus'] = $attobj['golfPaymentStatus'];
						}

						$reg_number = array();
						$seq = new Sequence();

						if(isset($attobj['registrationnumber']) && $attobj['registrationnumber'] != ''){
							$reg_number = explode('-',$attobj['registrationnumber']);

							$reg_number[0] = 'C';
							$reg_number[1] = $tocommit['regtype'];
							$reg_number[2] = ($tocommit['attenddinner'] == 'Yes')?str_pad(Config::get('eventreg.galadinner'), 2,'0',STR_PAD_LEFT):'00';

						}else if($attobj['registrationnumber'] == ''){
							$reg_number = array();
							$rseq = $seq->find_and_modify(array('_id'=>'attendee'),array('$inc'=>array('seq'=>1)),array('seq'=>1),array('new'=>true));

							$reg_number[0] = 'C';
							$reg_number[1] = $tocommit['regtype'];
							$reg_number[2] = ($tocommit['attenddinner'] == 'Yes')?str_pad(Config::get('eventreg.galadinner'), 2,'0',STR_PAD_LEFT):'00';

							$regsequence = str_pad($rseq['seq'], 6, '0',STR_PAD_LEFT);

							$reg_number[3] = $regsequence;

							$tocommit['regsequence'] = $regsequence;

						}

						$tocommit['registrationnumber'] = implode('-',$reg_number);

						$plainpass = rand_string(8);

						$tocommit['pass'] = Hash::make($plainpass);

						//golf sequencer
						$tocommit['golfSequence'] = 0;

						if($tocommit['golf'] == 'Yes' && $attobj['golf'] == 'No'){
							$gseq = $seq->find_and_modify(array('_id'=>'golf'),array('$inc'=>array('seq'=>1)),array('seq'=>1),array('new'=>true,'upsert'=>true));
							$tocommit['golfSequence'] = $gseq['seq'];
						}

						if($data['updatepass'] == 'Yes'){
							$plainpass = rand_string(8);
							$tocommit['pass'] = Hash::make($plainpass);
						}else{
							$tocommit['pass'] = $attobj['pass'];
							$plainpass = 'nochange';
						}

						if( $attobj['golfPaymentStatus']=='paid' || $attobj['conventionPaymentStatus']=='paid'){						

							$tocommit['regtype'] == $attobj['regtype'];
							$tocommit['golf'] == $attobj['golf'];

						}else{
							if(strtotime($dateA) > strtotime($earlybirddate)){

								if($data['foc']=='Yes'){
								
									$tocommit['totalIDR'] = '-';
									$tocommit['totalUSD'] = '-';
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
									$tocommit['conventionPaymentStatus'] = 'free';
									$tocommit['golfPaymentStatus'] = 'free';

								}elseif($data['earlybird'] == 'No'){

									$tocommit['overrideratenormal'] = 'no';
									//normalrate valid
									if($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'No'){
										$tocommit['totalIDR'] = $conventionrate['PD-normal'];
										$tocommit['totalUSD'] = '';
										$tocommit['regPD'] = $conventionrate['PD-normal'];
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'Yes'){
										$tocommit['totalIDR'] = $conventionrate['PD-normal']+$golfrate;
										$tocommit['totalUSD'] = '';
										$tocommit['regPD'] = $conventionrate['PD-normal'];
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'No'){
										$tocommit['totalIDR'] = '';
										$tocommit['totalUSD'] = $conventionrate['PO-normal'];
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = $conventionrate['PO-normal'];
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'Yes'){
										$tocommit['totalIDR'] = $golfrate;
										$tocommit['totalUSD'] = $conventionrate['PO-normal'];
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = $conventionrate['PO-normal'];
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'SD'){
										$tocommit['totalIDR'] = $conventionrate['SD'];
										$tocommit['totalUSD'] = '';
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = $conventionrate['SD'];
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'SO'){
										$tocommit['totalIDR'] = '';
										$tocommit['totalUSD'] = $conventionrate['SO'];
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = $conventionrate['SO'];
									}

									

								}else{

									$tocommit['overrideratenormal'] = 'yes';
									if($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'No'){
										$tocommit['totalIDR'] = $conventionrate['PD-earlybird'];
										$tocommit['totalUSD'] = '';
										$tocommit['regPD'] = $conventionrate['PD-earlybird'];
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'Yes'){
										$tocommit['totalIDR'] = $conventionrate['PD-earlybird']+$golfrate;
										$tocommit['totalUSD'] = '';
										$tocommit['regPD'] = $conventionrate['PD-earlybird'];
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'No'){
										$tocommit['totalIDR'] = '';
										$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = $conventionrate['PO-earlybird'];
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'Yes'){
										$tocommit['totalIDR'] = $golfrate;
										$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = $conventionrate['PO-earlybird'];
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'SD'){
										$tocommit['totalIDR'] = $conventionrate['SD'];
										$tocommit['totalUSD'] = '';
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = $conventionrate['SD'];
										$tocommit['regSO'] = '';
									}elseif ($tocommit['regtype'] == 'SO'){
										$tocommit['totalIDR'] = '';
										$tocommit['totalUSD'] = $conventionrate['SO'];
										$tocommit['regPD'] = '';
										$tocommit['regPO'] = '';
										$tocommit['regSD'] = '';
										$tocommit['regSO'] = $conventionrate['SO'];
									}

								}
							}else{

								if($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'No'){
									$tocommit['totalIDR'] = $conventionrate['PD-earlybird'];
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = $conventionrate['PD-earlybird'];
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'Yes'){
									$tocommit['totalIDR'] = $conventionrate['PD-earlybird']+$golfrate;
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = $conventionrate['PD-earlybird'];
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'No'){
									$tocommit['totalIDR'] = '';
									$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = $conventionrate['PO-earlybird'];
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'Yes'){
									$tocommit['totalIDR'] = $golfrate;
									$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = $conventionrate['PO-earlybird'];
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'SD'){
									$tocommit['totalIDR'] = $conventionrate['SD'];
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = $conventionrate['SD'];
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'SO'){
									$tocommit['totalIDR'] = '';
									$tocommit['totalUSD'] = $conventionrate['SO'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = $conventionrate['SO'];
								}
							}
						}

						if($attendee->update(array('email'=>$tocommit['email']),array('$set'=>$tocommit))){

							Event::fire('attendee.update',array($attobj['_id'],$plainpass,$pic['email'],$pic['firstname'].$pic['lastname']));

							//if($data['sendattendee'] == 'Yes'){
							//	// send message to each attendee
								//Event::fire('attendee.update',array($comobj['_id'],$plainpass));
							//}

							$commitedobj[] = $tocommit;

							//$icache->update(array('email'=>$tocommit['email']),array('$set'=>array('cache_commit'=>true)));
							$icache->update(array('_id'=>$tocommit['cache_obj']),array('$set'=>array('cache_commit'=>true)));

							$commit_count++;

						}

					}else if($type == 'exhibitor'){


						if($attendee->update(array('firstname'=>$tocommit['firstname'],'lastname'=>$tocommit['lastname']),array('$set'=>$tocommit))){

							Event::fire($type.'.update',array($attobj['_id'],$plainpass,$pic['email'],$pic['firstname'].$pic['lastname']));

							$commitedobj[] = $tocommit;

							$icache->update(array('_id'=>$tocommit['cache_obj']),array('$set'=>array('cache_commit'=>true)));

							$commit_count++;

						}

					}
					
				}else if($existing == false){
					
					
					if($type == 'attendee'){

						$tocommit['createdDate'] = new MongoDate();
						$tocommit['lastUpdate'] = new MongoDate();

						$tocommit['role'] = 'attendee';
						$tocommit['paymentStatus'] = 'unpaid';
						$tocommit['conventionPaymentStatus'] = 'unpaid';

						if($tocommit['golf'] == 'Yes'){
							$tocommit['golfPaymentStatus'] = 'unpaid';
						}else{
							$tocommit['golfPaymentStatus'] = '-';
						}

						$reg_number = array();

						$reg_number[0] = 'C';
						$reg_number[1] = $tocommit['regtype'];
						$reg_number[2] = ($tocommit['attenddinner'] == 'Yes')?str_pad(Config::get('eventreg.galadinner'), 2,'0',STR_PAD_LEFT):'00';

						$seq = new Sequence();

						$rseq = $seq->find_and_modify(array('_id'=>'attendee'),array('$inc'=>array('seq'=>1)),array('seq'=>1),array('new'=>true));

						//$reg_number[3] = str_pad($rseq['seq'], 6, '0',STR_PAD_LEFT);

						$regsequence = str_pad($rseq['seq'], 6, '0',STR_PAD_LEFT);

						$reg_number[3] = $regsequence;

						$tocommit['regsequence'] = $regsequence;


						$tocommit['registrationnumber'] = implode('-',$reg_number);

						$plainpass = rand_string(8);

						$tocommit['pass'] = Hash::make($plainpass);

						//golf sequencer
						$tocommit['golfSequence'] = 0;

						if($tocommit['golf'] == 'Yes'){
							$gseq = $seq->find_and_modify(array('_id'=>'golf'),array('$inc'=>array('seq'=>1)),array('seq'=>1),array('new'=>true,'upsert'=>true));
							$tocommit['golfSequence'] = $gseq['seq'];
						}

						if(strtotime($dateA) > strtotime($earlybirddate)){

							if($data['foc']=='Yes'){

								$tocommit['totalIDR'] = '-';
								$tocommit['totalUSD'] = '-';
								$tocommit['regPD'] = '';
								$tocommit['regPO'] = '';
								$tocommit['regSD'] = '';
								$tocommit['regSO'] = '';
								$tocommit['conventionPaymentStatus'] = 'free';
								$tocommit['golfPaymentStatus'] = 'free';

							}elseif($data['earlybird'] == 'No'){
								
								$tocommit['overrideratenormal'] = 'no';
								//normalrate valid
								if($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'No'){
									$tocommit['totalIDR'] = $conventionrate['PD-normal'];
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = $conventionrate['PD-normal'];
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'Yes'){
									$tocommit['totalIDR'] = $conventionrate['PD-normal']+$golfrate;
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = $conventionrate['PD-normal'];
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'No'){
									$tocommit['totalIDR'] = '';
									$tocommit['totalUSD'] = $conventionrate['PO-normal'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = $conventionrate['PO-normal'];
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'Yes'){
									$tocommit['totalIDR'] = $golfrate;
									$tocommit['totalUSD'] = $conventionrate['PO-normal'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = $conventionrate['PO-normal'];
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'SD'){
									$tocommit['totalIDR'] = $conventionrate['SD'];
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = $conventionrate['SD'];
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'SO'){
									$tocommit['totalIDR'] = '';
									$tocommit['totalUSD'] = $conventionrate['SO'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = $conventionrate['SO'];
								}
									

							}else{

								$tocommit['overrideratenormal'] = 'yes';

								if($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'No'){
									$tocommit['totalIDR'] = $conventionrate['PD-earlybird'];
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = $conventionrate['PD-earlybird'];
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'Yes'){
									$tocommit['totalIDR'] = $conventionrate['PD-earlybird']+$golfrate;
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = $conventionrate['PD-earlybird'];
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'No'){
									$tocommit['totalIDR'] = '';
									$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = $conventionrate['PO-earlybird'];
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'Yes'){
									$tocommit['totalIDR'] = $golfrate;
									$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = $conventionrate['PO-earlybird'];
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'SD'){
									$tocommit['totalIDR'] = $conventionrate['SD'];
									$tocommit['totalUSD'] = '';
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = $conventionrate['SD'];
									$tocommit['regSO'] = '';
								}elseif ($tocommit['regtype'] == 'SO'){
									$tocommit['totalIDR'] = '';
									$tocommit['totalUSD'] = $conventionrate['SO'];
									$tocommit['regPD'] = '';
									$tocommit['regPO'] = '';
									$tocommit['regSD'] = '';
									$tocommit['regSO'] = $conventionrate['SO'];
								}

							}

						}else{
							
							if($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'No'){
								$tocommit['totalIDR'] = $conventionrate['PD-earlybird'];
								$tocommit['totalUSD'] = '';
								$tocommit['regPD'] = $conventionrate['PD-earlybird'];
								$tocommit['regPO'] = '';
								$tocommit['regSD'] = '';
								$tocommit['regSO'] = '';
							}elseif ($tocommit['regtype'] == 'PD' && $tocommit['golf'] == 'Yes'){
								$tocommit['totalIDR'] = $conventionrate['PD-earlybird']+$golfrate;
								$tocommit['totalUSD'] = '';
								$tocommit['regPD'] = $conventionrate['PD-earlybird'];
								$tocommit['regPO'] = '';
								$tocommit['regSD'] = '';
								$tocommit['regSO'] = '';
							}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'No'){
								$tocommit['totalIDR'] = '';
								$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
								$tocommit['regPD'] = '';
								$tocommit['regPO'] = $conventionrate['PO-earlybird'];
								$tocommit['regSD'] = '';
								$tocommit['regSO'] = '';
							}elseif ($tocommit['regtype'] == 'PO' && $tocommit['golf'] == 'Yes'){
								$tocommit['totalIDR'] = $golfrate;
								$tocommit['totalUSD'] = $conventionrate['PO-earlybird'];
								$tocommit['regPD'] = '';
								$tocommit['regPO'] = $conventionrate['PO-earlybird'];
								$tocommit['regSD'] = '';
								$tocommit['regSO'] = '';
							}elseif ($tocommit['regtype'] == 'SD'){
								$tocommit['totalIDR'] = $conventionrate['SD'];
								$tocommit['totalUSD'] = '';
								$tocommit['regPD'] = '';
								$tocommit['regPO'] = '';
								$tocommit['regSD'] = $conventionrate['SD'];
								$tocommit['regSO'] = '';
							}elseif ($tocommit['regtype'] == 'SO'){
								$tocommit['totalIDR'] = '';
								$tocommit['totalUSD'] = $conventionrate['SO'];
								$tocommit['regPD'] = '';
								$tocommit['regPO'] = '';
								$tocommit['regSD'] = '';
								$tocommit['regSO'] = $conventionrate['SO'];
							}
						}



					}elseif($type == 'exhibitor'){
						$tocommit['createdDate'] = new MongoDate();
						$tocommit['lastUpdate'] = new MongoDate();

						$tocommit['role'] = 'worker';

					}

					
						

					
					$attendeeDB = new Worker();
						
					
					if($obj = $attendeeDB->insert($tocommit)){

						//if($data['sendattendee'] == 'Yes'){
							// send message to each attendee
						if($type == 'attendee'){
							Event::fire($type.'.create',array($obj['_id'],$plainpass,$pic['email'],$pic['firstname'].$pic['lastname']));
						}elseif ($type == 'exhibitor') {
							$plainpass = 'nopass';
							Event::fire($type.'.create',array($obj['_id'],$plainpass,$data['email'],$data['firstname'].$data['lastname']));
						}
						// /}

						$commitedobj[] = $tocommit;

						$icache->update(array('_id'=>$tocommit['cache_obj']),array('$set'=>array('cache_commit'=>true)));

						$commit_count++;

					}

				}

			}

			if($type == 'attendee' && $data['attendeesummary'] == 'Yes'){

				//print_r($commitedobj);

				$body = View::make('email.regsummarypic')
					->with('pic',$pic)
					->with('attendee',$commitedobj)
					->render();


				Message::to($pic['email'])
				    ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
				    ->cc(Config::get('eventreg.reg_finance_email'), Config::get('eventreg.reg_finance_name'))
				    ->subject('Registration Summary – '.$pic['company'])
				    ->body( $body )
				    ->html(true)
				    ->send();


				// send to pic , use
				// $commitedobj as input array
				// $pic as PIC data
			}

			//if($data['sendpic'] == 'Yes'){

				
			//}

			return Redirect::to('import/preview/'.$importid.'/'.$type)->with('notify_success','Committing '.$commit_count.' record(s)');
		}else{
			return Redirect::to('import/preview/'.$importid.'/'.$type)->with('notify_success','No entry selected to commit');
		}

	}

	public function post_preview($type = null,$exid = null)
	{

		//print_r(Session::get('permission'));
		$back = 'import/preview';

		if(is_null($type)){
		    $rules = array(
		        'email'  => 'required',
		        'firstname'  => 'required',
		        'lastname'  => 'required',
		        'position' => 'required',
		        'groupName' => 'required'
		    );			
		}else{
			if($type == 'exhibitor'){
			    $rules = array(
			        'email'  => 'required',
			        'firstname'  => 'required',
			        'lastname'  => 'required'
			    );			

			}
		}

	    $validation = Validator::make($input = Input::all(), $rules);

	    if($validation->fails()){

	    	return Redirect::to('import/'.$type.'/'.$exid)->with_errors($validation)->with_input(Input::all());


	    }else{

			$data = Input::get();

	    	//print_r($data);

			//pre save transform
			unset($data['csrf_token']);

			$data['createdDate'] = new MongoDate();
			$data['lastUpdate'] = new MongoDate();
			$data['creatorName'] = Auth::user()->fullname;
			$data['creatorId'] = Auth::user()->id;

			if(is_null($type)){
				if($data['groupId']=='' && $data['groupName'] != ''){
					//create new group
					$group = new Group();

					$groupdata = array(
						'firstname' => $data['firstname'],
						'lastname' => $data['lastname'],
						'email'=> $data['email'],
						'company' => $data['company'],
						'groupname'=>$data['groupName']
					);

					if($groupobj = $group->insert($groupdata)){
						$data['groupId'] = $groupobj['_id']->__toString();
					}

				}

			}


			$docupload = Input::file('docupload');

			$docupload['uploadTime'] = new MongoDate();

			$docupload['name'] = fixfilename($docupload['name']);

			$data['docFilename'] = $docupload['name'];

			$data['docFiledata'] = $docupload;

			$data['docFileList'][] = $docupload;

			$document = new Import();

			$newobj = $document->insert($data);


			if($newobj){


				if($docupload['name'] != ''){

					$newid = $newobj['_id']->__toString();

					if(is_null($type)){
						$newdir = realpath(Config::get('kickstart.storage')).'/imports/'.$newid;
					}else{
						$newdir = realpath(Config::get('kickstart.storage')).'/imports/'.$type.'/'.$newid;
					}


					Input::upload('docupload',$newdir,$docupload['name']);

				}

				if($newobj['docFilename'] != ''){

					$icache = new Importcache();

					$c_id = $newobj['_id']->__toString();

					if(is_null($type)){
						$filepath = Config::get('kickstart.storage').'/imports/'.$c_id.'/'.$newobj['docFilename'];
					}else{
						$filepath = Config::get('kickstart.storage').'/imports/'.$type.'/'.$c_id.'/'.$newobj['docFilename'];
					}

					$excel = new Excel();

					$extension = File::extension($filepath);

					$xls = $excel->load($filepath,$extension);

					$rows = $xls['cells'];

					if(is_null($type)){
						$heads = $rows[1];
					}else{
						if($type == 'exhibitor'){
							$heads = $rows[6];
						}
					}

					//print_r($heads);

					$theads = array();
					for($x = 0;$x < count($heads);$x++){
						if(trim($heads[$x]) == ''){
						}else{
							$theads[] = $heads[$x];
						}
					}

					$heads = $theads;

					//print_r($heads);

					//remove first two lines
					if(is_null($type)){
						$headindex = 1;
					}else{
						if($type == 'exhibitor'){
							$headindex = 6;
						}
					}

					for($i = 0;$i <= $headindex;$i++){
						array_shift($rows);
					}
					//array_shift($rows);
					//unset($rows[0]);
					//unset($rows[1]);


					//print_r($rows);

					//remove empty line arrays
					$trows = array();
					for($x = 0;$x < count($rows);$x++){
						if(trim(implode('',$rows[$x])) == ''){
							unset($rows[$x]);
						}else{
							$trows[] = $rows[$x];
						}
					}

					//print_r($trows);

					$rows = $trows;

					$inhead = array();

					$chead = array();

					foreach ($heads as $head) {
						$label = str_replace(array('.','\''), '', $head);

						$label = preg_replace('/[ ][ ]+/', ' ', $label);

						$label = str_replace(array('/',' '), '_', $label);
						$label = strtolower(trim($label));

						$chead[] = $label;
					}

					$inhead['head_labels'] = $chead;
					$inhead['cache_head'] = true;
					$inhead['cache_id'] = $c_id;
					$inhead['cache_commit'] = false;

					//print_r($inhead);

					$icache->insert($inhead);

					foreach($rows as $row){

						if(implode('',$row) != ''){
							$ins = array();
							for($i = 0; $i < count($heads); $i++){

								$label = str_replace(array('.','\''), '', $heads[$i]);
								$label = preg_replace('/[ ][ ]+/', ' ', $label);

								$label = str_replace(array('/',' '), '_', $label);

								$label = strtolower(trim($label));

								$ins[$label] = $row[$i];
							}

							$ins['cache_head'] = false;
							$ins['cache_id'] = $c_id;
							$ins['cache_commit'] = false;

							if(is_null($type)){
								$ins['groupId'] =   $newobj['groupId'];
								$ins['groupName'] = $newobj['groupName'];								
							}

							//print_r($ins);

							$icache->insert($ins);
						}

					}

				}

				Event::fire('import.create',array('id'=>$newobj['_id'],'result'=>'OK','department'=>Auth::user()->department,'creator'=>Auth::user()->id));

				if(is_null($type)){
					$back = $back.'/'.$newobj['_id'];
				}else{
					if($type == 'exhibitor'){
						$back = $back.'/'.$newobj['_id'].'/exhibitor';
					}
				}

		    	return Redirect::to($back)->with('notify_success','Document uploaded successfully');
			}else{
				Event::fire('import.create',array('id'=>$id,'result'=>'FAILED'));
		    	return Redirect::to($back)->with('notify_success','Document upload failed');
			}

	    }


	}

	public function get_pdftest()
	{
	    $doc = View::make('pdf.test')->render();

	    $pdf = new Pdf();

	    $pdf->make($doc);

		$newdir = realpath(Config::get('kickstart.storage'));

		$path = $newdir.'/test.pdf';

		$pdf->render();

	    $pdf->stream();

	    $pdf->save($path);

	}

}

?>