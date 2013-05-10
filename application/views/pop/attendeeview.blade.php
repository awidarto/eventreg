@layout('blank')

@section('content')


<?php
setlocale(LC_MONETARY, "en_US");
?>

<div class="row-fluid">
	
			<h2>{{ $profile['firstname'].' '.$profile['lastname'] }}</h2>
			<table class="profile-info profilepopup">
				<tr>
					<td class="detail-title">Registration Number</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['registrationnumber'] }}
						
					</td>
				</tr>
				<tr>
					<td class="detail-title">Email</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['email'] }}</td>
				</tr>
				<tr>
					<td class="detail-title">Position</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['position'] }}</td>
				</tr>
				<tr>
					<td class="detail-title">Mobile Phone Number</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
						{{ $profile['mobile'] }}
					</td>
				</tr>
				
				
				<tr>
					<td class="detail-title">Registration  Type</td>
					<td>:&nbsp;</td>
					@if($profile['regtype'] == 'PO')
						<td class="detail-info"><strong>Professional / Delegate Overseas</strong></td>
					@elseif($profile['regtype'] == 'PD')
						<td class="detail-info"><strong>Professional / Delegate Domestic</strong></td>
					@elseif($profile['regtype'] == 'SD')
						<td class="detail-info"><strong>Student Domestic</strong></td>
					@elseif($profile['regtype'] == 'SO')
						<td class="detail-info"><strong>Student Overseas</strong></td>
					@endif					
					
				</tr>
				<tr>
					<td class="detail-title">Industri Dinner RSVP</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
						<span>{{ $profile['attenddinner'] }}</span>
					</td>
				</tr>
				<tr>
					<td class="detail-title">Golf Tournament</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
						@if($profile['golf'] == 'Yes')
							@if($profile['golfPaymentStatus'] == 'unpaid')
								<span>{{ $profile['golf'] }} - <span style="color: #BC1C4B;text-transform:uppercase;text-decoration:underline;font-weight:bold;">{{ $profile['golfPaymentStatus'] }}</span></span>
							@elseif ($profile['golfPaymentStatus'] == 'pending')
								<span>{{ $profile['golf'] }} - <span style="text-transform:uppercase;font-weight:bold;">{{ $profile['golfPaymentStatus'] }}</span></span>
							@elseif ($profile['golfPaymentStatus'] == 'paid')
								<span>{{ $profile['golf'] }} - <span style="color: #229835;text-transform:uppercase;font-weight:bold;">{{ $profile['golfPaymentStatus'] }}</span></span>
							@else
								<span>{{ $profile['golf'] }} - <span style="color: #BC1C4B;text-transform:uppercase;font-weight:bold;">{{ $profile['golfPaymentStatus'] }}</span></span>
							@endif
						@else
							<span>{{ $profile['golf'] }}</span>
						@endif
					</td>
				</tr>
				
				<tr>
					<td class="detail-title">Status</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
						@if($profile['conventionPaymentStatus'] == 'unpaid')
							@if(Auth::user()->role == 'cashier')
								<span style="color: #BC1C4B;text-transform:uppercase;text-decoration:underline;font-weight:bold;" id="convpaymentstat">{{ $profile['conventionPaymentStatus'] }}</span>
							@else
								<span style="color: #BC1C4B;text-transform:uppercase;text-decoration:underline;font-weight:bold;" id="convpaymentstat">{{ $profile['conventionPaymentStatus'] }}</span>
							@endif
						@elseif($profile['conventionPaymentStatus'] == 'cancel')
							<span style="text-transform:uppercase;font-weight:bold;" id="convpaymentstat">{{ $profile['conventionPaymentStatus'] }}</span>
						@elseif($profile['conventionPaymentStatus'] == 'paid')
							<span style="color: #229835;text-transform:uppercase;font-weight:bold;" id="convpaymentstat">{{ $profile['conventionPaymentStatus'] }}</span>
						@else
							<span style="color: #BC1C4B;text-transform:uppercase;font-weight:bold;" id="convpaymentstat">{{ $profile['conventionPaymentStatus'] }}</span>
						@endif
						
					</td>
				</tr>

				<tr>
					<td class="detail-title">Notes</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
						@if(isset($profile['notes']))
							{{$profile['notes']}}
						@else
							-
						@endif
					</td>
				</tr>

				<tr>
					<td class="detail-title">USD</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
							@if($profile['totalUSD']=='')
								
							@else
								<span class="fontRed" style="font-weight:bold;">USD {{ money_format(" %!n ", $profile['totalUSD']) }}</span>
							@endif
						
					</td>
				</tr>

				<tr>
					<td class="detail-title">IDR</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
						
						@if($profile['totalIDR']=='')
								
						@else
							<span class="fontRed" style="font-weight:bold;">IDR {{ formatrp($profile['totalIDR']) }}</span>
						@endif
							
						
					</td>
				</tr>
			</table>
			<table class="secondtable">
				<tr><td colspan="3"><h4>Company Information</h4></td></tr>

				<tr>
					<td class="detail-title">Company Name</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['company'] }}</td>
				</tr>

				<tr>
					<td class="detail-title">Company NPWP</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['npwp'] }}</td>
				</tr>

				<tr>
					<td class="detail-title">Company Phone</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['companyphone'] }}</td>
				</tr>

				<tr>
					<td class="detail-title">Company Fax</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['companyfax'] }}</td>
				</tr>

				<tr>
					<td class="detail-title">Company Address</td>
					<td style="vertical-align:top">:&nbsp;</td>
					@if (isset($profile['address']))
					<td class="detail-info">{{ $profile['address'].' '.$profile['city'].' '.$profile['zip'] }}</td>
					@else
					<td class="detail-info">{{ $profile['address_1'].'</br>'.$profile['address_2'].'</br> '.$profile['city'].' '.$profile['zip'] }}</td>
					@endif
				</tr>

				<tr>
					<td class="detail-title">Country</td>
					<td>:</td>
					<?php
					
						//$countries = Config::get('country.countries');
					?>
					
					<td class="detail-info">{{ $profile['country']  }}</td>
					
				</tr>

				@if(Auth::user()->role != 'cashier')
				<tr>
					<td class="detail-title">Pickup badge notes</td>
					<td>:</td>
					@if(isset($profile['badgepickupnotes']))
					<td class="detail-info"><textarea class="text invAdress span10" id="badgepickupnotes" rows="4" name="notes" cols="50" style="font-size:11px;">{{$profile['badgepickupnotes'] }}</textarea>
					@else
					<td class="detail-info"><textarea class="text invAdress span10" id="badgepickupnotes" rows="4" name="notes" cols="50" style="font-size:11px;"></textarea>
					@endif
					</td>
					
				</tr>
				@endif

				
				
			</table>

			<table class="secondtable">

				<tr><td colspan="3"><h4>Invoice Address</h4></td></tr>
				<!--Find out if they are from import or not-->
				@if ( isset($profile['cache_obj']) && $profile['cache_obj']== '')
					<tr>
						<td class="detail-title">Company Name</td>
						<td>:&nbsp;</td>
						<td class="detail-info">{{ $profile['companyInvoice'] }}</td>
					</tr>

					<tr>
						<td class="detail-title">Company NPWP</td>
						<td>:&nbsp;</td>
						<td class="detail-info">{{ $profile['npwpInvoice'] }}</td>
					</tr>

					<tr>
						<td class="detail-title">Company Phone</td>
						<td>:&nbsp;</td>
						<td class="detail-info">{{ $profile['companyphoneInvoice'] }}</td>
					</tr>

					<tr>
						<td class="detail-title">Company Fax</td>
						<td>:&nbsp;</td>
						<td class="detail-info">{{ $profile['companyfaxInvoice'] }}</td>
					</tr>

					<tr>
						<td class="detail-title">Company Address</td>
						<td style="vertical-align:top">:&nbsp;</td>
						@if (isset($profile['address']))
							<td class="detail-info">{{ $profile['addressInvoice'].' '.$profile['cityInvoice'].' '.$profile['zipInvoice'] }}</td>
						@else
							<td class="detail-info">{{ $profile['addressInvoice_1'].'</br>'.$profile['addressInvoice_2'].'</br>'.$profile['cityInvoice'].' '.$profile['zipInvoice'] }}</td>
							
						@endif
					</tr>

					<tr>
						<td class="detail-title">Country</td>
						<td>:</td>
						<?php
						
							//$countries = Config::get('country.countries');
						?>
						
						<td class="detail-info">{{ $profile['countryInvoice']  }}</td>
						
						
					</tr>
					@else

						<tr>
							<td class="detail-title">Address</td>
							<td>:</td>
							<td class="detail-info">{{ $profile['invoice_address_conv']  }}</td>

						</tr>

					@endif
				
			</table>
			<div class="clear"></div>
			
			@if(Auth::user()->role == 'onsite')
				@if(isset($profile['printbadge']) && ($profile['printbadge']!=''))
					<br/>
					<p>This user already {{$profile['printbadge']}} print the badge, please input PIN for re-print</p>
					<input type="password" id="supervisorpin"></input><br/><button class="btn btn-info" value="Submit" id="submitpin">Submit for reprint</button>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="savebadgenotes btn btn-info" id="savebadgenotes"><i class="icon-">&#xe0c3;</i>&nbsp;&nbsp;Save badge pickup notes</button>
					<iframe src="{{ URL::to('attendee/printbadgeonsite/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
				@elseif($profile['conventionPaymentStatus']=='unpaid')
					<br/>
					<p>Unpaid user, please input PIN for print the badge</p>
					<input type="password" id="supervisorpin"></input><br/><button class="btn btn-info" value="Submit" id="submitpin">Submit for print</button>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="savebadgenotes btn btn-info" id="savebadgenotes"><i class="icon-">&#xe0c3;</i>&nbsp;&nbsp;Save badge pickup notes</button>
					<iframe src="{{ URL::to('attendee/printbadgeonsite/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
				@else
					<button class="printonsite btn btn-info" id="printstart"><i class="icon-">&#xe14c;</i>&nbsp;&nbsp;PRINT BADGE</button>
					<button class="savebadgenotes btn btn-info" id="savebadgenotes" style="margin-top: 40px;"><i class="icon-">&#xe0c3;</i>&nbsp;&nbsp;Save badge pickup notes</button>
					<iframe src="{{ URL::to('attendee/printbadgeonsite/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
				@endif

			@elseif(Auth::user()->role == 'cashier')
				@if($profile['conventionPaymentStatus']=='unpaid')
					<button class="printonsite btn btn-info" id="printstartcashier" style="display:none;"><i class="icon-">&#xe14c;</i>&nbsp;&nbsp;PRINT RECEIPT</button>
					<br/>
					<button class="dopayment btn btn-info" id="#" ><i class="icon-">&#xe150;</i>&nbsp;&nbsp;MAKE A PAYMENT</button>
					<iframe src="{{ URL::to('attendee/printreceipt/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
				@elseif(isset($profile['payonsite']) && $profile['payonsite']=='yes')
					<button class="printonsite btn btn-info" id="printstartcashier" style=""><i class="icon-">&#xe14c;</i>&nbsp;&nbsp;PRINT RECEIPT</button>
					<iframe src="{{ URL::to('attendee/reprintreceipt/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
				@endif
				
			@endif
			</div>


			<div id="stack3" class="modal hide fade" tabindex="1" data-focus-on="input:first">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			    <h3>Make a Payment</h3>
			  </div>
			  <div class="modal-body">
			  	<div class="paymentset" style="float:left;margin-left:10px;">
				    <label>Status</label>
				    <select class="statuspayment">
					  <option value="unpaid">Unpaid</option>
					  <option value="paid">Paid</option>
					  
					  
					</select>
				    <br/>
				    <label>Payment via</label>
				    <select class="paymentvia">
				    	<option value="null">Select payment via</option>
					  	<option value="cash">Cash</option>
					  	<option value="debit bca">Debit BCA</option>
					  	<option value="cc">Credit Card</option>
					</select>
				    <br/>
				    <div class="currencyselect" style="display:hide;">
				    <label>Currency</label>
				    <select class="currency">
				    	<option value="null">Select Currency</option>
				    	<option value="idr">IDR</option>
					  	<option value="usd">USD</option>
					</select>
					</div>
				    <br/>
				    <button class="btn btn-info" data-toggle="modal" id="submitaddassist" href="#">Submit & Print Receipt</button>
				    <button class="btn" data-toggle="modal" id="closestack3" href="#">Cancel</button>
				</div>
				<div class="ratepreview">
					<h3 id="currencypreview"></h3>
					<h1 id="totalpreview"></h1>
				</div>

			  </div>
			  <div class="modal-footer">
			  </div>
			</div>

	
