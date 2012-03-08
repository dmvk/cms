$(function () {
	$("a.ajax").live("click", function (event) {
		event.preventDefault();
		$.get(this.href);
	});
	
	$("form.ajaxform").submit(function () {
		$(this).ajaxSubmit();
		return false;
	});
});
