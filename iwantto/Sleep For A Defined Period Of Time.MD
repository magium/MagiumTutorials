# I Want To Sleep For A Defined Period Of Time

There might be some times when you want to sleep for a little bit of time.  One such scenario could be that the browser needs a few ms to catch its breath before the next operation or one of a hundred other things.

 Sleeping is really easy in Magium.

 ```
 class SleepTest extends AbstractTestCase
 {

    public function testSleep()
    {
        $this->sleep('1s'); 1 second
        $this->sleep('100ms'); // 100 milliseconds
        $this->sleep('100us'); // 100 microseconds
        $this->sleep('100ns'); // 100 nanoseconds
    }

 }
 ```