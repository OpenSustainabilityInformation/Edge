$(document).ready(function() {
	$(".cat").click(function() {
		$(".cat").removeClass('chosen_category');
		$(this).addClass('chosen_category');
		$("#category").val($(this).attr("id"));
	});
});
