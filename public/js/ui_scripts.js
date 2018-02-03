$(document).ready(function(){

	$('#navulliRegNum').hover(
		function(){
			$('#autoregnumUl').css({'visibility':'visible'});
		},
		function(){
			$('#autoregnumUl').css({'visibility':'hidden'});
		}
	);
});