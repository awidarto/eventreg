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
<style type="text/css">
#order-table td {

}

table.withborder tr td {

}
</style>
<table id="order-table" class="withborder cptable">
<tr class="even" style="background-color: #bdbdbd;">
  <td style="padding: 5px;border: 1px solid #d6d6d6;">No.</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Hall</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">PIC</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Phone</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Ext.</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Mobile Phone</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Email</td>
</tr>
<tr class="odd">
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">1.</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">Main Lobby</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Trisa</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">332</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 813 1847 1957</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">trisa@dyandra.com</td>
</tr>
<tr class="odd">
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Rachel</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">119</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 812 9850 9799</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">rachel.pardede@dyandra.com</td>
</tr>

<tr class="even">
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">2.</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">Assembly</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Dina</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">523</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 856 9364 0498</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">kusuma.ardina@dyandra.com</td>
</tr>
<tr class="even">
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Talitha</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">358</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 878 2276 5155</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">talitha.sabrina@dyandra.com</td>
</tr>


<tr class="odd">
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">3.</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">Cendrawasih</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Dian</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">271</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 811 143 004</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">dian@dyandra.com</td>
</tr>
<tr class="odd">
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Anita</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">326</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 812 1011 094</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">anita.afriani@dyandra.com</td>
</tr>

<tr class="even">
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">4.</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">Hall A</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Tia</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">323</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 812 8723 9036</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">tia.hamidah@dyandra.com</td>
</tr>
<tr class="even">
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Wulan</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">528</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 856 7578 738</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">wulan.septiani@dyandra.com</td>
</tr>


<tr class="odd">
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">5.</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;" rowspan="2">Hall B</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Raymond</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">356</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 852 1067 1046</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">raymond@dyandra.com</td>
</tr>
<tr class="odd">
  <td style="padding: 5px;border: 1px solid #d6d6d6;">Rain</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">(+6221) 3199 6077</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">329</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">+62 813 8057 3636</td>
  <td style="padding: 5px;border: 1px solid #d6d6d6;">rain.januardo@dyandra.com</td>
</tr>



</table>

