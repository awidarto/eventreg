@layout('master')


@section('content')
<div class="tableHeader">
<h3 class="formHead">{{$title}}</h3>
</div>

<p>{{ $updateCount }} Total data updated for TotalIDR and totalUSD</p>
<p>{{ $caheIDCount }} Total data updated for CacheID</p>
<p>{{ $caheOBJCount }} Total data updated for CacheOBJ</p>
<p>{{ $companyNPWPCount }} Total data updated for companyNPWP</p>
<p>{{ $groupIDCount }} Total data updated for GroupID</p>
<p>{{ $groupNameCount }} Total data updated for groupNameCount</p>
<p>{{ $invLetterCount }} Total data updated for Inv. Letter</p>
<p>{{ $invCompanyAddCount }} Total data updated for invCompanyAdd</p>
<p>{{ $paymentStatCount }} Total data updated for paymentStatCount</p>
<p>{{ $AddCount }} Total data updated for AddStatCount</p>
<p>{{ $AddCountInvoice }} Total data updated for AddStatCountInv</p>
<p>{{ $ConfCount }} Total data updated for ConfCount</p>
<p>{{ $normalRate }} Total data updated for normalRate</p>


@endsection