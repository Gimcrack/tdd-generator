<?php

namespace  {
    require_once( 'helpers.php' );
}

namespace Ingenious\TddGenerator\Tests {

    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Event;
    use Illuminate\Contracts\Console\Kernel;
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
            if ( file_exists( __DIR__.'../bootstrap/app.php' )  )
                $app = require __DIR__.'/../bootstrap/app.php';

            elseif ( file_exists(__DIR__.'/../../../../../bootstrap/app.php') )
                $app = require __DIR__.'/../../../../../bootstrap/app.php';

            elseif ( file_exists( __DIR__.'/../../../tdd-generator-test/bootstrap/app.php') )
                $app = require __DIR__.'/../../../tdd-generator-test/bootstrap/app.php';

            $app->make(Kernel::class)->bootstrap();

            //Event::fake();
            Notification::fake();

            Hash::setRounds(4);

            return $app;
        }
    }
}
