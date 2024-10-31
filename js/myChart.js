jQuery(document).ready(function($) {

if ($('#myChart').length > 0 ) 
    {
	
	 var ctx = document.getElementById('myChart').getContext('2d');
                  
                 var id_product=$('#myChart').attr("idp");
                  var data = {action: 'Behzad_wp',id:id_product};
                 $.post(the_chart_url.chart_url, data, function(response){ 
                { 
                  var x = $.parseJSON(response);
                  $('p.loader').hide();

                  window.myLine = new Chart(ctx, x["data"]);
                  window.myLine.update();

                  }
                  });  



} 

$(".close").click(function () {


	$(".chartmodal").hide() ;
}); 


});