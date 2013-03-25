@layout('blank')

@section('content')


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
					<td class="detail-info">{{ $profile['mobile'] }}</td>
				</tr>
				
				
				<tr>
					<td class="detail-title">Registration  Type</td>
					<td>:&nbsp;</td>
					@if($profile['regtype'] == 'PO')
						<td class="detail-info">Professional / Delegate Overseas</td>
					@elseif($profile['regtype'] == 'PD')
						<td class="detail-info">Professional / Delegate Domestic</td>
					@elseif($profile['regtype'] == 'SD')
						<td class="detail-info">Student Domestic</td>
					@elseif($profile['regtype'] == 'SO')
						<td class="detail-info">Student Overseas</td>
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
							<span style="color: #BC1C4B;text-transform:uppercase;text-decoration:underline;font-weight:bold;"><div class="convPayment" id="select_1">{{ $profile['conventionPaymentStatus'] }}</div></span>
						@elseif($profile['conventionPaymentStatus'] == 'cancel')
							<span style="text-transform:uppercase;font-weight:bold;"><div class="convPayment" id="select_1">{{ $profile['conventionPaymentStatus'] }}</div></span>
						@elseif($profile['conventionPaymentStatus'] == 'paid')
							<span style="color: #229835;text-transform:uppercase;font-weight:bold;"><div class="convPayment" id="select_1">{{ $profile['conventionPaymentStatus'] }}</div></span>
						@else
							<span style="color: #BC1C4B;text-transform:uppercase;font-weight:bold;"><div class="convPayment" id="select_1">{{ $profile['conventionPaymentStatus'] }}</div></span>
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
				<button class="printonsite btn btn-info" id="printstart"><i class="icon-">&#xe14c;</i>&nbsp;&nbsp;PRINT BADGE</button>
				<iframe src="{{ URL::to('attendee/printbadgeonsite/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
			@elseif(Auth::user()->role == 'cashier')
				<button class="printonsite btn btn-info" id="printstartcashier" disable="disable"><i class="icon-">&#xe14c;</i>&nbsp;&nbsp;PRINT RECEIPT</button>
				<iframe src="{{ URL::to('attendee/printreceipt/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
			@endif
			</div>

	
</div>
<script type="text/javascript">
<?php

	$ajaxpaymentupdateonsite = (isset($ajaxpaymentupdateonsite))?$ajaxpaymentupdateonsite:'/';
	$userid = $profile['_id']->__toString();
	$paystat = $profile['conventionPaymentStatus'];
?>
$(document).ready(function() {
	
	var paystat = '<?php echo $paystat;?>';
	if((paystat != 'paid') && (paystat != 'free')){

		$('#printstartcashier').attr('disabled', 'disabled');
	}
	
	
 	$('.convPayment').editable('{{ URL::to("attendee/paystatusconvonsite") }}', { 
	    indicator : 'Saving...',
	    name   : 'new_value',
	    data   : '{"unpaid":"Unpaid","paid":"Paid","free":"Free"}',
	    type   : 'select',
	    submit : 'OK',
	    style   : 'display: inline',
	    callback : function(value, settings) {
	    	console.log(value);
	    	if(value =='"paid"'){
	    		alert('Successfully change payment status, you can print the receipt now');
	    		$('#printstartcashier').removeAttr('disabled');     
	    	}else{
	    		$('#printstartcashier').attr('disabled', 'disabled');
	    	}
     	},
	    submitdata : {userid: '<?php echo $userid;?>'}

  	});
});
$('#printstart').click(function(){
	var pframe = document.getElementById('print_frame');
	var pframeWindow = pframe.contentWindow;
	pframeWindow.print();

});

</script>
@endsection