<?php

$checkBlacklist = array(

array(
	'check' => 'plural',
	'code' => array( 'gan','gan-hans','gan-hant','gn','hak','hu','ja',
		'ka','kk-arab','kk-cyrl','kk-latn','ko','lzh','mn','ms','sah','sq',
		'tet','th','wuu','xmf','yue','zh','zh-classical','zh-cn','zh-hans',
		'zh-hant','zh-hk','zh-sg','zh-tw','zh-yue'
	),
),
array(
	'group' => 'core',
	'check' => 'variable',
	'message' => array(
		'currentrev-asof',
		'filehist-thumbtext',
		'lastmodifiedatby',
		'protect-expiring',
		'rcnotefrom',
		'revisionasof',
		'confirmemail_body',
		'history-feed-item-nocomment',
	),
),

);
