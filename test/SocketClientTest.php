<?php
namespace Tvce;

class SocketClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SocketClient
     */
    private $client;

    protected function setUp()
    {
        $this->client = new SocketClient('my-access-token', 'example.org');
    }

    /**
     * @test
     */
    public function constructShouldConfigureTheAttributes()
    {
        $this->assertAttributeSame('my-access-token', 'accessToken', $this->client);
        $this->assertAttributeSame('example.org', 'host', $this->client);
    }

    /**
     * @test
     */
    public function constructShouldInicializeTheSockets()
    {
        $this->assertEquals('resource', gettype($this->client->getSocket()));
    }

    /**
     * @test
     * @expectedException \Tvce\SocketClientException
     */
    public function constructShouldReturnExceptionCaseNotConnectSocket()
    {
        $c = new SocketClient('invalid-token', 'localhost:2');
    }

    /**
     * @test
     */
    public function requestAsStringTest()
    {
        $this->assertEquals("GET /path?query=string HTTP/1.1\r\n", $this->client->requestAsString(Http::GET, '/path', '?query=string'));
    }

    /**
     * @test
     */
    public function queryTest()
    {
        $query = $this->client->query([]);
        $this->assertEquals('', $query);

        $query = $this->client->query(['query' => 'string']);
        $this->assertEquals("?query=string", $query);
    }

    /**
     * @test
     * @expectedException \Tvce\SocketClientException
     */
    public function serializeMethodShouldReturnExceptionCaseNotArray()
    {
        $this->client->serialize('');
    }

    /**
     * @test
     */
    public function serializeShouldReturnStringJSON()
    {
        $return = $this->client->serialize(['id' => '1']);
        $this->assertEquals('{"id":"1"}', $return);
    }

    /**
     * @test
     */
    public function testBuildMessageGET()
    {
        $message = $this->client->makeMessage(Http::GET, '/path', ['query' => 'string']);
        $this->assertEquals("GET /path?query=string HTTP/1.1\r\nHost: example.org\r\nContent-type: application/json\r\nAccess-Token: my-access-token\r\n\r\n", $message);
    }

    /**
     * @test
     */
    public function testBuildMessagePOST()
    {
        $message = $this->client->makeMessage(Http::POST, '/path/create', [], ['id' => '1']);
        $this->assertEquals("POST /path/create HTTP/1.1\r\nHost: example.org\r\nContent-type: application/json\r\nAccess-Token: my-access-token\r\n\r\n{\"id\":\"1\"}", $message);
    }

    /**
     * @test
     */
    public function testBuildMessagePUT()
    {
        $message = $this->client->makeMessage(Http::PUT, '/path/update', [], ['id' => '1']);
        $this->assertEquals("PUT /path/update HTTP/1.1\r\nHost: example.org\r\nContent-type: application/json\r\nAccess-Token: my-access-token\r\n\r\n{\"id\":\"1\"}", $message);
    }

    /**
     * @test
     */
    public function testBuildMessageDELETE()
    {
        $message = $this->client->makeMessage(Http::DELETE, '/path/1');
        $this->assertEquals("DELETE /path/1 HTTP/1.1\r\nHost: example.org\r\nContent-type: application/json\r\nAccess-Token: my-access-token\r\n\r\n", $message);
    }
}