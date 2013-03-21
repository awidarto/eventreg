<p><?php
	echo 'Jakarta, '.date('l jS F Y');
?>
</p>

<p>Attention to: <br/>
<strong>{{ $data['firstname'].' '.$data['lastname'] }}</strong><br/>
<strong>{{ $data['position'] }}</strong><br/>
<strong>{{ $data['company'] }}</strong><br/>
<strong>{{ $data['address_1'] }}</strong><br/>
{{ ($data['address_2'] == '')?'':'<strong>'.$data['address_2'].'</strong><br/>' }}
<strong>{{ $data['city'].' '.$data['zip'] }}</strong><br/>
<strong>Registration Number : {{ $data['registrationnumber'] }}</strong></p>

<p>Dear Sir/Madam,<br />
Thank you for registering to The 37th IPA Convention & Exhibition 2013 Secretariat. Please find below summary of your registration:</p>
</p>

@if($passwordRandom == 'nochange')

@elseif($fromadmin == 'yes')
<p><strong><u>LOGIN INFO</u></strong></p>
<table>
	<tr>
		<td>Email</td>
		<td>:</td>
		<td>{{ $data['email'] }}</td>
	</tr>
	<tr>
		<td>Password</td>
		<td>:</td>
		<td>{{ $passwordRandom }}</td>
	</tr>
</table>
<p>Please login to exhibitor profile in <a href="#">www.ipaconvex.com</a> and submit your requirement form </p> 
@endif


<p>If you need further information regarding the convention, please feel free to contact us.
Thank you very much for your participation and we look forward to see you on The 37th IPA Convention & Exhibition 2013.
</p>

<p>Regards,<br/>
<strong>The 37th IPA Convention & Exhibition 2013 Secretariat</strong><br/>
PT Dyandra Promosindo<br/>
The City Tower, 7th Floor | Jl. M.H. Thamrin No. 81 | Jakarta 10310 - Indonesia<br/>
T. +62-21-31996077, 31997174 (direct) | F. +62-21-31997176<br/>
E. conventionipa2013@dyandra.com | W. www.ipaconvex.com</p>

<p>*Kindly contact your hall coordinator for further enquires, requirements and operational form submission.</p>

<table id="order-table" class="withborder">
<tr>
  <th>HALL</th>
  <th>NAME </th>
  <th>PHONE </th>
  <th>EXT </th>
  <th>MOBILE PHONE </th>
  <th>EMAIL</th>
</tr>

</table>

