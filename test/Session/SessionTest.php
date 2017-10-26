<?php

namespace Vibe\Session;

/**
 * Test cases for class Guess.
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateObject() 
    {
        $session = new Session();
        $this->assertInstanceOf("Vibe\Session\Session", $session);
    }

    public function testGetSession()
    {
        $session = new Session();
        $session->set("value", 10);
        $this->assertEquals(10, $session->get("value"));
    }

    public function testGetOnce()
    {
        $session = new Session("Once");
        $session->set("value_2", 20);
        $session->getOnce("value_2");
        $this->assertEquals(null, $session->get("value_2"));
    }

    public function testDelete()
    {
        $session = new Session("Delete");
        $session->set("value_3", 50);
        $session->delete("value_3");
        $this->assertEquals(null, $session->get("value_3"));
    }
}
