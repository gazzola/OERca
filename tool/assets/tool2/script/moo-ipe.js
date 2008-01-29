var InPlaceEditor = new Class({
		initialize: function(el, container, iurl, empty_text) {
			var content = $(el).innerHTML;
			$(el).addEvent('click', function() {
				$(el).style.display = 'none';
				var content = this.innerHTML;
				
				//textarea
				var textarea = new Element('textarea').injectInside(container);
				textarea.value = content;
				textarea.setProperties({
					rows: '10',
					cols: '55'
				});
				
				//new line
				var br = new Element('br').injectInside(container);				
				
				//cancel
				var cancel = new Element('a').injectInside(container).injectAfter(br);
				cancel.setProperty('href', 'javascript:;');
				cancel.innerHTML = 'cancel';
				
				//seperator
				var span = new Element('span').injectAfter(cancel);
				span.innerHTML = ' - ';
				
				//save
				var save = new Element('a').injectInside(container);
				save.setProperty('href', 'javascript:;');
				save.innerHTML = 'save';
				
				save.addEvent('click', function() {
					var val = textarea.value;
					var url = $('server').value+iurl+escape(val);

            		var fb = $('feedback');
            		new Ajax(url, { method: 'get', update: fb, }).request();

					$(el).style.display = 'block';
					$(el).innerHTML = (val=='') ? empty_text : val; 
					textarea.remove();
					this.remove();
					cancel.remove();
					br.remove();
					span.remove();
				});
				cancel.addEvent('click', function() {
					$(el).style.display = 'block';
					textarea.remove();
					this.remove();
					save.remove();
					br.remove();
					span.remove();
				});	
			});
		},		
		
		hover: function(container, el, hilite, original) {
			var hilighter = new Fx.Style(el, 'background-color', {wait: false});
			$(container).addEvent('mouseover', function() {
				hilighter.set(hilite);
			});
			$(container).addEvent('mouseout', function() {
				hilighter.set(original);
			});
		}
	});
