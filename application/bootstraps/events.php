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

        //saveto outbox
        /*$outbox = new Outbox();

        $outboxdata['from'] = Config::get('eventreg.reg_admin_email');
        $outboxdata['to'] = $data['email'];
        $outboxdata['cc'] = Config::get('eventreg.reg_admin_email').','.$picemail;
        $outboxdata['bcc'] = '';
        $outboxdata['subject'] = 'Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')';
        $outboxdata['body'] = $body;
        $outboxdata['status'] = 'unsent';
        $outboxdata['createdDate'] = new MongoDate();
        $outboxdata['lastUpdate'] = new MongoDate();

        $outbox->insert($outboxdata);*/

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

        if(isset($data['registonsite'])){
            //donothing
        }else{

            $body = View::make('email.regsuccess')
            ->with('data',$data)
            ->with('passwordRandom',$newpass)
            ->with('fromadmin','yes')
            ->with('paymentstatus',$paymentstatus)
            ->render();

            //saveto outbox
            /*$outbox = new Outbox();

            $outboxdata['from'] = Config::get('eventreg.reg_admin_email');
            $outboxdata['to'] = $data['email'];
            $outboxdata['cc'] = Config::get('eventreg.reg_admin_email');
            $outboxdata['bcc'] = '';
            $outboxdata['subject'] = 'Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')';
            $outboxdata['body'] = $body;
            $outboxdata['status'] = 'unsent';

            $outboxdata['createdDate'] = new MongoDate();
            $outboxdata['lastUpdate'] = new MongoDate();

            $outbox->insert($outboxdata);*/


            Message::to($data['email'])
                ->from(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
                ->cc(Config::get('eventreg.reg_admin_email'), Config::get('eventreg.reg_admin_name'))
                ->subject('Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')')
                ->body( $body )
                ->html(true)
                ->send();
        }
        
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

    /*$outbox = new Outbox();

    $outboxdata['from'] = Config::get('eventreg.reg_admin_email');
    $outboxdata['to'] = $data['email'];
    $outboxdata['cc'] = '';
    $outboxdata['bcc'] = '';
    $outboxdata['subject'] = 'Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$data['registrationnumber'].')';
    $outboxdata['body'] = $body;
    $outboxdata['status'] = 'unsent';

    $outboxdata['createdDate'] = new MongoDate();
    $outboxdata['lastUpdate'] = new MongoDate();*/

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

    $hallname = $data['hall'];
    $piccontact = Config::get('eventreg.emailpichall');


    if($hallname == 'Main Lobby'){
        $cc1 = $piccontact['mainlobby1'];
        $cc2 = $piccontact['mainlobby2'];
    }elseif ($hallname == 'Hall A') {
        $cc1 = $piccontact['halla1'];
        $cc2 = $piccontact['halla2'];
    }elseif ($hallname == 'Assembly Hall') {
        $cc1 = $piccontact['assembly1'];
        $cc2 = $piccontact['assembly2'];
    }elseif ($hallname == 'Cendrawasih Hall') {
        $cc1 = $piccontact['cendrawasih1'];
        $cc2 = $piccontact['cendrawasih2'];
    }elseif ($hallname == 'Hall B') {
        $cc1 = $piccontact['hallb1'];
        $cc2 = $piccontact['hallb2'];
    }else{
        $cc1 = '';
        $cc2 = '';
    }

    $body = View::make('email.regsuccessexhib')
        ->with('data',$data)
        ->with('passwordRandom',$newpass)
        ->with('fromadmin','yes')
        ->render();

    Message::to($data['email'])
        ->from(Config::get('eventreg.reg_exhibitor_admin_email'), Config::get('eventreg.reg_exhibitor_admin_name'))
        ->cc($cc1['email'],$cc1['name'])
        ->cc($cc2['email'],$cc2['name'])
        ->subject('Indonesia Petroleum Association – 37th Convention & Exhibition (Exhibitor – '.$data['registrationnumber'].')')
        ->body( $body )
        ->html(true)
        ->send();

    
    

});

Event::listen('exhibitor.logmessage',function($id,$newpass){

    //log message 
    $exhibitor = new Exhibitor();
    $_id = $id;
    $data = $exhibitor->get(array('_id'=>$_id));

    $message = new Logmessage();

    $messagedata['user'] = $data['_id'];
    $messagedata['type'] = 'email.regsuccessexhibit';
    //$messagedata['emailto'] = $data['email'];
    //$messagedata['emailfrom'] = Config::get('eventreg.reg_admin_email');
    //$messagedata['emailfromname'] = Config::get('eventreg.reg_admin_name');
    $messagedata['passwordRandom'] = $newpass;
    //$messagedata['emailcc1'] = Config::get('eventreg.reg_dyandra_admin_email');
    //$messagedata['emailcc1name'] = Config::get('eventreg.reg_dyandra_admin_name');
    //$messagedata['emailsubject'] = 'Indonesia Petroleum Association – 37th Convention & Exhibition (Exhibitor – '.$data['registrationnumber'].')';
    $messagedata['createdDate'] = new MongoDate();
    $message->insert($messagedata);

});


Event::listen('exhibition.postoperationalform',function($id,$exhibitorid){

    $operationalform = new Operationalform();
    $exhibitor = new Exhibitor();

    $_id = $id;
    $data = $operationalform->get(array('_id'=>$_id));

    $user = $exhibitor->get(array('_id'=>$exhibitorid));

    $hallname = $user['hall'];
    $piccontact = Config::get('eventreg.emailpichall');


    if($hallname == 'Main Lobby'){
        $cc1 = $piccontact['mainlobby1'];
        $cc2 = $piccontact['mainlobby2'];
    }elseif ($hallname == 'Hall A') {
        $cc1 = $piccontact['halla1'];
        $cc2 = $piccontact['halla2'];
    }elseif ($hallname == 'Assembly Hall') {
        $cc1 = $piccontact['assembly1'];
        $cc2 = $piccontact['assembly2'];
    }elseif ($hallname == 'Cendrawasih Hall') {
        $cc1 = $piccontact['cendrawasih1'];
        $cc2 = $piccontact['cendrawasih2'];
    }elseif ($hallname == 'Hall B') {
        $cc1 = $piccontact['hallb1'];
        $cc2 = $piccontact['hallb2'];
    }else{
        $cc1 = '';
        $cc2 = '';
    }


    $regnumber = $user['registrationnumber'];

    $doc = View::make('pdf.confirmexhibitor')
            ->with('data',$data)
            ->with('user',$user)
            ->render();
    
    $pdf = new Pdf();

    $pdf->make($doc);

    $newdir = realpath(Config::get('kickstart.storage'));

    $path = $newdir.'/operationalforms/confirmexhibitor'.$regnumber.'.pdf';

    $pdf->render();

    //$pdf->stream();

    $pdf->save($path);
        
    $body = View::make('email.confirmpaymentexhibitor')
        ->with('data',$data)
        ->with('user',$user)
        ->render();

    Message::to($user['email'])
        ->from(Config::get('eventreg.reg_exhibitor_admin_email'), Config::get('eventreg.reg_exhibitor_admin_name'))
        ->cc($cc1['email'],$cc1['name'])
        ->cc($cc2['email'],$cc2['name'])
        ->subject('CONFIRMATION OF OPERATIONAL FORMS - Indonesia Petroleum Association – 37th Convention & Exhibition (Registration – '.$user['registrationnumber'].')')
        ->body( $body )
        ->html(true)
        ->attach($path)
        ->send();
    
    

});

/*
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
	
});*/

?>