@include('stylesheet')

@include('javascript')
<div id="debug-icon" class="debug-bar-ndisplay">
    <a id="debug-icon-link" href="javascript:void(0)">
        <img
            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAChBAMAAADD6QdgAAAAJ1BMVEUAJiEBJyIAJiFructsustuu8wAJiFyvs5Gnbk1cXkbS0tWmKNhssddwIqyAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfkAR8NFxf6kw9PAAADwUlEQVRo3u2ZQW7dIBCGp6qEb9AjdN1VrtBrkCwgajdPisR4WXURu1LXyQ3aI/R6tQHbDMwAzpNeNx0p0XuGj3/G8AbGBuDsnfGmodeU2e3+LNCplBEdjGGsR+NxjvbcZgIxJ/bS8E0RiURIVwPJiMVqrvHE/ENmFE8E37QkwhKBkURmwQQZWUSSWUS+zHOF0ZzIXDFGRlG30Ftd5mMiMuFuY0UmiQSJJTKiX4gs8z33bA/+JxY28p7tIsgY65naRBBlhnq2IROPjIxn0S+BiMwzh6Boa+u31LPo1yQjYx5MRBDrMnfmIfNrqiFjFkxAEBsyFGmJBJkjmBAKYlMmQ6YWssioBHnsQ45gfPSIbc8oMrWR8S3InCA9fq2evQnR+z2eepDxWgS77PbI1IeMH26CmKsQ7LTbInAVYvme7kD2xb91FeRMDWFlnNk/MIjhIygQlSCWEzmQ+yP1Fa1EZBvnJc2WKMu4tFUfaRxlmWQUglhRxpHxgEEKGdqWbnwoyKRDOLpXoiCTjkDOCrTBSiISEntt/0kDPZDkvvgrjl5/oMjvZLQEsciHAgN1gEUWxUt2hGsh+RHOkRnjkLUHQQaTrgsBuVBkufKnihgOsTVkbacIrKO8VpDV8exojYcMh/jWDBnSpMCq4AUKmXosFj8VSHPBFPXL0EIuZZnUQkBALIc4CRlCkBxieb+8jOERw4usMk5CLC/iF42AGJRrdwnRciUuIZVKXESkSlxLiBIrcZAQkCpxDUZziDKghEp8wTSUyNIEwFfi2iOQI+ARxVbiEBCgCASEkQlX/D+VInpDFFNWw4HokAqeQB0IlGW1Jsh6Zf8YEFXUyNBCMpntWxUhMvuXKnLIqOTRVB3Zeqr0oVkdgfggLxVpIbHv8hv5dQJZd/Jlylw/YvFmiDsbvg3IpQ8ZArLugrYXcX7L9Im4F/HpOWxF3YjvO6R7RwOBOHy6d7SQIQ7/PvndNBCAYltuI/AfuT2ikn7ko6k+gC+R2iP4NyDLby1kNoLETCQhPnMUiMV/jhg+Fvkux/yUITETieexNT/liDPyeSyeuXMkHhhY+xwSiEkRE4+2AhLykwqTHdaB8acalI6WMT/FG7QhZs9EAnOBFIl3fcDWO6swc+GPlNI1xG2I7UZOqgxlLGcQssNVEBeW1IbYDgRDcefn3XFVm1D6RASrswh0p9rnvQcZtoUWlleHXytz2ZChj9gm1HW+q02fMrXeoF6PKPPVnXjzvNpdLa3IycbgKcQ/mHg6h2DvLF6DQOfqyhip5S+Y/ZR+L0FMDAAAAABJRU5ErkJggg==">
    </a>
</div>
<div id="debug-bar">
    <div class="toolbar">
		<span id="toolbar-position">
            <a href="javascript: void(0)">
                <img
                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMBAMAAACkW0HUAAAAMFBMVEUAAAA0SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV6KW/P4AAAAD3RSTlMAA1BUVVxdZmmFho+St+B8v3FLAAAAKElEQVQIW2NgYOAMYACB/d9BJKP/IzCP/wKRFKM9RMP5H2ANXAkMDADptwvPwbMAUQAAAABJRU5ErkJggg==">
            </a>
        </span>

        @foreach($tool->collectors() as $collector)
        @continue($collector->show === false)
        @php($varname = strtolower($collector->name))
        <span class="octopy-label">
            <a href="javascript: void(0)" data-tab="octopy-{{ $varname }}">
                <img src="{{ $collector->icon() }}">
                <span class="hide-sm">
                	{{ $collector->name }}
                    @if($collector->badge)
                    	@if(method_exists($collector, 'badge'))
                        <span class="badge">{{ $collector->badge() }}</span>
                    @elseif(is_array($data->$varname) || is_object($data->$varname) || $data->$varname instanceof \stdClass)

                    	@php($countable = $data->$varname)
                    	@if(! is_countable($countable))
                   			@php($countable = (array) $data->$varname)
                   		@endif

                   		@if(is_countable($countable))
                   			<span class="badge">{{ count($countable) }}</span>
                   		@endif
                    @endif
                    @endif
                </span>
            </a>
        </span>
        @endforeach

        <!-- Open/Close Toggle -->
        <a id="debug-bar-link" href="javascript:void(0)" title="Open/Close">
            <img
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMBAMAAACkW0HUAAAAGFBMVEUAAAA0SV40SV40SV40SV40SV40SV40SV4tbTwAAAAAB3RSTlMAUIOFhoiJ960gEQAAAEJJREFUCB0FwbENgEAMBMFFFsRkn1KCM5dAKcSvC7Z9ZjgarpsV+DZjn4Yyrw2jAUobKG1gNFBmbMYuwwrM5nig7h+btg/A2Q4ZhQAAAABJRU5ErkJggg==">
        </a>
    </div>
    @foreach($tool->collectors() as $collector)
    @continue($collector->show === false)
    @include('child.' . strtolower($collector->name), compact('data', 'time', 'tool', 'collector'))
    @endforeach
</div>
