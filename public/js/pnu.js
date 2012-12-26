    $(document).ready(function(){

    	//base = 'http://localhost/pnu/public/';

		$('.date').datepicker({
			dateFormat: "dd-mm-yy"
		});

		var tags = [];

		$('.tag_email').tagsInput({
			'autocomplete_url': base + 'ajax/email',
		   	'height':'100px',
		   	'width':'300px',
		   	'interactive':true,
		   	'onChange' : function(c){

		   		},
		   	'onAddTag' : function(t){
		   			console.log(t);
		   		},
		   	'onRemoveTag' : function(t){
		   			console.log(t);
		   		},
		   	'defaultText':'add email',
		   	'removeWithBackspace' : true,
		   	'minChars' : 0,
		   	'maxChars' : 0, //if not provided there is no limit,
		   	'placeholderColor' : '#666666'
		});

		$('.tag_rev').tagsInput({
			'autocomplete_url': base + 'ajax/rev',
		   	'height':'100px',
		   	'width':'300px',
		   	'interactive':true,
		   	'onChange' : function(c){

		   		},
		   	'onAddTag' : function(t){
		   			console.log(t);
		   		},
		   	'onRemoveTag' : function(t){
		   			console.log(t);
		   		},
		   	'defaultText':'add title',
		   	'removeWithBackspace' : true,
		   	'minChars' : 0,
		   	'maxChars' : 0, //if not provided there is no limit,
		   	'placeholderColor' : '#666666'
		});

		$('.tag_keyword').tagsInput({
		   'height':'100px',
		   'width':'300px',
		   'interactive':true,
		   'onChange' : function(c){

		   		},
		   'onAddTag' : function(t){
		   			console.log(t);
		   		},
		   'onRemoveTag' : function(t){
		   			console.log(t);
		   		},
		   'defaultText':'add tag',
		   'removeWithBackspace' : true,
		   'minChars' : 0,
		   'maxChars' : 0, //if not provided there is no limit,
		   'placeholderColor' : '#666666'
		});

		$('.tag_project').autocomplete({
			source: base + 'ajax/project'
		});

		$('.tag_revision').autocomplete({
			source: base + 'ajax/rev'
		});

    });