<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
    <script type="text/javascript">
    	$(function(){
    		$('select[name="sub_district_id"]').change(function(){
    			$.get('/subdistrict/'+$(this).val()+'/housingestate', function(list){
    				$('select[name="housing_estate_id"]').empty();
    				for(var i in list){
    					$('select[name="housing_estate_id"]')
    						.append("<option value='"+list[i].id+"'>"+list[i].name+"</option>");
    				}
    			});
    		})
    	})
    </script>
    <title>信息录入</title>
  </head>
  <body>
  	<form method="post" action="/activity/{{$activity->id}}/information">
  		<label>Require:</label><br/>
      {{ csrf_field() }}
	  	Name: <input type="text" name="realname"/><br/>
	  	Tel: <input type="text" name="tel"/><br/>
      <br/><br/>
      <label>Option:</label><br/>
	  	Sub District: <select name="sub_district_id">
	  	@foreach($subDistricts as $subDistrict)
	  		<option value="{{$subDistrict->id}}">{{$subDistrict->name}}</option>
	  	@endforeach
	  	</select><br/>
	  	Housing Estate: <select name="housing_estate_id">
		@foreach($housingEstates as $housingEstate)
	  		<option value="{{$housingEstate->id}}">{{$housingEstate->name}}</option>
	  	@endforeach
	  	</select><br/>
		@if($activity->ticket_price>0)
      <br/><br/>
      <label>Amount:</label><br/>
		${{$activity->ticket_price}}<br/>
		@endif

      <br/><br/>
	  <input type="submit" value="Submit"></input>

	</form>
  </body>
</html>
