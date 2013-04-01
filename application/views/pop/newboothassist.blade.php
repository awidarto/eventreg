@layout('blank')

@section('content')


	
	<div class="modal-header">
		<button type="button" id="removeviewform" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<br/>
		<br/>
		<br/>
		<br/>
		<h3 id="myModalLabel">Please Input detail company first:</h3>
		
		{{ $form->hidden('exhibitorid','',array('id'=>'exhibitorid'))}}
        {{ $form->text('exhibitor','Company Name ( autocomplete, use company name to search ).req','',array('id'=>'exhibitorName','class'=>'auto_exhibitor span8'))}}
        <button class="btn update">Update</button>
		
	</div>
	<div class="modal-body" id="loaddata">
		
	</div>
	
	

</div>
<script type="text/javascript">
$(document).ready(function(){
$('.auto_exhibitor').autocomplete({
			source: base + 'ajax/exhibitor',
			select: function(event, ui){
				$('#exhibitorid').val(ui.item.id);
				hallId = $('#exhibitorid').val();
			}
		});
});
</script>
@endsection