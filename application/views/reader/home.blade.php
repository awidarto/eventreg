@layout('reader')

@section('content')

<header id="nav-bar" class="container-fluid donotprint headerreader">
  <div class="row-fluid">
     <div class="span12">
     	<div class="logoreader">
     		{{ HTML::image('images/logo-big.png','badge_bg',array('class'=>'cardtemplate','style'=>'width:70px;')) }}
        	<h3 style="display:inline-block;margin:0 0 0 4px;">THE 37TH IPA CONVENTION AND EXHIBITION 2013</h3><br/>
			<h5 style="display:block;margin:0 0 0 4px;">JAKARTA CONVENTION CENTER</h5>
			<h5 style="display:inline-block;margin:0 0 0 4px;">15-17 MAY 2013</h5>
		</div>
		@if (Session::has('notify_error'))
		    <div class="alert alert-error alertreader">
		         {{Session::get('notify_error')}}
		    </div>
		@endif
		@if (Session::has('notify_success'))
		    <div class="alert alert-success alertreader">
		         {{Session::get('notify_success')}}
		    </div>
		@endif
     </div>
     <div id="top-info" class="pull-right">
       
       
    </div>
</div>
</header>

<div class="container-fluid">
  

  <div class="row-fluid print">
    	

		<div class="bodyreader">
			{{$form->open('reader/process','POST',array('class'=>'custom addAttendeeForm'))}}
			<div class="row-fluid inputInline">
				<?php 
				$stationstore = Session::get('stationselected'); 
				$activitystore = Session::get('activityselected'); 
				?>
				{{$form->hidden('stationnumber',$stationstore)}}
				{{$form->hidden('activity',$activitystore)}}
				<div class="span6">
					{{ $form->text('regnumber','','',array('class'=>'text','id'=>'regnumber')) }}
				</div>
				<div class="span2">
					{{ Form::submit('submit',array('class'=>'button info'))}}&nbsp;&nbsp;
				</div>
			</div>
			{{$form->close()}}
		</div>
		{{ HTML::decode(HTML::link('reader/selectstation', '<i style="color:#cfcfcf;float:left;margin-right:10px;">&#x0023;</i><small>Select station & activity</small>', array('class' => 'selectbackstation icon- span 7'))); }}
		
  </div>
</div>
<script type="text/javascript">
function FocusOnInput()
{
     document.getElementById("regnumber").focus();
}
window.onload = FocusOnInput;

jQuery(document).ready(function () {
    //hide a div after 3 seconds
    setTimeout( "jQuery('.alertreader').hide();",3000 );
});

</script>

@endsection