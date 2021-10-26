/*
  speling.js - javascript component of an interactive spell checker.
  See http://simon.incutio.com/archive/2003/03/18/#phpAndJavascriptSpellChecker
  Simon Willison, 18th March 2003
*/

// from scottandrew.com
function addEvent(obj, evType, fn){
  if (obj.addEventListener){
    obj.addEventListener(evType, fn, true);
    return true;
  } else if (obj.attachEvent){
    var r = obj.attachEvent("on"+evType, fn);
    return r;
  } else {
    return false;
  }
}

function removeEvent(elm, evType, fn)
{
  if (elm.removeEventListener){
    elm.removeEventListener(evType, fn, true);
    return true;
  } else if (elm.detachEvent){
    var r = elm.detachEvent("on"+evType, fn);
    return r;
  } else {
    return false;
  }
}

function isRightClick(e)
{
    var rightclick;
	// Tests if an event is a right-click - see http://www.xs4all.nl/~ppk/js/events_properties.html
	if (!e) var e = window.event;
	if (e.which) rightclick = (e.which == 3);
	else if (e.button) rightclick = (e.button == 2);
	return rightclick;
}

function getTarget(e)
{
	var targ;
	if (!e) var e = window.event;
	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;
	// Nasty mozilla specific code - IE returns a span but Moz sometimes returns the text node
    if (targ.nodeType == 3) {
        targ = targ.parentNode;
    }
	return targ;
}

function getMousePosition(e)
{
	var pos = {x: 0, y: 0};
	if (!e) var e = window.event;
	if (e.pageX || e.pageY)
	{
		pos.x = e.pageX;
		pos.y = e.pageY;
	}
	else if (e.clientX || e.clientY)
	{
		pos.x = e.clientX + document.body.scrollLeft;
		pos.y = e.clientY + document.body.scrollTop;
	}
	return pos;
}

globalspan = null;

function makeMenu(word, suggestions, pos) {
    var words = new Array();
    words = suggestions.split(', ');
    menu = document.createElement('div');
    menu.setAttribute('style', 'position: absolute; left: '+(pos.x - 10)+'px; top:'+(pos.y - 10)+'px;');
    menu.className = 'spelingMenu';
    // Make sure this item has at least one suggestion
    if (words[0] == '') {
        // Add "None available" notice
        var none = document.createElement('div');
        none.appendChild(document.createTextNode('None available'));
        menu.appendChild(none);
    }
    for (var i = 0; i < words.length; i++) {
        var item = document.createElement('a');
        item.href = 'javascript:void(0);';
        var newword = words[i];
        var text = document.createTextNode(newword);
        item.word = newword;
        item.appendChild(text);
        menu.appendChild(item);
        // Add event - at the moment this works on the textarea with id="speling"
        var textarea = document.getElementById('speling');
        addEvent(item, 'click', function(e) {
            var re = new RegExp('(\\W|^)'+word+'(\\W|$)', 'gim');;
            textarea.value = textarea.value.replace(re, '$1'+getTarget(e).word+'$2');
            // Change the span to have the correct text as well
            globalspan.firstChild.nodeValue = getTarget(e).word;
            globalspan.className = '';
            // Now close the menu
            document.getElementsByTagName('body')[0].removeChild(spelingMenu);
            spelingMenu = null;
        });
    }
    // Add "cancel" button
    var cancel = document.createElement('div');
    cancel.className = 'cancel';
    cancel.appendChild(document.createTextNode('cancel'));
    addEvent(cancel, 'click', function(e) {
        document.getElementsByTagName('body')[0].removeChild(spelingMenu);
        spelingMenu = null;
    });
    menu.appendChild(cancel);
    return menu;
}

var spelingMenu = null;

function setupSpeling() {
    // Loop through span tags adding magic right menu to them
    spans = document.getElementsByTagName('span');
    for (var i = 0; i < spans.length; i++) {
        var span = spans[i];
        if (span.className == 'speling') {
            // Attach magic events
            span.suggestions = span.title;
            span.title = 'Click to correct spelling';
            addEvent(span, 'click', function(e) {
                var pos = getMousePosition(e);
                var target = getTarget(e);
                var suggestions = target.suggestions;
                var word = target.firstChild.nodeValue;
                // alert('Word: '+word+' Suggestions: '+suggestions);
                // alert('X: '+pos.x+' Y: '+pos.y+' Corrections: '+targ.title);
                // Destroy menu if it already exists
                if (spelingMenu != null) {
                    document.getElementsByTagName('body')[0].removeChild(spelingMenu);
                    spelingMenu = null;
                }
                globalspan = this;
                var menu = makeMenu(word, suggestions, pos);
                document.getElementsByTagName('body')[0].appendChild(menu);
                spelingMenu = menu;
            });
        }
    }
}

addEvent(window, "load", setupSpeling);
