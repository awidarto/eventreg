@layout('blank')

@section('content')
<style type="text/css">
.card-template-area{
	position: relative;
	width: 600px;
}
.headarea{
	position: absolute;
	top:225px;
	left:55px;
}
.barcodearea{
	position: absolute;
	top:225px;
	right:30px;	
	width:210px;
	text-align: center;
}
.card-template-area h1.fullname{
	font-size: 24px;
	font-family: Helvetica,Arial,Serif;
	position: relative;
	margin:0;
	padding:0;
	width: 285px;
	line-height: 25px;
	margin-bottom: 18px;
	
}
.card-template-area h1.companyname{
	font-size: 20px;
	font-family: Helvetica,Arial,Serif;
	position: relative;
	margin:0;
	padding:0;
	width: 285px;
	line-height: 25px;
}
.barcodetext{
	display: block;
	margin: 0 auto;
	text-align: center;
	margin-top: 5px;
	font-size: 16px;
	font-family: Helvetica,Arial,Serif;

}
</style>
<div id="preview-card">
	<div class="card-template-area">
		<img src="http://localhost/eventreg/public/idcard-template.jpg">
		<div class="headarea">
			<h1 class="fullname"><?php echo $profile['firstname'].' '.$profile['lastname'];?></h1>
			<h1 class="companyname"><?php echo $profile['company'];?></h1>
		</div>
		<div class="barcodearea">
			<img class="barcodeimage" src="{{URL::to('barcode/'.$profile['registrationnumber'] )}}" />
			<span class="barcodetext">{{ $profile['registrationnumber'] }}</span>
		</div>
	</div>
</div>

@endsection