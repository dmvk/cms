<?php

namespace Moes\Texy;

class FSHLHandler
{
	public function __invoke($invocation, $blocktype, $content, $lang, $modifier)
	{
		if ($blocktype !== 'block/code')
			return $invocation->proceed();

		$lang = strtolower($lang);
		
		if ($lang === 'js')
			$lang = 'javascript';

		$lexer = '\FSHL\Lexer\\' . ucfirst($lang);	
		
		if (!class_exists($lexer))
			return $invocation->proceed();

		$fshl = new \FSHL\Highlighter(new \FSHL\Output\Html);
		$fshl->setLexer(new $lexer);

		$texy = $invocation->getTexy();
		$content = \Texy::outdent($content);
		$content = $fshl->highlight($content);
		$content = $texy->protect($content, \Texy::CONTENT_BLOCK);

		$pre = \TexyHtml::el('pre');
		
		if ($modifier) $modifier->decorate($texy, $pre);

		$pre->attrs['class'] = strtolower($lang);
		$pre->create('code', $content);

		return $pre;
	}
}
