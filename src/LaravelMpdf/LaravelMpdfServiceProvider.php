<?php

namespace Meneses\LaravelMpdf;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class LaravelMpdfServiceProvider extends BaseServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../config/pdf.php', 'pdf'
		);

		$this->app->bind('mpdf.wrapper', function($app) {
			return new LaravelMpdfWrapper();
		});
	}

}
