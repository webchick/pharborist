<?php

namespace Pharborist;

use Pharborist\Objects\ClassMemberListNode;
use Pharborist\Objects\ClassNode;

class ClassMemberListNodeTest extends \PHPUnit_Framework_TestCase {
  public function testStatic() {
    /** @var ClassNode $class_node */
    $class_node = Parser::parseSnippet('class Foo { public static $bar; }');
    /** @var ClassMemberListNode $a */
    $a = $class_node->getStatements()[0];
    $this->assertTrue($a->isStatic());
    $class_node = Parser::parseSnippet('class Baz { protected $doodle; }');
    /** @var ClassMemberListNode $b */
    $b = $class_node->getStatements()[0];
    $this->assertFalse($b->isStatic());

    $a->setStatic(FALSE);
    $this->assertFalse($a->isStatic());
    $this->assertEquals('public $bar;', $a->getText());

    $b->setStatic(TRUE);
    $this->assertTrue($b->isStatic());
    $this->assertEquals('protected static $doodle;', $b->getText());
  }

  /**
   * @expectedException \BadMethodCallException
   */
  public function testRemoveVisibility() {
    /** @var ClassNode $class_node */
    $class_node = Parser::parseSnippet('class Foo { public $wrassle; }');
    /** @var ClassMemberListNode $property */
    $property = $class_node->getStatements()[0];
    $property->setVisibility(NULL);
  }

  public function testAddTo() {
    /** @var ClassNode $source */
    $source = Parser::parseSnippet('class Foo { protected $bar; }');
    /** @var ClassNode $target */
    $target = Parser::parseSnippet('class Bar {}');
    /** @var ClassMemberListNode $property_list */
    $property_list = $source->getStatements()[0];

    $property_list->addTo($target);
    $this->assertFalse($source->hasProperty('bar'));
    $this->assertTrue($target->hasProperty('bar'));
    $this->assertSame($property_list, $target->getProperty('bar')->parent()->parent());
  }

  public function testCloneInto() {
    /** @var ClassNode $source */
    $source = Parser::parseSnippet('class Foo { protected $bar; }');
    /** @var ClassNode $target */
    $target = Parser::parseSnippet('class Bar {}');
    /** @var ClassMemberListNode $original_list */
    $original_list = $source->getStatements()[0];

    $cloned_list = $original_list->cloneInto($target);
    $this->assertInstanceOf('\Pharborist\Objects\ClassMemberListNode', $cloned_list);
    $this->assertNotSame($original_list, $cloned_list);
    $this->assertTrue($source->hasProperty('bar'));
    $this->assertTrue($target->hasProperty('bar'));
  }
}
