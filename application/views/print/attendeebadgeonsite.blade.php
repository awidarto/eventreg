@layout('blank')

@section('content')
<style type="text/css">
body{
	margin: 0;
	padding:0;
}
.card-template-area{
	position: relative;
	width: 250px;
	margin-left:7px;
	margin-top:2px;
}
.headarea{
	position: absolute;
	top: 99px;
	left: 21px;
}
.barcodearea{
	position: absolute;
	top: 132px;
	left: 37px;
	
	text-align: center;
}
.card-template-area h1.fullname{
	font-size: 11px;
font-family: Helvetica,Arial,Serif;
position: relative;
margin: 0;
padding: 0;
width: 150px;
line-height: 12px;
margin-bottom: 0px;
	
}
.card-template-area h1.companyname{
	font-size: 10px;
font-family: Helvetica,Arial,Serif;
position: relative;
margin: 0;
padding: 0;
width: 150px;
line-height: 12px;
}
.barcodetext{
	display: block;
margin: 0 auto;
text-align: center;
margin-top: 5px;
font-size: 8px;
font-family: Helvetica,Arial,Serif;
position: absolute;
bottom: 0px;
left: 46px;
background: #fff;

}
/*.barcodeimage{
	width: 100px;
}*/
.cardtemplate{
	width:290px;
}
</style>
<div id="preview-card">
	<div class="card-template-area">
		{{ HTML::image('images/idcard-template.jpg','badge_bg',array('class'=>'cardtemplate')) }}
		<div class="headarea">
			<h1 class="fullname"><?php echo $profile['firstname'].' '.$profile['lastname'];?></h1>
			<h1 class="companyname"><?php echo $profile['company'];?></h1>
		</div>
		<div class="barcodearea">
			<img class="barcodeimage" src="{{URL::to('barcode/'.$profile['registrationnumber'].'?'.time() )}}" />
			<span class="barcodetext">{{ $profile['registrationnumber'] }}</span>
		</div>
	</div>
</div>

@endsection