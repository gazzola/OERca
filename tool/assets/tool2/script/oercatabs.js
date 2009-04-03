/** 
* @desc OERcaTabs, which is a tabbed interface plugin for mootools
*       1.2.x inspired by mootabs by silverscripting
*       http://www.silverscripting.com/mootabs/
*       and morpthabs by Shaun Freeman
*       http://www.shaunfreeman.co.uk/index.php?page=15
*       This class attempts to roll in several concepts from the
*       JQuery tab plugin.
* @author Ali Asad Lotia
* @date 2009/03/05
*/

var OERcaTabs = new Class({
  Implements: [Options, Chain],

  version: '0.1',

  // define the default options
  options: {
    defId: 'oercatabs',
    titleClass: '.morphtabs_title',
    tabClass: '.morphtabs_panel',
    activateOnLoad: 'first',
  },

  initialize: function(element, options) {
    this.setOptions(options);
    this.tabContainer = $(element);
    this.tabId = element;
    this.tabClasses = this.tabContainer.getElements(this.options.tabClass);
    this.tabItems = this.tabContainer.getElement(this.options.titleClass).
      getElements('li');
    this.hideAll(); // initially hide all the panels
    this.makeActive();
    this.tabItems.forEach(this.clickEvent); // attach event handler to tabItems
  },
  
  hideAll: function() {
    // check for useragent and use the mootools array operation function if
    // not present in the UA
    this.tabClasses.forEach(this.hidePanel);
  },
  
  hidePanel: function(item) {
    item.setStyle('display', 'none');
  },
  
  showPanel: function(item) {
    var currElement = this.tabContainer.getElementById(item);
    currElement.setStyle('display', 'inline');
  },
  
  makeActive: function(item) {
    alert('We called the function makeActive');
    var item = (item == null) ? this.options.activateOnLoad : item;
    // set the first pane active by default
    if (item == 'first') {
      item = this.tabItems[0].getProperty('title');
    }
    
    this.showPanel(item);
  },
  
  clickEvent: function(item) {
    //item.addEvent('click', this.makeActive);
    item.addEvent('click', function(){
      alert('The clickevent function works.');
      this.makeActive;
    });
  }

});

