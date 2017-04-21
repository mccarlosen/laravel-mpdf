<?php

namespace Meneses\LaravelMpdf;

use File;
use View;

class LaravelMpdfWrapper {

	/**
	 * Load a HTML string
	 *
	 * @param string $html
	 * @return Pdf
	 */
	public function loadHTML($html, $config = [])
	{
		return new LaravelMpdf($html, $config);
	}

	/**
	 * Load a HTML file
	 *
	 * @param string $file
	 * @return Pdf
	 */
	public function loadFile($file, $config = [])
	{
		return new LaravelMpdf(File::get($file), $config);
	}

	/**
	 * Load a View and convert to HTML
	 *
	 * @param string $view
	 * @param array $data
	 * @param array $mergeData
	 * @return Pdf
	 */
	public function loadView($view, $data = [], $mergeData = [], $config = [])
	{
		return new LaravelMpdf(View::make($view, $data, $mergeData)->render(), $config);
	}

}
