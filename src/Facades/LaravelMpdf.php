<?php

namespace Mccarlosen\LaravelMpdf\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;
use Mccarlosen\LaravelMpdf\LaravelMpdf as Pdf;

/**
 * Class LaravelMpdf
 * @package Mccarlosen\LaravelMpdf\Facades
 *
 * @method static Pdf loadHTML(string $html, ?array $config = [])
 * @method static Pdf loadFile(string $file, ?array $config = [])
 * @method static Pdf loadView(string $view, ?array $data = [], ?array $mergeData = [], ?array $config = [])
 * @method static Pdf chunkLoadHTML(string $separator, string $html, ?array $config = [])
 * @method static Pdf chunkLoadFile(string $separator, string $file, ?array $config = [])
 * @method static Pdf chunkLoadView(string $separator, string $view, ?array $data = [], ?array $mergeData = [], ?array $config = [])
 */
class LaravelMpdf extends BaseFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-mpdf';
    }
}