</div>
<script type="text/javascript">
<?php
	
	$ajaxpaymentupdateonsite = (isset($ajaxpaymentupdateonsite))?$ajaxpaymentupdateonsite:'/';
	$ajaxprintbadge = (isset($ajaxprintbadge))?$ajaxprintbadge:'/';
	$userid = $profile['_id']->__toString();
	$paystat = $profile['conventionPaymentStatus'];
?>



$('.dopayment').click(function(){
	$('#stack3').modal('show');
	

});

$('#closestack3').click(function(){
	$('#stack3').modal('hide');
	

});

$('#printstart').click(function(){
	var badgepickupnotes = $("#badgepickupnotes").val();
	
	$.post('{{ URL::to($ajaxprintbadge) }}',{'id':'{{$userid}}','badgepickupnotes':badgepickupnotes}, function(data) {
		if(data.status == 'OK'){
			var pframe = document.getElementById('print_frame');
			var pframeWindow = pframe.contentWindow;
			pframeWindow.print();
		}
	},'json');

});

$('#savebadgenotes').click(function(){
	var badgepickupnotes = $("#badgepickupnotes").val();
	
	$.post('{{ URL::to($ajaxsavebadgenotes) }}',{'id':'{{$userid}}','badgepickupnotes':badgepickupnotes}, function(data) {
		$('#viewformModal').modal('hide');
	},'json');
	

});


