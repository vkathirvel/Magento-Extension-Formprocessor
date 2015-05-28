<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Forms Grid
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Forms_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('formsGrid');
        $this->setDefaultSort('form_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     *
     * @return type
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('formprocessor/forms')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     *
     * @return type
     */
    protected function _prepareColumns()
    {
        $this->addColumn('form_id', array(
                'header' => Mage::helper('formprocessor')->__('ID'),
                'align' => 'center',
                'width' => '75px',
                'index' => 'form_id',
        ));

        $this->addColumn('description', array(
                'header' => Mage::helper('formprocessor')->__('Friendly Description'),
                'align' => 'left',
                'index' => 'description',
        ));

        $this->addColumn('status', array(
                'header' => Mage::helper('formprocessor')->__('Status'),
                'align' => 'left',
                'width' => '100px',
                'index' => 'status',
                'type' => 'options',
                'options' => array(
                        1 => 'Enabled',
                        2 => 'Disabled',
                ),
        ));

        $this->addColumn('start_date', array(
                'header' => Mage::helper('formprocessor')->__('Start Date'),
                'align' => 'center',
                'type' => 'datetime',
                'index' => 'start_date',
                'width' => '100px',
        ));

        $this->addColumn('end_date', array(
                'header' => Mage::helper('formprocessor')->__('End Date'),
                'align' => 'center',
                'type' => 'datetime',
                'index' => 'end_date',
                'width' => '100px',
        ));

        $this->addColumn('action', array(
                'header' => Mage::helper('formprocessor')->__('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                        array(
                                'caption' => Mage::helper('formprocessor')->__('Edit'),
                                'url' => array('base' => '*/*/edit'),
                                'field' => 'form_id'
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
     * @return \Optimiseweb_FormProcessor_Block_Adminhtml_Forms_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('form_id');
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
        return $this->getUrl('*/*/edit', array('form_id' => $row->getId()));
    }

}
