@layout('blank')

@section('content')


<div class="row-fluid">
	
			<h2>{{ $exhibitor['firstname'].' '.$exhibitor['lastname'] }}</h2>
			<table class="profile-info profilepopup">
				<tr>
					<td class="detail-title">Registration Number</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $exhibitor['registrationnumber'] }}
						
					</td>
				</tr>
				<tr>
					<td class="detail-title">Company</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $exhibitor['company'] }}
						
					</td>
				</tr>
				<tr>
					<td class="detail-title">Email</td>
					<td>:&nbsp;</td>
					<td class="detail-info">{{ $exhibitor['email'] }}</td>
				</tr>
				
			</table>
			<table class="secondtable">
				<tr>
					<td>Address</td>
					<td>: <?php 
					$address1 = (isset($exhibitor['address_1']))?$exhibitor['address_1']:'-'; 
					$address2 = (isset($exhibitor['address_2']))?$exhibitor['address_2']:'-'; 
					echo $address1.'<br/>'.$address2;

					?></td>
				</tr>
				<tr>
					<td>Hall</td>
					<td>: {{ $exhibitor['hall'] }}</td>
				</tr>
				<tr>
					<td>Booth:</td>
					<td>: {{ $exhibitor['booth'] }}</td>
				</tr>
				
			</table>
			<table class="secondtable">
				<tr>
					<td colspan="3"><span class="btn btn-info" id="printall"><span class="icon-">&#xe15c;</span>&nbsp;Print All Booth Assistant badge</span></td>
				</tr>
				<tr>
					<td>Add Booth Assistant</td>
					<td><input type="text" id="valboothadd" placeholder="input booth code"></input></td>
					<td><span class="btn" id="submitaddbooth">add</span></td>
				</tr>
				
			</table>

			<div class="clear"></div>
			
			<?php
       
			  $sizebooth = $booth['size'];
			  $freepasscount = 0;
			  $boothassistantcount = 0;
			  $addboothassistantcount = 0;

			  if($sizebooth >= 9 && $sizebooth <= 18){
			    $pass = 2;
			  }else if($sizebooth >= 18 && $sizebooth < 27){
			    $pass = 4;
			  }else if($sizebooth >= 27 && $sizebooth < 36){
			    $pass = 6;
			  }else if($sizebooth >= 36 && $sizebooth < 45){
			    $pass = 8;
			  }else if($sizebooth >= 45){
			    $pass = 10;
			  }else{
			    $pass = 10;
			  }


			  for($i=1;$i<$pass+1;$i++){
			    if(isset($boothassistantdata['freepassname'.$i.''])){
			      $freepasscount++;
			    }
			  }

			  for($i=1;$i<11;$i++){
			    if(isset($boothassistantdata['boothassistant'.$i.''])){
			      $boothassistantcount++;
			    }
			  }

			  for($i=1;$i<=$data['totaladdbooth'];$i++){
			  	if(isset($boothassistantdata['addboothname'.$i.''])){
			      $addboothassistantcount++;
			    }
			  }

			  //checking Slot
			  $frepassslot = $pass-$freepasscount;
			  $boothassslot = 10-$boothassistantcount;
			  $addboothslot = $data['totaladdbooth']-$addboothassistantcount;


			  
			?>
			<div id="statusnotif">
			</div>
			<div class="row-fluid" id="importboothassis">
			  <div class="span12">
			      
			      <br/>
			      <br/>
			      <legend>EXHIBITOR’S PASS HOLDERS (FREE)<small>{{ '<span id="freepassleft">'.$frepassslot.'</span> slot available'}}</small></legend>
			      <table id="order-table" class="tablefreepassname">
			      	@if($freepasscount!=0)
				        <thead>
				          <tr>
				            <th class="span1">No.</th>            
				            <th class="span7">Names of Exhibitor’s Pass Holders</th>
				            <th class="span3 align-center">Status</th>
				            <th class="align-center">Action</th>
				          </tr>
				        </thead>
				        <tbody>
					          @for($i=1;$i<=$freepasscount;$i++)
					            <tr>
					              <td>{{ $i }}. </td>
					              <td class="passname">{{ $boothassistantdata['freepassname'.$i.''] }}</td>
				                  <td class="aligncenter action" ><span class="icon- fontGreen existtrue">&#xe20c;</span>&nbsp;&nbsp;Imported on {{ date('d-m-Y',  $boothassistantdata['freepassname'.$i.'timestamp']->sec) }}</td>
				                  @if(isset($boothassistantdata['freepassname'.$i.'print']))
				                  	<td id="status_freepassname{{ $i }}" class="align-center status"><a class="icon- importidividual" id="freepassname{{ $i }}"  data-toggle="modal" href="#stack2" ><span class="formstatus" id="" >already printed {{ $boothassistantdata['freepassname'.$i.'print']}}</span></a></td>
				                  @else
				                  	<td id="status_freepassname{{ $i }}" class="align-center status"><a class="icon- importidividual printbadge" rel="printbadgefreepassname{{ $i }}" id="freepassname{{ $i }}"><i>&#xe14c;</i><span class="formstatus" id="freepassname{{ $i }}" > Print this data</span></a></td>
				                  @endif
				                  	<td><iframe src="{{ URL::to('exhibitor/printbadgeonsite/') }}{{$boothassistantdata['freepassname'.$i.'regnumber']}}/{{$boothassistantdata['freepassname'.$i.'']}}/{{ $exhibitor['company'] }}" id="printbadgefreepassname{{ $i }}"  style="display:none;" class="span12"></iframe></td>
					            </tr>
					          @endfor
				        </tbody>
			        @else
				      	<tr>
			              <td colspan="4">There's no data to show</td>
			            </tr>
		          	@endif
			      </table>
			      <br/>
			      <br/>
			      <legend>FREE ADDITIONAL EXHIBITOR PASS HOLDERS<small>{{ '<span id="boothassistleft">'.$boothassslot.'</span> slot available'}}</small></legend>
			      <table id="order-table" class="tableboothassist">
			      	@if($boothassistantcount!=0)
				        <thead>
				          <tr>
				          	<th class="span1">No.</th>            
				            <th class="span7">Names of Exhibitor’s Pass Holders</th>
				            <th class="span3 align-center">Status</th>
				            <th class="align-center">Action</th>
				            
				          </tr>
				        </thead>
				        <tbody>
			        	@for($i=1;$i<=$boothassistantcount;$i++)
				            <tr>
				              <td>{{ $i }}. </td>
				              <td class="passname">{{ $boothassistantdata['boothassistant'.$i.''] }}</td>
			                  <td class="aligncenter action" ><span class="icon- fontGreen existtrue">&#xe20c;</span>&nbsp;&nbsp;Imported on {{ date('d-m-Y',  $boothassistantdata['freepassname'.$i.'timestamp']->sec) }}</td>
			                  @if(isset($boothassistantdata['boothassistant'.$i.'print']))
			                  	<td id="status_freepassname{{ $i }}" class="align-center status"><a class="icon- importidividual" id="freepassname{{ $i }}"  data-toggle="modal" href="#stack2" ><span class="formstatus" id="" >already printed {{ $boothassistantdata['freepassname'.$i.'print']}}</span></a></td>
			                  @else
			                  	<td id="status_freepassname{{ $i }}" class="align-center status"><a class="icon- importidividual printbadge" id="boothassist{{ $i }}"><i>&#xe14c;</i><span class="formstatus" id="boothassist{{ $i }}" > Print this data</span></a></td>
			                  @endif
			                  <td><iframe src="{{ URL::to('exhibitor/printbadgeonsite2/') }}{{$boothassistantdata['boothassistant'.$i.'regnumber']}}/{{$boothassistantdata['boothassistant'.$i.'']}}/{{ $exhibitor['company'] }}" id="printbadgeboothassist{{ $i }}"  style="display:none;" class="span12"></iframe></td>
				            </tr>

			        	@endfor
				        </tbody>
				    @else
			        	<tr>
		              		<td colspan="4">There's no data to show</td>
		            	</tr>
			        @endif
			      </table>

			      <br/>
			      <br/>

			      <legend>ADDITIONAL EXHIBITOR PASS (PAYABLE)<small>{{ $addboothslot.' slot available'}}</small></legend>
			      <table id="order-table">
			      	@if($addboothassistantcount!=0)
				        <thead>
				          <tr>
				            <th class="span1">No.</th>            
				            <th class="span8">Names of Exhibitor’s Pass Holders</th>
				            <th class="align-center">Status</th>
				            <th class="align-center">Action</th>
				          </tr>
				        </thead>
				        <tbody>
				          @for($i=1;$i<=$addboothassistantcount;$i++)

				            <tr>
				              <td>{{ $i }}. </td>
				              <td class="passname">{{ $boothassistantdata['addboothname'.$i.''] }}</td>			              
			                  <td class="aligncenter action" >Imported on {{ date('d-m-Y',  $boothassistantdata['addboothname'.$i.'timestamp']->sec) }}</td>
			                  <td id="status_addboothname{{ $i }}" class="align-center status"><span class="icon- fontGreen existtrue">&#xe20c;</span></td>
				            </tr>

				          @endfor
				          
				        </tbody>
				    @else
				    	<tr>
		              		<td colspan="4">There's no data to show</td>
		            	</tr>
				    @endif
			      </table>

			      <div id="modal2" class="modal hide">
			      	modal 2
			      </div>

			  </div>
			</div>
