<p><?php
	echo 'Jakarta, '.date('l jS F Y');
?>
</p>

<p>Attention to: <br/>
<strong>{{ $data['firstname'].' '.$data['lastname'] }}</strong><br/>
<strong>{{ $data['position'] }}</strong><br/>
<strong>{{ $data['company'] }}</strong><br/>
<strong>{{ $data['address'].' '.$data['city'].' '.$data['zip'] }}</strong><br/>
<strong>Registration Number : {{ $data['registrationnumber'] }}</strong></p>

<p>Dear Sir/Madam,
Thank you for your confirmation payment in 37th IPA Convention & Exhibition. </br>
We have received your payment confirmation and will process it shortly. </br>
Please find below summary of your registration:</p>

<p><strong><u>CONVENTION REGISTRATION</u></strong></p>

<p>Type of registration fee 
	@if($data['regtype'] == 'PO')
		(Professional / Delegate Overseas)
	@elseif($data['regtype'] == 'PD')
		(Professional / Delegate Domestic)
	@elseif($data['regtype'] == 'SD')
		(Student Domestic)
	@elseif($data['regtype'] == 'SO')
		(Student Overseas)
	@endif : 
	@if($data['regtype'] == 'PO')
		USD 500
	@elseif($data['regtype'] == 'PD')
		IDR 4.500.000,-
	@elseif($data['regtype'] == 'SD')
		IDR 400.000,-
	@elseif($data['regtype'] == 'SO')
		USD 120
	@endif
<br/>
Attend on Industrial Dinner (16 May 2013): {{ $data['attenddinner'] }}
</p>

<p><strong><u>PAYMENT INFORMATION</u></strong></p>

<p>
	Date Transfered    : {{ $data['transferdate'] }}<br/>
	Total Payment      : {{ $data['totalpayment'] }}<br/>
	Payment tranfer to : {{ $data['transferto'] }}<br/>
	Payment from       : {{ $data['fromaccountname'] }} - {{ $data['fromaccnumber'] }} - {{ $data['frombank'] }}<br/>
</p>

<p><strong><u>IMPORTANT NOTES</u></strong></p>
<ol>
	<li style="margin:3px;"><strong>Early Bird scheme applies to those who have registered and have settled payment by 15 March 2013 at the latest</strong>. Normal rate will be applied for the registration payment after 15 March 2013.</li>
	<li style="margin:3px;">Registration Forms received without registration fees will not be processed. </li>
	<li style="margin:3px;"><strong>No refund will be granted for cancellation after 14 April 2013.</strong> All cancellations must be made in writing to the Convention Secretariat and the refund for cancellations before 14 April 2013 will be made after the convention.</li>
	<li style="margin:3px;"><strong>If billing address for sending the invoice is different from participant’s company information, please send billing address information along with the registration form.</strong></li>
	<li style="margin:3px;"><strong>Convention registration payment deadline is 30 April 2013.</strong></li>
	<li style="margin:3px;">For those who <strong>participate in golf tournament, the payment must be settled before 14 April 2013.</strong></li>
	<li style="margin:3px;">Registration counter will be open in front of JCC Main Lobby on:
		<ul>
			<li><span style="margin:6px;display:inline-block;width:20%;">Monday</span> <span style="display:inline-block;width:20%;">13 May 2013</span><span style="display:inline-block;width:30%;">10.00 AM – 03.00 PM</span></li>
			<li><span style="margin:6px;display:inline-block;width:20%;">Tuesday</span> <span style="display:inline-block;width:20%;">14 May 2013</span><span style="display:inline-block;width:30%;">10.00 AM – 03.00 PM</span></li>
			<li><span style="margin:6px;display:inline-block;width:20%;">Wednesday</span> <span style="display:inline-block;width:20%;">15 May 2013</span><span style="display:inline-block;width:30%;">08.00 AM – 03.00 PM</span></li>
			<li><span style="margin:6px;display:inline-block;width:20%;">Thursday</span> <span style="display:inline-block;width:20%;">16 May 2013</span><span style="display:inline-block;width:30%;">08.00 AM – 03.00 PM</span></li>
			<li><span style="margin:6px;display:inline-block;width:20%;">Friday</span> <span style="display:inline-block;width:20%;">17 May 2013</span><span style="display:inline-block;width:30%;">08.00 AM – 12.00 PM</span></li>
			<li>Participants will be able to collect the convention kits at registration counter</li>
		</ul>
	</li>
	<li>Registered participants <strong>must wear ID badges</strong> all the times for sessions and function access</li>

</ol>

<p>If you need further information regarding the convention, please feel free to contact us.
Thank you very much for your participation and we look forward to see you on 37th IPA Convex.
</p>

<p>Regards,<br/>
<strong>37th IPA Convex Secretariat</strong><br/>
PT Dyandra Promosindo<br/>
The City Tower, 7th Floor | Jl. M.H. Thamrin No. 81 | Jakarta 10310 - Indonesia<br/>
T. +62-21-31996077, 31997174 (direct) | F. +62-21-31997176<br/>
E. ipaconvention@dyandra.com | W. www.ipaconvex.com</p>