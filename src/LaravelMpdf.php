<?php

namespace Mccarlosen\LaravelMpdf;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Traits\Macroable;

/**
 * Laravel Mpdf: mPDF wrapper for Laravel
 *
 * @package laravel-mpdf
 * @author Carlos Meneses
 */
class LaravelMpdf
{
    use Macroable;

    protected $mpdf;
    protected $config = [];

    public function __construct($config = [])
    {
        $this->config = $config;

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs      = $defaultConfig['fontDir'];
        $tempDir       = $defaultConfig['tempDir'];
        
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];
        $configGlobal      = [
            'mode'              => $this->getConfig('mode'),
            'format'            => $this->getConfig('format'),
            'orientation'       => $this->getConfig('orientation'),
            'default_font_size' => $this->getConfig('default_font_size'),
            'default_font'      => $this->getConfig('default_font'),
            'margin_left'       => $this->getConfig('margin_left'),
            'margin_right'      => $this->getConfig('margin_right'),
            'margin_top'        => $this->getConfig('margin_top'),
            'margin_bottom'     => $this->getConfig('margin_bottom'),
            'margin_header'     => $this->getConfig('margin_header'),
            'margin_footer'     => $this->getConfig('margin_footer'),
            'fontDir'           => array_merge($fontDirs, [
                $this->getConfig('custom_font_dir')
            ]),
            'fontdata'          => array_merge($fontData, $this->getConfig('custom_font_data')),
            'autoScriptToLang'  => $this->getConfig('auto_language_detection'),
            'autoLangToFont'    => $this->getConfig('auto_language_detection'),
            'tempDir'           => ($this->getConfig('temp_dir')) ?: $tempDir,
        ];
        $configMerge = array_merge($configGlobal, $this->config);
        
        $this->mpdf = new Mpdf(array_merge($defaultConfig, $configMerge));

        $this->mpdf->SetTitle($this->getConfig('title'));
        $this->mpdf->SetSubject($this->getConfig('subject'));
        $this->mpdf->SetAuthor($this->getConfig('author'));
        $this->mpdf->SetWatermarkText($this->getConfig('watermark'));
        $this->mpdf->SetWatermarkImage(
            $this->getConfig('watermark_image_path'),
            $this->getConfig('watermark_image_alpha'),
            $this->getConfig('watermark_image_size'),
            $this->getConfig('watermark_image_position')
        );
        $this->mpdf->SetDisplayMode($this->getConfig('display_mode'));

        $this->mpdf->PDFA               = $this->getConfig('pdfa') ?: false;
        $this->mpdf->PDFAauto           = $this->getConfig('pdfaauto') ?: false;
        $this->mpdf->showWatermarkText  = $this->getConfig('show_watermark');
        $this->mpdf->showWatermarkImage = $this->getConfig('show_watermark_image');
        $this->mpdf->watermark_font     = $this->getConfig('watermark_font');
        $this->mpdf->watermarkTextAlpha = $this->getConfig('watermark_text_alpha');
        // use active forms
        $this->mpdf->useActiveForms = $this->getConfig('use_active_forms');
    }

    protected function getConfig($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : Config::get('pdf.' . $key);
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
     * @throws \Mpdf\MpdfException
     */
    public function output()
    {
        return $this->mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * Save the PDF to a file
     *
     * @param $filename
     * @return static
     * @throws \Mpdf\MpdfException
     */
    public function save($filename)
    {
        return $this->mpdf->Output($filename, Destination::FILE);
    }

    /**
     * Make the PDF downloadable by the user
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Mpdf\MpdfException
     */
    public function download($filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, Destination::DOWNLOAD);
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Mpdf\MpdfException
     */
    public function stream($filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, Destination::INLINE);
    }
}