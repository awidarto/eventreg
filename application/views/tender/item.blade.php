<h5 class="pop" rel="{{$popsrc}}" id="{{$doc['_id']}}">{{$doc['title']}}</h5>
Created : {{date('Y-m-d H:i:s', $doc['createdDate']->sec)}} Last Update : 
{{isset($doc['lastUpdate'])?date('Y-m-d H:i:s', $doc['lastUpdate']->sec):''}}
<br />
Created by : {{$doc['creatorName']}}<br />
Managed by : {{$doc['tenderManager']}}<br />
<p>
{{$doc['body']}}
</p>
