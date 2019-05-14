<ol class="trace">
@foreach ($trace as $index => $row)
	<li><p>
						
	@if(isset($row['file']) && is_file($row['file']))
		@if(isset($row['function']) && in_array($row['function'], ['include', 'include_once', 'require', 'require_once']))
            {{ $row['function'] . ' ' . $row['file'] }}
        @else
            {{ $row['file'] }}
		@endif
	@else
		{ PHP Internal Code }
	@endif

{{-- 
	@if(isset($row['class']))
		&nbsp;&mdash;&nbsp;{{ $row['class'].$row['type'].$row['function'] }}
		
		@php($identity = uniqid('error') . $index)
		@if (!empty($row['args']))
		( <a href="#" onclick="return toggle('{{ $identity }}');">arguments</a> )
			<div class="args" id="{{ $identity }}">
			<table cellspacing="0">
			@php
				$params = null;
				if (substr($row['function'], -1) !== '}') {
					$mirror = isset($row['class']) ? new \ReflectionMethod($row['class'], $row['function']) : new \ReflectionFunction($row['function']);
					$params = $mirror->getParameters();
				}
			@endphp
			@foreach ($row['args'] as $key => $value)
				<tr class="arguments">
					<td class="arguments">
						<code>
							{{ isset($params[$key]) ? '$'.$params[$key]->name : "#$key" }}
						</code>
					</td>
					<td class="arguments" style="width: 100%;">{{{ dump($value) }}}</td>
				</tr>
			@endforeach
			</table>
		</div>
		@else
			()
		@endif
	@endif
 --}}
	
	@if (!isset($row['class']) && isset($row['function']))
		&nbsp;&nbsp;&mdash;&nbsp;&nbsp;{{ $row['function'] }}()
	@endif
	</p>@if(isset($row['file']) && is_file($row['file']) &&  isset($row['class']))
		{{{ $app->syntax->highlight($row['file'], $row['line'], '4:4') }}}
	@endif</li>
@endforeach
</ol>