</div>

<div id="stack2" class="modal hide fade" tabindex="1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h3>Input PIN to re-print</h3>
  </div>
  <div class="modal-body">
    
    <input type="password" id="supervisorpin" data-tabindex="1">
    <br/>
    <button class="btn" data-toggle="modal" id="submitpin" href="#">Submit</button>
  </div>
  <div class="modal-footer">
  </div>
</div>

<div id="stack3" class="modal hide fade" tabindex="1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h3>Input Full Name</h3>
  </div>
  <div class="modal-body">
    
    <input type="text" id="addboothnameinput" data-tabindex="1" class="span5">
    <br/>
    <button class="btn" data-toggle="modal" id="submitaddassist" href="#">Submit</button>
  </div>
  <div class="modal-footer">
  </div>
</div>

<?php	
	
	$ajaxprintbadge = (isset($ajaxprintbadge))?$ajaxprintbadge:'/';
	$userid = $exhibitor['_id']->__toString();
	
?>
<script type="text/javascript">
$('#submitpin').click(function(){
	
	var pintrue = '{{ Config::get("eventreg.pinsupervisorexhibitor") }}';
	var pinvalue = $('#supervisorpin').val();
	if(pinvalue == pintrue){
		$.post('{{ URL::to($ajaxprintbadge) }}',{'id':'{{$userid}}'}, function(data) {
			if(data.status == 'OK'){
				//var pframe = document.getElementById('print_frame');
				//var pframeWindow = pframe.contentWindow;
				//pframeWindow.print();
				$('#supervisorpin').val('');
				$('#stack2').modal('hide');

			}
		},'json');
	}else{
		alert("Wrong PIN, please try again");
	}

});

