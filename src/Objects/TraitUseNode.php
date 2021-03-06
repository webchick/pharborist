<?php
namespace Pharborist\Objects;

use Pharborist\CommaListNode;
use Pharborist\Namespaces\NameNode;
use Pharborist\NodeCollection;
use Pharborist\StatementBlockNode;

/**
 * A trait use declaration.
 */
class TraitUseNode extends ClassStatementNode {
  /**
   * @var CommaListNode
   */
  protected $traits;

  /**
   * @var StatementBlockNode
   */
  protected $adaptations;

  /**
   * @return NodeCollection|NameNode[]
   */
  public function getTraits() {
    return $this->traits->getItems();
  }

  /**
   * @return TraitAdaptationStatementNode[]
   */
  public function getAdaptations() {
    return $this->adaptations->getStatements();
  }
}