$('#submitpin').click(function(){
	var pintrue = '{{ Config::get("eventreg.pinsupervisorconvention") }}';
	var pinvalue = $('#supervisorpin').val();
	var badgepickupnotes = $("#badgepickupnotes").val();
	if(pinvalue == pintrue){
		$.post('{{ URL::to($ajaxprintbadge) }}',{'id':'{{$userid}}','badgepickupnotes':badgepickupnotes}, function(data) {
			if(data.status == 'OK'){
				var pframe = document.getElementById('print_frame');
				var pframeWindow = pframe.contentWindow;
				pframeWindow.print();
				$('#supervisorpin').val('');
			}
		},'json');
	}else{
		alert("Wrong PIN, please try again");
	}

});

$('#printstartcashier').click(function(){
	var pframe = document.getElementById('print_frame');
	var pframeWindow = pframe.contentWindow;
	pframeWindow.print();

});


var PD_CASH_idrnominal = '5.500.000,00';
var PD_CASH_idrwords = '{{ $towords->to_words(5500000,"en")}} Rupiahs';
var PD_CASH_idrnominal_todb = '5500000';

var PD_CASH_usdnominal = '550.00';
var PD_CASH_usdwords = '{{ $towords->to_words(550,"en")}} US Dollars';
var PD_CASH_usdnominal_todb = '550';


