jQuery(function($) {
  function fixDiv() {
    var $cache = $('#getFixed');
    if ($(window).scrollTop() > 145)
      $cache.css({
        'position': 'fixed',
        'top': '60px'
      });
    else
      $cache.css({
        'position': 'relative',
        'top': 'auto'
      });
  }


  function moveScroll(){
    var scroll = $(window).scrollTop() + 60;	// 60 is Height of Navigation bar
    var anchor_top = $("#maintable").offset().top;	// This will be starting point of table's body
	var anchor_bottom = $("#bottom_anchor").offset().top - 60;	// This indicates end of whole table.

    if (scroll>anchor_top && scroll<anchor_bottom) {
    clone_table = $("#clone");
    if(clone_table.length === 0){
        clone_table = $("#maintable").clone();
        clone_table.attr('id', 'clone');
        clone_table.css({position:'fixed',
                 'pointer-events': 'none',
                 top:58,
                 'background-color': '#74CF54'});
        clone_table.width($("#maintable").width());
        $("#table-container").append(clone_table);
        $("#clone").css({visibility:'hidden'});
        $("#clone thead").css({visibility:'visible'});
    }
    } else {
    $("#clone").remove();
    }
	}
	
$(window).scroll(moveScroll);

	$("#search").keyup(function () {
		var value = this.value.toLowerCase().trim();

		$("table tr").each(function (index) {
			if (!index) return;
			$(this).find("td").each(function () {
				var id = $(this).text().toLowerCase().trim();
				var not_found = (id.indexOf(value) == -1);
				$(this).closest('tr').toggle(!not_found);
				return not_found;
			});
		});
	});


});


