<?php

class PluginTest extends TestCase {

    public function testEmojiPlugin()
    {
        $plugin = PluginRepository::find('emoji');

        $post = (object)['content' => ':octocat:'];
        $params = (object)['entry' => &$post];

        $plugin->convert($params);

        $this->assertRegExp('/<img .*?>/', $post->content);
    }

    public function testTocPlugin()
    {
        $plugin = PluginRepository::find('toc');

        $html = <<<EOF
{:TOC}

<h2>one</h2>
Hello world!
<h3>one-child1</h3>
I love dogs.
<h3>one-child2</h3>
I love cats.
<h2>two</h2>
This is pochika.
<h2>three</h2>
php
EOF;
        
        $post = (object)['content' => $html];
        $params = (object)['entry' => &$post];
        $plugin->handle($params);
        $this->assertNotFalse(strpos($post->content, '<div class="toc">'));
        
        // should not work if content has no {:TOC} tag
        $post = (object)['content' => 'hello'];
        $params = (object)['entry' => &$post];
        $plugin->handle($params);
        $this->assertFalse(strpos($post->content, '<div class="toc">'));
    }

//    // gfm - fenced code block
//    public function testFencedCodeBlock()
//    {
//        $plugin = new GfmPlugin();
//
//        $method = new ReflectionMethod($plugin, 'fencedCodeBlock');
//        $method->setAccessible(true);
//
//        $content = <<<EOF
//```
//test
//```
//EOF;
//
//        $res = $method->invoke($plugin, $content);
//
//        $this->assertRegExp('|<pre><code.*? >test</code></pre>|', $res);
//    }

    //// gfm - underscores in words
    //public function testUnderscoresInWords()
    //{
    //    $plugin = PluginRepository::find('gfm');
    //
    //    $entry = (object)['md' => 'wow_great_stuff'];
    //
    //    $plugin->convert($entry);
    //
    //    $this->assertEquals('wow\_great\_stuff', $entry->md);
    //}
    //
    //// gfm - autolink
    //public function testAutolink()
    //{
    //    $plugin = PluginRepository::find('gfm');
    //
    //    $entry = (object)['md' => "http://example.com/"];
    //
    //    $plugin->convert($entry);
    //
    //    $this->assertRegExp('|^\[http:.*\]\(http:.*\)$|', $entry->md);
    //}

}