var PO_CASH_usdnominal = '550.00';
var PO_CASH_usdwords = '{{ $towords->to_words(550,"en") }} US Dollars';
var PO_CASH_usdnominal_todb = '550';

var PO_CASH_idrnominal = '5.500.000,00';
var PO_CASH_idrwords = '{{ $towords->to_words(5500000,"en") }} Rupiahs';
var PO_CASH_idrnominal_todb = '5500000';


var PD_CC_idrnominal = '5.665.000,00';
var PD_CC_idrwords = '{{ $towords->to_words(5665000,"en") }} Rupiahs';
var PD_CC_idrnominal_todb = '5665000';


var PO_CC_idrnominal = '5.665.000,00';
var PO_CC_idrwords = '{{ $towords->to_words(5665000,"en") }} Rupiahs';
var PO_CC_idrnominal_todb = '5665000';



//student

var SD_CASH_idrnominal = '440.000,00';
var SD_CASH_idrwords = '{{ $towords->to_words(440000,"en") }} Rupiahs';
var SD_CASH_idrnominal_todb = '440000';

var SD_CASH_usdnominal = '44.00';
var SD_CASH_usdwords = '{{ $towords->to_words(44,"en") }} US Dollars';
var SD_CASH_usdnominal_todb = '44';

var SD_CC_idrnominal = '453.200,00';
var SD_CC_idrwords = '{{ $towords->to_words(453200,"en") }} Rupiahs';
var SD_CC_idrnominal_todb = '453200';


