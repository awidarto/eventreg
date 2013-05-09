@layout('master')


@section('content')
<div class="tableHeader">
<h3 class="formHead">Change company name</h3>
</div>


{{$form->open('attendee/changecompanyname','POST',array('class'=>'custom addAttendeeForm'))}}
{{ $form->text('companyname','Find Company name.req','',array('class'=>'text span8','id'=>'firstname')) }}

{{ $form->text('companyname','Replace to.req','',array('class'=>'text span8','id'=>'firstname')) }}

{{ Form::submit('Submit',array('class'=>'button'))}}&nbsp;&nbsp;
{{ Form::reset('Reset',array('class'=>'button'))}}
</div>
{{$form->close()}}
@endsection