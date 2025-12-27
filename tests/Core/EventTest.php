<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/TestCase.php';
require_once dirname(__DIR__, 2) . '/core/Event.php';

use Core\Event;

/**
 * Unit tests for Core\Event
 */
class EventTest extends TestCase
{
    public function setUp(): void
    {
        // Clear all listeners before each test
        Event::clear();
    }

    public function tearDown(): void
    {
        Event::clear();
    }

    /**
     * Test that a listener is called when an event is triggered.
     */
    public function test_on_and_trigger_single_listener(): void
    {
        $called = false;

        Event::on('test_event', function () use (&$called) {
            $called = true;
        });

        Event::trigger('test_event');

        $this->assertTrue($called, 'Listener should be called when event is triggered');
    }

    /**
     * Test that listeners receive the payload.
     */
    public function test_trigger_passes_payload(): void
    {
        $receivedPayload = null;

        Event::on('data_event', function ($data) use (&$receivedPayload) {
            $receivedPayload = $data;
        });

        Event::trigger('data_event', ['key' => 'value']);

        $this->assertEquals(['key' => 'value'], $receivedPayload, 'Listener should receive payload');
    }

    /**
     * Test that lower priority listeners fire first.
     */
    public function test_priority_order(): void
    {
        $order = [];

        Event::on('priority_event', function () use (&$order) {
            $order[] = 'priority_10';
        }, 10);

        Event::on('priority_event', function () use (&$order) {
            $order[] = 'priority_1';
        }, 1);

        Event::on('priority_event', function () use (&$order) {
            $order[] = 'priority_20';
        }, 20);

        Event::trigger('priority_event');

        $this->assertEquals(['priority_1', 'priority_10', 'priority_20'], $order, 'Listeners should fire in priority order (low to high)');
    }

    /**
     * Test that off() removes a listener.
     */
    public function test_off_removes_listener(): void
    {
        $called = false;

        $callback = function () use (&$called) {
            $called = true;
        };

        Event::on('removable_event', $callback);
        Event::off('removable_event', $callback);
        Event::trigger('removable_event');

        $this->assertFalse($called, 'Removed listener should not be called');
    }

    /**
     * Test that filter() passes value through callbacks.
     */
    public function test_filter_modifies_value(): void
    {
        Event::on('modify_value', function (string $value): string {
            return $value . '_modified';
        });

        Event::on('modify_value', function (string $value): string {
            return $value . '_again';
        });

        $result = Event::filter('modify_value', 'original');

        $this->assertEquals('original_modified_again', $result, 'Filter should chain modifications');
    }

    /**
     * Test that filter returns original value if no listeners.
     */
    public function test_filter_returns_original_if_no_listeners(): void
    {
        $result = Event::filter('no_listeners', 'unchanged');

        $this->assertEquals('unchanged', $result, 'Filter should return original value if no listeners');
    }

    /**
     * Test that hasListeners() works correctly.
     */
    public function test_has_listeners(): void
    {
        $this->assertFalse(Event::hasListeners('empty_event'), 'Should return false for event with no listeners');

        Event::on('has_event', function () {});

        $this->assertTrue(Event::hasListeners('has_event'), 'Should return true for event with listeners');
    }

    /**
     * Test that clear() removes all listeners.
     */
    public function test_clear_removes_all_listeners(): void
    {
        Event::on('clear_test', function () {});
        Event::on('clear_test_2', function () {});

        Event::clear();

        $this->assertFalse(Event::hasListeners('clear_test'), 'All listeners should be cleared');
        $this->assertFalse(Event::hasListeners('clear_test_2'), 'All listeners should be cleared');
    }

    /**
     * Test that clear() can target a specific event.
     */
    public function test_clear_specific_event(): void
    {
        Event::on('event_a', function () {});
        Event::on('event_b', function () {});

        Event::clear('event_a');

        $this->assertFalse(Event::hasListeners('event_a'), 'event_a should be cleared');
        $this->assertTrue(Event::hasListeners('event_b'), 'event_b should still have listeners');
    }
}
