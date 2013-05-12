@layout('master')


@section('content')
<div class="tableHeader">
<h3 class="formHead">Write bulk notes company</h3>

@if (Session::has('notify_success'))
    <div class="alert alert-success alertreader">
         {{Session::get('notify_success')}}
    </div>
@endif

</div>


{{$form->open('attendee/writenotesbasedcompany','POST',array('class'=>'custom addAttendeeForm'))}}
{{ $form->text('findcompanyname','Find Company name.req','',array('class'=>'text span8','id'=>'firstname')) }}

{{ $form->text('notes','Notes.req','',array('class'=>'text span8','id'=>'firstname')) }}

{{ Form::submit('Submit',array('class'=>'button'))}}&nbsp;&nbsp;
{{ Form::reset('Reset',array('class'=>'button'))}}
{{$form->close()}}
@endsection