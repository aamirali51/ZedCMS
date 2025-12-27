<?php
/**
 * Aurora Pro Demo Content Importer
 * 
 * Creates 15 sample posts with rich content, images, and videos
 * to showcase the theme's capabilities.
 * 
 * Run this script once: http://localhost/ZedCMS/content/themes/aurora-pro/import-demo.php
 */

declare(strict_types=1);

// Bootstrap the CMS
require_once __DIR__ . '/../../../index.php';

use Core\Database;

/**
 * Convert HTML content to BlockNote JSON blocks
 * This ensures content is saved in the proper format for the editor
 */
function htmlToBlockNoteBlocks(string $html): array {
    $blocks = [];
    
    // Parse the HTML
    $doc = new DOMDocument();
    @$doc->loadHTML('<meta charset="utf-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    $body = $doc->getElementsByTagName('body')->item(0);
    if (!$body) {
        $body = $doc->documentElement;
    }
    
    if ($body) {
        foreach ($body->childNodes as $node) {
            $block = nodeToBlock($node);
            if ($block) {
                $blocks[] = $block;
            }
        }
    }
    
    // Ensure at least one block
    if (empty($blocks)) {
        $blocks[] = createParagraphBlock('');
    }
    
    return $blocks;
}

function nodeToBlock(DOMNode $node): ?array {
    if ($node->nodeType === XML_TEXT_NODE) {
        $text = trim($node->textContent);
        if (!empty($text)) {
            return createParagraphBlock($text);
        }
        return null;
    }
    
    if ($node->nodeType !== XML_ELEMENT_NODE) {
        return null;
    }
    
    $tagName = strtolower($node->nodeName);
    
    switch ($tagName) {
        case 'h1':
        case 'h2':
        case 'h3':
        case 'h4':
        case 'h5':
        case 'h6':
            $level = (int) substr($tagName, 1);
            return createHeadingBlock(getNodeText($node), $level);
            
        case 'p':
            return createParagraphBlock(getInlineContent($node));
            
        case 'blockquote':
            // Create paragraph with special styling for quotes
            return createParagraphBlock(getNodeText($node));
            
        case 'ul':
            return createListBlocks($node, 'bulletListItem');
            
        case 'ol':
            return createListBlocks($node, 'numberedListItem');
            
        case 'li':
            return createParagraphBlock(getNodeText($node));
            
        case 'pre':
            $code = $node->getElementsByTagName('code')->item(0);
            $text = $code ? $code->textContent : $node->textContent;
            return createCodeBlock($text);
            
        case 'img':
            $src = $node->getAttribute('src');
            if ($src) {
                return createImageBlock($src, $node->getAttribute('alt') ?? '');
            }
            return null;
            
        case 'iframe':
            $src = $node->getAttribute('src');
            if ($src && strpos($src, 'youtube') !== false) {
                return createVideoBlock($src);
            }
            return null;
            
        default:
            // Try to extract text content
            $text = trim($node->textContent);
            if (!empty($text)) {
                return createParagraphBlock($text);
            }
            return null;
    }
}

function getNodeText(DOMNode $node): string {
    return trim($node->textContent);
}

function getInlineContent(DOMNode $node): string {
    // For now, just get text content
    // TODO: Handle bold, italic, links etc.
    return trim($node->textContent);
}

function createParagraphBlock(string $text): array {
    return [
        'id' => uniqid('block_'),
        'type' => 'paragraph',
        'props' => [
            'textColor' => 'default',
            'backgroundColor' => 'default',
            'textAlignment' => 'left'
        ],
        'content' => $text ? [['type' => 'text', 'text' => $text, 'styles' => []]] : [],
        'children' => []
    ];
}

function createHeadingBlock(string $text, int $level): array {
    return [
        'id' => uniqid('block_'),
        'type' => 'heading',
        'props' => [
            'textColor' => 'default',
            'backgroundColor' => 'default',
            'textAlignment' => 'left',
            'level' => min(max($level, 1), 3) // BlockNote supports levels 1-3
        ],
        'content' => [['type' => 'text', 'text' => $text, 'styles' => []]],
        'children' => []
    ];
}

function createListBlocks(DOMNode $list, string $type): ?array {
    // Get first list item and return as single block
    $items = [];
    foreach ($list->childNodes as $li) {
        if ($li->nodeName === 'li') {
            $items[] = [
                'id' => uniqid('block_'),
                'type' => $type,
                'props' => [
                    'textColor' => 'default',
                    'backgroundColor' => 'default',
                    'textAlignment' => 'left'
                ],
                'content' => [['type' => 'text', 'text' => trim($li->textContent), 'styles' => []]],
                'children' => []
            ];
        }
    }
    return $items[0] ?? null; // Return first item only for now
}

function createCodeBlock(string $code): array {
    return [
        'id' => uniqid('block_'),
        'type' => 'codeBlock',
        'props' => [
            'language' => 'javascript'
        ],
        'content' => [['type' => 'text', 'text' => $code, 'styles' => []]],
        'children' => []
    ];
}

function createImageBlock(string $url, string $caption = ''): array {
    return [
        'id' => uniqid('block_'),
        'type' => 'image',
        'props' => [
            'url' => $url,
            'caption' => $caption,
            'width' => 'auto'
        ],
        'content' => [],
        'children' => []
    ];
}

function createVideoBlock(string $url): array {
    return [
        'id' => uniqid('block_'),
        'type' => 'video',
        'props' => [
            'url' => $url
        ],
        'content' => [],
        'children' => []
    ];
}

// Check if already imported (allow force with ?force=1)
$db = Database::getInstance();
$existing = $db->queryValue("SELECT COUNT(*) FROM zed_content WHERE slug LIKE 'demo-%'");

if ($existing > 0 && !isset($_GET['force'])) {
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Demo Content</title><script src='https://cdn.tailwindcss.com'></script></head>";
    echo "<body class='bg-gray-50 min-h-screen flex items-center justify-center p-6'><div class='max-w-lg w-full bg-white rounded-2xl shadow-xl p-8 text-center'>";
    echo "<h2 class='text-2xl font-bold text-gray-900 mb-4'>Demo content already exists ({$existing} posts)</h2>";
    echo "<div class='flex gap-4 justify-center'>";
    echo "<a href='/ZedCMS/' class='px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700'>View Site</a>";
    echo "<a href='/ZedCMS/admin' class='px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200'>Admin Panel</a>";
    echo "<a href='?force=1' class='px-6 py-3 bg-red-100 text-red-700 font-semibold rounded-xl hover:bg-red-200'>Re-import</a>";
    echo "</div></div></body></html>";
    exit;
}

// Delete existing demo content if force
if (isset($_GET['force']) && $existing > 0) {
    $db->query("DELETE FROM zed_content WHERE slug LIKE 'demo-%'");
}

// Sample categories
$categories = [
    ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Latest tech news and tutorials'],
    ['name' => 'Design', 'slug' => 'design', 'description' => 'UI/UX and graphic design articles'],
    ['name' => 'Travel', 'slug' => 'travel', 'description' => 'Travel guides and destinations'],
    ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Life tips and personal growth'],
    ['name' => 'Business', 'slug' => 'business', 'description' => 'Entrepreneurship and marketing'],
];

// Insert categories
foreach ($categories as $cat) {
    try {
        $db->query(
            "INSERT INTO zed_categories (name, slug, description) VALUES (:name, :slug, :desc) 
             ON DUPLICATE KEY UPDATE name = name",
            ['name' => $cat['name'], 'slug' => $cat['slug'], 'desc' => $cat['description']]
        );
    } catch (Exception $e) {
        // Category might exist
    }
}

// High-quality placeholder images from Unsplash
$images = [
    'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1200&h=800&fit=crop', // Tech laptop
    'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1200&h=800&fit=crop', // Design colors
    'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1200&h=800&fit=crop', // Travel map
    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1200&h=800&fit=crop', // Portrait
    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=800&fit=crop', // Business charts
    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&h=800&fit=crop', // Code
    'https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=1200&h=800&fit=crop', // Workspace
    'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1200&h=800&fit=crop', // Mountains
    'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&h=800&fit=crop', // Team
    'https://images.unsplash.com/photo-1504805572947-34fad45aed93?w=1200&h=800&fit=crop', // Motivation
    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1200&h=800&fit=crop', // Tech team
    'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=1200&h=800&fit=crop', // Meeting
    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&h=800&fit=crop', // Beach
    'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=1200&h=800&fit=crop', // Purple tech
    'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1200&h=800&fit=crop', // Developer
];

// Sample video embeds
$videos = [
    '<iframe width="100%" height="400" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>',
    '<iframe width="100%" height="400" src="https://www.youtube.com/embed/jNQXAC9IVRw" frameborder="0" allowfullscreen></iframe>',
];

// Demo posts data
$demoPosts = [
    [
        'title' => 'Getting Started with Modern Web Development in 2024',
        'slug' => 'demo-modern-web-development-2024',
        'type' => 'post',
        'category' => 'technology',
        'excerpt' => 'A comprehensive guide to the latest web development tools, frameworks, and best practices for building modern applications.',
        'content' => '<h2>The Modern Web Development Landscape</h2>
<p>Web development has evolved dramatically over the past few years. From static HTML pages to complex single-page applications, the tools and techniques we use today would have seemed like science fiction just a decade ago.</p>

<blockquote>
<p>"The web is not just about documents anymore. It\'s about applications, experiences, and connections."</p>
</blockquote>

<h3>Essential Tools for 2024</h3>
<p>Here are the must-have tools every web developer should know:</p>

<ul>
<li><strong>React or Vue.js</strong> - For building interactive UIs</li>
<li><strong>TypeScript</strong> - For type-safe JavaScript</li>
<li><strong>Tailwind CSS</strong> - For utility-first styling</li>
<li><strong>Node.js</strong> - For server-side JavaScript</li>
</ul>

<h3>Getting Started</h3>
<p>The best way to learn is by building. Start with a simple project and gradually add complexity as you become more comfortable with the tools.</p>

<pre><code>// Example: Simple React component
function Welcome({ name }) {
    return &lt;h1&gt;Hello, {name}!&lt;/h1&gt;;
}</code></pre>

<p>Remember, the goal isn\'t to learn everything at once. Focus on fundamentals and build from there.</p>',
        'image' => 0,
    ],
    [
        'title' => 'The Art of Minimalist Design: Less is More',
        'slug' => 'demo-minimalist-design-principles',
        'type' => 'post',
        'category' => 'design',
        'excerpt' => 'Discover how minimalist design principles can create more impactful and user-friendly digital experiences.',
        'content' => '<h2>Embracing Simplicity</h2>
<p>In a world of information overload, minimalist design offers a breath of fresh air. By stripping away the unnecessary, we can create experiences that truly resonate with users.</p>

<p>Minimalism isn\'t about having less—it\'s about making room for more of what matters.</p>

<h3>Key Principles</h3>
<ol>
<li><strong>White Space</strong> - Give elements room to breathe</li>
<li><strong>Typography</strong> - Let beautiful type speak for itself</li>
<li><strong>Color</strong> - Use it sparingly for maximum impact</li>
<li><strong>Hierarchy</strong> - Guide users with clear visual structure</li>
</ol>

<h3>Examples That Inspire</h3>
<p>Look at brands like Apple, Muji, and Airbnb. They\'ve mastered the art of saying more with less. Their interfaces feel calm, focused, and purposeful.</p>

<blockquote>
<p>"Perfection is achieved not when there is nothing more to add, but when there is nothing left to take away." — Antoine de Saint-Exupéry</p>
</blockquote>

<p>Start your minimalist journey today by auditing your current designs. What can you remove while still maintaining clarity?</p>',
        'image' => 1,
    ],
    [
        'title' => 'Exploring the Hidden Gems of Portugal',
        'slug' => 'demo-hidden-gems-portugal',
        'type' => 'post',
        'category' => 'travel',
        'excerpt' => 'Beyond Lisbon and Porto lies a Portugal few tourists ever see. Join us on a journey through ancient villages and stunning coastlines.',
        'content' => '<h2>Discover Portugal\'s Best-Kept Secrets</h2>
<p>While most tourists flock to Lisbon\'s cobblestone streets and Porto\'s wine cellars, Portugal has so much more to offer the adventurous traveler willing to venture off the beaten path.</p>

<h3>1. Monsanto - The Boulder Village</h3>
<p>Imagine a village built between giant boulders, where houses use massive rocks as walls and roofs. That\'s Monsanto, officially named "the most Portuguese village in Portugal."</p>

<h3>2. The Azores Islands</h3>
<p>In the middle of the Atlantic Ocean, these nine volcanic islands offer:</p>
<ul>
<li>Stunning crater lakes</li>
<li>Hot springs in the ocean</li>
<li>World-class whale watching</li>
<li>Dramatic volcanic landscapes</li>
</ul>

<h3>3. Marvão - The Eagle\'s Nest</h3>
<p>Perched at 800 meters above sea level, this medieval fortress town offers panoramic views stretching to Spain. The sunset here is absolutely magical.</p>

<h3>Travel Tips</h3>
<p>The best time to visit these locations is during spring (April-May) or fall (September-October) when the weather is pleasant and crowds are thin.</p>

<p>Rent a car to truly explore—Portugal\'s roads are excellent, and the journey is half the adventure!</p>',
        'image' => 2,
    ],
    [
        'title' => 'Building Habits That Actually Stick',
        'slug' => 'demo-building-habits-that-stick',
        'type' => 'post',
        'category' => 'lifestyle',
        'excerpt' => 'Learn the science-backed strategies for creating lasting positive habits and breaking the ones holding you back.',
        'content' => '<h2>The Science of Habit Formation</h2>
<p>We all have habits we want to build and habits we want to break. But why is change so hard? The answer lies in how our brains work.</p>

<h3>The Habit Loop</h3>
<p>Every habit consists of three parts:</p>
<ol>
<li><strong>Cue</strong> - The trigger that initiates the behavior</li>
<li><strong>Routine</strong> - The behavior itself</li>
<li><strong>Reward</strong> - The benefit you get from the behavior</li>
</ol>

<h3>Strategies That Work</h3>

<h4>Start Incredibly Small</h4>
<p>Want to exercise more? Start with just 2 minutes. Seriously. The goal isn\'t the exercise—it\'s building the neural pathway.</p>

<h4>Stack Your Habits</h4>
<p>Attach new habits to existing ones. "After I pour my morning coffee, I will write in my journal for 5 minutes."</p>

<h4>Design Your Environment</h4>
<p>Make good habits easy and bad habits hard. Want to eat healthier? Keep fruit on the counter and hide the chips.</p>

<blockquote>
<p>"You do not rise to the level of your goals. You fall to the level of your systems." — James Clear</p>
</blockquote>

<h3>The 21-Day Myth</h3>
<p>Contrary to popular belief, habits take an average of 66 days to form—not 21. Be patient with yourself!</p>',
        'image' => 3,
    ],
    [
        'title' => 'The Future of Remote Work: Trends and Predictions',
        'slug' => 'demo-future-remote-work',
        'type' => 'post',
        'category' => 'business',
        'excerpt' => 'How remote work is reshaping the business landscape and what leaders need to know to stay ahead.',
        'content' => '<h2>Remote Work is Here to Stay</h2>
<p>The global shift to remote work that began in 2020 has permanently changed how we think about offices, productivity, and work-life balance.</p>

<h3>Key Trends Shaping the Future</h3>

<h4>1. Hybrid is the New Normal</h4>
<p>Most companies are settling on hybrid models—typically 2-3 days in office, with the rest remote. This offers the best of both worlds.</p>

<h4>2. Global Talent Pools</h4>
<p>Companies are no longer limited to hiring within commuting distance. The best talent could be anywhere in the world.</p>

<h4>3. Results Over Hours</h4>
<p>The focus is shifting from time spent to outcomes delivered. Async communication is becoming the norm.</p>

<h3>Challenges to Address</h3>
<ul>
<li>Maintaining company culture remotely</li>
<li>Preventing burnout from always-on availability</li>
<li>Ensuring equitable opportunities for remote workers</li>
<li>Building trust without physical presence</li>
</ul>

<h3>Tools for Success</h3>
<p>Successful remote teams invest in:</p>
<ul>
<li>Robust communication platforms (Slack, Teams)</li>
<li>Project management tools (Notion, Asana)</li>
<li>Virtual collaboration spaces (Miro, Figma)</li>
<li>Strong documentation practices</li>
</ul>

<p>The companies that thrive will be those that embrace flexibility while maintaining strong connections with their people.</p>',
        'image' => 4,
    ],
    [
        'title' => 'Mastering CSS Grid: A Complete Visual Guide',
        'slug' => 'demo-mastering-css-grid',
        'type' => 'post',
        'category' => 'technology',
        'excerpt' => 'Everything you need to know about CSS Grid Layout, from basic concepts to advanced techniques with practical examples.',
        'content' => '<h2>Why CSS Grid Changes Everything</h2>
<p>CSS Grid is the most powerful layout system available in CSS. It\'s a 2-dimensional system, meaning it can handle both columns and rows simultaneously.</p>

<h3>Basic Concepts</h3>
<pre><code>.container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 20px;
}</code></pre>

<p>This simple code creates a three-column layout with equal widths and 20px gaps between items.</p>

<h3>Key Properties</h3>
<ul>
<li><strong>grid-template-columns</strong> - Define column sizes</li>
<li><strong>grid-template-rows</strong> - Define row sizes</li>
<li><strong>grid-gap</strong> - Space between items</li>
<li><strong>grid-area</strong> - Place items in specific areas</li>
</ul>

<h3>Advanced Techniques</h3>

<h4>Auto-fit vs Auto-fill</h4>
<pre><code>/* Responsive columns that wrap */
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));</code></pre>

