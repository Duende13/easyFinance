$(document).ready(function() {

	var dialogFormUrl;
	var type;
	var annee;
	var ordre;
	var elementsATraiter;

	$.jGrowl.defaults.closer = false;

	function initialize(section){
		initForms(section);
	}

	function reloadSection(section){
		if ($('#'+section).length) {
		$('#'+section).load('listing.php?type='+type+'&ordre='+ordre+'&annee='+annee+'&ajaxed=1',function(){
				initialize(section);
			});
		};
	}

	function initForms(section){
		if ($('#'+section).length) {
			$('#'+section+' form')
			.prepend('<input type="hidden" name="ajaxed" value="1" />')
			.ajaxForm({
				// resetForm: true,
				// sectionName: section,
				dataType: "json",
				beforeSubmit: ajaxShowRequest,
				success: ajaxShowResponse,
				error: javascriptError
			});
		};
	}

	function ajaxShowRequest(formData, jqForm, options){
		elementsATraiter = $('input[type="checkbox"]:checked').parent();
		var nombreElementsSelectionnes = elementsATraiter.size();
		if (nombreElementsSelectionnes > 0) {
			if (nombreElementsSelectionnes == 1) {
				var confirmation = confirm("Voulez-vous supprimer "+nombreElementsSelectionnes+" élément ?");
			}else{
				var confirmation = confirm("Voulez-vous supprimer "+nombreElementsSelectionnes+" éléments ?");
			}
			if (confirmation) {
				$('#'+options.sectionName+' input[type="submit"],input[type="button"]').attr('disabled','disabled');
				$('#'+options.sectionName+' input[type="submit"]').parent().append('<img id="signalLoading" style="position:absolute;margin:3px 0 0 5px;" src="../images_admin/icn-loading.gif" />');
			} else {
				return false;
			}
		} else {
			$.jGrowl('Aucun élément sélectionné.');
			return false;
		}
	}

	function ajaxShowResponse(data, statusText){
		reloadSection("Liste");
		$.jGrowl(data.msg);
	}

	function ajaxShowRequestFormDialog(formData, jqForm, options){
	}

	function ajaxShowResponseFormDialog(data, statusText){
		// alert('test');
		$.jGrowl(data.msg);
		reloadSection('Liste');
		$('#dialog').dialog('close');
	}

	function javascriptError(data, statusText){
		$.jGrowl("Erreur, veuillez recharger la page.");
		console.log(statusText);
		console.log(data);
	}


	function isTagSupported(tag){
		eltTag = document.getElementsByTagName(tag)[0];
	//	alert(tag+" :\n\n"+eltTag);	// Débug.
		if (eltTag == "[object HTMLUnknownElement]" || eltTag == null){
			return false;
		} else {
			return true;
		}
	}

	function makeAutocomplete(fieldId, table, champ) {
		if ($("#"+fieldId).length) {
			$.getJSON('requetes/get_list.php', { table: table, champ: champ }, function(data) {
				$("#"+fieldId).autocomplete({
					source: data,
					minLength: 2
				});
			});
		};
	}

	type = $('#type').val();
	annee = $('#annee').val();
	ordre = $('#ordre').val();
	initialize("Liste");

	// $('form').prepend('<input type="hidden" name="ajaxed" value="1" />');

	// dialog : formulaire d'ajout/modification d’un élément
	$('body').prepend('<div id="dialog"></div>');
	$('#dialog').dialog({
		resizable: false,
		modal: true,
		autoOpen: false,
		close: function(event, ui) {
			//reloadSection('Liste');
		},
		open: function(event, ui){
			$(this).parent().hide();
			$(this).html("");
			$(this).load(dialogFormUrl, function(){
				$('#totalAmount').click(function(event) {
					$('#amount_paid').val($(this).html()).focus();
				});
				$(this).parent().show();
				if (type == 'sortantes') {
					makeAutocomplete('denomination', 'clients', 'denomination');
				} else if (type == 'entrantes') {
					makeAutocomplete('objet', 'facturesEntrantes', 'objet');
					makeAutocomplete('denomination', 'facturesEntrantes', 'denomination');
				} else {
					makeAutocomplete('denomination', 'clients', 'denomination');
				}
				$('input[autofocus="autofocus"]').focus();
				$('#dialog').dialog('option', 'width', 'auto');
				$('#dialog').dialog('option', 'position', 'center');
				$(this).children('form')
				.prepend('<input type="hidden" name="ajaxed" value="1" />')
				.ajaxForm({
					dataType: "json",
					beforeSubmit: ajaxShowRequestFormDialog,
					success: ajaxShowResponseFormDialog,
					error: javascriptError
				});
			});
		}
	});

	$('#boutonAjouterFactureSortante').live('click',function(){
		dialogFormUrl = "formFactures.php?type=sortantes&ajaxed=1";
		dialogTitle = $(this).attr('title');
		$('#dialog').dialog('option', 'title', dialogTitle);
		$('#dialog').dialog('open');
		return false;
	});

	$('#boutonAjouterContrat').live('click',function(){
		dialogFormUrl = "formContrats.php?ajaxed=1";
		dialogTitle = $(this).attr('title');
		$('#dialog').dialog('option', 'title', dialogTitle);
		$('#dialog').dialog('open');
		return false;
	});

	$('#boutonAjouterFactureEntrante').live('click',function(){
		dialogFormUrl = "formFactures.php?type=entrantes&ajaxed=1";
		dialogTitle = $(this).attr('title');
		$('#dialog').dialog('option', 'title', dialogTitle);
		$('#dialog').dialog('open');
		return false;
	});

	$('#boutonAjouterClient').live('click',function(){
		dialogFormUrl = "formClient.php?ajaxed=1";
		dialogTitle = $(this).attr('title');
		$('#dialog').dialog('option', 'title', dialogTitle);
		$('#dialog').dialog('open');
		return false;
	});

	$('#boutonAjouterAuto').live('click',function(){
		dialogFormUrl = "formAuto.php?ajaxed=1";
		dialogTitle = $(this).attr('title');
		$('#dialog').dialog('option', 'title', dialogTitle);
		$('#dialog').dialog('open');
		return false;
	});

	$('#boutonAjouterBudget').live('click',function(){
		dialogFormUrl = $(this).attr('href')+"&ajaxed=1";//"formBudget.php?ajaxed=1";
		dialogTitle = $(this).attr('title');
		$('#dialog').dialog('option', 'title', dialogTitle);
		$('#dialog').dialog('open');
		return false;
	});

	$('.popup').live('click',function(){
		dialogFormUrl = $(this).attr('href')+"&ajaxed=1";
		dialogTitle = $(this).attr('title');
		$('#dialog').dialog('option', 'title', dialogTitle);
		$('#dialog').dialog('open');
		return false;
	});

});

// Autocomplete functions
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}