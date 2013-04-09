@layout('master')
@section('content')

{{ HTML::link('exhibitor', '', array('class' => 'win-backbutton')) }}
<?php
       
  $sizebooth = $booth['size'];
  $freepasscount = 0;
  $boothassistantcount = 0;

  /*if($sizebooth >= 9 && $sizebooth <= 18){
    $pass = 2;
  }else if($sizebooth >= 18 && $sizebooth < 27){
    $pass = 4;
  }else if($sizebooth >= 27 && $sizebooth < 36){
    $pass = 6;
  }else if($sizebooth >= 36 && $sizebooth < 45){
    $pass = 8;
  }else if($sizebooth >= 45){
    $pass = 10;
  }else{
    $pass = 10;
  }*/
  $pass = $booth['freepassslot'];
  
  for($i=1;$i<$pass+1;$i++){
    if($data['freepassname'.$i.'']!=''){
      $freepasscount++;
    }
  }

  for($i=1;$i<11;$i++){
    if($data['boothassistant'.$i.'']!=''){
      $boothassistantcount++;
    }
  }


  
?>
<div class="tableHeader">
<h3 class="formHead">{{$title}}</h3>
</div>
<div class="row-fluid" id="importboothassis">
  <div class="span12">
      <hr/>
      <div class="buttonlistimportboothassis">
        <span class="btn btn-info"><span class="icon-">&#xe1dd;</span>&nbsp;Import All Free Exhibitor's Data</span>
        <span class="btn btn-success"><span class="icon-">&#xe1dd;</span>&nbsp;Import All Free Add. Exhibitor's Data</span>
        <span class="btn btn-primary"><span class="icon-">&#xe1dd;</span>&nbsp;Import All Payable Add. Exhibitor's Data</span>
        <span class="btn btn-danger"><span class="icon-">&#xe1dd;</span>&nbsp;Import All Pass Holder Data</span>
      </div>
      <br/>
      <br/>
      <legend>EXHIBITOR’S PASS HOLDERS (FREE)<small>{{ $freepasscount.' name registered from '.$pass.' slot available'}}</small></legend>
      <table id="order-table">
        <thead>
          <tr>
            <th class="span1">No.</th>            
            <th class="span8">Names of Exhibitor’s Pass Holders</th>
            <th class="align-center">Status</th>
            <th class="align-center">Action</th>
          </tr>
        </thead>
        <tbody>
          
          @for($i=1;$i<=$freepasscount;$i++)
            
            <tr>
              <td>{{ $i }}. </td>
              <td class="passname">{{ $data['freepassname'.$i.''] }}</td>

              @if(isset($boothassistantdata['freepassname'.$i.'']))
                  <td class="aligncenter action" >Imported on {{ date('d-m-Y',  $boothassistantdata['freepassname'.$i.'timestamp']->sec) }}</td>
                  <td id="status_freepassname{{ $i }}" class="align-center status"><span class="icon- fontGreen existtrue">&#xe20c;</span></td>
                
              @else
              <td id="status_freepassname{{ $i }}" class="align-center status"></td>
              <td class="align-center action"><a class="icon- importidividual" id="freepassname{{ $i }}" type="freepassname" typeid="{{ $i }}"><i>&#xe20b;</i><span class="formstatus" id="" > Import this data</span></a></td>
              @endif
              
            </tr>
          @endfor
          
        </tbody>
      </table>
      <br/>
      <br/>
      <legend>FREE ADDITIONAL EXHIBITOR PASS HOLDERS<small>{{ $boothassistantcount.' name registered from 10 slot available'}}</small></legend>
      <table id="order-table">
        <thead>
          <tr>
            <th class="span1" >No.</th>            
            <th class="span8" >Names of Exhibitor’s Pass Holders</th>
            <th class="align-center">Status</th>
            <th class="align-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @for($i=1;$i<=$boothassistantcount;$i++)

            <tr>
              <td>{{ $i }}. </td>
              <td class="passname">{{ $data['boothassistant'.$i.''] }}</td>

              @if(isset($boothassistantdata['boothassistant'.$i.'']))
                  <td class="aligncenter action" >Imported on {{ date('d-m-Y',  $boothassistantdata['boothassistant'.$i.'timestamp']->sec) }}</td>
                  <td id="status_boothassistant{{ $i }}" class="align-center status"><span class="icon- fontGreen existtrue">&#xe20c;</span></td>
                
              @else
              <td id="status_boothassistant{{ $i }}" class="align-center status"></td>
              <td class="align-center action"><a class="icon- importidividual" id="boothassistant{{ $i }}" type="boothassistant" typeid="{{ $i }}"><i>&#xe20b;</i><span class="formstatus" id="" > Import this data</span></a></td>
              @endif
              
            </tr>

          @endfor
          
        </tbody>
      </table>

      <br/>
      <br/>
      <legend>ADDITIONAL EXHIBITOR PASS (PAYABLE)<small>{{ $data['totaladdbooth'].' name registered'}}</small></legend>
      <table id="order-table">
        <thead>
          <tr>
            <th class="span1">No.</th>            
            <th class="span8">Names of Exhibitor’s Pass Holders</th>
            <th class="align-center">Status</th>
            <th class="align-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @for($i=1;$i<=$data['totaladdbooth'];$i++)

            <tr>
              <td>{{ $i }}. </td>
              <td class="passname">{{ $data['addboothname'.$i.''] }}</td>

              @if(isset($boothassistantdata['addboothname'.$i.'']))
                  <td class="aligncenter action" >Imported on {{ date('d-m-Y',  $boothassistantdata['addboothname'.$i.'timestamp']->sec) }}</td>
                  <td id="status_addboothname{{ $i }}" class="align-center status"><span class="icon- fontGreen existtrue">&#xe20c;</span></td>
                
              @else
              <td id="status_addboothname{{ $i }}" class="align-center status"></td>
              <td class="align-center action"><a class="icon- importidividual" id="addboothname{{ $i }}" type="addboothname" typeid="{{ $i }}"><i>&#xe20b;</i><span class="formstatus" id="" > Import this data</span></a></td>
              @endif
              
            </tr>
            
          @endfor
          
        </tbody>
      </table>

  </div>
