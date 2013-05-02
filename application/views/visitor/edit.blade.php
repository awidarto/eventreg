@layout('master')


@section('content')
<div class="tableHeader">
<h3 class="formHead">{{$title}}</h3>
</div>

{{$form->open('visitor/edit/'.$user['_id'],'POST',array('class'=>'custom'))}}

    {{ $form->hidden('id',$user['_id'])}}
    {{ $form->hidden('registrationnumber',$user['registrationnumber'])}}

<div class="row-fluid formNewAttendee">
    <div class="span6">
        <fieldset>
            <legend>Mandatory Information</legend>

                {{ Form::label('formnumber','Form Number *')}}
                <div class="row-fluid inputInline">
                    {{ $form->text('formnumber1','','',array('class'=>'text areacodePhone','id'=>'formnumber1','maxlength'=>2)) }}
                    
                    {{ $form->text('formnumber2','','',array('class'=>'text codePhone','id'=>'formnumber2','maxlength'=>4)) }}
                </div>

                

                {{ $form->text('firstname','Full Name.req','',array('class'=>'text span8','id'=>'firstname')) }}
                
                
                
                {{ $form->text('company','Company / Institution.req','',array('class'=>'text span6','id'=>'company')) }}
                {{$form->select('role','Visitor Type',Config::get('eventreg.visitors'),array('class'=>'span12'))}}

        </fieldset>

    </div>

    <div class="span6">

        <fieldset>
            <legend>Additional Information</legend>
                
                {{ $form->text('email','Email','',array('class'=>'text span8','id'=>'email')) }}

                <div class="questionare">
                {{$form->select('business','Please indicate the nature of your business ',Config::get('visitorquestions.business'),array('class'=>'span12','id'=>'business'))}}
                {{ $form->text('businessother','','',array('class'=>'text span8','id'=>'businessother','style'=>'display:none;','placeholder'=>'typehere')) }}
                {{$form->select('purpose','Please indicate the purpose of your visit',Config::get('visitorquestions.purpose'),array('class'=>'span12'))}}

                {{$form->select('organizationsize','Please indicate your organization’s size',Config::get('visitorquestions.organizationsize'),array('class'=>'span12'))}}

                {{$form->select('knowipa37','How did you know about the 37th IPA Convex 2013',Config::get('visitorquestions.knowipa37'),array('class'=>'span12'))}}
                {{ $form->text('knowother','','',array('class'=>'text span8','id'=>'knowother','style'=>'display:none;','placeholder'=>'typehere')) }}
                {{$form->select('country','Country of Origin',Config::get('country.countries'),array('class'=>'span12'))}}
                </div>  


        </fieldset>

    </div>
</div>

<hr />

<div class="row right">
{{ Form::submit('Save',array('class'=>'button'))}}&nbsp;&nbsp;

</div>
{{$form->close()}}

<script type="text/javascript">

  $(document).ready(function() {
    $('#formnumber1, #formnumber2').autotab_magic().autotab_filter('numeric');
  });

  $('select').select2({
    width : 'resolve'
  });
  $("#s2id_field_country").select2("val", "-");



  $("#field_business").on("change", function(e) {
    var value = e.val ;
    var toshow = $('#businessother');
    if(value == 'others'){
      toshow.show();
    }else{
      toshow.hide();
      toshow.val('');
    }
  });

  $("#field_knowipa37").on("change", function(e) {
    var value = e.val ;
    var toshow = $('#knowother');
    if(value == 'others'){
      toshow.show();
    }else{
      toshow.hide();
      toshow.val('');
    }
  });

  $('#field_role').change(function(){
      //alert($('#field_role').val());
      // load default permission here
  });
</script>

@endsection