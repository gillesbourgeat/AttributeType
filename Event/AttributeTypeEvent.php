<?php
/*************************************************************************************/
/*      This file is part of the module AttributeType                                */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace AttributeType\Event;

use AttributeType\Model\AttributeType;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Core\Event\ActionEvent;
use Thelia\Model\Attribute;

/**
 * Class AttributeTypeEvent
 * @package AttributeType\Event
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class AttributeTypeEvent extends ActionEvent
{
    /** @var ConnectionInterface|null */
    private $connectionInterface = null;

    /** @var AttributeType */
    private $attributeType = null;

    /** @var Attribute|null */
    private $attribute = null;

    /**
     * @param AttributeType $attributeType
     */
    public function __construct(AttributeType $attributeType)
    {
        $this->attributeType = $attributeType;
    }

    /**
     * @return AttributeType
     */
    public function getAttributeType()
    {
        return $this->attributeType;
    }

    /**
     * @param $attributeType
     * @return $this
     */
    public function setAttributeType(AttributeType $attributeType)
    {
        $this->attributeType = $attributeType;

        return $this;
    }

    /**
     * @return null|Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     * @return $this
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return null|ConnectionInterface
     */
    public function getConnectionInterface()
    {
        return $this->connectionInterface;
    }

    /**
     * @param ConnectionInterface $connectionInterface
     * @return $this
     */
    public function setConnectionInterface(ConnectionInterface $connectionInterface)
    {
        $this->connectionInterface = $connectionInterface;

        return $this;
    }
}