</div>

<script>
$(document).ready(function(){

  var current_type = '';
  var current_type_id = 0;
  var current_id='';
  <?php $exhibitorid = $userdata['_id']->__toString();?>
  var exhibitorid     = '<?php echo $exhibitorid;?>';
  var companyname     = '<?php echo $userdata['company'];?>';
  var companypic      = '<?php echo $userdata['firstname'].' '.$userdata['lastname'];?>';
  var companypicemail = '<?php echo $userdata['email'];?>';
  var hallname        = '<?php echo $userdata['hall'];?>';
  var boothname       = '<?php echo $userdata['booth'];?>';
  
  $('.importidividual').click(function(){
    var thisitem = $(this);
    current_id = $(this).parent().parent().find('td.status').attr('id');
    current_type = $(this).attr('type');
    current_type_id = $(this).attr('typeid');
    var current_pass_name = $(this).parent().parent().find('td.passname').text();
    
    
    <?php

      $ajaxImportBoothAssistant = (isset($ajaxImportBoothAssistant))?$ajaxImportBoothAssistant:'/';
    ?>
    $.post('{{ URL::to($ajaxImportBoothAssistant) }}',{'exhibitorid':exhibitorid,'companyname':companyname,'companypic':companypic,'companypicemail':companypicemail,'hallname':hallname,'boothname':boothname,'type':current_type,'typeid':current_type_id,'passname':current_pass_name}, function(data) {
      
      $('#'+current_id).html('Processing');
      thisitem.html('');
      

      if(data.status == 'OK'){
        thisitem.parent().append('<span class="icon- fontGreen existtrue">&#xe20c;</span>');
        thisitem.remove();
        $('#'+current_id).html(data.message);
        
      }
    },'json');
  });

  

});
</script>

@endsection