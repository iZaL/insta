<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the connections below you wish to use as
	| your default connection for all work. Of course, you may use many
	| connections at once using the manager class.
	|
	*/

	'default' => 'main',

	/*
    |--------------------------------------------------------------------------
    | Instagram Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

	'connections' => [

		'main' => [
			'client_id' => '37ef9ee4f70d488d9b1ffb3b8f14b4b6',
			'client_secret' => 'bf217b50f8fd4a53a16229153a9b501f',
			'callback_url' => 'http://insta.ideasowners.net/instagrams/account/authenticate'
		],

		'alternative' => [
			'client_id' => 'your-client-id',
			'client_secret' => null,
			'callback_url' => null
		],

	]

];
