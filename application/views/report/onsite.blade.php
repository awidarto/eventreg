@layout('master')

@section('content')
<?php
$day = 0;
$day1 = 0;
?>
<div class="metro span12">
	<div class="metro-sections-noeffect">

	   <div id="section1" class="metro-section tile-span-8">
	   		<div class="blockseparate marginbottom">
		      <h2>Onsite Registration</h2>
		      
		     <!-- <h5>Convention Registration</h5> -->
		      <a class="tile imagetext bg-color-blue statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $totalonsite }}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Onsite</div>
		            <div class="text">Registration</div>
		         </div>   
		      </a>

		      <?php
		      foreach($getCountOnsite as $key => $value):
		      $day++;
		      ?>
		      <a class="tile imagetext bg-color-green statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $value }}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Day {{$day}}</div>
		            <div class="text">Registration</div>
		         </div>   
		      </a>
		      @endforeach

		      <a class="tile imagetext bg-color-blue statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $totalallparticipant }}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">All</div>
		            <div class="text">Participant</div>
		         </div>   
		      </a>

		      <a class="tile app bg-color-empty" href="#"></a>
	  		</div>
	      <div class="clear"></div>
	      <div class="blockseparate marginbottom">
		      <h2>Badge Pickup</h2>
		      
		     <!-- <h5>Convention Registration</h5> -->
		      <a class="tile imagetext bg-color-blue statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $getbadgepickupnotes }}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Onsite</div>
		            <div class="text">Pickup</div>
		         </div>   
		      </a>

		      <a class="tile imagetext bg-color-blue statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ 137 }}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">group</div>
		            <div class="text">Pickup</div>
		         </div>   
		      </a>

		      <?php

		      foreach($getCountpickupnotes as $key => $value):
		      $day1++;
		      ?>
		      <a class="tile imagetext bg-color-green statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $value }}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Day {{$day1}}</div>
		            <div class="text">Pickup</div>
		         </div>   
		      </a>
		      @endforeach

		      <a class="tile app bg-color-empty" href="#"></a>
	     
	  	  <div class="clear"></div>

	   </div>

		
	</div>
</div>


@endsection