var asstype = <?php print json_encode(Config::get("eventreg.exhibitorassistanttype"));?>;
//checking Slot	

var boothassslot = '{{ 10-$boothassistantcount; }}';
var addboothslot = '{{ $data['totaladdbooth']-$addboothassistantcount; }}';


var current_type = 0;
var current_type_id = 0;
var current_type_id_freepass = parseInt('<?php echo $freepasscount;?>');
var current_type_id_boothassist = parseInt('<?php echo $boothassistantcount;?>');
var frepassslot =0;
var boothassistslot =0;
var exhibitorid = '<?php echo $exhibitor['_id']->__toString();?>';
var companyname     = '<?php echo $exhibitor['company'];?>';
var companypic      = '<?php echo $exhibitor['firstname'].' '.$exhibitor['lastname'];?>';
var companypicemail = '<?php echo $exhibitor['email'];?>';
var hallname        = '<?php echo $exhibitor['hall'];?>';
var boothname       = '<?php echo $exhibitor['booth'];?>';
var current_pass_name = '';


$('#submitaddbooth').click(function(){
	
	frepassslot = $('#freepassleft').text();
	frepassslot = parseInt(frepassslot);
	boothassistslot = $('#boothassistleft').text();
	boothassistslot = parseInt(boothassistslot);

	var boothvalue = $('#valboothadd').val();

	if(boothvalue == asstype.freepassname){
		//chekcfirst
		if(frepassslot>0){
			$('#stack3').modal('show');
			current_type = 'freepassname';
			current_type_id = current_type_id_freepass+1;
		}else{
			alert('Cannot add more data, this exhibitor already have '+current_type_id_freepass+' record')
		}
	}else if(boothvalue == asstype.boothassistant){
		//chekcfirst
		if(boothassistslot>0){
			$('#stack3').modal('show');
			current_type = 'boothassistant';
			current_type_id = current_type_id_boothassist+1;
		}else{
			alert('Cannot add more data, this exhibitor already have '+current_type_id_boothassist+' record')
		}
	}else if(boothvalue == asstype.addboothname){
		//chekcfirst
		if(addboothslot>0){
			$('#stack3').modal('show');
			current_type = 'addboothname';
			current_type_id = '<?php echo $addboothassistantcount +1;?>';
		}else{
			alert('Cannot add more data, this exhibitor already have '+{{ $addboothassistantcount }}+' record' )
		}
	}else{
		alert('Wrong booth code, please try again');
	}

	

});

