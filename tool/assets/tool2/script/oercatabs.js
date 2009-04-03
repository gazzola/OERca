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
    activateOnLoad: 'first'
  },

  /*
   * Set up the initial tab state
   */
  initialize: function(element, options) {
    // combine the defaults defined in 'options' with user specified ones
    this.setOptions(options);
    
    // select elements and define as properties
    this.tabContainer = $(element);
    this.tabId = element;
    this.tabClasses = this.tabContainer.getElements(this.options.tabClass);
    this.tabItems = this.tabContainer.getElement(this.options.titleClass).
      getElements('li');
        
    this.hideAll(); // hide all the panels
    this.makeActive(); // make activateOnLoad element active
    // attach event handler to tabItems
    this.tabItems.forEach(this.clickEvent, this);
  },
  
  hideAll: function() {
    this.tabClasses.forEach(this.hidePanel);
  },
  
  hidePanel: function(item) {
    item.setStyle('display', 'none').removeClass('active');
  },
  
  showPanel: function(item) {
    // check to see 'item' is an event and select parent if so
    var currElement = (item.type == 'click') ? this.tabContainer.
      getElementById($(item.target).getParent().getProperty('title')) :
      this.tabContainer.getElementById(item);
    this.hideAll();
    currElement.setStyle('display', 'block').addClass('active');
  },
  
  makeActive: function(item) {
    var item = (item == null) ? this.options.activateOnLoad : item;
    // set the first pane active by default
    if (item == 'first') {
      item = this.tabItems[0].getProperty('title');
    }
    this.activeTab(item);
    this.showPanel(item);
  },
  
  clickEvent: function(item) {
    item.addEvent('click', this.makeActive.bind(this));
  },
  
  activeTab: function(item) {
    // if we have a click event as parameter, get target title
    var item = (item.type == 'click') ? $(item.target).getParent().
      getProperty('title') : item;
      
    // set the 'active' class on the active tab and remove on all others  
    for (var tabNum = 0; tabNum < this.tabItems.length; tabNum++) {
      if (this.tabItems[tabNum].getProperty('title') == item) {
        this.tabItems[tabNum].addClass('active');
      }
      else {
        this.tabItems[tabNum].removeClass('active');
      }
    }
  }

});
