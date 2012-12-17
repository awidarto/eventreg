@layout('master')


@section('content')
<h3>{{$title}}</h3>
<div class="row">
	<a class="medium button right" href="#">{{$newbutton}}</a>
</div>
<div class="row">
	<table class="dataTable">
	    <thead>
	        <tr>
	        	@foreach($heads as $head)
	        		<th>
	        			{{ $head }}
	        		</th>
	        	@endforeach
	        </tr>
	    </thead>
	    <tbody>
	        <tr>
	            <td>1</td>
	            <td>Row 1 Data 2</td>
	            <td>etc</td>
	            <td>Row 1 Data 1</td>
	            <td>Row 1 Data 2</td>
	            <td>etc</td>
	        </tr>
	    </tbody>
	    <tfoot>
	    <tr>
	    	@foreach($searchinput as $in)
	    		@if($in)
	        		<td><input type="text" name="search_{{$in}}" id="search_{{$in}}" value="Search {{$in}}" class="search_init" /></td>
	    		@else
	        		<td>&nbsp;</td>
	    		@endif
	    	@endforeach        	
	    </tr>
	    </tfoot>
	</table>
</div>

  <script type="text/javascript">
    $(document).ready(function(){
		var asInitVals = new Array();
        var oTable = $('.dataTable').DataTable(
			{
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{$ajaxsource}}",
				"oLanguage": { "sSearch": "Search "},
				"sPaginationType": "full_numbers",
				"sDom": 'T<"clear">lfrtip',
				"oTableTools": {
					"sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
				},
			    "fnServerData": function ( sSource, aoData, fnCallback ) {
		            $.ajax( {
		                "dataType": 'json', 
		                "type": "POST", 
		                "url": sSource, 
		                "data": aoData, 
		                "success": fnCallback
		            } );
		        }
			}
        );

		$('tfoot input').keyup( function () {
			/* Filter on the column (the index) of this element */
			oTable.fnFilter( this.value, $('tfoot input').index(this) );
		} );

		/*
		 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
		 * the footer
		 */
		$('tfoot input').each( function (i) {
			asInitVals[i] = this.value;
		} );

		$('tfoot input').focus( function () {
			if ( this.className == 'search_init' )
			{
				this.className = '';
				this.value = '';
			}
		} );

		$('tfoot input').blur( function (i) {
			if ( this.value == '' )
			{
				this.className = 'search_init';
				this.value = asInitVals[$('tfoot input').index(this)];
			}
		} );




		$('.filter input').keyup( function () {
			/* Filter on the column (the index) of this element */
			oTable.fnFilter( this.value, $('.filter input').index(this) );
		} );

		/*
		 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
		 * the footer
		 */
		$('.filter input').each( function (i) {
			asInitVals[i] = this.value;
		} );

		$('.filter input').focus( function () {
			if ( this.className == 'search_init' )
			{
				this.className = '';
				this.value = '';
			}
		} );

		$('.filter input').blur( function (i) {
			if ( this.value == '' )
			{
				this.className = 'search_init';
				this.value = asInitVals[$('.filter input').index(this)];
			}
		} );


    });
  </script>

@endsection