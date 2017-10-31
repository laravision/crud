<h1>Index Page</h1>
<div class="pull-right">
	<a href="{{Request::url()}}/create" class="btn btn-sm btn-success">Create new item</a>
</div>
@if(!empty($data))
	<table>
		<th>
			<td>#</td>
		</th>
		@foreach($data as $key=>$item)
		<tr>
			<td>{{$key}}</td>
		</tr>
		@endforeach
	</table>
@else
<center>Empty data !</center>
@endif