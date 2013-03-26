@layout('master')
@section('content')

{{ HTML::link('exhibitor', '', array('class' => 'win-backbutton')) }}
<?php
       
  $sizebooth = $booth['size'];
  $freepasscount = 0;
  $boothassistantcount = 0;

  if($sizebooth >= 9 && $sizebooth <= 18){
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
  }

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
              <td>{{ $data['freepassname'.$i.''] }}. </td>
              <td class="align-center">-</td>
              <td class="align-center"><a class="icon-"  ><i>&#xe20b;</i><span class="formstatus" id="'.$doc['_id'].'" > Import this data</span></a></td>
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
              <td>{{ $data['boothassistant'.$i.''] }}. </td>
              <td class="align-center">-</td>
              <td class="align-center"><a class="icon-"  ><i>&#xe20b;</i><span class="formstatus" id="'.$doc['_id'].'" > Import this data</span></a></td>
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
              <td>{{ $data['addboothname'.$i.''] }}. </td>
              <td class="align-center">-</td>
              <td class="align-center"><a class="icon-"  ><i>&#xe20b;</i><span class="formstatus" id="'.$doc['_id'].'" > Import this data</span></a></td>
            </tr>
          @endfor
          
        </tbody>
      </table>

  </div>
</div>

@endsection