
window.addEventListener("load", function() {

	// tabulação da página
	var tabs = document.querySelectorAll("ul.nav-tabs > li");

	for (i = 0; i < tabs.length; i++) {
		tabs[i].addEventListener("click", switchTab);
	}

	function switchTab(event) {
		event.preventDefault();

		document.querySelector("ul.nav-tabs li.active").classList.remove("active");
		document.querySelector(".tab-pane.active").classList.remove("active");

		var clickedTab = event.currentTarget;
		var anchor = event.target;
		var activePaneID = anchor.getAttribute("href");

		clickedTab.classList.add("active");
		document.querySelector(activePaneID).classList.add("active");

	}

	//seleção dos itens de menu pelo checkbox
	var checkboxes = Array.from(document.querySelectorAll(".tab-content  .menu  .form-table  input[type=checkbox]"));

	for (i = 0; i < checkboxes.length; i++) {
		checkboxes[i].addEventListener("change", checkboxChanged);
	}

	function checkboxChanged(event){
		var element = event.target;

		var children = checkboxes.filter(function (item) {
			return item != element && item.name.includes(element.name)  ;
		});

		var split = element.name.split("_");
		var lang = split[1];
		var fatherIds = split.slice(2, split.length - 1);

		var parents = [];
		while (fatherIds.length > 0){
			var fullName = "menu_" + lang + "_" + fatherIds.join("_");
			var parent = checkboxes.filter(function(item){
				return item.name == fullName;
			})[0];

			parents.push(parent);

			fatherIds.pop();
		}

		children.forEach(child => {
			child.checked = element.checked;
		});

		if (element.checked){
			parents.forEach(parent => {
				parent.checked = true;
			});
		}else{
			parents.forEach(parent => {
				var siblings = checkboxes.filter(function(item){
					return item != parent && item.name.includes(parent.name) && item.checked;
				})

				if (siblings.length == 0){
					parent.checked = false
				}
			});
		}
		
	}

	

});