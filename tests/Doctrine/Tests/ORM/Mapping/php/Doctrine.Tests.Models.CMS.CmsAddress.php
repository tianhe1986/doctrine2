<?php

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping;
use Doctrine\DBAL\Types\Type;

/* @var $metadata ClassMetadata */
$tableMetadata = new Mapping\TableMetadata();

$tableMetadata->setName('company_person');

$metadata->setPrimaryTable($tableMetadata);

$fieldMetadata = new Mapping\FieldMetadata('id');

$fieldMetadata->setType(Type::getType('integer'));
$fieldMetadata->setPrimaryKey(true);

$metadata->addProperty($fieldMetadata);

$fieldMetadata = new Mapping\FieldMetadata('zip');

$fieldMetadata->setType(Type::getType('string'));
$fieldMetadata->setLength(50);

$metadata->addProperty($fieldMetadata);

$fieldMetadata = new Mapping\FieldMetadata('city');

$fieldMetadata->setType(Type::getType('string'));
$fieldMetadata->setLength(50);

$metadata->addProperty($fieldMetadata);

$joinColumns = array();

$joinColumn = new Mapping\JoinColumnMetadata();

$joinColumn->setReferencedColumnName("id");

$joinColumns[] = $joinColumn;

$metadata->mapOneToOne(array(
    'fieldName'     => 'user',
    'targetEntity'  => 'CmsUser',
    'joinColumns'   => $joinColumns,
));

$metadata->addNamedNativeQuery(array (
    'name'              => 'find-all',
    'query'             => 'SELECT id, country, city FROM cms_addresses',
    'resultSetMapping'  => 'mapping-find-all',
));

$metadata->addNamedNativeQuery(array (
    'name'              => 'find-by-id',
    'query'             => 'SELECT * FROM cms_addresses WHERE id = ?',
    'resultClass'       => 'Doctrine\\Tests\\Models\\CMS\\CmsAddress',
));

$metadata->addNamedNativeQuery(array (
    'name'              => 'count',
    'query'             => 'SELECT COUNT(*) AS count FROM cms_addresses',
    'resultSetMapping'  => 'mapping-count',
));


$metadata->addSqlResultSetMapping(array (
    'name'      => 'mapping-find-all',
    'columns'   => array(),
    'entities'  => array ( array (
        'fields' => array (
          array (
            'name'      => 'id',
            'column'    => 'id',
          ),
          array (
            'name'      => 'city',
            'column'    => 'city',
          ),
          array (
            'name'      => 'country',
            'column'    => 'country',
          ),
        ),
        'entityClass' => 'Doctrine\Tests\Models\CMS\CmsAddress',
      ),
    ),
));

$metadata->addSqlResultSetMapping(array (
    'name'      => 'mapping-without-fields',
    'columns'   => array(),
    'entities'  => array(array (
        'entityClass' => 'Doctrine\\Tests\\Models\\CMS\\CmsAddress',
        'fields' => array()
      )
    )
));

$metadata->addSqlResultSetMapping(array (
    'name' => 'mapping-count',
    'columns' =>array (
        array (
            'name' => 'count',
        ),
    )
));

$metadata->addEntityListener(\Doctrine\ORM\Events::postPersist, 'CmsAddressListener', 'postPersist');
$metadata->addEntityListener(\Doctrine\ORM\Events::prePersist, 'CmsAddressListener', 'prePersist');

$metadata->addEntityListener(\Doctrine\ORM\Events::postUpdate, 'CmsAddressListener', 'postUpdate');
$metadata->addEntityListener(\Doctrine\ORM\Events::preUpdate, 'CmsAddressListener', 'preUpdate');

$metadata->addEntityListener(\Doctrine\ORM\Events::postRemove, 'CmsAddressListener', 'postRemove');
$metadata->addEntityListener(\Doctrine\ORM\Events::preRemove, 'CmsAddressListener', 'preRemove');

$metadata->addEntityListener(\Doctrine\ORM\Events::preFlush, 'CmsAddressListener', 'preFlush');
$metadata->addEntityListener(\Doctrine\ORM\Events::postLoad, 'CmsAddressListener', 'postLoad');