<h4>Named Grid Lines</h4>
<p>You can name grid lines for easier placement:</p>
<pre><code>grid-template-columns: [sidebar-start] 300px [sidebar-end main-start] 1fr [main-end];</code></pre>

<h3>Browser Support</h3>
<p>CSS Grid is supported in all modern browsers. For older browsers, consider using feature queries with @supports.</p>

<p>Start experimenting with Grid today—you\'ll wonder how you ever lived without it!</p>',
        'image' => 5,
    ],
    [
        'title' => 'Creating the Perfect Home Office Setup',
        'slug' => 'demo-perfect-home-office',
        'type' => 'post',
        'category' => 'lifestyle',
        'excerpt' => 'Transform any space into a productive and comfortable home office with these expert tips and recommendations.',
        'content' => '<h2>Your Space, Your Productivity</h2>
<p>A well-designed home office can dramatically improve your focus, creativity, and overall work satisfaction. Here\'s how to create yours.</p>

<h3>Essential Equipment</h3>

<h4>The Chair</h4>
<p>Invest in ergonomics. A quality chair with proper lumbar support is worth every penny—you\'ll spend thousands of hours in it.</p>

<h4>The Desk</h4>
<p>Consider a standing desk or sit-stand converter. Alternating between sitting and standing throughout the day has proven health benefits.</p>

<h4>The Monitor</h4>
<p>If you\'re serious about productivity, a large external monitor (or two) makes a huge difference. 27" is the sweet spot for most people.</p>

<h3>Lighting Matters</h3>
<ul>
<li>Position your desk near natural light (but avoid glare on screens)</li>
<li>Add a desk lamp for task lighting</li>
<li>Consider bias lighting behind your monitor to reduce eye strain</li>
</ul>

<h3>The Little Things</h3>
<ul>
<li>Plants add life and improve air quality</li>
<li>A good pair of headphones for focus</li>
<li>Cable management for a clean look</li>
<li>Personal touches that inspire you</li>
</ul>

<blockquote>
<p>"Your environment shapes your behavior. Design it intentionally."</p>
</blockquote>

<p>Remember: the best home office is one that works for YOUR needs. Start simple and iterate.</p>',
        'image' => 6,
    ],
    [
        'title' => 'A Week in the Swiss Alps: An Adventure Guide',
        'slug' => 'demo-swiss-alps-adventure',
        'type' => 'post',
        'category' => 'travel',
        'excerpt' => 'Experience the majesty of the Swiss Alps with this day-by-day itinerary covering hiking, culture, and breathtaking views.',
        'content' => '<h2>The Ultimate Swiss Alps Experience</h2>
<p>The Swiss Alps are a bucket-list destination for good reason. Towering peaks, crystal-clear lakes, charming villages, and world-class infrastructure make this region perfect for adventurers of all levels.</p>

<h3>Day 1-2: Zermatt and the Matterhorn</h3>
<p>Start your journey in Zermatt, home of the iconic Matterhorn. This car-free village maintains its traditional charm while offering modern amenities.</p>
<ul>
<li>Take the Gornergrat railway for panoramic views</li>
<li>Hike the Five Lakes Trail (Seenweg)</li>
<li>Enjoy fondue at a traditional restaurant</li>
</ul>

<h3>Day 3-4: Interlaken and Beyond</h3>
<p>The adventure capital of Switzerland sits between two stunning lakes. This is where adrenaline junkies come to play.</p>
<ul>
<li>Paragliding over the lakes</li>
<li>Day trip to Jungfraujoch—the "Top of Europe"</li>
<li>Kayaking on Lake Brienz</li>
</ul>

<h3>Day 5-6: Lucerne and Mount Pilatus</h3>
<p>End your trip in the picturesque city of Lucerne, with its Chapel Bridge and stunning mountain backdrop.</p>

<h3>Practical Tips</h3>
<ul>
<li>Get the Swiss Travel Pass for unlimited train travel</li>
<li>Book mountain railway tickets in advance during peak season</li>
<li>Pack layers—weather changes quickly in the mountains</li>
<li>Budget generously—Switzerland is expensive!</li>
</ul>

<p>Whether you\'re seeking adventure or tranquility, the Swiss Alps deliver unforgettable experiences.</p>',
        'image' => 7,
    ],
    [
        'title' => 'The Complete Guide to Team Collaboration Tools',
        'slug' => 'demo-team-collaboration-tools',
        'type' => 'post',
        'category' => 'business',
        'excerpt' => 'Navigate the landscape of collaboration tools and find the perfect stack for your team\'s needs.',
        'content' => '<h2>Choosing the Right Tools</h2>
<p>With hundreds of collaboration tools available, choosing the right ones for your team can be overwhelming. Let\'s break it down.</p>

<h3>Communication</h3>
<h4>Slack</h4>
<p>The gold standard for team chat. Channels, threads, and integrations make it powerful. Best for: Tech teams and startups.</p>

<h4>Microsoft Teams</h4>
<p>Deep integration with Microsoft 365. Best for: Enterprise and Microsoft-centric organizations.</p>

<h3>Project Management</h3>
<h4>Notion</h4>
<p>The all-in-one workspace. Documents, databases, and wikis in one tool. Incredibly flexible.</p>

<h4>Asana</h4>
<p>Purpose-built for project management. Great for complex projects with many dependencies.</p>

<h4>Linear</h4>
<p>Modern issue tracking designed for speed. Best for: Development teams.</p>

<h3>Visual Collaboration</h3>
<h4>Figma</h4>
<p>Not just for designers anymore. FigJam has become a go-to for remote brainstorming.</p>

<h4>Miro</h4>
<p>Infinite whiteboard for any purpose. Workshops, planning, and ideation.</p>

<h3>Building Your Stack</h3>
<p>The best tool stack is one your team will actually use. Consider:</p>
<ul>
<li>Integration between tools</li>
<li>Learning curve</li>
<li>Cost at scale</li>
<li>Mobile experience</li>
</ul>

<p>Start with fewer tools and add as needs arise. Too many tools leads to fragmentation and frustration.</p>',
        'image' => 8,
    ],
    [
        'title' => 'Mindfulness for Busy Professionals',
        'slug' => 'demo-mindfulness-professionals',
        'type' => 'post',
        'category' => 'lifestyle',
        'excerpt' => 'Practical mindfulness techniques you can use even with the busiest schedule to reduce stress and increase focus.',
        'content' => '<h2>Finding Calm in the Chaos</h2>
<p>You don\'t need an hour of meditation to benefit from mindfulness. Even small moments of presence can transform your day.</p>

<h3>The 2-Minute Reset</h3>
<p>Between meetings or tasks, try this quick reset:</p>
<ol>
<li>Close your eyes</li>
<li>Take 5 deep breaths</li>
<li>Notice your body in the chair</li>
<li>Set an intention for the next hour</li>
</ol>

<h3>Mindful Transitions</h3>
<p>Use transitions as mindfulness triggers:</p>
<ul>
<li>Before opening your laptop, pause and breathe</li>
<li>When your phone rings, take one breath before answering</li>
<li>As you walk to a meeting, feel your feet on the floor</li>
</ul>

<h3>The STOP Technique</h3>
<p>When stress builds, remember STOP:</p>
<ul>
<li><strong>S</strong>top what you\'re doing</li>
<li><strong>T</strong>ake a breath</li>
<li><strong>O</strong>bserve your experience</li>
<li><strong>P</strong>roceed with awareness</li>
</ul>

<blockquote>
<p>"Mindfulness is not about getting anywhere else. It\'s about being where you are and knowing it." — Jon Kabat-Zinn</p>
</blockquote>

<h3>Building the Habit</h3>
<p>Start with just one mindful moment per day. Consistency beats duration every time.</p>

<p>Your mind is your most valuable tool. Taking care of it isn\'t optional—it\'s essential.</p>',
        'image' => 9,
    ],
    [
        'title' => 'Introduction to API Design: Best Practices',
        'slug' => 'demo-api-design-best-practices',
        'type' => 'post',
        'category' => 'technology',
        'excerpt' => 'Learn how to design APIs that developers love to use, with practical examples and common pitfalls to avoid.',
        'content' => '<h2>Designing APIs That Developers Love</h2>
<p>A well-designed API is a joy to work with. A poorly designed one creates frustration and bugs. Here\'s how to get it right.</p>

<h3>REST Fundamentals</h3>
<p>Use HTTP methods correctly:</p>
<ul>
<li><strong>GET</strong> - Retrieve resources (safe, idempotent)</li>
<li><strong>POST</strong> - Create new resources</li>
<li><strong>PUT</strong> - Update resources (full replacement)</li>
<li><strong>PATCH</strong> - Partial updates</li>
<li><strong>DELETE</strong> - Remove resources</li>
</ul>

<h3>URL Structure</h3>
<pre><code># Good
GET /users
GET /users/123
GET /users/123/posts

# Bad
GET /getUsers
GET /user?id=123
POST /createUser</code></pre>

<h3>Response Codes Matter</h3>
<ul>
<li><strong>200</strong> - Success</li>
<li><strong>201</strong> - Created</li>
<li><strong>400</strong> - Bad Request (client error)</li>
<li><strong>401</strong> - Unauthorized</li>
<li><strong>404</strong> - Not Found</li>
<li><strong>500</strong> - Server Error</li>
</ul>

<h3>Error Responses</h3>
<p>Always return helpful error messages:</p>
<pre><code>{
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Email is required",
        "field": "email"
    }
}</code></pre>

