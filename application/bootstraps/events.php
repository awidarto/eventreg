<?php

Event::listen('attendee.create',function($id,$newpass,$picemail,$picname){
    $attendee = new Attendee();
    $_id = $id;
    $data = $attendee->get(array('_id'=>$_id));

    //log message 
    $message = new Logmessage();

    $messagedata['user'] = $data['_id'];
    $messagedata['type'] = 'email.regsuccess';
    $messagedata['emailto'] = $data['email'];
    $messagedata['emailfrom'] = Config::get('eventreg.reg_admin_email');
    $messagedata['emailfromname'] = Config::get('eventreg.reg_admin_name');
    $messagedata['passwordRandom'] = $newpass;
    $messagedata['emailcc1'] = Config::get('eventreg.reg_dyandra_admin_email');
    $messagedata['emailcc1name'] = Config::get('eventreg.reg_dyandra_admin_name');
    $messagedata['emailcc2'] = $picemail;
    $messagedata['emailcc2name'] = $picname;
    $messagedata['emailsubject'] = 'Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')';
    $messagedata['createdDate'] = new MongoDate();
    
    if($message->insert($messagedata)){
        
        $body = View::make('email.regsuccess')
            ->with('data',$data)
            ->with('passwordRandom',$newpass)
            ->with('fromadmin','yes')
            ->render();

        Message::to($data['email'])
            ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
            ->cc($picemail, $picname)
            ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
            ->subject('Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')')
            ->body( $body )
            ->html(true)
            ->send();
    }

});

Event::listen('attendee.update',function($id,$newpass,$picemail,$picname){
    $attendee = new Attendee();
    $_id = $id;
    $data = $attendee->get(array('_id'=>$_id));

    $body = View::make('email.regsuccess')
        ->with('data',$data)
        ->with('passwordRandom',$newpass)
        ->with('fromadmin','yes')
        ->render();

    Message::to($data['email'])
        ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
        ->cc($picemail, $picname)
        ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
        ->subject('Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')')
        ->body( $body )
        ->html(true)
        ->send();

});

Event::listen('attendee.createformadmin',function($id,$newpass,$paymentstatus){
    $attendee = new Attendee();
    $_id = $id;
    $data = $attendee->get(array('_id'=>$_id));

    //log message 
    $message = new Logmessage();

    $messagedata['user'] = $data['_id'];
    $messagedata['type'] = 'email.regsuccess';
    $messagedata['emailto'] = $data['email'];
    $messagedata['emailfrom'] = Config::get('eventreg.reg_admin_email');
    $messagedata['emailfromname'] = Config::get('eventreg.reg_admin_name');
    $messagedata['passwordRandom'] = $newpass;
    $messagedata['emailcc1'] = Config::get('eventreg.reg_dyandra_admin_email');
    $messagedata['emailcc1name'] = Config::get('eventreg.reg_dyandra_admin_name');
    $messagedata['emailcc2'] = '';
    $messagedata['emailcc2name'] = '';
    $messagedata['emailsubject'] = 'Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')';
    $messagedata['createdDate'] = new MongoDate();
    
    if($message->insert($messagedata)){

        $body = View::make('email.regsuccess')
            ->with('data',$data)
            ->with('passwordRandom',$newpass)
            ->with('fromadmin','yes')
            ->with('paymentstatus',$paymentstatus)
            ->render();

        Message::to($data['email'])
            ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
            ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
            ->subject('Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')')
            ->body( $body )
            ->html(true)
            ->send();
    }

});




Event::listen('attendee.update',function($id,$newpass){
    $attendee = new Attendee();
    $_id = $id;
    $data = $attendee->get(array('_id'=>$_id));

    $body = View::make('email.regsuccess')
        ->with('data',$data)
        ->with('passwordRandom',$newpass)
        ->with('fromadmin','yes')
        ->render();

    Message::to($data['email'])
        ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
        ->subject('Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')')
        ->body( $body )
        ->html(true)
        ->send();

});

//EXHIBITOR
Event::listen('exhibitor.createformadmin',function($id,$newpass){
    $exhibitor = new Exhibitor();
    $_id = $id;
    $data = $exhibitor->get(array('_id'=>$_id));

    $body = View::make('email.regsuccessexhib')
        ->with('data',$data)
        ->with('passwordRandom',$newpass)
        ->with('fromadmin','yes')
        ->render();

    Message::to($data['email'])
        ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
        ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
        ->subject('Indonesia Petroleum Association – 37th Convention & Exhibition (Exhibitor – '.$data['registrationnumber'].')')
        ->body( $body )
        ->html(true)
        ->send();

});


