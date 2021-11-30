@php
	$config = [
		'appName' => config('app.name'),
	];
@endphp
@production
	@php
		$manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
	@endphp
@endproduction
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="text-[16px]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
	@production
		<link rel="stylesheet" href="/build/{$manifest['resources/js/app.js']['css'][0]}">
	@endproduction
</head>
<body class="bg-gray-100 text-gray-700 font-sans antialiased leading-normal tracking-normal text-base">
    <div id="app"></div>
	<script>
		window.config = @json($config);
	</script>
	@production
		<script type="module" src="/build/{$manifest['resources/js/app.js']['file']}"></script>
	@else
		<script type="module" src="http://localhost:3000/@vite/client"></script>
		<script type="module" src="http://localhost:3000/resources/js/app.js"></script>
	@endproduction
</body>
</html>
