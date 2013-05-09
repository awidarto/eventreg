@layout('public')


@section('content')
<div class="tableHeader">
<h3>Online Registration has been closed.</h3>
<br/>
<ul style="margin-left:25px;">
<li> For those who wish registered after 30th April 2013, please contact conventionipa2013@dyandra.com or call 021 31997174</li>

<li> Registration via phone will be open until 10th May 2013</li>

<li> Onsite registration will be open on 13th - 17th May 2013</li>
</ul>
</p>
<h3>{{$title}}</h3>
</div>
<!--{{ HTML::image('images/checked.png','checked',array('class'=>'check-icon','style'=>'float:left;')) }}-->
<!--<p>Please select registration type :</p>-->

<!--<p>{{ HTML::link('register','Individual Registration',array('class'=>'registIndividuType registType')).' '.HTML::link('register/group','Group Registration',array('class'=>'groupIndividuType registType')) }}-->

</p>
<br/>
<br/>
<h4 class="headIpaSite">Convention Login Form</h4>
<div class="row">
    {{ Form::open('attendee/login') }}
    <!-- check for login errors flash var -->
    @if (Session::has('login_errors'))
        <div class="alert alert-error">
             Email or password incorrect.
        </div>
    @endif
    <!-- username field -->
    {{ Form::label('username', 'Email') }}
    {{ Form::text('username') }}
    <!-- password field -->
    {{ Form::label('password', 'Password') }}
    {{ Form::password('password') }}
    <!-- submit button -->
    {{ Form::submit('Login',array('class' => 'button')) }}
    &nbsp;&nbsp;&nbsp;<img src="http://www.ipaconvex.com/images/arrow1.jpg" border="0" align="absmiddle" style="margin-right:5px ">{{ HTML::link('reset','Forgot your password ? ',array('class'=>'backtohome'))}}
    {{ Form::close() }}
	


    
</div>


@endsection