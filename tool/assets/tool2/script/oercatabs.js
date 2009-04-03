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
    mouseOverClass: 'over',
    activateOnLoad: 'first',
  },
  
  initialize: function(element) {
    var ourStuff = $$(element);
    alert("testing alert again " + ourStuff);
  }
  
});