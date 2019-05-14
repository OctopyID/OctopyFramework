<html>
<head>
	<meta charset="UTF-8">
	<meta name="robots" content="noindex">
	<title>{{ $exception }}</title>
	<link rel="icon" type="image/x-icon" href="data:image/vnd.microsoft.icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAANcNAADXDQAAAAAAAAAAAAAAAAAAAAAAAAAAAAA8Mx0AGBQLR05GLd1cVTn8PTgmoQAAABEDAwIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADEc9I71WTDDNPjknlGVdP/ApJhlcQj0pAAgIBRsSEAs1BQUDFRcVDgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABpTRyneODEeegAAABRbVDnbPjknfhMRCjxVTjTSYlo98VBJMsIODQkqFRMNAAAAAAAAAAAAAAAAAAAAAAEPDQgzS0Al4zAqGnUAAAASWlI42TczIoZFPSeqWFA1zyEeFWdhWDvkPTgmivznmwAAAAAAAAAAAGVWMAAgGw9UXVM13FpSNvhXUDbAAAAAOltTONc0LyCMU0ovw1RNMpQAAAAGUkowwEtFLqUAAAAAAAAAAAAAAAAAAAAKST8kwU5FK74bGRJgYFY35jEsGppbUzjZOzUimVhOMclkWzyLAAAAA0A6JrM7NiSZ////AAAAAAAAAAAAAAAAElFGKNU+NiCJAAAADFBGKstWSCX2YVc2+V5QKe9gUy75UUoxuDs2JWcoJRmfKSUZlB0bEj8AAAAGAAAAAAAAAA49NB3ELykYegAAAAtRRirKU0Yk/2NYN/9aTCf/X1Iu/2hfQOVmXkDdXVU64V9XO+ZiWTzsLCgbdAAAAAcdGxJEJyQYny8rHYo4MyNgZVg03ox+Uv+ommf/koVY/5WIWv9SSzO4AAAAJgAAAB4AAAAiS0MrtlFJMN0pJBV2YFc57F9XO+RkXD7gYlc282laM/6Yi17/4M2L/9HAgv91a0n/h3xU9ysnG2EMCwckLCgbZ1pSNtNIQivGTUMo3UtDK7UAAAAeAgICQGlYL+pGMiD/WEk0/9rJiP+woW3/Lx4Y/4N1UP9hWTy0FBMNdFVONehQSjLAFxUOPkQ7I8VaUTTULiodZwkIBVZ5ZTbsbVo3/4d5U//cyon/xLR6/2JTOv+gkWP/aWFCwAAAABcEBAIkAQEAEAcGBAAUEQk8TkcvvlVONeYTEg2CZFQsz8mxbf/ayIj/28mI/9vJiP/Yx4f/yLh8/0hCLZkAAAAAAAAAAAAAAAAAAAAAAgIBAAAAAA8CAgEjAAAACzEpFYKnkVX/3cqH/9vJiP/byYj/3syK/5OHW/EVEw1HIyAWAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgHBAAAAAAab141rL+scP7cyon/2ciH/6eZaPs8OCaKAAAABgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGBQKABURByBGPyiweW9L+G9mRewzLyCHAAAADwMDAgAAAAAAAAAAAAAAAAAAAAAA8H8AAOBHAADgAwAAwAMAAMABAACAAwAAgAAAAIAAAAAAAAAAAAAAAAAAAAAAAQAAAAcAAIAPAADwDwAA+B8AAA==">
	<style type="text/css">body{height:100%;background:#fafafa;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;color:#777;margin:0;padding:0}h1{font-weight:lighter;letter-spacing:.8;font-size:3rem;color:#222;margin:0}.container{max-width:75rem;margin:0 auto;padding:1rem}.header{background:#0c1021;color:#fff}.header h1{color:#fff}.header p{font-size:1.2rem;margin:0;line-height:2.5}.header a{color:rgba(255,255,255,.5);margin-left:2rem;display:none;text-decoration:none}.header:hover a{display:inline}.footer .container{border-top:1px solid #e7e7e7;margin-top:1rem;text-align:center}.tabs{list-style:none inside;margin:0 0 -1px;padding:0}.tabs li{display:inline}.tabs a:link,.tabs a:visited{padding:0 1rem;line-height:2.7;text-decoration:none;color:#a7a7a7;background:#f1f1f1;border:1px solid #e7e7e7;border-bottom:0;border-top-left-radius:5px;border-top-right-radius:5px;display:inline-block}.tabs a:hover{background:#e7e7e7;border-color:#e1e1e1}.tabs a.active{background:#fff}.tab-content{background:#fff;border:1px solid #efefef}.content{padding:1rem}.hide{display:none}table{border:1px solid #fff;width:100%;overflow:hidden;border-radius:7px;border-collapse:collapse}th{text-align:left;border-bottom:1px solid #e7e7e7;padding-bottom:.5rem}td{padding:.2rem .5rem}tr:hover td{background:#f1f1f1}tr.arguments:hover td{background:#0c1021}td.arguments{border-bottom:1pt solid #fff;border-left:1pt solid #fff}td pre{white-space:pre-wrap}.trace a{color:inherit}.trace table{width:auto}.trace tr td:first-child{min-width:5em;font-weight:700}.trace td{background:#0c1021;color:#fff}.trace li{margin-right:3%}.trace pre{margin:0}.args{display:none}.center{text-align:center}</style>
	{{{ $app['syntax']->stylesheet() }}}
</head>
<body onload="init()">
	<div class="header">
		<div class="container">
			<h1>{{ $exception, $code ? ' #' . $code : '' }}</h1>
			<p>{{{ $message }}}</p>
		</div>
	</div>

	<div class="container">
		<p>{{ $file }}</p>
		@if (is_file($file))
			{{{ $app->syntax->highlight($file, $line, '5:5') }}}
		@endif
	</div>
	<div class="container">
		<ul class="tabs" id="tabs">
			<li><a href="#backtrace">Backtrace</a></li>
			<li><a href="#server">Server</a></li>
			<li><a href="#request">Request</a></li>
			<li><a href="#response">Response</a></li>
			<li><a href="#files">Files</a></li>
			<li><a href="#memory">Memory</a></li>
		</ul>
		<div class="tab-content">
			<div class="content" id="backtrace">
				@include('inc.backtrace')
			</div>
			<div class="content" id="server">
				@include('inc.server')
			</div>
			<div class="content" id="request">
				@include('inc.request')
			</div>
			<div class="content" id="response">
			@include('inc.response')
			</div>
			<div class="content" id="files">
				@include('inc.files')
			</div>
			<div class="content" id="memory">
				@include('inc.memory')
			</div>
		</div> 
	</div>
	<div class="footer">
		<div class="container">
			<p>
				Displayed at {{ date('H:i:s') }} &mdash;
				PHP Version : {{ substr(phpversion(), 0, 5) }}  &mdash;
				Octopy Version : {{ $app->version() }}
			</p>
		</div>
	</div>
	<script type="text/javascript">
		var tab=[],contentDivs=[];function init(){for(var t=document.getElementById("tabs").childNodes,e=0;e<t.length;e++)if("LI"==t[e].nodeName){var n=getFirstChildWithTagName(t[e],"A"),i=getHash(n.getAttribute("href"));tab[i]=n,contentDivs[i]=document.getElementById(i)}for(i in e=0,tab)tab[i].onclick=showTab,tab[i].onfocus=function(){this.blur()},0==e&&(tab[i].className="active"),e++;for(i in e=0,contentDivs)0!=e&&(contentDivs[i].className="content hide"),e++}function showTab(){var t,e=getHash(this.getAttribute("href"));for(t in contentDivs)contentDivs[t].className=t==e?(tab[t].className="active","content"):(tab[t].className="","content hide");return!1}function getFirstChildWithTagName(t,e){for(var n=0;n<t.childNodes.length;n++)if(t.childNodes[n].nodeName==e)return t.childNodes[n]}function getHash(t){var e=t.lastIndexOf("#");return t.substring(e+1)}function toggle(t){if((t=document.getElementById(t)).style&&t.style.display)var e=t.style.display;else t.currentStyle?e=t.currentStyle.display:window.getComputedStyle&&(e=document.defaultView.getComputedStyle(t,null).getPropertyValue("display"));return t.style.display="block"==e?"none":"block",!1}
	</script>
	{{{ $app['syntax']->javascript() }}}
</body>
</html>