Event::listen('exhibition.postoperationalform',function($id,$exhibitorid){

    $operationalform = new Operationalform();
    $exhibitor = new Exhibitor();

    $_id = $id;
    $data = $operationalform->get(array('_id'=>$_id));

    $user = $exhibitor->get(array('_id'=>$exhibitorid));

        
    $body = View::make('email.confirmpaymentexhibitor')
        ->with('data',$data)
        ->with('user',$user)
        ->render();

    Message::to($user['email'])
        ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
        ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
        ->subject('CONFIRMATION OF OPERATIONAL FORMS - Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$user['registrationnumber'].')')
        ->body( $body )
        ->html(true)
        ->send();
    

});

Event::listen('document.create',function($id, $result){
    $activity = new Activity();

    $doc = getdocument($id);

    $ev = array('event'=>'document.create',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId(Auth::user()->id),
        'creator_name'=>Auth::user()->fullname,
        'updater_id'=>new MongoId(Auth::user()->id),
        'updater_name'=>Auth::user()->fullname,
        'sharer_id'=>'',
        'sharer_name'=>'',
        'department'=>$doc['docDepartment'],
        'doc_id'=>$id,
        'doc_title'=>$doc['title'],
        'doc_filename'=>$doc['docFilename'],
        'result'=>$result
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});

Event::listen('document.update',function($id,$result){
    $activity = new Activity();

    $doc = getdocument($id);

    $ev = array('event'=>'document.update',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId($doc['creatorId']),
        'creator_name'=>$doc['creatorName'],
        'updater_id'=>new MongoId(Auth::user()->id),
        'updater_name'=>Auth::user()->fullname,
        'sharer_id'=>'',
        'sharer_name'=>'',
        'department'=>$doc['docDepartment'],
        'doc_id'=>$id,
        'doc_title'=>$doc['title'],
        'doc_filename'=>$doc['docFilename'],
        'result'=>$result
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});

Event::listen('document.delete',function($id,$creator_id,$result){
    $activity = new Activity();

    $ev = array('event'=>'document.delete',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId($creator_id),
        'remover_id'=>new MongoId(Auth::user()->id),
        'doc_id'=>$id,
        'result'=>$result
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});

Event::listen('document.share',function($id,$sharer_id,$shareto){
    $activity = new Activity();

    $doc = getdocument($id);

    $ev = array('event'=>'document.share',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId($doc['creatorId']),
        'creator_name'=>$doc['creatorName'],
        'sharer_id'=>new MongoId($sharer_id),
        'sharer_name'=>Auth::user()->fullname,
        'shareto'=>$shareto,
        'doc_id'=>$id,
        'doc_filename'=>$doc['docFilename'],
        'doc_title'=>$doc['title']
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});


//Project events

Event::listen('project.create',function($id, $result){
    $activity = new Activity();

    $doc = getproject($id);

    $ev = array('event'=>'project.create',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId(Auth::user()->id),
        'creator_name'=>Auth::user()->fullname,
        'updater_id'=>new MongoId(Auth::user()->id),
        'updater_name'=>Auth::user()->fullname,
        'sharer_id'=>'',
        'sharer_name'=>'',
        'department'=>$doc['projectDepartment'],
        'doc_id'=>$id,
        'doc_number'=>$doc['projectNumber'],
        'doc_title'=>$doc['title'],
        'result'=>$result
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});

Event::listen('project.update',function($id,$result){
    $activity = new Activity();

    $doc = getproject($id);

    $ev = array('event'=>'project.update',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId($doc['creatorId']),
        'creator_name'=>$doc['creatorName'],
        'updater_id'=>new MongoId(Auth::user()->id),
        'updater_name'=>Auth::user()->fullname,
        'sharer_id'=>'',
        'sharer_name'=>'',
        'department'=>$doc['projectDepartment'],
        'doc_id'=>$id,
        'doc_number'=>$doc['projectNumber'],
        'doc_title'=>$doc['title'],
        'result'=>$result
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});

Event::listen('project.delete',function($id,$creator_id,$result){
    $activity = new Activity();

    $ev = array('event'=>'peoject.delete',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId($creator_id),
        'remover_id'=>new MongoId(Auth::user()->id),
        'doc_id'=>$id,
        'result'=>$result
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});


//Request events


Event::listen('request.approval',function($id,$approvalby){
    $activity = new Activity();

    $doc = getdocument($id);

    $ev = array('event'=>'request.approval',
        'timestamp'=>new MongoDate(),
        'creator_id'=>new MongoId($doc['creatorId']),
        'creator_name'=>$doc['creatorName'],
        'sharer_id'=>'',
        'sharer_name'=>'',
        'requester_id'=>new MongoId(Auth::user()->id),
        'requester_name'=>Auth::user()->fullname,
        'shareto'=>'',
        'approvalby'=>$approvalby,
        'doc_id'=>$id,
        'doc_filename'=>$doc['docFilename'],
        'doc_title'=>$doc['title']
    );

    if($activity->insert($ev)){
        return true;
    }else{
        return false;
    }

});



Event::listen('send.message',function($from,$to,$subject){
	
});

?>