<h3>Documentation</h3>
<p>Great APIs have great documentation. Consider OpenAPI/Swagger for interactive docs.</p>

<p>Remember: Your API is a product. Treat it like one.</p>',
        'image' => 10,
    ],
    [
        'title' => 'Color Theory for Digital Designers',
        'slug' => 'demo-color-theory-designers',
        'type' => 'post',
        'category' => 'design',
        'excerpt' => 'Master the fundamentals of color theory and learn how to create harmonious, accessible color palettes.',
        'content' => '<h2>The Power of Color</h2>
<p>Color is one of the most powerful tools in a designer\'s toolkit. It evokes emotion, guides attention, and communicates meaning—all in an instant.</p>

<h3>The Color Wheel</h3>
<p>Understanding relationships on the color wheel is fundamental:</p>
<ul>
<li><strong>Complementary</strong> - Opposite colors (high contrast)</li>
<li><strong>Analogous</strong> - Adjacent colors (harmonious)</li>
<li><strong>Triadic</strong> - Three evenly spaced colors (vibrant)</li>
<li><strong>Split-complementary</strong> - A color + two adjacent to its complement</li>
</ul>

<h3>Psychology of Color</h3>
<ul>
<li><strong>Blue</strong> - Trust, stability, professional</li>
<li><strong>Red</strong> - Energy, urgency, passion</li>
<li><strong>Green</strong> - Growth, nature, health</li>
<li><strong>Yellow</strong> - Optimism, clarity, warmth</li>
<li><strong>Purple</strong> - Creativity, luxury, mystery</li>
</ul>

<h3>Accessibility First</h3>
<p>Always check color contrast ratios:</p>
<ul>
<li>WCAG AA: 4.5:1 for normal text</li>
<li>WCAG AAA: 7:1 for normal text</li>
<li>Large text: 3:1 minimum</li>
</ul>

<h3>Building a Palette</h3>
<p>Start with one brand color, then:</p>
<ol>
<li>Choose a neutral palette (grays)</li>
<li>Add semantic colors (success, error, warning)</li>
<li>Create tints and shades for flexibility</li>
</ol>

<p>Tools like Coolors, Adobe Color, and Contrast Checker are invaluable for palette creation.</p>',
        'image' => 11,
    ],
    [
        'title' => 'Sustainable Travel: Making a Positive Impact',
        'slug' => 'demo-sustainable-travel-guide',
        'type' => 'post',
        'category' => 'travel',
        'excerpt' => 'How to explore the world responsibly while minimizing your environmental footprint and supporting local communities.',
        'content' => '<h2>Travel With Purpose</h2>
<p>Travel broadens minds and connects cultures. But it also has an environmental cost. Here\'s how to minimize your impact while maximizing positive experiences.</p>

<h3>Before You Go</h3>
<ul>
<li>Choose destinations with sustainable tourism practices</li>
<li>Book eco-certified accommodations</li>
<li>Pack reusable items (water bottle, shopping bag, utensils)</li>
<li>Offset your carbon from flights</li>
</ul>

<h3>During Your Trip</h3>
<h4>Transportation</h4>
<p>Once at your destination:</p>
<ul>
<li>Use public transit when possible</li>
<li>Walk or bike for short distances</li>
<li>Consider trains over short-haul flights</li>
</ul>

<h4>Support Local</h4>
<ul>
<li>Eat at locally-owned restaurants</li>
<li>Buy from local artisans (not imported souvenirs)</li>
<li>Hire local guides</li>
</ul>

<h4>Respect the Environment</h4>
<ul>
<li>Stay on marked trails</li>
<li>Never disturb wildlife</li>
<li>Leave no trace</li>
<li>Reduce plastic consumption</li>
</ul>

<h3>Giving Back</h3>
<p>Consider volunteering or donating to local conservation efforts. Many organizations need support from visitors.</p>

<blockquote>
<p>"Take only memories, leave only footprints." — Chief Seattle</p>
</blockquote>

<p>Sustainable travel isn\'t about sacrificing experiences—it\'s about traveling more intentionally and making choices that benefit everyone.</p>',
        'image' => 12,
    ],
    [
        'title' => 'The Rise of AI in Creative Industries',
        'slug' => 'demo-ai-creative-industries',
        'type' => 'post',
        'category' => 'technology',
        'excerpt' => 'Exploring how artificial intelligence is transforming creative work, from design and writing to music and video production.',
        'content' => '<h2>AI as Creative Partner</h2>
<p>Artificial intelligence is no longer just about automation. It\'s becoming a creative collaborator, augmenting human creativity in unprecedented ways.</p>

<h3>Current Applications</h3>

<h4>Visual Design</h4>
<ul>
<li>Midjourney and DALL-E for image generation</li>
<li>AI-assisted photo editing</li>
<li>Automated design variations</li>
<li>Logo and brand generation</li>
</ul>

<h4>Writing</h4>
<ul>
<li>Content drafting and brainstorming</li>
<li>Grammar and style checking</li>
<li>Translation and localization</li>
<li>SEO optimization</li>
</ul>

<h4>Music & Audio</h4>
<ul>
<li>AI-composed background music</li>
<li>Voice cloning and synthesis</li>
<li>Audio cleanup and enhancement</li>
</ul>

<h3>The Human Element</h3>
<p>AI is a tool, not a replacement. The most powerful applications combine AI capabilities with human creativity, judgment, and emotional intelligence.</p>

<blockquote>
<p>"AI won\'t replace creatives. Creatives using AI will replace those who don\'t."</p>
</blockquote>

<h3>Ethical Considerations</h3>
<ul>
<li>Attribution and ownership questions</li>
<li>Training data and consent</li>
<li>Impact on creative jobs</li>
<li>Authenticity and disclosure</li>
</ul>

<h3>What\'s Next?</h3>
<p>We\'re just at the beginning. As these tools mature, the possibilities for creative expression will expand in ways we can barely imagine.</p>

<p>The question isn\'t whether to embrace AI—it\'s how to use it thoughtfully and ethically.</p>',
        'image' => 13,
    ],
    [
        'title' => 'Building a Personal Brand That Matters',
        'slug' => 'demo-personal-brand-building',
        'type' => 'post',
        'category' => 'business',
        'excerpt' => 'Your personal brand is your professional reputation. Learn how to build one authentically and strategically.',
        'content' => '<h2>You Are Your Brand</h2>
<p>In today\'s connected world, everyone has a personal brand—whether they\'ve cultivated it intentionally or not. Why not take control of yours?</p>

<h3>Start With Why</h3>
<p>Before tactics, clarify your purpose:</p>
<ul>
<li>What unique value do you offer?</li>
<li>What do you want to be known for?</li>
<li>Who do you want to reach?</li>
<li>What impact do you want to make?</li>
</ul>

<h3>Find Your Voice</h3>
<p>Authenticity is essential. Your brand should be a genuine expression of who you are—not a manufactured persona.</p>
<ul>
<li>Share your real opinions (thoughtfully)</li>
<li>Tell your story, including struggles</li>
<li>Be consistent but not robotic</li>
</ul>

<h3>Choose Your Platforms</h3>
<p>You don\'t need to be everywhere. Pick 1-2 platforms where your audience lives:</p>
<ul>
<li><strong>LinkedIn</strong> for professional/B2B</li>
<li><strong>Twitter</strong> for tech and thought leadership</li>
<li><strong>Instagram</strong> for visual and lifestyle</li>
<li><strong>YouTube</strong> for long-form education</li>
</ul>

<h3>Create Consistently</h3>
<p>The secret to building a brand is showing up regularly. Quality matters, but consistency matters more.</p>

<blockquote>
<p>"Your brand is what people say about you when you\'re not in the room." — Jeff Bezos</p>
</blockquote>

<h3>Give More Than You Take</h3>
<p>The strongest personal brands are built on generosity—sharing knowledge, helping others, and adding value before asking for anything in return.</p>

<p>Your personal brand is a long-term investment. Start building today.</p>',
        'image' => 14,
    ],
];

