<?php

return [
	'other-page/(.*)' => 'Demo/index/$1',
	
	'^pages/(.*)' => 'Pages/byCategory/$1',
	'^page/(.*)' => 'Pages/detail/$1',
];
