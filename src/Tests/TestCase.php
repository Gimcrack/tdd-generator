<?php

namespace Ingenious\TddGenerator\Tests;

use App\User;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Assert;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\EventFake;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $response = null;
    protected $headers = [];
    protected $api_prefix = "api/v1";
    protected $api = false;

    /**
     * Create an admin and login
     * @method actingAsAdmin
     *
     * @return   $this
     */
    public function actingAsAdmin()
    {
        $user = create_state(User::class,'admin');

        return $this->actingAs($user,'api');
    }

    /**
     * Create an editor and login
     * @method actingAsEditor
     *
     * @return   $this
     */
    public function actingAsEditor()
    {
        $user = create_state(User::class,'editor');

        return $this->actingAs($user,'api');
    }

    /**
     * Create a user and login
     * @method actingAsUser
     *
     * @return   $this
     */
    public function actingAsUser()
    {
        $user = create(User::class);

        return $this->actingAs($user,'api');
    }

    /**
     * Login and make the request as the selected user
     *
     * @param Authenticatable|User $user
     * @param string|null $driver
     * @return $this
     */
    public function actingAs(Authenticatable $user, $driver = null)
    {
        parent::actingAs($user, $driver);

        if ( $driver !== 'web' )
            $this->headers(['Authorization' => "Bearer {$user->api_token}"]);

        return $this;
    }

    /**
     * Get the response
     *
     * @return     TestResponse
     */
    public function response()
    {
        if ( ! $this->response) {
            $this->fail("No response yet");
        }
        return $this->response;
    }

    /**
     * Assert that the json data has the specified count
     * @method assertJsonCount
     *
     * @param $count
     *
     * @return $this
     */
    public function assertJsonCount($count)
    {
        $this->assertCount($count, $this->response()->json());
        return $this;
    }

    /**
     * Set some headers
     *
     * @param      array $headers The headers
     *
     * @return $this
     */
    public function headers($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * Set the referrer header
     * @method from
     *
     * @param string $from
     *
     * @return $this
     */
    public function from(string $from)
    {
        return $this->headers(['referer' => $from]);
    }

    /**
     * Access an api endpoint
     * @method api
     *
     * @return $this
     */
    public function api()
    {
        $this->api = true;
        return $this;
    }

    /**
     * Format the desired endpoint
     *
     * @param $params string|array  The application endpoint to access
     * @return string
     */
    private function endpoint($params)
    {
        $params = Arr::wrap($params);
        $url = array_shift($params);

        // determine if we are trying to access a named route
        if ( strpos($url, ".") !== false ) {
            return route($url, $params);
        }

        // we may be wanting an api route, otherwise return the endpoint unchanged
        return ($this->api) ?
            vsprintf("%s/%s", [$this->api_prefix, trim($url, "/")]) :
            $url;
    }

    /**
     * Post to some endpoint with some data and save the response
     * @method post
     *
     * @param string|array $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return $this
     */
    public function post($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->response = parent::json('POST', $this->endpoint($endpoint), $data, $this->headers);
        $this->api = false;
        return $this;
    }

    /**
     * Get some endpoint with some data and save the response
     * @method get
     *
     * @param string|array $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return $this
     */
    public function get($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->response = parent::json('GET', $this->endpoint($endpoint), $data, $this->headers);
        $this->api = false;
        return $this;
    }

    /**
     * Visit some page and save the response
     * @method visit
     *
     * @param string|array $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return $this
     */
    public function visit($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->response = parent::get($this->endpoint($endpoint), $this->headers); //parent::json('GET', $this->endpoint($endpoint), $data, $this->headers);
        $this->api = false;
        return $this;
    }

    /**
     * Patch some endpoint with some data and save the response
     * @method patch
     *
     * @param string|array $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return $this
     */
    public function patch($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->response = parent::json('PATCH', $this->endpoint($endpoint), $data, $this->headers);
        $this->api = false;
        return $this;
    }

    /**
     * Put some endpoint with some data and save the response
     * @method put
     *
     * @param string|array $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return $this
     */
    public function put($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->response = parent::json('PUT', $this->endpoint($endpoint), $data, $this->headers);
        $this->api = false;
        return $this;
    }

    /**
     * Delete some endpoint with some data and save the response
     * @method delete
     *
     * @param string|array $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return $this
     */
    public function delete($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->response = parent::json('DELETE', $this->endpoint($endpoint), $data, $this->headers);
        $this->api = false;
        return $this;
    }

    /**
     * Fake the specified events
     *
     * @param array $events
     */
    public function fakeEvent($events = [])
    {
        Event::swap( new EventFake( Event::getFacadeRoot(), Arr::wrap($events) ));
    }

    /**
     * Assert that the event was dispatched and has the proper data
     * @method assertEvent
     *
     * @return      $this
     */
    public function assertEvent($event, $models = [])
    {
        Event::assertDispatched($event, function($e) use ($models) {
            // make sure the event has the expected models
            foreach($models as $model_type => $model) {
                if ( ! is_object($model) ) {
                    if ( $e->$model_type != $model ) return false;
                }
                else {
                    if ( ! $e->$model_type->is($model) ) return false;
                }

            }
            return true;
        });
        return $this;
    }

    /**
     * Assert that the event was dispatched and has the proper data
     * @method assertEvent
     *
     * @return      $this
     */
    public function assertNotEvent($event)
    {
        Event::assertNotDispatched($event);
        return $this;
    }

    /**
     * Assert that the job was pushed onto the queue
     *
     * @param $job
     * @param array $models
     * @return $this
     */
    public function assertJob($job, $models = [])
    {
        Queue::assertPushed($job, function($j) use ($models) {
            // make sure the event has the expected models
            foreach($models as $model_type => $model) {
                if ( ! $j->$model_type->is($model) ) return false;
            }
            return true;
        });

        return $this;
    }

    /**
     * Assert that the notification was sent and has the proper data
     * @method assertNotification
     *
     * @return   $this
     */
    public function assertNotification($notification, $user, $models = [])
    {
        Notification::assertSentTo( $user, $notification, function($n, $channels) use($models) {
            foreach($models as $model_type => $model) {
                if ( ! $n->$model_type->is($model) ) return false;
            }
            return true;
        });
    }

    /**
     * Assert that the notification was not sent
     * @method assertNotification
     *
     * @return   $this
     */
    public function assertNotificationNotSent($notification, $user, $models = [])
    {
        Notification::assertNotSentTo( $user, $notification, function($n, $channels) use($models) {
            foreach($models as $model_type => $model) {
                if ( ! $n->$model_type->is($model) ) return false;
            }
            return true;
        });
    }
}
