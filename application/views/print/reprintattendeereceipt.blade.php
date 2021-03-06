<style type="text/css">
body{
	margin: 0;
	padding:0;
}
table{
	margin-left: 20px;
}
table td{
	padding:8px;
}
span.currency{
	display: inline-block;
	width: 40px;
}
</style>
<?php

setlocale(LC_MONETARY, "en_US");
$currency = $data['payonsite_currency'];

if($currency == 'idr'){
	$totalIDR = $data['payonsite_totalidr'];
	$totalUSD = '';
	$sayinwords = $towords->to_words($totalIDR,"en").' Rupiahs';
}else if($currency == 'usd'){
	$totalIDR = '';
	$totalUSD = $data['payonsite_totalusd'];
	$sayinwords = $towords->to_words($totalUSD,"en").' US Dollars';
}

?>

<div style="width:100%;position:relative;display:block;font-family:Helvetica,Arial,Sans-serif;font-size:10px;margin:0;">
	<div style="width:100%;position:relative;display:block;margin:0;float:left;">

		<div style="position:relative;display:inline-block;float:left;margin:0 10px 10px 0;">{{ HTML::image('images/ipa-logo.jpg','badge_bg',array('class'=>'cardtemplate','style'=>'width:70px;')) }}</div>
		<div style="width:80%;position:relative;display:inline-block;float:left;">
			<h2 style="display:inline-block;margin:15px 0 0 7px;">&nbsp;</h2><br/>
			<h3 style="display:inline-block;margin:0 0 0 4px;">THE 37TH IPA CONVENTION AND EXHIBITION 2013</h3><br/>
			<h5 style="display:inline-block;margin:0 0 0 4px;">JAKARTA CONVENTION CENTER, 15-17 MAY 2013</h5>
		</div>
	</div>
	<div style="clear:both;"></div>
	<hr/>
	<div style="width:100%;position:relative;display:block;float:left;font-size:10px;">
		<h2 style="margin:0 auto;padding:5px 0 2px;text-align:center;font-size:18px;">RECEIPT</h2>
		<div style="width:100%;position:relative;display:inline-block;float:left;">
			<table style="font-size:11px;margin-top:6px;">
			<tr style="vertical-align: top;margin:5px 0;">
				<td>No. Registration</td>
				<td>:</td>
				<td>{{ $data['registrationnumber'] }}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>
				Received From
				</td>
				<td>:</td>
				<td><?php echo $data['salutation'];?>. <?php echo $data['firstname'].' '.$data['lastname'] ;?><br/>
					
					<?php echo $data['company'];?><br/>
					<?php
					if($data['address_1']!='-'){
						echo $data['address_1'];
						echo ',<br/>';
					}
					?>
					<?php 
					if($data['address_2']!='-' || $data['address_2']!=''){
						echo $data['address_2'];
						echo '<br/>';
					}
					?>
					<?php
					if($data['city']!='-'){
						echo $data['city'].' - '.$data['zip'];
						echo '<br/>';
					}?>
					<?php 
					if($data['country']!='none'){
						echo $data['country'];
					}?>
					<br/>
				</td>
				
			</tr>

			<tr style="vertical-align: top;margin:5px 0;">
				<td>In Payment Of:</td>
				<td>:</td>
				<td>Registration Fees (
					@if($data['regtype']=='PD')
					Professional Domestic
					@elseif($data['regtype']=='PO')
					Professional Overseas
					@elseif($data['regtype']=='SO')
					Student Overseas
					@elseif($data['regtype']=='SD')
					Student Domestic
					@endif
				)</td>
			</tr>

			

			<tr style="vertical-align: top;margin:5px 0;">
				<td>Amount:</td>
				<td>:</td>
				<td>
					@if($currency == 'idr')
						<p><span class="currency">(IDR)</span> <strong class="idrnominal">{{ formatrp ($totalIDR) }}</strong></p>
						<p><span class="currency">(USD)</span><strong class="usdnominal"></strong></p>
					@elseif($currency == 'usd')
						<p><span class="currency">(IDR)</span> <strong class="idrnominal"></strong></p>
						<p><span class="currency">(USD)</span><strong class="usdnominal">{{money_format(" %!n ", $totalUSD) }}</strong></p>
					@endif
				</td>
			</tr>

			<tr style="vertical-align: top;margin:5px 0;">
				<td>Say in Word:</td>
				<td>:</td>
				<td><i class="sayinwords">{{$sayinwords}}</i></td>
			</tr>

			<tr style="vertical-align: top;margin:5px 0;">
				<td>Payment Method:</td>
				<td>:</td>
				<td>
					@if($data['payonsite_paymentvia']=='cash')
						<span class="imagecheckcash">√</span>&nbsp;&nbsp;Cash &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="imagecheckcc"></span>&nbsp;&nbsp;Credit Card&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="imagecheckdebit"></span>&nbsp;&nbsp;Debit BCA </td>
					@elseif($data['payonsite_paymentvia']=='cc')
						<span class="imagecheckcash"></span>&nbsp;&nbsp;Cash &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="imagecheckcc">√</span>&nbsp;&nbsp;Credit Card&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="imagecheckdebit"></span>&nbsp;&nbsp;Debit BCA </td>
					@elseif($data['payonsite_paymentvia']=='debit bca')
						<span class="imagecheckcash"></span>&nbsp;&nbsp;Cash &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="imagecheckcc"></span>&nbsp;&nbsp;Credit Card&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="imagecheckdebit">√</span>&nbsp;&nbsp;Debit BCA </td>
					@endif
			</tr>

			<tr style="vertical-align: top;margin:5px 0;">
				<td>Disclaimer</td>
				<td>:</td>
				<td>There is no refund after payment made</td>
			</tr>
			

			</table>
		</div>

		
	</div>

	<div style="clear:both;"></div>
	
	

	<div style="clear:both;"></div>
	

</div>
<div style="width:32%;margin-top:-15px;float:right;margin-right:40px;display:block;font-family:Helvetica,Arial,Sans-serif;font-size:10px;">
	<p>Jakarta, <?php echo date('l jS F Y');?></p>
	<br/>
	<br/>
	<p style="margin-bottom:0;"><span style="border-bottom:1px solid #000;width:35%;display:block;">KANIA ANISIA</span><span>Finance & Accounting Dept.</span></p>
	
	
</div>

