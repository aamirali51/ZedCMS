<?php

declare(strict_types=1);

/**
 * ZedCMS Test Case Base Class
 * 
 * A minimal test case class providing assertion helpers.
 * No dependencies, no Composer—just pure PHP.
 */
abstract class TestCase
{
    /**
     * Track test results.
     * @var array{passed: int, failed: int, errors: array<string>}
     */
    public array $results = [
        'passed' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    /**
     * Assert that a condition is true.
     */
    protected function assertTrue(bool $condition, string $message = ''): void
    {
        if ($condition === true) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $this->results['errors'][] = $message ?: 'Expected true, got false';
        }
    }

    /**
     * Assert that a condition is false.
     */
    protected function assertFalse(bool $condition, string $message = ''): void
    {
        if ($condition === false) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $this->results['errors'][] = $message ?: 'Expected false, got true';
        }
    }

    /**
     * Assert that two values are equal.
     */
    protected function assertEquals(mixed $expected, mixed $actual, string $message = ''): void
    {
        if ($expected === $actual) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $expectedStr = var_export($expected, true);
            $actualStr = var_export($actual, true);
            $this->results['errors'][] = $message ?: "Expected {$expectedStr}, got {$actualStr}";
        }
    }

    /**
     * Assert that a value is not null.
     */
    protected function assertNotNull(mixed $value, string $message = ''): void
    {
        if ($value !== null) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $this->results['errors'][] = $message ?: 'Expected non-null value, got null';
        }
    }

    /**
     * Assert that a value is null.
     */
    protected function assertNull(mixed $value, string $message = ''): void
    {
        if ($value === null) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $valueStr = var_export($value, true);
            $this->results['errors'][] = $message ?: "Expected null, got {$valueStr}";
        }
    }

    /**
     * Assert that a value is an instance of a class.
     */
    protected function assertInstanceOf(string $class, mixed $object, string $message = ''): void
    {
        if ($object instanceof $class) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $actualType = is_object($object) ? get_class($object) : gettype($object);
            $this->results['errors'][] = $message ?: "Expected instance of {$class}, got {$actualType}";
        }
    }

    /**
     * Assert that an array contains a specific key.
     */
    protected function assertArrayHasKey(string|int $key, array $array, string $message = ''): void
    {
        if (array_key_exists($key, $array)) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $this->results['errors'][] = $message ?: "Expected array to have key '{$key}'";
        }
    }

    /**
     * Assert that a count matches.
     */
    protected function assertCount(int $expected, array|Countable $haystack, string $message = ''): void
    {
        $actual = count($haystack);
        if ($expected === $actual) {
            $this->results['passed']++;
        } else {
            $this->results['failed']++;
            $this->results['errors'][] = $message ?: "Expected count {$expected}, got {$actual}";
        }
    }

    /**
     * Setup method called before each test.
     * Override in subclasses if needed.
     */
    public function setUp(): void
    {
        // Override in subclasses
    }

    /**
     * Teardown method called after each test.
     * Override in subclasses if needed.
     */
    public function tearDown(): void
    {
        // Override in subclasses
    }
}
