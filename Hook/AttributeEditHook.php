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

namespace AttributeType\Hook;

use AttributeType\Form\AttributeTypeAvMetaUpdateForm;
use AttributeType\Model\AttributeAttributeType;
use AttributeType\Model\AttributeAttributeTypeQuery;
use AttributeType\Model\AttributeTypeAvMeta;
use AttributeType\Model\AttributeTypeAvMetaQuery;
use AttributeType\Model\Map\AttributeAttributeTypeTableMap;
use AttributeType\Model\Map\AttributeTypeAvMetaTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Thelia;
use Thelia\Model\AttributeAv;
use Thelia\Model\AttributeAvQuery;
use Thelia\Model\Lang;
use Thelia\Model\LangQuery;

/**
 * Class AttributeEditHook
 * @package AttributeType\Hook
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class AttributeEditHook extends BaseHook
{
    /** @var ContainerInterface */
    private $container = null;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param HookRenderEvent $event
     */
    public function onAttributeEditBottom(HookRenderEvent $event)
    {
        $form = new AttributeTypeAvMetaUpdateForm(
            $this->getRequest(),
            'form',
            self::hydrateForm($event->getArgument('attribute_id')),
            array(),
            $this->container
        );

        $this->container->get('thelia.parser.context')->addForm($form);

        $event->add($this->render(
            'attribute-type/hook/attribute-edit-bottom.html',
            array(
                'attribute_id' => $event->getArgument('attribute_id')
            )
        ));
    }

    /**
     * @param HookRenderEvent $event
     */
    public function onAttributeEditJs(HookRenderEvent $event)
    {
        // Fix for Thelia 2.1, because the hook "attribute-edit.bottom" does not exist
        if (version_compare(Thelia::THELIA_VERSION, '2.2', '<')) {
            $event->add('<script type="text/template" id="attribute-type-fix-t21">');
            self::onAttributeEditBottom($event);
            $event->add('</script>');
        }

        $event->add($this->render(
            'attribute-type/hook/attribute-edit-js.html',
            array(
                'attribute_id' => $event->getArgument('attribute_id')
            )
        ));
    }

    /**
     * @param AttributeAv $attributeAv
     * @return array|mixed|\Propel\Runtime\Collection\ObjectCollection
     */
    private function getAttributeTypeAvMetas(AttributeAv $attributeAv)
    {
        $join = new Join();

        $join->addExplicitCondition(
            AttributeTypeAvMetaTableMap::TABLE_NAME,
            'ATTRIBUTE_ATTRIBUTE_TYPE_ID',
            null,
            AttributeAttributeTypeTableMap::TABLE_NAME,
            'ID',
            null
        );

        $join->setJoinType(Criteria::INNER_JOIN);

        return AttributeTypeAvMetaQuery::create()
            ->filterByAttributeAvId($attributeAv->getId())
            ->addJoinObject($join)
            ->withColumn('`attribute_attribute_type`.`attribute_type_id`', 'ATTRIBUTE_TYPE_ID')
            ->find();
    }

    /**
     * @param int $attributeId
     * @return array
     */
    private function hydrateForm($attributeId)
    {
        $data = array('attribute_av' => array());

        $attributeAvs = AttributeAvQuery::create()->findByAttributeId($attributeId);

        $attributeTypes = AttributeAttributeTypeQuery::create()->findByAttributeId($attributeId);

        $langs = LangQuery::create()->find();

        /** @var AttributeAv $attributeAv */
        foreach ($attributeAvs as $attributeAv) {
            $attributeAvMetas = self::getAttributeTypeAvMetas($attributeAv);

            $data['attribute_av'][$attributeAv->getId()] = array(
                'lang' => array()
            );

            /** @var Lang $lang */
            foreach ($langs as $lang) {
                $data['attribute_av'][$attributeAv->getId()]['lang'][$lang->getId()] = array(
                    'attribute_type' => array()
                );

                /** @var AttributeTypeAvMeta $attributeAvMeta */
                foreach ($attributeAvMetas as $attributeAvMeta) {
                    /** @var AttributeAttributeType $attributeType */
                    foreach ($attributeTypes as $attributeType) {
                        if ($attributeAvMeta->getLocale() === $lang->getLocale()
                            && intval($attributeAvMeta->getVirtualColumn("ATTRIBUTE_TYPE_ID")) === $attributeType->getAttributeTypeId()
                        ) {
                            $data['attribute_av'][$attributeAv->getId()]['lang'][$lang->getId()]['attribute_type'][$attributeType->getAttributeTypeId()] = $attributeAvMeta->getValue();
                        }
                    }
                }
            }
        }

        return $data;
    }
}
