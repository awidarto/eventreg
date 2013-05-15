@layout('master')

@section('content')
<div class="metro span12">

	<div class="metro-sections-noeffect">

	   <div id="section1" class="metro-section tile-span-8">
	   		<div class="blockseparate marginbottom">
		      <h2>Participant</h2>
		     <!-- <h5>Convention Registration</h5> -->
		     <a class="tile wide imagetext greenDark statistic" href="{{ URL::to('export/report/?type=all') }}">
		         <div class="image-wrapper">
		            <div class="text-biggest">{{ $stat['onsiteregist']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Onsite Registration</div>
		         </div>
		         <span class="app-label"></span>
		      </a>
		      
		      
		      
	  		</div>
	      <div class="clear"></div>

	      
	   </div>

		
	</div>

	<div class="metro-sections-noeffect">

	   <div id="section1" class="metro-section tile-span-8">
	   		<div class="blockseparate marginbottom">
		      <h2>Visitor</h2>
		     <!-- <h5>Convention Registration</h5> -->
		     <a class="tile wide imagetext greenDark statistic" href="{{ URL::to('export/report/?type=all') }}">
		         <div class="image-wrapper">
		            <div class="text-biggest">{{ $stat['Visitor']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Visitor</div>
		         </div>
		         <span class="app-label"></span>
		      </a>
		      <a class="tile imagetext bg-color-blue statistic" href="{{ URL::to('export/report/?type=PO') }}">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['VS']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Walk</div>
		            <div class="text">In</div>
		            <div class="text">Visitor</div>
		         </div>   
		      </a>
		      <a class="tile imagetext bg-color-purple statistic" href="{{ URL::to('export/report/?type=PD') }}">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['VIP']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text"></div>
		            <div class="text">VIP</div>
		            <div class="text">Visitor</div>
		         </div>
		      </a>
		      <a class="tile imagetext bg-color-red statistic" href="{{ URL::to('export/report/?type=SO') }}">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['VVIP']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text"></div>
		            <div class="text">VVIP</div>
		            <div class="text">Visitor</div>
		         </div>
		      </a>
		      <a class="tile imagetext bg-color-purple statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['media']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text"></div>
		            <div class="text">Media</div>
		            <div class="text"></div>
		         </div>
		      </a>
		      <a class="tile imagetext bg-color-orange statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['OC']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Other</div>
		            <div class="text">Complimentary</div>
		            <div class="text">Visitor</div>
		         </div>
		      </a>
		      
		      
	  		</div>
	      <div class="clear"></div>

	      
	   </div>

		
	</div>


	<div class="metro-sections-noeffect">

	   <div id="section1" class="metro-section tile-span-8">
	   		<div class="blockseparate marginbottom">
		      <h2>Official</h2>
		     <!-- <h5>Convention Registration</h5> -->
		     <a class="tile wide imagetext greenDark statistic" href="{{ URL::to('export/report/?type=all') }}">
		         <div class="image-wrapper">
		            <div class="text-biggest">{{ $stat['official']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Official</div>
		         </div>
		         <span class="app-label"></span>
		      </a>
		      <a class="tile imagetext bg-color-blue statistic" href="{{ URL::to('export/report/?type=PO') }}">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['COM']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Comittee</div>
		            <div class="text"></div>
		         </div>   
		      </a>
		      <a class="tile imagetext bg-color-orange statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['ATB']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Advisor to</div>
		            <div class="text">The Board</div>
		         </div>
		      </a>
		      <a class="tile imagetext bg-color-purple statistic" href="{{ URL::to('export/report/?type=PD') }}">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['BOD']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">BOD</div>
		            <div class="text"></div>
		         </div>
		      </a>
		      <a class="tile imagetext bg-color-red statistic" href="{{ URL::to('export/report/?type=SO') }}">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['SD']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text"></div>
		            <div class="text">Total</div>
		            <div class="text">Student</div>
		         </div>
		      </a>
		      <a class="tile imagetext bg-color-purple statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['ORG']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Organizer</div>
		            <div class="text"></div>
		         </div>
		      </a>
		      <a class="tile imagetext bg-color-orange statistic" href="#">
		         <div class="image-wrapper text-big">
		            <div class="text-big">{{ $stat['SPK']}}</div>
		         </div>
		         <div class="column-text">
		            <div class="text">Total</div>
		            <div class="text">Speaker</div>
		            <div class="text"></div>
		         </div>
		      </a>

		      
		      
		      <a class="tile app bg-color-empty" href="#"></a>
	  		</div>
	      <div class="clear"></div>

	      
	   </div>

		
	</div>

</div>


@endsection