// Insert posts
$inserted = 0;
$errors = [];

foreach ($demoPosts as $i => $post) {
    try {
        // Convert HTML content to BlockNote JSON blocks
        $blocks = htmlToBlockNoteBlocks($post['content']);
        
        $data = [
            'content' => $blocks, // Now saves as JSON array, not HTML string
            'excerpt' => $post['excerpt'],
            'featured_image' => $images[$post['image']],
            'categories' => [$post['category']],
            'status' => 'published',
        ];
        
        // Create dates spread over last 30 days
        $daysAgo = count($demoPosts) - $i;
        $createdAt = date('Y-m-d H:i:s', strtotime("-{$daysAgo} days"));
        
        $db->query(
            "INSERT INTO zed_content (title, slug, type, data, author_id, created_at, updated_at) 
             VALUES (:title, :slug, :type, :data, 1, :created_at, :updated_at)",
            [
                'title' => $post['title'],
                'slug' => $post['slug'],
                'type' => $post['type'],
                'data' => json_encode($data),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]
        );
        $inserted++;
    } catch (Exception $e) {
        $errors[] = "Post '{$post['title']}': " . $e->getMessage();
    }
}

// Output results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Content Imported - Aurora Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Demo Content Imported!</h1>
        <p class="text-gray-600 mb-6">
            Successfully created <strong><?= $inserted ?></strong> sample posts with rich content.
        </p>
        
        <?php if (!empty($categories)): ?>
        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-2">Categories created:</p>
            <div class="flex flex-wrap justify-center gap-2">
                <?php foreach ($categories as $cat): ?>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm rounded-full"><?= $cat['name'] ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
        <div class="mb-6 text-left bg-red-50 p-4 rounded-lg">
            <p class="text-red-700 font-medium mb-2">Some errors occurred:</p>
            <ul class="text-sm text-red-600 list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="flex gap-4 justify-center">
            <a href="/ZedCMS/" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                View Site
            </a>
            <a href="/ZedCMS/admin" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                Admin Panel
            </a>
        </div>
        
        <p class="mt-6 text-xs text-gray-400">
            Delete this file after use: import-demo.php
        </p>
    </div>
</body>
</html>
