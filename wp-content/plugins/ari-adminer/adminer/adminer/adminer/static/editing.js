// Adminer specific functions

/** Load syntax highlighting
* @param string first three characters of database system version
*/
function bodyLoad(version) {
	if (window.jush) {
		jush.create_links = ' target="_blank" rel="noreferrer"';
		if (version) {
			for (var key in jush.urls) {
				var obj = jush.urls;
				if (typeof obj[key] != 'string') {
					obj = obj[key];
					key = 0;
				}
				obj[key] = obj[key]
					.replace(/\/doc\/mysql/, '/doc/refman/' + version) // MySQL
					.replace(/\/docs\/current/, '/docs/' + version) // PostgreSQL
				;
			}
		}
		if (window.jushLinks) {
			jush.custom_links = jushLinks;
		}
		jush.highlight_tag('code', 0);
		var tags = document.getElementsByTagName('textarea');
		for (var i = 0; i < tags.length; i++) {
			if (/(^|\s)jush-/.test(tags[i].className)) {
				var pre = jush.textarea(tags[i]);
				if (pre) {
					setupSubmitHighlightInput(pre);
				}
			}
		}
	}
}

/** Get value of dynamically created form field
* @param HTMLFormElement
* @param string
* @return HTMLElement
*/
function formField(form, name) {
	// required in IE < 8, form.elements[name] doesn't work
	for (var i=0; i < form.length; i++) {
		if (form[i].name == name) {
			return form[i];
		}
	}
}

/** Try to change input type to password or to text
* @param HTMLInputElement
* @param boolean
*/
function typePassword(el, disable) {
	try {
		el.type = (disable ? 'text' : 'password');
	} catch (e) {
	}
}



var dbCtrl;
var dbPrevious = {};

/** Check if database should be opened to a new window
* @param MouseEvent
* @param HTMLSelectElement
*/
function dbMouseDown(event, el) {
	dbCtrl = isCtrl(event);
	if (dbPrevious[el.name] == undefined) {
		dbPrevious[el.name] = el.value;
	}
}

/** Load database after selecting it
* @param HTMLSelectElement
*/
function dbChange(el) {
	if (dbCtrl) {
		el.form.target = '_blank';
	}
	el.form.submit();
	el.form.target = '';
	if (dbCtrl && dbPrevious[el.name] != undefined) {
		el.value = dbPrevious[el.name];
		dbPrevious[el.name] = undefined;
	}
}



/** Check whether the query will be executed with index
* @param HTMLFormElement
*/
function selectFieldChange(form) {
	var ok = (function () {
		var inputs = form.getElementsByTagName('input');
		for (var i=0; i < inputs.length; i++) {
			if (inputs[i].value && /^fulltext/.test(inputs[i].name)) {
				return true;
			}
		}
		var ok = form.limit.value;
		var selects = form.getElementsByTagName('select');
		var group = false;
		var columns = {};
		for (var i=0; i < selects.length; i++) {
			var select = selects[i];
			var col = selectValue(select);
			var match = /^(where.+)col\]/.exec(select.name);
			if (match) {
				var op = selectValue(form[match[1] + 'op]']);
				var val = form[match[1] + 'val]'].value;
				if (col in indexColumns && (!/LIKE|REGEXP/.test(op) || (op == 'LIKE' && val.charAt(0) != '%'))) {
					return true;
				} else if (col || val) {
					ok = false;
