var m = require('mithril');

//model
var Page = {
	list: function() {
		return m.prop([{
			title: 'mapas culturais',
			url: 'http://mapas.com'
		}]);
		// return m.request({method: "GET", url: "pages.json"});
	}
};

var Demo = {
	//controller
	controller: function() {
		var pages = Page.list();
		return {
			pages: pages,
			rotate: function() {
				pages().push(pages().shift());
			}
		}
	},

	//view
	view: function(ctrl) {
		return m("div", [
			ctrl.pages().map(function(page) {
				return m("a", {href: page.url}, page.title);
			}),
			m("button", {onclick: ctrl.rotate}, "Rotate links")
		]);
	}
};


//initialize
m.mount(document.getElementById("example"), Demo);
