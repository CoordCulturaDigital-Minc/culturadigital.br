(function() {

	/**
	* class Button
	*/
	var Button = function(button) {
		this.construct(button);
	}

	/**
	* Methods which belong to the button object
	*/
	Button.prototype = {
		/**
		* Button.construct(button) < Button
		* 	- button (Object): DOM reference to a button
		*/
		construct: function(button) {
			var newButton = document.createElement('iframe');

			var url = "http://api.tweetmeme.com/button.js?url=" + escape(button.href);

			// get all the addiontial options
			url += '&amp;' + button.rel.toLowerCase();

			// tracking data
			if (document && document.referrer) {
				var ref = document.referrer;
				if (ref) {
					url += '&amp;o=' + escape(ref);
				}
			}

			newButton.src = url;
			newButton.frameBorder = "0";
			newButton.scrolling = "no";

			// get the style info
			var params = new Array();
			var qp = button.rel.split('&');
			for (var i = 0; i < qp.length; i++) {
				var kv = qp[i].split('=');
				if (kv.length > 1) {
					params[kv[0]] = kv[1];
				}
			}

			if (params['style']) {
				switch(params['style']) {
			        case 'compact': {
			            var h = 20;
			            var w = 90;
			            break;
			        }
			        default: {
			            var h = 61;
			            var w = 50;
			            break;
			        }
			    }

				newButton.height = h;
		    	newButton.width = w;
			} else {
				newButton.height = 61;
		    	newButton.width = 50;
			}

			button.parentNode.insertBefore(newButton, button);
			var parent = button.parentNode;
			parent.removeChild(button);

			this.button = newButton;
		}
	}

	/**
	* class Selector
	*
	* Provides a means to select DOM objects from expressions
	*/
	var Selector = {
		/**
		* Selector.findChildElements -> Array
		* 	- element (Object): Element to search for children from
		*   - expression (String): Expression to search for
		*
		* <h2>Example</h2>
		*
		* The types of selectors supported in the expression are #(id), .(classes) and tag names
		*
		* Selector.findChildElements(div, '#hello')
		* 	-> Objects with id hello which are children of div
		*/
		findChildElements: function(element, expression) {
			// set up the results array
			var results = new Array();

			if (typeof expression != 'string') {
				return false;
			}

			// pull the identifier from the expression (either # or .)
			var identifier = expression.substring(0, 1);
			var match = expression.substring(1);
			// fetch all the child nodes (tested on FF3.6/Opera10.5/Chrome4.1.249.1045
			this.search(element.childNodes, function(node) {
				switch(identifier) {
					case '#':
						node.id = match ? results.push(node) : false;
						break;
					case '.':
						var classes = node.className;
						if (classes) {
							classes = classes.split(' ');
							for (j in classes) {
								if (classes[j] == match) {
									results.push(node);
								}
							}
						}
						break;
					default:
						node.tagName == expression.toUpperCase() ? results.push(node) : false
						break;
				}
			});
			return results;
		},

		/**
		* Selector.search
		* 	- nodes (Array): Array of DOM elements
		*   - match (Object): function to run on each node
		*
		* <h2>Example</h2>
		*
		* Selector.search(children, function(node) {
		*	node.innerHTML = 'yo';
		* });
		*/
		search: function(nodes, match) {
			for (var i = 0; i < nodes.length; i++) {
				if (nodes[i].childNodes.length > 0) {
					this.search(nodes[i].childNodes, match);
				}
				match(nodes[i]);
			}
		}
	}

	// grab all the buttons
	var buttons = Selector.findChildElements(document.body, '.tm_button');

	// create the button objects
	var buttonObjs = new Array();
	if (buttons.length > 0) {
		for (var i = 0; i < buttons.length; i++) {
			var obj = new Button(buttons[i]);
			buttonObjs[obj.url] = obj;
		}
	}
})();