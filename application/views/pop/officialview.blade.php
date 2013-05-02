@layout('blank')

@section('content')


<div class="row-fluid">
	
			<h2>{{ $profile['firstname'] }}</h2>
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
				
				@if(isset($profile['mobile']))
				<tr>
					<td class="detail-title">Mobile Phone Number</td>
					<td>:&nbsp;</td>
					<td class="detail-info">
						
						{{ $profile['mobile'] }}

					</td>
				</tr>
				@endif
				
			</table>
			<table class="secondtable">
				<tr><td colspan="3"><h4>Company Information</h4></td></tr>

				<tr>
					<td class="detail-title">Company Name</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['company'] }}</td>
				</tr>

				<tr>
					<td class="detail-title">Official Type</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $profile['role'] }}</td>
				</tr>

				

				
				
			</table>

			
			<div class="clear"></div>
			
			@if(Auth::user()->role == 'onsite')

				<button class="printonsite btn btn-info" id="printstart"><i class="icon-">&#xe14c;</i>&nbsp;&nbsp;PRINT BADGE</button>
				<iframe src="{{ URL::to('official/printbadgeonsite/') }}{{ $profile['_id']}}" id="print_frame" style="display:none;" class="span12"></iframe>
			@elseif(Auth::user()->role == 'cashier')
				
			@endif
			</div>

	
</div>
<script type="text/javascript">
<?php
	
	
	$ajaxprintbadge = (isset($ajaxprintbadge))?$ajaxprintbadge:'/';
	$userid = $profile['_id']->__toString();
	
?>



/*$('#printstart').click(function(){
	$.post('{{ URL::to($ajaxprintbadge) }}',{'id':'{{$userid}}'}, function(data) {
		if(data.status == 'OK'){
			var pframe = document.getElementById('print_frame');
			var pframeWindow = pframe.contentWindow;
			pframeWindow.print();
		}
	},'json');

});*/

$('#printstart').click(function(){
	
	var pframe = document.getElementById('print_frame');
	var pframeWindow = pframe.contentWindow;
	pframeWindow.print();
	

});

$('#submitpin').click(function(){
	var pintrue = '{{ Config::get("eventreg.pinsupervisorconvention") }}';
	var pinvalue = $('#supervisorpin').val();
	if(pinvalue == pintrue){
		$.post('{{ URL::to($ajaxprintbadge) }}',{'id':'{{$userid}}'}, function(data) {
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

</script>
@endsection