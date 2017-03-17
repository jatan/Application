<?php
/** User: KALPAN
 *  Date: 1/19/17
 *  Configure Monolog handlers for application logging.
 */

namespace Bootstrap;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger as Monolog;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Monolog\Handler\StreamHandler;

Class ConfigureLogging extends BaseConfigureLogging {


	protected function configureHandlers(Application $app, Writer $log)
	{
		$bubble = false;

		// Stream Handlers
		$infoStreamHandler = new StreamHandler( storage_path("/logs/laravel_info.log"), Monolog::INFO, $bubble);
		$warningStreamHandler = new StreamHandler( storage_path("/logs/laravel_warning.log"), Monolog::WARNING, $bubble);
		$errorStreamHandler = new StreamHandler( storage_path("/logs/laravel_error.log"), Monolog::ERROR, $bubble);

		//Formatting
		// the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
		$logFormat = "%datetime% [%level_name%] (%channel%): %message% %context% %extra%\n";
		$formatter = new LineFormatter($logFormat);

		$infoStreamHandler->setFormatter($formatter);
		$warningStreamHandler->setFormatter($formatter);
		$errorStreamHandler->setFormatter($formatter);

		//push handlers
		$logger = $log->getMonolog();
		$logger->pushHandler($infoStreamHandler);
		$logger->pushHandler($warningStreamHandler);
		$logger->pushHandler($errorStreamHandler);

		$log->useDailyFiles($app->storagePath().'/logs/daily.log');
	}
}