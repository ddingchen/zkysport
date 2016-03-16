<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>信息录入</title>
  </head>
  <body>
  	<form method="post" action="/activity/{{$activity->id}}/information">
  		{{ csrf_field() }}
	  	Name: <input type="text" name="realname"/><br/>
	  	Tel: <input type="text" name="tel"/><br/>
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
		Pay For ${{$activity->ticket_price}}<br/>
		@endif

	  	<input type="submit" value="Submit"></input>
	</form>
  </body>
</html>
