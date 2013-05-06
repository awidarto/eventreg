@layout('master')

@section('content')
<?php
setlocale(LC_MONETARY, "en_US");
?>
<div class="metro span12">
	<div class="metro-sections-noeffect">

	   <div id="section1" class="metro-section tile-span-8">
	   		<div class="blockseparate marginbottom">
		    <h1>Cashier Report</h1>
		    <p>Cashier name: <strong>{{ Auth::user()->fullname }}</strong></p>
			</div> 
			  
	      <div class="clear"></div>
	      <button class="right btn needsupervisor donotprint" id="downloadall" ><i class="icon-" >&#xe111;</i>&nbsp;&nbsp;download all data</button>
	      <button class="right btn donotprint" id="print" style="margin-right:5px;"><i class="icon-" >&#xe14c;</i>&nbsp;&nbsp;print</button>

	      <div class="blockseparate marginbottom">
		    <h4><?php echo date('jS F Y');?></h4>
		    
			</div> 
			<hr/>  
	      <div class="clear"></div>

	      <div class="resumecashier row-fluid">
	      	<div class="span3">
	      		<h2>TOTAL CASH IDR</h2>
	      		<i class="icon- donotprint needsupervisor" id="download_cash_idr">&#xe111;</i>
	      	</div>
	      	<div class="span9 sumduit">
	      		<h2><strong>{{formatrp ($getMoney['total_cash_idr'][0]) }} IDR</strong></h2>
	      		<p><strong>{{$getCount['total_cash_idr'][0]}} total transactions</strong></p>
	      		<div class="row-fluid typelist">
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Domestic</h4>
	      				<h4><strong>{{formatrp ($getMoney['pd_cash_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['pd_cash_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_pd_cash_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Overseas</h4>
	      				<h4><strong>{{formatrp ($getMoney['po_cash_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['po_cash_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_po_cash_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Domestic</h4>
	      				<h4><strong>{{formatrp ($getMoney['sd_cash_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['sd_cash_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_sd_cash_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Overseas</h4>
	      				<h4><strong>{{formatrp ($getMoney['so_cash_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['so_cash_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_so_cash_idr">&#xe111;</i>
	      			</div>
	      		</div>
	      	</div>
	      </div>


	      <div class="resumecashier row-fluid">
	      	<div class="span3">
	      		<h2>TOTAL CASH USD</h2>
	      		<i class="icon- donotprint needsupervisor" id="download_cash_usd">&#xe111;</i>
	      	</div>
	      	<div class="span9 sumduit">
	      		<h2><strong>
	      			
	      			{{money_format(" %!n ", $getMoney['total_cash_usd'][0]) }} USD
	      			
	      			</strong>
	      		</h2>
	      		<p><strong>{{$getCount['total_cash_usd'][0]}} total transactions</strong></p>
	      		<div class="row-fluid typelist">
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Domestic</h4>
	      				<h4><strong>{{money_format(" %!n ", $getMoney['pd_cash_usd'][0]) }}</strong></h4>
	      				<p>{{$getCount['pd_cash_usd'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_pd_cash_usd">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Overseas</h4>
	      				<h4><strong>{{money_format(" %!n ", $getMoney['po_cash_usd'][0]) }}</strong></h4>
	      				<p>{{$getCount['po_cash_usd'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_po_cash_usd">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Domestic</h4>
	      				<h4><strong>{{money_format(" %!n ", $getMoney['sd_cash_usd'][0]) }}</strong></h4>
	      				<p>{{$getCount['sd_cash_usd'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_sd_cash_usd">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Overseas</h4>
	      				<h4><strong>{{money_format(" %!n ", $getMoney['so_cash_usd'][0]) }}</strong></h4>
	      				<p>{{$getCount['so_cash_usd'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_so_cash_usd">&#xe111;</i>
	      			</div>
	      		</div>
	      	</div>
	      </div>


	      <div class="resumecashier row-fluid">
	      	<div class="span3">
	      		<h2>TOTAL Credit Card IDR</h2>
	      		<i class="icon- donotprint needsupervisor" id="download_cc_idr">&#xe111;</i>
	      	</div>
	      	<div class="span9 sumduit">
	      		<h2><strong>{{formatrp ($getMoney['total_cc_idr'][0]) }} IDR</strong></h2>
	      		<p><strong>{{$getCount['total_cc_idr'][0]}} total transactions</strong></p>
	      		<div class="row-fluid typelist">
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Domestic</h4>
	      				<h4><strong>{{formatrp ($getMoney['pd_cc_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['pd_cc_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_pd_cc_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Overseas</h4>
	      				<h4><strong>{{formatrp ($getMoney['po_cc_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['po_cc_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_po_cc_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Domestic</h4>
	      				<h4><strong>{{formatrp ($getMoney['sd_cc_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['sd_cc_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_sd_cc_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Overseas</h4>
	      				<h4><strong>{{formatrp ($getMoney['so_cc_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['so_cc_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_so_cc_idr">&#xe111;</i>
	      			</div>
	      		</div>
	      	</div>
	      </div>

	      <div class="resumecashier row-fluid">
	      	<div class="span3">
	      		<h2>TOTAL Debit Bca IDR</h2>
	      		<i class="icon- donotprint needsupervisor" id="download_debit_idr">&#xe111;</i>
	      	</div>
	      	<div class="span9 sumduit">
	      		<h2><strong>{{formatrp ($getMoney['total_debit_idr'][0]) }} IDR</strong></h2>
	      		<p><strong>{{$getCount['total_debit_idr'][0]}} total transactions</strong></p>
	      		<div class="row-fluid typelist">
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Domestic</h4>
	      				<h4><strong>{{formatrp ($getMoney['pd_debit_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['pd_debit_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_pd_debit_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Professional Overseas</h4>
	      				<h4><strong>{{formatrp ($getMoney['po_debit_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['po_debit_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_po_debit_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Domestic</h4>
	      				<h4><strong>{{formatrp ($getMoney['sd_debit_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['sd_debit_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_sd_debit_idr">&#xe111;</i>
	      			</div>
	      			<div class="span3">
	      				<h4 class="typeregistresume">Student Overseas</h4>
	      				<h4><strong>{{formatrp ($getMoney['so_debit_idr'][0]) }}</strong></h4>
	      				<p>{{$getCount['so_debit_idr'][0]}} total transactions</p>
	      				<i class="icon- donotprint needsupervisor" id="download_so_debit_idr">&#xe111;</i>
	      			</div>
	      		</div>
	      	</div>
	      </div>

	      
	   </div>

		
	</div>
</div>
<div id="stack4" class="modal hide fade" tabindex="1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h3>Input PIN</h3>
  </div>
  <div class="modal-body">
    
    <input type="password" id="supervisorpinadd" data-tabindex="1">
    <br/>
    <button class="btn" data-toggle="modal" id="submitpinadd" href="#">Submit</button>
  </div>
  <div class="modal-footer">
  </div>
</div>
<script type="text/javascript">



var idclicked;
var urltoriderect;
$('.needsupervisor').click(function(e){
	$('#stack4').modal('show');
	idclicked = e.target.id;
	alert(idclicked);

	if(idclicked == 'downloadall'){
		urltoriderect = "{{ URL::to('export/cashier/?type=all') }}";
	}else if(idclicked == 'download_cash_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=idr') }}";
	}else if(idclicked == 'download_pd_cash_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=idr&regtype=PD') }}";
	}else if(idclicked == 'download_po_cash_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=idr&regtype=PO') }}";
	}else if(idclicked == 'download_sd_cash_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=idr&regtype=SD') }}";
	}else if(idclicked == 'download_so_cash_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=idr&regtype=SO') }}";

	}else if(idclicked == 'download_cash_usd'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=usd') }}";
	}else if(idclicked == 'download_pd_cash_usd'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=usd&regtype=PD') }}";
	}else if(idclicked == 'download_po_cash_usd'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=usd&regtype=PO') }}";
	}else if(idclicked == 'download_sd_cash_usd'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=usd&regtype=SD') }}";
	}else if(idclicked == 'download_so_cash_usd'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cash&currency=usd&regtype=SO') }}";
	

	}else if(idclicked == 'download_cc_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cc&currency=idr') }}";
	}else if(idclicked == 'download_pd_cc_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cc&currency=idr&regtype=PD') }}";
	}else if(idclicked == 'download_po_cc_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cc&currency=idr&regtype=PO') }}";
	}else if(idclicked == 'download_sd_cc_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cc&currency=idr&regtype=SD') }}";
	}else if(idclicked == 'download_so_cc_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=cc&currency=idr&regtype=SO') }}";
	

	}else if(idclicked == 'download_debit_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=debit bca&currency=idr') }}";
	}else if(idclicked == 'download_pd_debit_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=debit bca&currency=idr&regtype=PD') }}";
	}else if(idclicked == 'download_po_debit_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=debit bca&currency=idr&regtype=PO') }}";
	}else if(idclicked == 'download_sd_debit_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=debit bca&currency=idr&regtype=SD') }}";
	}else if(idclicked == 'download_so_debit_idr'){
		urltoriderect = "{{ URL::to('export/cashier/?type=debit bca&currency=idr&regtype=SO') }}";
	}

});


$('#print').click(function(e){
	window.print();
});

$('#submitpinadd').click(function(e){
	
	var pintrue = '{{ Config::get("eventreg.pinsupervisorcashier") }}';
	var pinvalue = $('#supervisorpinadd').val();
	
	
	if(pinvalue == pintrue){
		window.location.href = urltoriderect;
		$('#supervisorpinadd').val('');
	}else{
		alert("Wrong PIN, please try again");
		$('#supervisorpinadd').val('');
	}

});

</script>

@endsection