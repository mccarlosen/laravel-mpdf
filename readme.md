	
# Laravel Mpdf: Using Mpdf in Laravel 5 for generate Pdfs

> Easily generate PDF documents from HTML right inside of Laravel using this mpdf wrapper.

## Important Note

> Currently supported mpdf version `6.1.3`

## Installation

Require this package in your `composer.json` 

```
"require": {
	carlos-meneses/laravel-pdf: "^1.0"
}
```

or install it by running:

```
composer require carlos-meneses/laravel-pdf
```

To start using Laravel, add the Service Provider and the Facade to your `config/app.php`:

```php
'providers' => [
	// ...
	Meneses\LaravelMpdf\LaravelMpdfServiceProvider::class
]
```

```php
'aliases' => [
	// ...
	'PDF' => Meneses\LaravelMpdf\Facades\LaravelMpdf::class
]
```

## Basic Usage

To use Laravel Mpdf add something like this to one of your controllers. You can pass data to a view in `/resources/views`.

```php
//....
use PDF;
class ReportController extends Controller {
	public function generate_pdf() 
	{
		$data = [
			'foo' => 'bar'
		];
		$pdf = PDF::loadView('pdf.document', $data);
		return $pdf->stream('document.pdf');
	}
}
```

## Config

You can use a custom file to overwrite the default configuration. Just create `config/pdf.php` and add this:

```php
return [
	'mode'                 => '',
	'format'               => 'A4',
	'default_font_size'    => '12',
	'default_font'         => 'sans-serif',
	'margin_left'          => 10,
	'margin_right'         => 10,
	'margin_top'           => 10,
	'margin_bottom'        => 10,
	'margin_header'        => 0,
	'margin_footer'        => 0,
	'orientation'          => 'P',
	'title'                => 'Laravel mPDF',
	'author'               => '',
	'watermark'            => '',
	'show_watermark'       => false,
	'watermark_font'       => 'sans-serif',
	'display_mode'         => 'fullpage',
	'watermark_text_alpha' => 0.1
];
```

To override this configuration on a per-file basis use the fourth parameter of the initializing call like this:

```
PDF::loadView('pdf', $data, [], [
  'title' => 'Another Title',
  'margin_top' => 0
])->save($pdfFilePath);
```

## Headers and Footers

If you want to have headers and footers that appear on every page, add them to your `<body>` tag like this:

```html
<htmlpageheader name="page-header">
	Your Header Content
</htmlpageheader>

<htmlpagefooter name="page-footer">
	Your Footer Content
</htmlpagefooter>
```

Now you just need to define them with the name attribute in your CSS:

```css
@page {
	header: page-header;
	footer: page-footer;
}
```

Inside of headers and footers `{PAGENO}` can be used to display the page number.

## Included Fonts

By default you can use all the fonts [shipped with Mpdf](https://mpdf.github.io/fonts-languages/available-fonts-v6.html).

## Custom Fonts

You can use your own fonts in the generated PDFs. The TTF files have to be located in one folder, e.g. `/resources/fonts/`. Add this to your configuration file (`/config/pdf.php`):

```php
return [
	'custom_font_path' => base_path('/resources/fonts/'), // don't forget the trailing slash!
	'custom_font_data' => [
		'examplefont' => [
			'R'  => 'ExampleFont-Regular.ttf',    // regular font
			'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
			'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
			'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
		]
		// ...add as many as you want.
	]
];
```

Now you can use the font in CSS:

```css
body {
	font-family: 'examplefont', sans-serif;
}
```

## Get instance your Mpdf

You can access all mpdf methods through the mpdf instance with `getMpdf()`.

```php
use PDF;

$pdf = PDF::loadView('pdf.document', $data);
$pdf->getMpdf()->AddPage(...);

```

## License

Laravel Mpdf is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
