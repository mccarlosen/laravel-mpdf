<?php

namespace Meneses\LaravelMpdf;

use Illuminate\Support\Facades\Config;
use Mpdf\Mpdf;

/**
 * Laravel Mpdf: mPDF wrapper for Laravel 5
 *
 * @package laravel-mpdf
 * @author Carlos Meneses
 */
class LaravelMpdf {
	
	protected $mpdf;
	protected $config = [];

	public function __construct($html = '', $config = [])
	{
		$this->config = $config;

		$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];
		$tempDir = $defaultConfig['tempDir'];

		$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];
		
		$this->mpdf = new Mpdf([
			'mode' => $this->getConfig('mode'),
			'format' => $this->getConfig('format'),
			'orientation' => $this->getConfig('orientation'),
			'default_font_size' => $this->getConfig('default_font_size'),
			'default_font' => $this->getConfig('default_font'),
			'margin_left' => $this->getConfig('margin_left'),
			'margin_right' => $this->getConfig('margin_right'),
			'margin_top' => $this->getConfig('margin_top'),
			'margin_bottom' => $this->getConfig('margin_bottom'),
			'margin_header' => $this->getConfig('margin_header'),
			'margin_footer' => $this->getConfig('margin_footer'),
			'fontDir' => array_merge($fontDirs, [
				$this->getConfig('custom_font_dir')
			]),
			'fontdata' => ($this->getConfig('custom_font_data') ?: $fontData),
			'default_font' => $this->getConfig('default_font'), 
			'autoScriptToLang' => $this->getConfig('auto_language_detection'), 
			'autoLangToFont' => $this->getConfig('auto_language_detection'),
			'tempDir' => ($this->getConfig('temp_dir')) ?: $tempDir,
		]);

		$this->mpdf->SetTitle         ( $this->getConfig('title') );
		$this->mpdf->SetAuthor        ( $this->getConfig('author') );
		$this->mpdf->SetWatermarkText ( $this->getConfig('watermark') );
		$this->mpdf->SetDisplayMode   ( $this->getConfig('display_mode') );

		$this->mpdf->showWatermarkText  = $this->getConfig('show_watermark');
		$this->mpdf->watermark_font     = $this->getConfig('watermark_font');
		$this->mpdf->watermarkTextAlpha = $this->getConfig('watermark_text_alpha');

		$this->mpdf->WriteHTML($html);
	}

	protected function getConfig($key) {
		if (isset($this->config[$key])) {
			return $this->config[$key];
		} else {
			return Config::get('pdf.' . $key);
		}
	}
	
	/**
	 * Get instance mpdf
	 * @return static
	 */
	public function getMpdf()
	{
		return $this->mpdf;
	}


	/**
	 * Output the PDF as a string.
	 *
	 * @return string The rendered PDF as string
	 */
	public function output()
	{
		return $this->mpdf->Output('', 'S');
	}

	/**
	 * Save the PDF to a file
	 *
	 * @param $filename
	 * @return static
	 */
	public function save($filename)
	{
		return $this->mpdf->Output($filename, 'F');
	}

	/**
	 * Make the PDF downloadable by the user
	 *
	 * @param string $filename
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function download($filename = 'document.pdf')
	{
		return $this->mpdf->Output($filename, 'D');
	}

	/**
	 * Return a response with the PDF to show in the browser
	 *
	 * @param string $filename
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function stream($filename = 'document.pdf')
	{
		return $this->mpdf->Output($filename, 'I');
	}
}
