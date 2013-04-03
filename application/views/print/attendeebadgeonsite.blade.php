@layout('blank')

@section('content')
{{ HTML::style('css/typography.css')}}
<style type="text/css">
body{
	margin: 0;
	padding:0;
}

.card-template-area{
	position: relative;
	width:315px;
	/*height:196px;*/
	/*margin-left:7px;
	margin-top:2px;*/
	
}
.cardtemplate{
	width:315px;
	/*height:196px;*/
}
.headarea{
	position: absolute;
	top: 75px;
	left: 21px;
	width: 220px;
	height: 75px;
	vertical-align: middle;
	display: table;
}
.barcodearea{
	position: absolute;
	top: 25px;
	right: 5px;
	
	text-align: center;
}
.card-template-area .fullname{
	font-size: 18px;
	font-family: "RobotoCondensed";
	position: relative;
	margin: 0 auto;
	line-height: 17px;
	margin-bottom: 15px;
	letter-spacing: 0;
	font-weight: normal;
	vertical-align: middle;
	
	
}
.card-template-area .companyname{
font-size: 15px;
font-family: "RobotoLight";
position: relative;
margin: 0;
padding: 0;
font-weight: normal;
line-height: 15px;
letter-spacing: 0px;
}
.barcodetext{
	display: block;
margin: 0 auto;
text-align: center;
margin-top: 5px;
font-size: 7px;
font-family: Helvetica,Arial,Serif;
position: absolute;
bottom: 0px;
left: 46px;
background: #fff;

}
#preview-card{
	
	display: block;
}

.barcodeimage{
	width:167px;
}
.fullname,.companyname{
	text-transform:uppercase; 
}
</style>
<div id="preview-card">
	<div class="card-template-area">
		{{ HTML::image('images/idcard-template-attendee1.jpg','badge_bg',array('class'=>'cardtemplate')) }}
		<div class="headarea">
			<span class="fullname"><?php echo $profile['firstname'].' '.$profile['lastname'];?><br />
			<small class="companyname"><?php echo $profile['company'];?></small>
		</div>
		<div class="barcodearea">
			<?php
			//$barcode = new Code39();
			$onlyconsonants = str_replace("-", "", $profile['registrationnumber']);
			//echo $barcode->draw($onlyconsonants);?>
			<img class="barcodeimage" src="{{URL::to('barcode/'.$onlyconsonants.'?'.time() )}}" />
			<span class="barcodetext">{{ $profile['registrationnumber'] }}</span>
		</div>
	</div>
</div>

@endsection