<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Entries Grid
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Entries_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('entriesGrid');
        $this->setDefaultSort('entry_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     *
     * @return type
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('formprocessor/entries')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     *
     * @return type
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entry_id', array(
                'header' => Mage::helper('formprocessor')->__('ID'),
                'align' => 'center',
                'width' => '75px',
                'index' => 'entry_id',
        ));

        $this->addColumn('name', array(
                'header' => Mage::helper('formprocessor')->__('Name'),
                'align' => 'left',
                'index' => 'name',
        ));

        $this->addColumn('email', array(
                'header' => Mage::helper('formprocessor')->__('Email'),
                'align' => 'left',
                'index' => 'email',
        ));

        $this->addColumn('form_id', array(
                'header' => Mage::helper('formprocessor')->__('Form'),
                'align' => 'left',
                'index' => 'form_id',
                'type' => 'options',
                'options' => Mage::getModel('formprocessor/forms')->getFormsAsOptionsArray(),
        ));

        $this->addColumn('action', array(
                'header' => Mage::helper('formprocessor')->__('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                        array(
                                'caption' => Mage::helper('formprocessor')->__('Edit'),
                                'url' => array('base' => '*/*/edit'),
                                'field' => 'entry_id'
                        )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'align' => 'center',
                'width' => '100px',
                'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('formprocessor')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('formprocessor')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     *
     * @return \Optimiseweb_FormProcessor_Block_Adminhtml_Entries_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entry_id');
        $this->getMassactionBlock()->setFormFieldName('formprocessor');

        $this->getMassactionBlock()->addItem('delete', array(
                'label' => Mage::helper('formprocessor')->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('formprocessor')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('formprocessor/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
                'label' => Mage::helper('formprocessor')->__('Change status'),
                'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array(
                        'visibility' => array(
                                'name' => 'status',
                                'type' => 'select',
                                'class' => 'required-entry',
                                'label' => Mage::helper('formprocessor')->__('Status'),
                                'values' => $statuses
                        )
                )
        ));
        return $this;
    }

    /**
     *
     * @param type $row
     * @return type
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('entry_id' => $row->getId()));
    }

}
