<?php

namespace  {
    require_once( 'helpers.php' );
}

namespace Tests {

    use Illuminate\Contracts\Console\Kernel;
    use Illuminate\Support\Facades\Event;
    use Illuminate\Support\Facades\Notification;

    trait CreatesApplication
    {
        /**
         * Creates the application.
         *
         * @return \Illuminate\Foundation\Application
         */
        public function createApplication()
        {
            $app = require __DIR__.'../../../../../bootstrap/app.php';

            $app->make(Kernel::class)->bootstrap();

            //Event::fake();
            Notification::fake();

            return $app;
        }
    }
}
