@layout('master')

@section('content')

<h4>User Profile</h4>
<div class="row">
	<div class="profileContent">
		<div class="two columns">
			{{ getavatar($profile['_id'])}}
		</div>
		<div class="ten columns">
			<h5>{{ $profile['firstname'].' '.$profile['lastname'] }}</h5>
			<table class="profile-info">
				<tr>
					<td class="detail-title">Email</td>
					<td class="detail-info">{{ $profile['email'] }}</td>
				</tr>

			</table>
		</div>
		</div>
	</div>
</div>
@endsection