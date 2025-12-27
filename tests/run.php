<?php

declare(strict_types=1);

/**
 * ZedCMS Test Runner
 * 
 * A minimal, zero-dependency test runner.
 * Discovers *Test.php files and executes public test_* methods.
 * 
 * Usage: php tests/run.php
 */

// Change to project root
chdir(dirname(__DIR__));

// Autoload core classes
spl_autoload_register(function (string $class): void {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// Load the TestCase base class
require_once __DIR__ . '/TestCase.php';

/**
 * ANSI color codes for terminal output.
 */
function colorize(string $text, string $color): string
{
    // Disable colors on Windows unless ANSICON is present
    $hasColors = DIRECTORY_SEPARATOR !== '\\' || getenv('ANSICON') !== false;
    
    if (!$hasColors) {
        return $text;
    }

    $colors = [
        'green'  => "\033[32m",
        'red'    => "\033[31m",
        'yellow' => "\033[33m",
        'cyan'   => "\033[36m",
        'reset'  => "\033[0m",
    ];

    return ($colors[$color] ?? '') . $text . $colors['reset'];
}

/**
 * Discover all test files recursively.
 */
function discoverTests(string $directory): array
{
    $tests = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), 'Test.php')) {
            $tests[] = $file->getPathname();
        }
    }

    return $tests;
}

/**
 * Run a single test class.
 */
function runTestClass(string $filePath): array
{
    require_once $filePath;

    // Extract class name from file
    $content = file_get_contents($filePath);
    if (!preg_match('/class\s+(\w+)\s+extends\s+TestCase/', $content, $matches)) {
        return ['skipped' => true, 'reason' => 'No TestCase class found'];
    }

    $className = $matches[1];
    
    // Handle namespaced classes
    if (preg_match('/namespace\s+([\w\\\\]+);/', $content, $nsMatches)) {
        $className = $nsMatches[1] . '\\' . $className;
    }

    if (!class_exists($className)) {
        return ['skipped' => true, 'reason' => "Class {$className} not found"];
    }

    $testCase = new $className();
    $reflection = new ReflectionClass($testCase);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

    $results = [
        'class' => $className,
        'tests' => [],
        'passed' => 0,
        'failed' => 0,
    ];

    foreach ($methods as $method) {
        if (str_starts_with($method->getName(), 'test_')) {
            $testName = $method->getName();
            
            try {
                // Reset results for this specific test
                $testCase->results = ['passed' => 0, 'failed' => 0, 'errors' => []];
                
                // Run setUp, test, tearDown
                $testCase->setUp();
                $testCase->{$testName}();
                $testCase->tearDown();

                $testResult = [
                    'name' => $testName,
                    'passed' => $testCase->results['failed'] === 0,
                    'assertions' => $testCase->results['passed'] + $testCase->results['failed'],
                    'errors' => $testCase->results['errors'],
                ];

                if ($testResult['passed']) {
                    $results['passed']++;
                } else {
                    $results['failed']++;
                }

                $results['tests'][] = $testResult;

            } catch (Throwable $e) {
                $results['failed']++;
                $results['tests'][] = [
                    'name' => $testName,
                    'passed' => false,
                    'assertions' => 0,
                    'errors' => [$e->getMessage()],
                ];
            }
        }
    }

    return $results;
}

// ============================================================================
// MAIN EXECUTION
// ============================================================================

echo "\n";
echo colorize("╔═══════════════════════════════════════════════╗\n", 'cyan');
echo colorize("║         ZedCMS Test Suite                     ║\n", 'cyan');
echo colorize("╚═══════════════════════════════════════════════╝\n", 'cyan');
echo "\n";

$testsDir = __DIR__;
$testFiles = discoverTests($testsDir);

if (empty($testFiles)) {
    echo colorize("No test files found in {$testsDir}\n", 'yellow');
    exit(0);
}

$totalPassed = 0;
$totalFailed = 0;
$totalTests = 0;

foreach ($testFiles as $testFile) {
    $relativePath = str_replace(dirname(__DIR__) . DIRECTORY_SEPARATOR, '', $testFile);
    echo colorize("► {$relativePath}\n", 'cyan');

    $results = runTestClass($testFile);

    if (isset($results['skipped'])) {
        echo colorize("  ⊘ Skipped: {$results['reason']}\n", 'yellow');
        continue;
    }

    foreach ($results['tests'] as $test) {
        $totalTests++;
        $status = $test['passed'] 
            ? colorize('✓ PASS', 'green') 
            : colorize('✗ FAIL', 'red');
        
        echo "  {$status} {$test['name']}";
        
        if (!empty($test['assertions'])) {
            echo " ({$test['assertions']} assertions)";
        }
        echo "\n";

        if (!$test['passed'] && !empty($test['errors'])) {
            foreach ($test['errors'] as $error) {
                echo colorize("    → {$error}\n", 'red');
            }
        }
    }

    $totalPassed += $results['passed'];
    $totalFailed += $results['failed'];
    echo "\n";
}

// Summary
echo colorize("═══════════════════════════════════════════════\n", 'cyan');
echo "Total: {$totalTests} tests, ";
echo colorize("{$totalPassed} passed", 'green');
echo ", ";
echo ($totalFailed > 0 ? colorize("{$totalFailed} failed", 'red') : "{$totalFailed} failed");
echo "\n\n";

exit($totalFailed > 0 ? 1 : 0);
