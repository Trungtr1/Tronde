$(document).ready(function(){

	if ($('.dt').length > 0) {
	
		$('.dt').DataTable();
	
	}
	
	$('.sequence-input').blur(function(){
		$(this).val($(this).val().trim().replace(/(\s|\r\n|\n|\r)/gm,""));
	});

	$('form').submit(function(){
		return doValidate($(this));
	});
	
	// $('.jsubmit').click(function(){
		// doValidate($(this).parents('.jform'));
	// });
	
	function doValidate(form)
	{
		var t = form;
		if(t.find('.validate-control').length > 0)
		{
			t.find('.validate-control:enabled').each(function(){
				if ($(this).hasClass('validate-fasta')) {
					if (!validateFasta($(this).val())) {
						$(this).siblings('.text-error').text('Fasta Invalid').show();
					} else {
						$(this).siblings('.text-error').hide();
					}
				} else if ($(this).hasClass('validate-sequence')) {
					if (!validateSequence($(this).val())) {
						$(this).siblings('.text-error').text('Sequence Invalid').show();
					} else {
						$(this).siblings('.text-error').hide();
					}	
				} else if ($(this).hasClass('validate-nu-sequence')) {
					if (!validateNuSequence($(this).val())) {
						$(this).siblings('.text-error').text('Nucleotide Sequence Invalid').show();
					} else {
						$(this).siblings('.text-error').hide();
					}
				}		
			});
			if (t.find('.text-error:visible').length>0) {
				return false;
			}
			return true;
		}
	}
	
	function convertToInlineSequence(sequence)
	{
		return sequence.trim().replace(/(\s|\r\n|\n|\r)/gm,"");
	}

/*
 * Validates (true/false) a sequence
 */
	function validateSequence(string)
	{
		return /^[ACDEFGHIKLMNPQRSTUVWY\s]+$/i.test(string);
	}
/*
 * Validates (true/false) a nucleotide sequence
 */
	function validateNuSequence(string)
	{
		return /^[ATGC\s]+$/i.test(string);
	}	
/*
 * Validates (true/false) a single fasta sequence string
 * param   fasta    the string containing a putative single fasta sequence
 * returns boolean  true if string contains single fasta sequence, false 
 *                  otherwise 
 */
	function validateFasta(fasta) {

		if (!fasta) { // check there is something first of all
			return false;
		}

		// immediately remove trailing spaces
		fasta = fasta.trim();

		// split on newlines... 
		var lines = fasta.split('\n');

		// check for header
		if (fasta[0] == '>') {
			// remove one line, starting at the first position
			lines.splice(0, 1);
		}

		// join the array back into a single string without newlines and 
		// trailing or leading spaces
		fasta = lines.join('').trim();

		if (!fasta) { // is it empty whatever we collected ? re-check not efficient 
			return false;
		}

		// note that the empty string is caught above
		// allow for Selenocysteine (U)
		return /^[ACDEFGHIKLMNPQRSTUVWY\s]+$/i.test(fasta);
	}
/*
 * Draw barcoed function
 */
	$('#gen-barcode-btn').click(function(){
		if (doValidate($(this).parents('.jform'))) {
			drawBarcode('barcode-canvas', $('#input-sequence').val());
		}
	});
 
	function drawBarcode(element, sequence){
		var c=document.getElementById(element); 
		sequence = sequence.trim().replace(/(\s|\r\n|\n|\r)/gm,"").toUpperCase();
		var ctx=c.getContext("2d"); 
		ctx.canvas.height = (Math.floor(sequence.length/c.offsetWidth)+1)*80;
		ctx.canvas.width  = c.parentNode.offsetWidth;
		var color;
		var bheigh = 20;
		var theigh = 10;
		
		ctx.fillStyle="black";
		ctx.fillText("0", 0, theigh);
		
		var j = 0;
		
		for(var i=0; i<sequence.length; i++)
		{
			switch(sequence[i])
			{
				case 'A': color = 'rgb(0,248,0)'; break;
				case 'T': color = 'rgb(255,0,0)'; break;
				case 'G': color = 'rgb(0,0,0)'; break;
				case 'C': color = 'rgb(0,0,248)'; break;
				default: color = 'rgb(255,255,255)'; break;
			}

			ctx.fillStyle=color;
			ctx.fillRect(j, bheigh, 1, 50);
			// console.log(j);
			if(j >= c.offsetWidth-6)
			{
				ctx.fillStyle="black";
				ctx.fillText(i+1, j-(String(i).length*6), theigh);
				// console.log(i);
				bheigh = bheigh + 80;
				theigh = theigh + 80;
				j = 0;
				ctx.fillText(i+2, 0, theigh);
			}
			else
			{
				j+=2;
			}
			
		}
		ctx.fillStyle="black";
		ctx.fillText(sequence.length, j-(String(sequence.length).length*6), theigh);
		
		document.getElementById('generate-result').style.display = "block";
		
	}
	
	$('#search-type').change(function(){
		if ($(this).val()=='sequence'){
			window.location.href = '/search/sequence';
		}
	});
	
	
	
	$('#search-dropdown').click(function(){
		$('#menu-search-box').toggle();
	});
	
	$('html').click(function() {
		$('#menu-search-box').hide();
	});

	$('#menu-search-box,#search-dropdown').click(function(event){
		event.stopPropagation();
	});
	
});