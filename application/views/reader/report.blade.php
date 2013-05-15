@layout('reader')

@section('content')

<header id="nav-bar" class="container-fluid donotprint headerreader headerreaderreport">
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
			{{$form->select('activityselected','',Config::get('eventreg.activity'),array('class'=>'span3','id'=>'activityselected'))}}
		</div>
			
  	</div>
  	<br/>
  	<div class="metro span12">
		<div class="metro-sections-noeffect">

		   	<div id="section1" class="metro-section tile-span-8">
		   		<div class="blockseparate marginbottom">
		   			<?php 
		   			/*$namaacara='';
		   			$config = Config::get('eventreg.activity');
		   			foreach ($config as $key => $value) {
		   				$namaacara = $value;
		   			}*/
		   			?>
		   		<p>&nbsp;&nbsp;&nbsp;resume for {{ $sessionselected }} session</p>
			    <!-- <h5>Convention Registration</h5> -->
			    	 <a class="tile wide imagetext bg-color-orange statistic" href="#">
				         <div class="image-wrapper">
				            <div class="text-biggest">{{$stat['all']}}</div>
				         </div>
				         <div class="column-text">
				            <div class="text">Total</div>
				            <div class="text">All</div>
				         </div>
				         <span class="app-label"></span>
			      	</a>
			      	<a class="tile wide imagetext bg-color-green statistic" href="#">
			        	<div class="image-wrapper">
			            	<div class="text-biggest">{{$stat['attendee']}}</div>
			         	</div>
			         	<div class="column-text">
			            	<div class="text">Total</div>
			            	<div class="text">Participant</div>
			         	</div>
			         	<span class="app-label"></span>
			      	</a>

			      	<a class="tile wide imagetext bg-color-gree statistic" href="#">
			        	<div class="image-wrapper">
			            	<div class="text-biggest">{{$stat['commitee']}}</div>
			         	</div>
			         	<div class="column-text">
			            	<div class="text">Total</div>
			            	<div class="text">Comittee</div>
			         	</div>
			         	<span class="app-label"></span>
			      	</a>

			      	<a class="tile wide imagetext bg-color-gree statistic" href="#">
			        	<div class="image-wrapper">
			            	<div class="text-biggest">{{$stat['vip']}}</div>
			         	</div>
			         	<div class="column-text">
			            	<div class="text">Total</div>
			            	<div class="text">VIP & VVIP</div>
			         	</div>
			         	<span class="app-label"></span>
			      	</a>

			      	<a class="tile wide imagetext bg-color-gree statistic" href="#">
			        	<div class="image-wrapper">
			            	<div class="text-biggest">{{$stat['other']}}</div>
			         	</div>
			         	<div class="column-text">
			            	<div class="text">Total</div>
			            	<div class="text">Other</div>
			         	</div>
			         	<span class="app-label"></span>
			      	</a>

		  		</div>
		      	<div class="clear"></div>
		      	<a class="btn" id="" href="{{URL::to('export/reader?session='.$sessionselected)}}"><span class="icon-">&#xe1dd;</span>&nbsp;Download data as .csv</a>
		      	<a class="btn" id="" href="{{URL::to('reader')}}"><span class="icon-">&#xe1dd;</span>&nbsp;Back to reader scan</a>
		   	</div>
		</div>
	</div>
</div>
<script type="text/javascript">


$(function(){
  // bind change event to select
  $('#field_activityselected').bind('change', function () {
      var activityselected = $(this).val(); // get selected value
      var url = '<?php echo $urlredirect;?>?activityselected='+activityselected;
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
  });
});

</script>

@endsection