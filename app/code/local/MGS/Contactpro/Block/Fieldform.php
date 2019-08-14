<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_GoogleCheckout
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MGS_Contactpro_Block_Fieldform
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_addRowButtonHtml = array();
    protected $_removeRowButtonHtml = array();
	
	protected $_fields = array();

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
		
		$html = '';
		$html .= '<style type="text/css">
			#row_contactpro_items_field_items table.form-list{
				margin-bottom:15px;
				padding-bottom:10px;
				border-bottom:3px solid #6F8992!important;
			}
			#row_contactpro_items_field_items td.label{
				display:none;
			}
			#row_contactpro_items_field_items td.value td.label{
				display:block;
			}
			#row_contactpro_items_field_items td.value,
			#row_contactpro_items_field_items td.label{
				padding:2px 5px!important;
			}
			#row_contactpro_items_field_items td.value{
				width:700px!important;
			}
			#row_contactpro_items_field_items td.value td.value {
				width: 530px!important;
			}';
			
		$html .= '</style>';
        $html .= '<div id="field-template" style="display:none">';
        $html .= $this->_getRowTemplateHtml();
        $html .= '</div>';
		
		//echo '<pre>';print_r($this->getFieldsForm());die();
        $html .= '<ul id="field-items">';
        if (count($this->getFieldsForm())) {
			$i = 1;
            foreach ($this->getFieldsForm() as $field) {
                $html .= $this->_getRowTemplateHtml($field->getContactProFieldId());
            }
        }
        $html .= '</ul>';
        $html .= $this->_getAddRowButtonHtml('field-items',
            'field-template', $this->__('Add Field'));
        return $html;
    }

    protected function _getRowTemplateHtml($i=0)
    {	
        $html = '<li>';
		
		$html .= '<table cellspacing="0" class="form-list">';
		
		$html .= $this->_getId($i);
		$html .= $this->_getLabel($i);
		$html .= $this->_getRequired($i);
		$html .= $this->_getPosition($i);
		$html .= $this->_getStatus($i);
		
		$html .= '<td class="label">';
		$html .= '<label for="name"></label>';
		$html .= '</td>';
		
		$html .= '<td class="value">';
		$html .= $this->_getRemoveRowButtonHtml();
		$html .= '</td>';
        
		$html .= '</table>';
        $html .= '</div>';
        $html .= '</li>';

        return $html;
    }	

    protected function _getAddRowButtonHtml($container, $template, $title='Add')
    {
        if (!isset($this->_addRowButtonHtml[$container])) {
            $this->_addRowButtonHtml[$container] = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('add ')
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.insert($('" . $container . "'), {bottom: $('" . $template . "').innerHTML})")
                    ->toHtml();
        }
        return $this->_addRowButtonHtml[$container];
    }

    protected function _getRemoveRowButtonHtml($selector = 'li', $title = 'Remove this field')
    {
        if (!$this->_removeRowButtonHtml) {
            $this->_removeRowButtonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('delete v-middle ')
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.remove($(this).up('" . $selector . "'))")
                    ->toHtml();
        }
        return $this->_removeRowButtonHtml;
    }
	
	//==========================================================
	protected function getFieldsForm(){
		$model  	= Mage::getModel('contactpro/contactprofield')->getCollection()->load();
		$fields 	= array();
		$i=1;
		foreach ($model as $field) {
			$fields[$i++] = $field;
		}
		$this->_fields = $fields;
		return $this->_fields;
	}
	
	//==========================================================
	protected function _getId($i=0){
		$field = Mage::getModel('contactpro/contactprofield')->load($i);
		
		$html = '';
		
		$html .= '<tr style="display:none;">';
		
		$html .= '<td class="contactprofieldid">';
		$html .= '</td>';
		
		$html .= '<td class="value">';
		$html .= '<input type="hidden" class="input-text" value="'.$field->getContactProFieldId().'" name="contactprofieldid[]" id="contactprofieldid'.$i.'"/>';
		$html .= '</td>';
		
		$html .= '</tr>';
		return $html;
	}
	
	protected function _getLabel($i=0){
		$field 	= Mage::getModel('contactpro/contactprofield')->load($i);
		$select 	= (int)$field->getLabel();

		
		$html = '';
		$html .= '<tr>';
		
		$html .= '<td class="label">';
		$html .= '<label for="label">'.$this->__("Field Label").'</label>';
		$html .= '</td>';
		
		$html .= '<td class="value">';
		$html .= '<input type="text" class="input-text" value="'.$field->getLabel().'" name="label[]" id="label'.$i.'"/>';
		$html .= '</td>';
		
		$html .= '</tr>';
		return $html;
	}
	
	protected function _getRequired($i=0){
		$field = Mage::getModel('contactpro/contactprofield')->load($i);
		
		$select 	= (int)$field->getRequired();
		$select1 = '';
		$select2 = '';
		
		if($select == 1){
			$select1 = "selected=\"selected\"";
		}elseif($select == 2){
			$select2 = "selected=\"selected\"";
		}
		
		$html = '';
		$html .= '<tr>';
		
		$html .= '<td class="label">';
		$html .= '<label for="required">'.$this->__("Required").'</label>';
		$html .= '</td>';
		
		$html .= '<td class="value">';
		$html .= '<select class="required select" name="required[]" id="required'.$i.'">';
		$html .= '<option '. $select2 .' value="2">' . $this->__("No").'</option>';
		$html .= '<option '. $select1 .' value="1">' . $this->__("Yes").'</option>';
		$html .= '</select>';
		$html .= '</td>';
		
		$html .= '</tr>';
		return $html;
	}
	
	protected function _getPosition($i=0){
		$field 		= Mage::getModel('contactpro/contactprofield')->load($i);
		$select 	= (int)$field->getPosition();
		
		$html = '';
		$html .= '<tr>';
		
		$html .= '<td class="label">';
		$html .= '<label for="position">'.$this->__("Position").'</label>';
		$html .= '</td>';
		
		$html .= '<td class="value">';
		$html .= '<input type="text" class="input-text" value="'.$field->getPosition().'" name="position[]" id="position'.$i.'"/>';
		$html .= '</td>';
		
		$html .= '</tr>';
		return $html;
	}
	
	protected function _getStatus($i=0){
		$field = Mage::getModel('contactpro/contactprofield')->load($i);
		
		$select 	= (int)$field->getStatus();
		$select1 = '';
		$select2 = '';
		
		if($select == 1){
			$select1 = "selected=\"selected\"";
		}elseif($select == 2){
			$select2 = "selected=\"selected\"";
		}
		
		$html = '';
		$html .= '<tr>';
		
		$html .= '<td class="label">';
		$html .= '<label for="status">'.$this->__("Status").'</label>';
		$html .= '</td>';
		
		$html .= '<td class="value">';
		$html .= '<select class="status select" name="status[]" id="status'.$i.'">';		
		$html .= '<option '. $select1 .' value="1">' . $this->__("Enabled").'</option>';
		$html .= '<option '. $select2 .' value="2">' . $this->__("Disabled").'</option>';
		$html .= '</select>';
		$html .= '</td>';
		
		$html .= '</tr>';
		return $html;
	}	
}
