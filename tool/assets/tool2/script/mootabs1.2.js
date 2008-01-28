var mootabs = new Class({
	
	initialize: function(element, options) {
	
		this.el = $(element);
		this.elid = element;

		this.panels = $$('#' + this.elid + ' .mootabs_panel');
		this.toggles = $$('#' + this.elid + ' ul.mootabs_title li');

		this.toggles.each(function(item) {
			item.addEvent('click', function(){
					item.removeClass('active');
					this.activate(item);
				}.bind(this)
			);
			
			item.addEvent('mouseover', function() {
				if(item != this.activeTitle)
				{
					item.addClass('over');
				}
			}.bind(this));
			
			item.addEvent('mouseout', function() {
				if(item != this.activeTitle)
				{
					item.removeClass('over');
				}
			}.bind(this));
		}.bind(this));

		this.activate(this.toggles[0], true);
	},
	
	activate: function(tab, skipAnim){
		if(! $defined(skipAnim))
		{
			skipAnim = true;
		}
		if($type(tab) == 'string') 
		{
			myTab = $$('#' + this.elid + ' ul li').filterByAttribute('title', '=', tab)[0];
			tab = myTab;
		}
		
		if($type(tab) == 'element')
		{
			var newTab = tab.getProperty('title');
			this.panels.removeClass('active');
			
			this.activePanel = this.panels.filterById(newTab)[0];
			
			this.activePanel.addClass('active');
			
			this.toggles.removeClass('active');
			
			tab.addClass('active');
			
			this.activeTitle = tab;
		}
	},
});
