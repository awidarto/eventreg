<?php

class Cashier_Controller extends Base_Controller {

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
		//$this->crumb->add('cashier','Dashboard');

		date_default_timezone_set('Asia/Jakarta');
		$this->filter('before','auth');
	}

	


	public function get_report()
	{
		$form = new Formly();
		if(isset($_GET['date'])){
			$today = $_GET['date'];
		}else{
			$today = date("Y-m-d");
		}
		$getCountAll = $this->getCountAttendee($today, $today);

		$getCount['total_cash_idr'] = $this->getCountAttendee($today, $today,'idr',null,'cash');
		$getCount['total_cash_usd'] = $this->getCountAttendee($today, $today,'usd',null,'cash');
		$getCount['total_cc_idr'] = $this->getCountAttendee($today, $today,'idr',null,'cc');
		$getCount['total_debit_idr'] = $this->getCountAttendee($today, $today,'idr',null,'debit bca');
		
		//money
		$getMoney['total_cash_idr'] = $this->getCountMoney($today, $today,'idr',null,'cash');
		$getMoney['total_cash_usd'] = $this->getCountMoney($today, $today,'usd',null,'cash');
		$getMoney['total_cc_idr'] = $this->getCountMoney($today, $today,'idr',null,'cc');
		$getMoney['total_debit_idr'] = $this->getCountMoney($today, $today,'idr',null,'debit bca');


		$getCount['pd_cash_idr'] = $this->getCountAttendee($today, $today,'idr','PD','cash');
		$getCount['pd_cash_usd'] = $this->getCountAttendee($today, $today,'usd','PD','cash');
		$getCount['pd_cc_idr'] = $this->getCountAttendee($today, $today,'idr','PD','cc');
		$getCount['pd_debit_idr'] = $this->getCountAttendee($today, $today,'idr','PD','debit bca');

		//money
		$getMoney['pd_cash_idr'] = $this->getCountMoney($today, $today,'idr','PD','cash');
		$getMoney['pd_cash_usd'] = $this->getCountMoney($today, $today,'usd','PD','cash');
		$getMoney['pd_cc_idr'] = $this->getCountMoney($today, $today,'idr','PD','cc');
		$getMoney['pd_debit_idr'] = $this->getCountMoney($today, $today,'idr','PD','debit bca');


		$getCount['po_cash_idr'] = $this->getCountAttendee($today, $today,'idr','PO','cash');
		$getCount['po_cash_usd'] = $this->getCountAttendee($today, $today,'usd','PO','cash');
		$getCount['po_cc_idr'] = $this->getCountAttendee($today, $today,'idr','PO','cc');
		$getCount['po_debit_idr'] = $this->getCountAttendee($today, $today,'idr','PO','debit bca');
		//money
		$getMoney['po_cash_idr'] = $this->getCountMoney($today, $today,'idr','PO','cash');
		$getMoney['po_cash_usd'] = $this->getCountMoney($today, $today,'usd','PO','cash');
		$getMoney['po_cc_idr'] = $this->getCountMoney($today, $today,'idr','PO','cc');
		$getMoney['po_debit_idr'] = $this->getCountMoney($today, $today,'idr','PO','debit bca');

		$getCount['sd_cash_idr'] = $this->getCountAttendee($today, $today,'idr','SD','cash');
		$getCount['sd_cash_usd'] = $this->getCountAttendee($today, $today,'usd','SD','cash');
		$getCount['sd_cc_idr'] = $this->getCountAttendee($today, $today,'idr','SD','cc');
		$getCount['sd_debit_idr'] = $this->getCountAttendee($today, $today,'idr','SD','debit bca');
		//money
		$getMoney['sd_cash_idr'] = $this->getCountMoney($today, $today,'idr','SD','cash');
		$getMoney['sd_cash_usd'] = $this->getCountMoney($today, $today,'usd','SD','cash');
		$getMoney['sd_cc_idr'] = $this->getCountMoney($today, $today,'idr','SD','cc');
		$getMoney['sd_debit_idr'] = $this->getCountMoney($today, $today,'idr','SD','debit bca');

		$getCount['so_cash_idr'] = $this->getCountAttendee($today, $today,'idr','SO','cash');
		$getCount['so_cash_usd'] = $this->getCountAttendee($today, $today,'usd','SO','cash');
		$getCount['so_cc_idr'] = $this->getCountAttendee($today, $today,'idr','SO','cc');
		$getCount['so_debit_idr'] = $this->getCountAttendee($today, $today,'idr','SO','debit bca');

		//money
		$getMoney['so_cash_idr'] = $this->getCountMoney($today, $today,'idr','SO','cash');
		$getMoney['so_cash_usd'] = $this->getCountMoney($today, $today,'usd','SO','cash');
		$getMoney['so_cc_idr'] = $this->getCountMoney($today, $today,'idr','SO','cc');
		$getMoney['so_debit_idr'] = $this->getCountMoney($today, $today,'idr','SO','debit bca');

		

		return View::make('cashier.report')
			->with('displaydate',$today)
			->with('getCount',$getCount)
			->with('getMoney',$getMoney)
			->with('getCountAll',$getCountAll)
			->with('title','Cashier Report')
			->with('form',$form)
			->with('crumb',$this->crumb);
	}

	private function getCountAttendee($strDateFrom,$strDateTo,$currency = null,$type=null,$paymentmethod=null){

	  $cashierdata = new Cashier();
	  $aryRange=array();
	  $userobj = Auth::user()->id;

	  $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	  $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	  if ($iDateTo>=$iDateFrom) {
	   
	    
	    $fromDate = date('d-M-Y',$iDateFrom);
	    $toDate = date('d-M-Y',$iDateFrom);
	    $dateFrom = new MongoDate(strtotime($fromDate." 00:00:00"));
		$dateTo = new MongoDate(strtotime($toDate." 23:59:59"));
		if($currency ==null && $type ==null && $paymentmethod==null){
			$dataresult = $cashierdata->count(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo)));
		}else if($currency!=null && $paymentmethod!=null && $type == null){
			$dataresult = $cashierdata->count(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'paymentvia'=>$paymentmethod));
		}else{
			$dataresult = $cashierdata->count(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'regtype'=>$type,'paymentvia'=>$paymentmethod));
		}
		array_push($aryRange,$dataresult ); // first entry


	    while ($iDateFrom<$iDateTo) {
	      	$iDateFrom+=86400; // add 24 hours
			$fromDate = date('d-M-Y',$iDateFrom);
			$toDate = date('d-M-Y',$iDateFrom);
			$dateFrom = new MongoDate(strtotime($fromDate." 00:00:00"));
			$dateTo = new MongoDate(strtotime($toDate." 23:59:59"));
			if($currency ==null && $type ==null && $paymentmethod==null){
				$dataresult = $cashierdata->count(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo)));
			}else if($currency!=null && $paymentmethod!=null && $type == null){
				$dataresult = $cashierdata->count(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'paymentvia'=>$paymentmethod));
			}else{
				$dataresult = $cashierdata->count(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'regtype'=>$type,'paymentvia'=>$paymentmethod));
			}
			array_push($aryRange,$dataresult ); // first entry

	    }
	  }
	  return $aryRange;
	}




	private function getCountMoney($strDateFrom,$strDateTo,$currency = null,$type=null,$paymentmethod=null){

	  $cashierdata = new Cashier();
	  $aryRange=array();
	  $userobj = Auth::user()->id;

	  $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	  $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	  if ($iDateTo>=$iDateFrom) {
	   
	    
	    $fromDate = date('d-M-Y',$iDateFrom);
	    $toDate = date('d-M-Y',$iDateFrom);
	    $dateFrom = new MongoDate(strtotime($fromDate." 00:00:00"));
		$dateTo = new MongoDate(strtotime($toDate." 23:59:59"));

		if($currency!=null && $paymentmethod!=null && $type == null){
			
			$data = $cashierdata->find(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'paymentvia'=>$paymentmethod));
			$dataresult =0;
			foreach ($data as $key => $value) {
				if($currency == 'idr'){
					$dataresult += $value['totalidr'];
				}elseif ($currency == 'usd'){
					$dataresult += $value['totalusd'];
				}
			}

		}else{

			$data = $cashierdata->find(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'paymentvia'=>$paymentmethod,'regtype'=>$type));
			$dataresult =0;
			foreach ($data as $key => $value) {
				if($currency == 'idr'){
					$dataresult += $value['totalidr'];
				}elseif ($currency == 'usd'){
					$dataresult += $value['totalusd'];
				}
			}

		}
		array_push($aryRange,$dataresult ); // first entry


	    while ($iDateFrom<$iDateTo) {
	      	$iDateFrom+=86400; // add 24 hours
			$fromDate = date('d-M-Y',$iDateFrom);
			$toDate = date('d-M-Y',$iDateFrom);
			$dateFrom = new MongoDate(strtotime($fromDate." 00:00:00"));
			$dateTo = new MongoDate(strtotime($toDate." 23:59:59"));
			if($currency!=null && $paymentmethod!=null && $type == null){
				
				$data = $cashierdata->find(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'paymentvia'=>$paymentmethod));
				$dataresult =0;
				foreach ($data as $key => $value) {
					if($currency == 'idr'){
						$dataresult += $value['totalidr'];
					}elseif ($currency == 'usd'){
						$dataresult += $value['totalusd'];
					}
				}

			}else{

				$data = $cashierdata->find(array('cashierid'=>$userobj,'paymentdate'=>array('$gte'=>$dateFrom,'$lte'=>$dateTo),'currency'=>$currency,'paymentvia'=>$paymentmethod,'regtype'=>$type));
				$dataresult =0;
				foreach ($data as $key => $value) {
					if($currency == 'idr'){
						$dataresult += $value['totalidr'];
					}elseif ($currency == 'usd'){
						$dataresult += $value['totalusd'];
					}
				}

			}
			array_push($aryRange,$dataresult ); // first entry

	    }
	  }
	  return $aryRange;
	}

}