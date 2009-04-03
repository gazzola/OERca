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
  
  //var myThis = null,

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
    // attach event handler to tabItems
    this.tabItems.forEach(this.clickEvent.bind(this));
  },
  
  hideAll: function() {
    // check for useragent and use the mootools array operation function if
    // not present in the UA
    this.tabClasses.forEach(this.hidePanel);
  },
  
  hidePanel: function(item) {
    // TODO: should we check to see if it has the "active" class?
    item.setStyle('display', 'none').removeClass('active');
  },
  
  showPanel: function(item) {
    var currElement = (item.type == 'click') ? this.tabContainer.
      getElementById(item.target.getParent().getProperty('title')) :
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
    this.showPanel(item);
  },
  
  clickEvent: function(item) {
    item.addEvent('click', this.makeActive.bind(this));
  },
  
  activeTab: function(item) {
    
  }

});
