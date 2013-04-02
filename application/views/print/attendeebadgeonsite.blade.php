@layout('blank')

@section('content')
<style type="text/css">
body{
	margin: 0;
	padding:0;
}

.card-template-area{
	position: relative;
	width:315px;
	height:196px;
	/*margin-left:7px;
	margin-top:2px;*/
	
}
.headarea{
	position: absolute;
	top: 85px;
	left: 21px;
	width: 175px;
}
.barcodearea{
	position: absolute;
	top: 26px;
	right: -25px;
	
	text-align: center;
}
.card-template-area h1.fullname{
	font-size: 12px;
font-family: Helvetica,Arial,Serif;
position: relative;
margin: 0;
padding: 0;
width: 150px;
line-height: 12px;
margin-bottom: 5px;
letter-spacing: 0;
	
}
.card-template-area h1.companyname{
font-size: 10px;
font-family: Helvetica,Arial,Serif;
position: relative;
margin: 0;
padding: 0;
width: 150px;
line-height: 12px;
letter-spacing: 0px;
}
.barcodetext{
	display: block;
margin: 0 auto;
text-align: center;
margin-top: 5px;
font-size: 5px;
font-family: Helvetica,Arial,Serif;
position: absolute;
bottom: 0px;
left: 46px;
background: #fff;

}
#preview-card{
	width:315px;
	display: block;
}
.cardtemplate{
	width:315px;
	height:196px;
}
.barcodeimage{
	width:155px;
}
</style>
<div id="preview-card">
	<div class="card-template-area">
		{{ HTML::image('images/idcard-template-attendee.jpg','badge_bg',array('class'=>'cardtemplate')) }}
		<div class="headarea">
			<h1 class="fullname"><?php echo $profile['firstname'].' '.$profile['lastname'];?></h1>
			<h1 class="companyname"><?php echo $profile['company'];?></h1>
		</div>
		<div class="barcodearea">
			<?php
			$barcode = new Code39();
			$onlyconsonants = str_replace("-", "", $profile['registrationnumber']);
			echo $barcode->draw($onlyconsonants);?>
			<!--<img class="barcodeimage" src="{{URL::to('barcode/'.$profile['registrationnumber'].'?'.time() )}}" />-->
			<span class="barcodetext">{{ $profile['registrationnumber'] }}</span>
		</div>
	</div>
</div>

@endsection