var SO_CASH_usdnominal = '120.00';
var SO_CASH_usdwords = '{{ $towords->to_words(120,"en") }} US Dollars';
var SO_CASH_usdnominal_todb = '120';

var SO_CASH_idrnominal = '1.200.000,00';
var SO_CASH_idrwords = '{{ $towords->to_words(1200000,"en") }} Rupiahs';
var SO_CASH_idrnominal_todb = '1200000';

var SO_CC_idrnominal = '1.236.000,00';
var SO_CC_idrwords = '{{ $towords->to_words(1236000,"en") }} Rupiahs';
var SO_CC_idrnominal_todb = '1236000';



$(document).ready(function() {


	$('.paymentvia').change(function() {
		var selected = $(this).val();
		if(selected == 'cc' || selected == 'debit bca'){
			
			$('.currencyselect').hide();
			$('.currency').val('idr');
			$('#currencypreview').text('IDR');
		}else{
			$('.currencyselect').show();
			$('.currency').val('null');
		}
		calculate();

	});

	$('.currency').change(function() {
		var selected = $(this).val();
		
		if(selected == 'cc' || selected == 'debit bca'){
			$('#currencypreview').text('USD');
		}

		if(selected == 'idr'){
			$('#currencypreview').text('IDR');
		}else if(selected == 'usd'){
			$('#currencypreview').text('USD');
		}
		calculate();

	});

	var regtype = '{{$profile['regtype']}}';
	function calculate(){

		var curencypreview = $('#currencypreview');
		var totalpreview = $('#totalpreview');
		var paymentvia = $('.paymentvia').val();
		var currency = $('.currency').val();

		

		if(regtype=='PD' && paymentvia=='cash' && currency == 'idr'){
			
			totalpreview.text(PD_CASH_idrnominal);

		}else if(regtype=='PD' && paymentvia=='cash' && currency == 'usd'){

			totalpreview.text(PD_CASH_usdnominal);
			
		}else if(regtype=='PD' && paymentvia=='cc' && currency == 'idr'){
			
			totalpreview.text(PD_CC_idrnominal);

		}else if(regtype=='PD' && paymentvia=='debit bca' && currency == 'idr'){
			
			totalpreview.text(PD_CASH_idrnominal);
		
		}else if(regtype=='PO' && paymentvia=='cash' && currency == 'usd'){
			
			totalpreview.text(PO_CASH_usdnominal);


		}else if(regtype=='PO' && paymentvia=='cc' && currency == 'idr'){

			
			totalpreview.text(PO_CC_idrnominal);
			

		}else if(regtype=='PO' && paymentvia=='cash' && currency == 'idr'){

			
			totalpreview.text(PO_CASH_idrnominal);
			
		}

		else if(regtype=='PO' && paymentvia=='debit bca' && currency == 'idr'){

			
			totalpreview.text(PO_CASH_idrnominal);
			
		}

		else if(regtype=='SD' && paymentvia=='cash' && currency == 'idr'){

			
			totalpreview.text(SD_CASH_idrnominal);
			

		}else if(regtype=='SD' && paymentvia=='cc' && currency == 'idr'){
			
			totalpreview.text(SD_CC_idrnominal);
		
		}else if(regtype=='SD' && paymentvia=='cash' && currency == 'usd'){
			
			totalpreview.text(SD_CASH_usdnominal);

		}else if(regtype=='SD' && paymentvia=='debit bca' && currency == 'idr'){
			
			totalpreview.text(SD_CASH_idrnominal);
		
		
		}else if(regtype=='SO' && paymentvia=='cash' && currency == 'usd'){
			
			totalpreview.text(SO_CASH_usdnominal);
			

		}else if(regtype=='SO' && paymentvia=='cc' && currency == 'idr'){

			
			totalpreview.text(SO_CC_idrnominal);
			

		}else if(regtype=='SO' && paymentvia=='cash' && currency == 'idr'){

			
			totalpreview.text(SO_CASH_idrnominal);

		}else if(regtype=='SO' && paymentvia=='debit bca' && currency == 'idr'){

			totalpreview.text(SO_CASH_idrnominal);
			
		}else{
			totalpreview.text('');
			curencypreview.text('');
		}

	}

});

