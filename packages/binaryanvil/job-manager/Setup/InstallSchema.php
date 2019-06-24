<?php
/**
 * Binary Anvil, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Binary Anvil, Inc. Software Agreement
 * that is bundled with this package in the file LICENSE_BAS.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.binaryanvil.com/software/license/
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@binaryanvil.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this software to
 * newer versions in the future. If you wish to customize this software for
 * your needs please refer to http://www.binaryanvil.com/software for more
 * information.
 *
 * @category    BinaryAnvil
 * @package     JobManager
 * @copyright   Copyright (c) 2016-present Binary Anvil,Inc. (http://www.binaryanvil.com)
 * @license     http://www.binaryanvil.com/software/license
 */

namespace BinaryAnvil\JobManager\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use BinaryAnvil\JobManager\Helper\Config;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->dropTable($installer->getTable(Config::SCHEMA_JOB_TABLE_NAME));

        $jobs = $installer->getConnection()
            ->newTable($installer->getTable(Config::SCHEMA_JOB_TABLE_NAME))
            ->addColumn(
                Config::SCHEMA_JOB_FIELD_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Job Id'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_TYPE,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Job Type'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_DETAILS,
                Table::TYPE_TEXT,
                16777216,
                ['nullable' => false, 'default' => ''],
                'Job Details'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_STATUS,
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => Config::JOB_STATUS_PENDING],
                'Job Status'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_PRIORITY,
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => Config::JOB_PRIORITY_LOWEST],
                'Job Priority'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_CREATED,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Job Created At'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_EXECUTED,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'default' => null],
                'Job Executed At'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_ATTEMPTS,
                Table::TYPE_SMALLINT,
                3,
                ['nullable' => false, 'default' => 0],
                'Job Attemts Number'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_LAST_ATTEMPT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'default' => null],
                'Job Last Execution Attempt At'
            )->addColumn(
                Config::SCHEMA_JOB_FIELD_LAST_ERROR,
                Table::TYPE_TEXT,
                16777216,
                ['nullable' => true, 'default' => null],
                'Job Last Error Text'
            )->addIndex(
                $installer->getIdxName(Config::SCHEMA_JOB_TABLE_NAME, [Config::SCHEMA_JOB_FIELD_TYPE]),
                [Config::SCHEMA_JOB_FIELD_TYPE]
            )->addIndex(
                $installer->getIdxName(Config::SCHEMA_JOB_TABLE_NAME, [Config::SCHEMA_JOB_FIELD_CREATED]),
                [Config::SCHEMA_JOB_FIELD_CREATED]
            )->addIndex(
                $installer->getIdxName(Config::SCHEMA_JOB_TABLE_NAME, [Config::SCHEMA_JOB_FIELD_LAST_ATTEMPT]),
                [Config::SCHEMA_JOB_FIELD_LAST_ATTEMPT]
            )->addIndex(
                $installer->getIdxName(Config::SCHEMA_JOB_TABLE_NAME, [Config::SCHEMA_JOB_FIELD_STATUS]),
                [Config::SCHEMA_JOB_FIELD_STATUS]
            );

        $installer->getConnection()->createTable($jobs);
    }
}
