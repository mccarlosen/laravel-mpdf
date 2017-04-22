<?php

namespace Meneses\LaravelMpdf;

use Config;
use mPDF;

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

		if (Config::has('pdf.custom_font_path') && Config::has('pdf.custom_font_data')) {
			define('_MPDF_SYSTEM_TTFONTS_CONFIG', __DIR__ . '/../mpdf_ttfonts_config.php');
		}

		$this->mpdf = new mPDF(
			$this->getConfig('mode'),              // mode - default ''
			$this->getConfig('format'),            // format - A4, for example, default ''
			$this->getConfig('default_font_size'), // font size - default 0
			$this->getConfig('default_font'),      // default font family
			$this->getConfig('margin_left'),       // margin_left
			$this->getConfig('margin_right'),      // margin right
			$this->getConfig('margin_top'),        // margin top
			$this->getConfig('margin_bottom'),     // margin bottom
			$this->getConfig('margin_header'),     // margin header
			$this->getConfig('margin_footer'),     // margin footer
			$this->getConfig('orientation')        // L - landscape, P - portrait
		);

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
