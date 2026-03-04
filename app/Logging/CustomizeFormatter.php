<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Monolog\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $formatter = new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                'Y-m-d H:i:s',
                true, // allowInlineLineBreaks
                true  // ignoreEmptyContextAndExtra
            );
            $handler->setFormatter($formatter);
        }
    }
}