//happened when submit
$('#submitaddassist').click(function(){


	var status = $('.statuspayment').val();
	var paymentvia = $('.paymentvia').val();
	var currency = $('.currency').val();
	var texttonominal='';
	var texttowords='';
	var userid = '{{$userid}}';

	var regtype = '{{$profile['regtype']}}';

	var iframe = $('iframe'); // or some other selector to get the iframe
	var idrnominalidrobject = $('.idrnominal', iframe.contents());
	var usdnominalidrobject = $('.usdnominal', iframe.contents());
	var sayinwordsobject = $('.sayinwords', iframe.contents());
	var checkedimagecashobj = $('.imagecheckcash', iframe.contents());
	var checkedimageccobj = $('.imagecheckcc', iframe.contents());
	var checkedimagedebitobj = $('.imagecheckdebit', iframe.contents());

	var idr_todb ='';
	var usd_todb ='';

	if(status == 'unpaid'){
		alert('Please select payment status');

	}else if(paymentvia == 'null'){
		alert('Please select payment method');
	}else if(currency == 'null'){
		alert('Please select currency');
	}else{
		//access iframe
		if(regtype=='PD' && paymentvia=='cash' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(PD_CASH_idrnominal);
			sayinwordsobject.text(PD_CASH_idrwords);

			idr_todb = PD_CASH_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='PD' && paymentvia=='cash' && currency == 'usd'){

			usdnominalidrobject.text(PD_CASH_usdnominal);
			idrnominalidrobject.text('--');
			sayinwordsobject.text(PD_CASH_usdwords);

			idr_todb = '';
			usd_todb = PD_CASH_usdnominal_todb;

		}else if(regtype=='PD' && paymentvia=='cc' && currency == 'idr'){
			usdnominalidrobject.text('--');
			idrnominalidrobject.text(PD_CC_idrnominal);
			sayinwordsobject.text(PD_CC_idrwords);

			idr_todb = PD_CC_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='PD' && paymentvia=='debit bca' && currency == 'idr'){
			usdnominalidrobject.text('--');
			idrnominalidrobject.text(PD_CASH_idrnominal);
			sayinwordsobject.text(PD_CASH_idwords);

			idr_todb = PD_CASH_idrnominal_todb;
			usd_todb = '';
		
		}else if(regtype=='PO' && paymentvia=='cash' && currency == 'usd'){
			
			usdnominalidrobject.text(PO_CASH_usdnominal);
			idrnominalidrobject.text('--');
			sayinwordsobject.text(PO_CASH_usdwords);

			idr_todb = '';
			usd_todb = PO_CASH_usdnominal_todb;

		}else if(regtype=='PO' && paymentvia=='cc' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(PO_CC_idrnominal);
			sayinwordsobject.text(PO_CC_idrwords);

			idr_todb = PO_CC_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='PO' && paymentvia=='cash' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(PO_CASH_idrnominal);
			sayinwordsobject.text(PO_CASH_idrwords);

			idr_todb = PO_CASH_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='PO' && paymentvia=='debit bca' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(PO_CASH_idrnominal);
			sayinwordsobject.text(PO_CASH_idrwords);

			idr_todb = PO_CASH_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='SD' && paymentvia=='cash' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(SD_CASH_idrnominal);
			sayinwordsobject.text(SD_CASH_idrwords);

			idr_todb = SD_CASH_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='SD' && paymentvia=='cash' && currency == 'usd'){

			usdnominalidrobject.text(SD_CASH_usdnominal);
			idrnominalidrobject.text('--');
			sayinwordsobject.text(SD_CASH_usdwords);

			idr_todb = '';
			usd_todb = SD_CASH_usdnominal_todb;

		}else if(regtype=='SD' && paymentvia=='cc' && currency == 'idr'){
			usdnominalidrobject.text('--');
			idrnominalidrobject.text(SD_CC_idrnominal);
			sayinwordsobject.text(SD_CC_idrwords);

			idr_todb = SD_CC_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='SD' && paymentvia=='debit bca' && currency == 'idr'){
			usdnominalidrobject.text('--');
			idrnominalidrobject.text(SD_CASH_idrnominal);
			sayinwordsobject.text(SD_CASH_idrwords);

			idr_todb = SD_CASH_idrnominal_todb;
			usd_todb = '';
		
		}else if(regtype=='SO' && paymentvia=='cash' && currency == 'usd'){
			
			usdnominalidrobject.text(SO_CASH_usdnominal);
			idrnominalidrobject.text('--');
			sayinwordsobject.text(SO_CASH_usdwords);

			idr_todb = '';
			usd_todb = SO_CASH_usdnominal_todb;

		}else if(regtype=='SO' && paymentvia=='cc' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(SO_CC_idrnominal);
			sayinwordsobject.text(SO_CC_idrwords);

			idr_todb = SO_CC_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='SO' && paymentvia=='cash' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(SO_CASH_idrnominal);
			sayinwordsobject.text(SO_CASH_idrwords);

			idr_todb = SO_CASH_idrnominal_todb;
			usd_todb = '';

		}else if(regtype=='SO' && paymentvia=='debit bca' && currency == 'idr'){

			usdnominalidrobject.text('--');
			idrnominalidrobject.text(SO_CASH_idrnominal);
			sayinwordsobject.text(SO_CASH_idrwords);

			idr_todb = SO_CASH_idrnominal_todb;
			usd_todb = '';
		}

		var imagecheck = '√';

		if(paymentvia == 'cash'){
			checkedimagecashobj.prepend(imagecheck);
		}else if(paymentvia == 'cc'){
			checkedimageccobj.prepend(imagecheck);
		}else if(paymentvia == 'debit bca'){
			checkedimagedebitobj.prepend(imagecheck);
		}

		$.post('{{ URL::to($ajaxpaymentupdateonsite) }}',{'id':userid,'status':status,'paymentvia':paymentvia,'currency':currency,'totalidr':idr_todb,'totalusd':usd_todb}, function(data) {

	      if(data.status == 'OK'){

			$('#convpaymentstat').text(status);

			var pframe = document.getElementById('print_frame');
			var pframeWindow = pframe.contentWindow;
			pframeWindow.print();

			$('#stack3').modal('hide');
			//change button to print
			$('#printstartcashier').show();
			$('.dopayment').hide();


	      }else{
	      	alert(data.data);
	      }
	    },'json');
		
	}

});


</script>
@endsection