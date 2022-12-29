$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	
	$(".form-group").submit(function(){
		$(".btn_submit").attr("disabled", true);
	});
});

function del()
{
	var con = confirm("Do you want to delete this record");
	if(con == true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function selectall(source)
{
	var checkboxes = document.getElementsByName("del_items[]");
	for(i in checkboxes)
	{
		checkboxes[i].checked = source.checked;
	}
}

function gotoURL(site)
{
	if (site !="")
	{
		self.location = site;
	}
}

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode != 45  && charCode > 31 && (charCode < 48 || charCode > 57))
	return false;

	return true;
}

$(function () {
    $('.genealogy-tree ul').hide();
    $('.genealogy-tree>ul').slideDown();
    $('.genealogy-tree ul.active').slideDown();
    $('.genealogy-tree li').on('click', function (e) {
        var children = $(this).find('> ul');
        if (children.is(":visible")) children.slideUp('fast').removeClass('active');
        else children.slideDown('fast').addClass('active');
        e.stopPropagation();
    });
});


jQuery.fn.liScroll = function(settings) {
	settings = jQuery.extend({
		travelocity: 0.03
		}, settings);		
		return this.each(function(){
				var $strip = jQuery(this);
				$strip.addClass("newsticker")
				var stripHeight = 20;
				$strip.find("li").each(function(i){
					stripHeight += jQuery(this, i).outerHeight(true); // thanks to Michael Haszprunar and Fabien Volpi
				});
				var $mask = $strip.wrap("<div class='mask'></div>");
				var $tickercontainer = $strip.parent().wrap("<div class='tickercontainer'></div>");								
				var containerHeight = $strip.parent().parent().height();	//a.k.a. 'mask' width 	
				$strip.height(stripHeight);			
				var totalTravel = stripHeight;
				var defTiming = totalTravel/settings.travelocity;	// thanks to Scott Waye		
				function scrollnews(spazio, tempo){
				$strip.animate({top: '-='+ spazio}, tempo, "linear", function(){$strip.css("top", containerHeight); scrollnews(totalTravel, defTiming);});
				}
				scrollnews(totalTravel, defTiming);				
				$strip.hover(function(){
				  jQuery(this).stop();
				},
				function(){
				  var offset = jQuery(this).offset();
				  var residualSpace = offset.top + stripHeight;
				  var residualTime = residualSpace/settings.travelocity;
				  scrollnews(residualSpace, residualTime);
				});			
		});	
};

$(function(){
    $("ul#ticker01").liScroll();
});