<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/TestCase.php';
require_once dirname(__DIR__, 2) . '/core/Router.php';

use Core\Router;

/**
 * Unit tests for Core\Router
 */
class RouterTest extends TestCase
{
    /**
     * Test that normalizeUri removes query strings.
     */
    public function test_normalize_uri_removes_query_string(): void
    {
        $result = Router::normalizeUri('/page?foo=bar&baz=qux');

        $this->assertEquals('/page', $result, 'Query string should be removed');
    }

    /**
     * Test that normalizeUri removes trailing slashes.
     */
    public function test_normalize_uri_removes_trailing_slash(): void
    {
        $result = Router::normalizeUri('/blog/post/');

        $this->assertEquals('/blog/post', $result, 'Trailing slash should be removed');
    }

    /**
     * Test that root path stays as root.
     */
    public function test_normalize_uri_keeps_root(): void
    {
        $result = Router::normalizeUri('/');

        $this->assertEquals('/', $result, 'Root path should remain as /');
    }

    /**
     * Test that normalizeUri ensures leading slash.
     */
    public function test_normalize_uri_adds_leading_slash(): void
    {
        $result = Router::normalizeUri('page');

        $this->assertEquals('/page', $result, 'Leading slash should be added');
    }

    /**
     * Test getSegments splits URI correctly.
     */
    public function test_get_segments(): void
    {
        $result = Router::getSegments('/blog/post/123');

        $this->assertEquals(['blog', 'post', '123'], $result, 'URI should be split into segments');
    }

    /**
     * Test getSegments with root path.
     */
    public function test_get_segments_root(): void
    {
        $result = Router::getSegments('/');

        $this->assertCount(0, $result, 'Root path should return empty segments');
    }

    /**
     * Test matchPattern with simple placeholder.
     */
    public function test_match_pattern_simple(): void
    {
        $result = Router::matchPattern('/user/{id}', '/user/123');

        $this->assertNotNull($result, 'Pattern should match');
        $this->assertEquals(['id' => '123'], $result, 'Should extract id parameter');
    }

    /**
     * Test matchPattern with multiple placeholders.
     */
    public function test_match_pattern_multiple(): void
    {
        $result = Router::matchPattern('/blog/{year}/{slug}', '/blog/2024/hello-world');

        $this->assertNotNull($result, 'Pattern should match');
        $this->assertEquals(['year' => '2024', 'slug' => 'hello-world'], $result, 'Should extract both parameters');
    }

    /**
     * Test matchPattern returns false on no match.
     */
    public function test_match_pattern_no_match(): void
    {
        $result = Router::matchPattern('/user/{id}', '/posts/123');

        $this->assertFalse($result, 'Pattern should not match different path');
    }

    /**
     * Test matchPattern with static route.
     */
    public function test_match_pattern_static(): void
    {
        $result = Router::matchPattern('/admin/login', '/admin/login');

        $this->assertEquals([], $result, 'Static route should match with empty params');
    }

    /**
     * Test matchPattern static route no match.
     */
    public function test_match_pattern_static_no_match(): void
    {
        $result = Router::matchPattern('/admin/login', '/admin/logout');

        $this->assertFalse($result, 'Static route should not match different path');
    }

    /**
     * Test url() generates correct URLs.
     */
    public function test_url_generation(): void
    {
        // Note: This depends on getBasePath() which reads from server vars
        // In a test environment without a server, it should return the path as-is
        $path = '/admin/dashboard';
        $result = Router::url($path);

        // The URL should at least contain the path
        $this->assertTrue(str_contains($result, '/admin/dashboard'), 'Generated URL should contain the path');
    }
}