$('#submitaddassist').click(function(){
    current_pass_name = $('#addboothnameinput').val();
    <?php

      $ajaxonsiteBoothAssistant = (isset($ajaxonsiteBoothAssistant))?$ajaxonsiteBoothAssistant:'/';
    ?>
    if(current_type =='0' || current_pass_name==''){
    	
	    alert('Error, cannot proccess data, please try again');

	}else{
	 	$.post('{{ URL::to($ajaxonsiteBoothAssistant) }}',{'exhibitorid':exhibitorid,'companyname':companyname,'companypic':companypic,'companypicemail':companypicemail,'hallname':hallname,'boothname':boothname,'type':current_type,'typeid':current_type_id,'passname':current_pass_name}, function(data) {
	    	$('#submitaddassist').text('Processing..');
			$('#submitaddassist').attr("disabled", true);
	      	
	    	if(data.status == 'OK'){
	        	$('#submitaddassist').text('Submit');
				$('#submitaddassist').attr("disabled", false);
	        	$('#stack3').modal('hide');
	        	$('#statusnotif').prepend('<div class="alert alert-info fade in">' +
      			'Updated!<button type="button" class="close" data-dismiss="alert">&nbsp;</button>' +
    			'</div>');
    			$('#addboothnameinput').val('');
    			if(current_type == 'freepassname'){
    				$('.tablefreepassname').append(
    				'<tr>'+
    				'<td>'+current_type_id+'.&nbsp;</td>'+
				    '<td class="passname">'+current_pass_name+'</td>'+
			        '<td class="aligncenter action" ><span class="icon- fontGreen existtrue">&#xe20c;</span>&nbsp;&nbsp;Imported on '+data.importedtime+'</td>'+
			        '<td id="status_freepassname'+current_type_id+'" class="align-center status"><a class="icon- importidividual" id="freepassname'+current_type_id+'"><i>&#xe14c;</i><span class="formstatus" id="" > Print this data</span></a></td>'+
			        '</tr>');
			        frepassslot --;
			        current_type_id_freepass ++;
			        
			        $('#freepassleft').text(frepassslot);

    			}else if(current_type == 'boothassistant'){
    				$('.tableboothassist').append(
    				'<tr>'+
    				'<td>'+current_type_id+'.&nbsp;</td>'+
				    '<td class="passname">'+current_pass_name+'</td>'+
			        '<td class="aligncenter action" ><span class="icon- fontGreen existtrue">&#xe20c;</span>&nbsp;&nbsp;Imported on '+data.importedtime+'</td>'+
			        '<td id="status_freepassname'+current_type_id+'" class="align-center status"><a class="icon- importidividual" id="freepassname'+current_type_id+'"><i>&#xe14c;</i><span class="formstatus" id="" > Print this data</span></a></td>'+
			        '</tr>');
			        boothassistslot --;
			        current_type_id_boothassist ++;
			        
			        $('#boothassistleft').text(boothassistslot);
    			}

    			current_type_id = 0;

	      	}else{
	    		alert('Error, cannot proccess data, please try again');  		
	      	}
	    },'json');
	 	
	}
	return false;
  
});


//print
$('.printbadge').click(function(e){
	var _id = 'printbadge'+e.target.id;
	
	var pframe = document.getElementById(_id);
	var pframeWindow = pframe.contentWindow;
	pframeWindow.print();
	
});



</script>


@endsection