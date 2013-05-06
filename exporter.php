<?php

function http_build_cookie(array $data)
{
	$cookie_string = '';
	foreach ($data as $key => $value)
	{
		$cookie_string .= $key.'='.$value.';';
	}
	return $cookie_string;
}

class RottenTomatoesExporter
{
	private $user_id = null;
	private $session_id = null;
	private $fb = [];

	public function __construct($session_id, array $fb)
	{
		$this->session_id = $session_id;
		list($dnc, $this->user_id) = explode('-', $session_id);
		list($dnc, $id) = explode('_', array_keys($fb)[0]);
		$fb['fbm_'.$id] = 'base_domain=.www.rottentomatoes.com';
		$this->fb = $fb;
	}

	public function export()
	{
		echo 'Exporting user_id = '.$this->user_id.', session_id = '.$this->session_id.PHP_EOL;

		$cookie_details = ['session_id' => $this->session_id];
		$cookie_details = array_merge($cookie_details, $this->fb);

		$context_options = ['http' =>	[
										'method' => 'GET',
										'header' => 'Cookie: '.http_build_cookie($cookie_details)
										]
							];
		$context = stream_context_create($context_options);

		$page = 1;
		$content = '';

		if (!file_exists($this->user_id))
		{
			mkdir($this->user_id);
		}

		while (true)
		{
			echo 'Getting page '.$page.' ... ';
			$time_start = microtime(true);

			$page_url = 'http://www.rottentomatoes.com/user/id/'.$this->user_id.'/ratings/?ajax=true&profileUserId='.$this->user_id.'&pageNum='.$page.'&sortby=ratingDate';
			$page_content = file_get_contents($page_url, false, $context);

			if ($page_content === false || $page_content === $content) return;

			// Get content of interest
			file_put_contents($this->user_id.'/'.$page.'.html', $page_content);

			$content = $page_content;
			++$page;

			$duration = round(microtime(true) - $time_start, 2);
			echo 'Done ('.$duration.'s)'.PHP_EOL;
		}
	}

	public function get_results()
	{
		$data = [];

		foreach (glob($this->user_id.'/*.html') as $file)
		{
			$parsing_string = 'Parsing file '.$file;
			echo $parsing_string.PHP_EOL;
			echo str_repeat('-', strlen($parsing_string)).PHP_EOL;
			$content = file_get_contents($file);

			if ($content === '') continue;

			// Force ISO-8859-1 encoding
			$content = '<?xml version="1.0" encoding="iso-8859-1" ?>'.$content;

			$content = str_replace('&', '&amp;', $content);

			$xml = new SimpleXMLElement($content);
			$result = $xml->xpath('//div[@data-title]');

			foreach ($result as $node)
			{
				echo 'Title: '.$node['data-title'].PHP_EOL;
				echo 'Rating: '.$node['data-score'].PHP_EOL.PHP_EOL;

				$data[] = ['title' => (string)$node['data-title'], 'rating' => (string)$node['data-score']];
			}
		}

		usort($data, [__CLASS__, 'sort_by_title']);

		return $data;
	}

	public static function sort_by_title($a, $b)
	{
		if ($a['title'] === $b['title']) return 0;
		return $a['title'] < $b['title'] ? -1 : 1;
	}
}