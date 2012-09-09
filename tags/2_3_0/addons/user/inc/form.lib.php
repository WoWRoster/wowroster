<?php
/** 
 * Dev.PKComp.net user Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @author	   mdeshane
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    user 
 * @subpackage user Form Handler 
 */

if ( !defined('ROSTER_INSTALLED') ) 
{ 
    exit('Detected invalid access to this file!'); 
}

class userForm extends user
{
      var $method;
      var $action;
      var $name;
      var $class;

      var $elements;
      var $getStatus;
      var $formCols;

      /* default constructor
       ** purpose: sets status to 0 */
      function userForm()
      {
		$this->getStatus = array();
      }
	/* start new form
       ** purpose: sets form globals */
      function newForm($method,$action,$name,$class,$columns=2)
      {
		$this->method[$name] = $method;
		$this->action[$name] = $action;
		$this->name[$name] = $name;
		$this->class[$name] = $class;
		$this->elements[$name] = array();
		$this->getStatus = 0;
		$this->formCols[$name] = $columns;
      }
      /* add new element
       ** purpose: adds an entity to the container */
      function addElement( $element,$form )
	{
		$element['id'] = $this->nextElementID( $form );
		$location = $element['id'];
		$this->elements[$form][$location] = $element;

		return $element['id'];
      }
	/* update element
	 ** purpose: updates an existing form element */
	function updateElement( $updatedElement,$form )
	{
		$id = $updatedElement['id'];
		$this->elements[$form][$id] = $updatedElement;
	}

	function nextElementID( $form )
	{
		if(is_array($this->elements[$form]))
		{
			return count( $this->elements[$form] );
		}
	}

	function getElement( $id,$form )
	{
		return $this->elements[$form][$id];
	}

	function numElements( $form )
	{
		return count( $this->elements[$form] );
	}
	/* get the next element
	 ** purpose: each time this is called it gets the next element until the end is reached */
	function getNext( $form )
	{
		if( $this->getStatus == $this->numElements( $form ) )
		{        // is this the end of the elements?
			$this->getStatus = 0; // reset the sentinal 
			return false; // indicate ending
		}
		$element = $this->getElement( $this->getStatus,$form );
		$this->getStatus++;
		return $element;
	}
	/* add a select box entity to the form
	 ** purpose: adds a select box to the container */
	function addSelect($name,$description,$required=0,$form,$value='')
	{
		$element['type'] = 'select';
		$element['name'] = $name;
		$element['selections'] = array();
		$element['description'] = $description;
		$element['required'] = $required;
		$element['value'] = $value;
		$element['beginCode'] = "<select name='{$element['name']}'>";
		$element['endCode'] = "</select>";

		return $this->addElement($element,$form);
	}
	/* add item to a select box instance
	 ** purpose: adds an option to a select box entity */
	function addSelect_Item($id,$val,$description,$form)
	{
		// get the element from the array of elements
		$element = $this->getElement($id,$form);
		if( $element['type'] != 'select' )
		{
			return false;
		}
		$selected = '';
		if( $element['value'] == $val )
		{
			$selected = 'SELECTED';
		}
		$selections = $element['selections'];
		$location = count( $selections );
		$selections[$location]['value'] = $val;
		$selections[$location]['description'] = $description;
		$selections[$location]['code'] = "\t\t<option value='{$selections[$location]['value']}' {$selected}>{$selections[$location]['description']}</option>\n";
		$element['selections'] = $selections;

		$this->updateElement($element,$form);
	}

	function getSelectItems($element,$form,$type='')
	{
		if( $element['type'] == 'select' )
		{
			$outp = '';
			$selections = $element['selections'];
			for( $i = 0; $i < count( $selections ); $i++ )
			{
				$outp .= $selections[$i]['code'];
			}
			return $outp;
		}
		elseif( $element['type'] == 'tray' )
		{
			$outp = '';
			$selections = $element['selections'];
			for( $i = 0; $i < count( $selections ); $i++ )
			{
				$outp .= $selections[$i]['code'];
			}
			return $outp;
		}
		elseif( $element['type'] == 'dateselect' )
		{
			$outp = '';
			$selections[$type] = $element['selections'][$type];
			for( $i = 0; $i < count( $selections[$type] ); $i++ )
			{
				$outp .= $selections[$type][$i]['code'];
			}
			return $outp;
		}
		else
		{
			return false;
		}
	}
	/* add a text box
	 ** purpose: adds a text box to the container */
	function addTextBox($type,$name,$value,$description,$class,$required=0,$form)
	{
		$element['type'] = $type;
		$element['name'] = $name;
		$element['value'] = $value;
		$element['description'] = $description;
		$element['class'] = $class;
		$element['required'] = $required;
		$element['code'] = "<input type='{$element['type']}' name='{$element['name']}' class='{$element['class']}' value='{$element['value']}' />";

		return $this->addElement($element,$form);
	}
	/* add text
	 ** purpose: adds a text to the container */
	function addFormText($name,$description,$class,$align,$form)
	{
		$element['type'] = 'formtext';
		$element['name'] = $name;
		$element['description'] = $description;
		$element['class'] = $class;
		$element['required'] = 0;
		$element['align'] = $align;

		return $this->addElement($element,$form);
	}
	/* add a check box
	 ** purpose: adds a check box to the container */
	function addCheckBox($name,$value,$description,$required=0,$form)
	{
		$element['type'] = 'checkbox';
		$element['name'] = $name;
		$element['value'] = $value;
		$element['description'] = $description;
		$element['class'] = 'checkbox';
		$element['required'] = $required;
		$element['code'] = "<input type='checkbox' name='{$element['name']}' class='{$element['class']}' value='{$element['value']}' />";

		return $this->addElement($element,$form);
	}
	/* add a radio button
	 ** purpose: adds a radio button to the container */
	function addRadioButton($name,$value,$description,$required=0,$form)
	{
		$element['type'] = 'radio';
		$element['name'] = $name;
		$element['value'] = $value;
		$element['description'] = $description;
		$element['class'] = 'checkbox';
		$element['required'] = $required;
		$element['code'] = "<input type='radio' class='{$element['class']}' value='{$element['value']}' " . "name='{$element['name']}' />";

		return $this->addElement($element,$form);
	}
	/* add a textarea
	 ** purpose: adds a textarea to the container */
	function addTextArea($name,$value,$description,$required=0,$form)
	{
		$element['type'] = 'textarea';
		$element['name'] = $name;
		$element['value'] = $value;
		$element['description'] = $description;
		$element['class'] = 'textBoxArea';
		$element['required'] = $required;
		$element['code'] = "<textarea name='{$element['name']}' class='{$element['class']}' rows='4' cols='50'>{$element['value']}</textarea>";
		return $this->addElement($element,$form);
	}
	/* add a date selectbox item
	 ** purpose: adds a date selectbox item to the date selectbox */
	function addDateSelect_Item($id,$type,$val,$description,$form)
	{
		$element = $this->getElement($id,$form);
		if( $element['type'] != 'dateselect' )
		{
			return false;
		}
		$selections[$type] = $element['selections'][$type];
		$location = count( $selections[$type] );
		$selections[$type][$location]['value'] = $val;
		$selections[$type][$location]['description'] = $description;
		$selections[$type][$location]['code'] = "\t<option value='{$selections[$type][$location]['value']}'>{$selections[$type][$location]['description']}</option>\n";
		$element['selections'][$type] = $selections[$type];

		return $this->updateElement($element,$form);
	}
	/* add a date selectbox
	 ** purpose: adds a date selectbox to the container */
	function addDateSelect($name,$description,$required=0,$form)
	{
		global $roster;
		$element['type'] = 'dateselect';
		$element['name'] = $name;
		$element['selections']['month'] = array();
		$element['selections']['day'] = array();
		$element['selections']['year'] = array();
		$element['description'] = $description;
		$element['required'] = $required;
		$element['beginMonth'] = "<select name='{$element['name']}_Month'>";
		$element['endMonth'] = "</select>";
		$element['beginDay'] = "<select name='{$element['name']}_Day'>";
		$element['endDay'] = "</select>";
		$element['beginYear'] = "<select name='{$element['name']}_Year'>";
		$element['endYear'] = "</select>";
		$id = $this->addElement($element,$form);
		for ($i = 1; $i <= 31; $i++)
		{
			$this->addDateSelect_Item($id,'day',$i,$i,$form);
		}
		for ($i = date('Y')-90; $i <= date('Y'); $i++)
		{
			$year[$i] = $i;
		}
		rsort($year);
		foreach ($year as $key => $val)
		{
			$this->addDateSelect_Item($id,'year',$key,$val,$form);
		}
		$month = $roster->locale->act['user_form']['month'];
		foreach ($month as $key => $val)
		{
			$this->addDateSelect_Item($id,'month',$key,$val,$form);
		}
		return;
	}
	/* add a timezone selectbox
	 ** purpose: adds a timezone selectbox to the container */
	function addTimeZone($name,$description,$required=0,$form)
	{
		global $roster;

		$selectId = $this->addSelect($name,$description,$required,$form);
			$this->addSelect_Item($selectId, '-11', $roster->locale->act['user_form']['zone']['MIT'],$form);
			$this->addSelect_Item($selectId, '-10', $roster->locale->act['user_form']['zone']['HST'],$form);
			$this->addSelect_Item($selectId, '-9', $roster->locale->act['user_form']['zone']['AST'],$form);
			$this->addSelect_Item($selectId, '-8', $roster->locale->act['user_form']['zone']['PST'],$form);
			$this->addSelect_Item($selectId, '-7', $roster->locale->act['user_form']['zone']['PNT'],$form);
			$this->addSelect_Item($selectId, '-7', $roster->locale->act['user_form']['zone']['MST'],$form);
			$this->addSelect_Item($selectId, '-6', $roster->locale->act['user_form']['zone']['CST'],$form);
			$this->addSelect_Item($selectId, '-5', $roster->locale->act['user_form']['zone']['IET'],$form);
			$this->addSelect_Item($selectId, '-5', $roster->locale->act['user_form']['zone']['EST'],$form);
			$this->addSelect_Item($selectId, '-4', $roster->locale->act['user_form']['zone']['PRT'],$form);
			$this->addSelect_Item($selectId, '-3.5', $roster->locale->act['user_form']['zone']['CNT'],$form);
			$this->addSelect_Item($selectId, '-3', $roster->locale->act['user_form']['zone']['AGT'],$form);
			$this->addSelect_Item($selectId, '-3', $roster->locale->act['user_form']['zone']['BET'],$form);
			$this->addSelect_Item($selectId, '-1', $roster->locale->act['user_form']['zone']['CAT'],$form);
			$this->addSelect_Item($selectId, '0', $roster->locale->act['user_form']['zone']['GMT'],$form);
			$this->addSelect_Item($selectId, '1', $roster->locale->act['user_form']['zone']['ECT'],$form);
			$this->addSelect_Item($selectId, '2', $roster->locale->act['user_form']['zone']['EET'],$form);
			$this->addSelect_Item($selectId, '2', $roster->locale->act['user_form']['zone']['ART'],$form);
			$this->addSelect_Item($selectId, '3', $roster->locale->act['user_form']['zone']['EAT'],$form);
			$this->addSelect_Item($selectId, '3.5', $roster->locale->act['user_form']['zone']['MET'],$form);
			$this->addSelect_Item($selectId, '4', $roster->locale->act['user_form']['zone']['NET'],$form);
			$this->addSelect_Item($selectId, '5', $roster->locale->act['user_form']['zone']['PLT'],$form);
			$this->addSelect_Item($selectId, '5.5', $roster->locale->act['user_form']['zone']['IST'],$form);
			$this->addSelect_Item($selectId, '6', $roster->locale->act['user_form']['zone']['BST'],$form);
			$this->addSelect_Item($selectId, '7', $roster->locale->act['user_form']['zone']['VST'],$form);
			$this->addSelect_Item($selectId, '8', $roster->locale->act['user_form']['zone']['CTT'],$form);
			$this->addSelect_Item($selectId, '9', $roster->locale->act['user_form']['zone']['JST'],$form);
			$this->addSelect_Item($selectId, '9.5', $roster->locale->act['user_form']['zone']['ACT'],$form);
			$this->addSelect_Item($selectId, '10', $roster->locale->act['user_form']['zone']['AET'],$form);
			$this->addSelect_Item($selectId, '11', $roster->locale->act['user_form']['zone']['SST'],$form);
			$this->addSelect_Item($selectId, '12', $roster->locale->act['user_form']['zone']['NST'],$form);
	}
	/* add a user selectbox
	 ** purpose: adds a user selectbox to the container */
	function addUserSelect($name,$description,$required=0,$form,$val)
	{
		global $roster, $user;

		$selectId = $this->addSelect($name,$description,$required,$form,$val);
		$sql = 'SELECT `id`,`usr` FROM `' . $roster->db->table('user_members') . '`';
		$query = $roster->db->query($sql);
		$a = array();
		while($row = $roster->db->fetch($query, SQL_ASSOC))
		{
			if ($row['id'] != '0')
			{
				$a[$row['id']] = $row['usr'];
			}
		}
		foreach($a as $key => $val)
		{
			$this->addSelect_Item($selectId,$key,$val,$form);
		}
	}
	/* add a select box entity to the form
	 ** purpose: adds a select box to the container */
	function addTray($name,$form)
	{
		$element['type'] = 'tray';
		$element['name'] = $name;
		$element['selections'] = array();
		$element['required'] = 0;
		$element['beginCode'] = '';//"<tr><td id='{$element['name']}' class='formTray' colspan='{$this->formCols[$form]}'>";
		$element['endCode'] = '';//"</td></tr>";

		return $this->addElement($element,$form);
	}
	function addTrayElement($type,$id,$name,$val,$class,$form)
	{
		if( $type != 'submit' )
		{
			if( $type != 'button' )
			{
				if( $type != 'hidden' )
				{
					if( $type != 'reset' )
					{
						return false;
					}
				}
			}
		}
		// get the element from the array of elements
		$element = $this->getElement($id,$form);
		if( $element['type'] != 'tray' )
		{
			return false;
		}
		$selections = $element['selections'];
		$location = count( $selections );
		$selections[$location]['type'] = $type;
		$selections[$location]['value'] = $val;
		$selections[$location]['name'] = $name;
		$selections[$location]['class'] = $class;
		$selections[$location]['code'] = "\t<input class='{$selections[$location]['class']}' type='{$selections[$location]['type']}' value='{$selections[$location]['value']}' name='{$selections[$location]['name']}' />\n";
		$element['selections'] = $selections;

		$this->updateElement($element,$form);
	}
	/* add a submit button
	 ** purpose: add a submit button to the container */
	function addSubmitButton($id,$name,$value,$form)
	{
		$type = 'submit';
		$class = 'submit';

		return $this->addTrayElement($type,$id,$name,$value,$class,$form);
	}
	/* add a hidden field
	 ** purpose: add a hidden field to the container */
	function addHiddenField($id,$name,$value,$form)
	{
		$type = 'hidden';
		$class = 'hidden';

		return $this->addTrayElement($type,$id,$name,$value,$class,$form);
	}
	/* add a button field
	 ** purpose: add a button field to the container */
	function addButtonField($id,$name,$value,$form)
	{
		$type = 'button';
		$class = 'button';

		return $this->addTrayElement($type,$id,$name,$value,$class,$form);
	}
	/* add a reset button field
	 ** purpose: add a reset button field to the container */
	function addResetButton($id,$name,$value,$form)
	{
		$type = 'reset';
		$class = 'button';

		return $this->addTrayElement($type,$id,$name,$value,$class,$form);
	}
	/* add an empty column
	 ** purpose: add an empty column to the container */
	function addColumn($name,$span=1,$description='',$class='formElement',$form)
	{
		$element['type'] = 'column';
		$element['name'] = $name;
		$element['class'] = $class;
		$element['description'] = $description;
		$element['required'] = 0;
		$element['span'] = $span;

		return $this->addElement($element,$form);
	}
	function addRow($name,$span=1,$description='',$class='formElement',$form)
	{
		$element['type'] = 'column';
		$element['name'] = $name;
		$element['class'] = $class;
		$element['description'] = $description;
		$element['required'] = 0;
		$element['span'] = $span;

		return $this->addElement($element,$form);
	}
	/* get HTML output as a table
	 ** purpose: returns the HTML data from the container with the descriptions on the left and the form elements on the right */
	function getTableOfElements_1($rowColoring=0,$form,$i=1,$ftgs=true)
	{
		$outp = "";
		if ($ftgs)
		{
		$outp .= "<form method='{$this->method[$form]}' action='{$this->action[$form]}' name='{$this->name[$form]}'><table width=\"100%\">\n";
		}
	//	$outp .= "<table class='{$this->class[$form]}' cellspacing='1'>\n";
		$element = $this->getNext($form);
		$el_num = $this->numElements($form);
		$cols = $this->formCols[$form];
		$rowEnd = '';
		while( $element )
		{
			if( $i > $cols)
			{
				$i = 1;
			}

			if( $i <= $cols )
			{
				if( $i == 1 && $element['id'] != 0 )
				{
					$row = '</tr><tr>';
				}
				elseif( $i == 1 && $element['id'] == 0 )
				{
					$row = '<tr>';
				}
				else
				{
					$row = '';
				}
				if( $element['id'] == $el_num - 1 )
				{
					$rowEnd = '</tr>';
				}
			}

			if ($rowColoring == 1)
			{
				if ($element['id']&1)
				{
					$rowColor = 'membersRow1';
				}
				else
				{
					$rowColor = 'membersRow2';
				}
			}
			else
			{
				$rowColor = 'formElement';
			}

			$outp .= "\t";

			if ($element['required'] == 1)
			{
				$element['required'] = '<font color="#FF0000">*</font>';
			}
			else
			{
				$element['required'] = '';
			}

			switch($element['type'])
			{
				case 'dateselect':
					$selectItems['month'] = $this->getSelectItems( $element,$form,'month' );
					$selectItems['day'] = $this->getSelectItems( $element,$form,'day' );
					$selectItems['year'] = $this->getSelectItems( $element,$form,'year' );
					/*$outp .= $row .
					"\n\t<td class='{$rowColor}'>{$element['description']}</td>\n" .
					"\t<td class='{$rowColor}'>\n" .
					"\t" . $element['beginMonth'] . "\n" .
					$selectItems['month'] .
					"\t" . $element['endMonth'] . "\n" .
					"\t" . $element['beginDay'] . "\n" .
					$selectItems['day'] .
					"\t" . $element['endDay'] . "\n" .
					"\t" . $element['beginYear'] . "\n" .
					$selectItems['year'] .
					"\t" . $element['endYear'] . "{$element['required']}\n" .
					"\t</td>\n\t";
					*/
					$desc = $element['description'];
					$name = $element['name'];
					$code = $element['beginMonth'] . "\n" .	$selectItems['month'] .	"\t" . $element['endMonth'] . "\n" ."\t" . $element['beginDay'] . "\n" .
					$selectItems['day'] ."\t" . $element['endDay'] . "\n" ."\t" . $element['beginYear'] . "\n" .$selectItems['year'] ."\t" . $element['endYear'];
					$req = $element['required'];
					
					$outp .= $this->boxLayout($code, $name, $req, $desc);
					
					
					$i = ($i + 2);
					break;
				case 'select':
					$selectItems = $this->getSelectItems( $element,$form,'select' );
					
					/*
					$row .
					"\n\t<td class='{$rowColor}'>{$element['description']}</td>\n" .
					"\t<td class='{$rowColor}'>\n" .
					"\t" . $element['beginCode'] . "\n" .
					$selectItems .
					"\t" . $element['endCode'] . "{$element['required']}\n" .
					"\t</td>\n\t";
					*/
					$desc = $element['description'];
					$name = $element['name'];
					$code = $element['beginCode'] . "\n" .$selectItems ."\t" . $element['endCode'];
					$req = $element['required'];
					
					$outp .= $this->boxLayout($code, $name, $req, $desc);
					
					$i = ($i + 2);
					break;
				case 'formtext':
					$outp .= $row . "<td id='{$element['name']}' class='{$element['class']}' colspan='{$cols}' align='{$element['align']}'>{$element['description']}</td>";
					$i = ($i + $cols);
					break;
				case 'text':
					//$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$desc = $element['description'];
					$name = $element['name'];
					$code = $element['code'];// . "\n" .$selectItems ."\t" . $element['endCode'];
					$req = $element['required'];
					
					$outp .= $this->boxLayout($code, $name, $req, $desc);
					$i = ($i + 2);
					break;
				case 'password':
					$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$i = ($i + 2);
					break;
				case 'checkbox':
					$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$i = ($i + 2);
					break;
				case 'radio':
					$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$i = ($i + 2);
					break;
				case 'textarea':
					//$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$outp .= '<div class="tier-2-aa">
								<div class="article-right">
									<div class="info-text-h">
									'.$element['description'].$element['code'].$element['required'].'
									</div>
								</div>
							</div>';
					$i = ($i + 2);
					break;
				case 'tray':
					$selectItems = $this->getSelectItems( $element,$form,'tray' );
					if( $element['id'] == $el_num - 1 )
					{
					//	$outp .= $rowEnd;
						$rowEnd = '';
					}
					//$outp .= "\t" . $element['beginCode'] . "\n" .					$selectItems .					"\t" . $element['endCode'] . "\n";
					$outp .= '<div class="tier-3-a">
							<div class="tier-3-b">
								<div class="config">
									<div class="config-name">
									</div>
									<div class="config-input">
									'.$selectItems.'
									</div>
								</div>
							</div></div>';
					break;
				case 'row':
					if($element['class'] != '')
					{
						$rowColor = $element['class'];
					}
					//$outp .= $row . "<td id='{$element['name']}' class='{$rowColor}' colspan='{$element['span']}'>{$element['description']}</td>";
					$outp .= '<div class="tier-3-a">
							<div class="tier-3-b">
								<div class="config">
									<div class="config-name">
									</div>
									<div class="config-input">
									'.$element['description'].'
									</div>
								</div>
							</div></div>';
					$i = ($i + $element['span']);
					break;
				case 'column':
					if($element['class'] != '')
					{
						$rowColor = $element['class'];
					}
					$outp .= $row . "<td id='{$element['name']}' class='{$rowColor}' colspan='{$element['span']}'>{$element['description']}</td>";

					$i = ($i + $element['span']);
					break;
			}
			$outp .= "\n";
			$element = $this->getNext($form);
			$outp .= $rowEnd;
		}
	//	$outp .= "</table>\n";
		if ($ftgs)
		{
		$outp .= "</table></form>\n";
		}
		return $outp;
	}
	
	
	function getTableOfElements_3($rowColoring=0,$form,$i=1,$ftgs=true)
	{
		$outp = "";
		$element = $this->getNext($form);
		$el_num = $this->numElements($form);
		$cols = $this->formCols[$form];
		$rowEnd = '';
		while( $element )
		{
			if( $i > $cols)
			{
				$i = 1;
			}

			if( $i <= $cols )
			{
				if( $i == 1 && $element['id'] != 0 )
				{
					$row = '</tr><tr>';
				}
				elseif( $i == 1 && $element['id'] == 0 )
				{
					$row = '<tr>';
				}
				else
				{
					$row = '';
				}
				if( $element['id'] == $el_num - 1 )
				{
					$rowEnd = '</tr>';
				}
			}

			if ($rowColoring == 1)
			{
				if ($element['id']&1)
				{
					$rowColor = 'membersRow1';
				}
				else
				{
					$rowColor = 'membersRow2';
				}
			}
			else
			{
				$rowColor = 'formElement';
			}

			$outp .= "\t";

			if ($element['required'] == 1)
			{
				$element['required'] = '<font color="#FF0000">*</font>';
			}
			else
			{
				$element['required'] = '';
			}

			switch($element['type'])
			{
				case 'dateselect':
					$selectItems['month'] = $this->getSelectItems( $element,$form,'month' );
					$selectItems['day'] = $this->getSelectItems( $element,$form,'day' );
					$selectItems['year'] = $this->getSelectItems( $element,$form,'year' );
					/*$outp .= $row .
					"\n\t<td class='{$rowColor}'>{$element['description']}</td>\n" .
					"\t<td class='{$rowColor}'>\n" .
					"\t" . $element['beginMonth'] . "\n" .
					$selectItems['month'] .
					"\t" . $element['endMonth'] . "\n" .
					"\t" . $element['beginDay'] . "\n" .
					$selectItems['day'] .
					"\t" . $element['endDay'] . "\n" .
					"\t" . $element['beginYear'] . "\n" .
					$selectItems['year'] .
					"\t" . $element['endYear'] . "{$element['required']}\n" .
					"\t</td>\n\t";
					*/
					$desc = $element['description'];
					$name = $element['name'];
					$code = $element['beginMonth'] . "\n" .	$selectItems['month'] .	"\t" . $element['endMonth'] . "\n" ."\t" . $element['beginDay'] . "\n" .
					$selectItems['day'] ."\t" . $element['endDay'] . "\n" ."\t" . $element['beginYear'] . "\n" .$selectItems['year'] ."\t" . $element['endYear'];
					$req = $element['required'];
					
					$outp .= $this->boxLayout($code, $name, $req, $desc);
					
					
					$i = ($i + 2);
					break;
				case 'select':
					$selectItems = $this->getSelectItems( $element,$form,'select' );
					
					/*
					$row .
					"\n\t<td class='{$rowColor}'>{$element['description']}</td>\n" .
					"\t<td class='{$rowColor}'>\n" .
					"\t" . $element['beginCode'] . "\n" .
					$selectItems .
					"\t" . $element['endCode'] . "{$element['required']}\n" .
					"\t</td>\n\t";
					*/
					$desc = $element['description'];
					$name = $element['name'];
					$code = $element['beginCode'] . "\n" .$selectItems ."\t" . $element['endCode'];
					$req = $element['required'];
					
					$outp .= $this->boxLayout($code, $name, $req, $desc);
					
					$i = ($i + 2);
					break;
				case 'formtext':
					$outp .= $row . "<td id='{$element['name']}' class='{$element['class']}' colspan='{$cols}' align='{$element['align']}'>{$element['description']}</td>";
					$i = ($i + $cols);
					break;
				case 'text':
					//$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$desc = $element['description'];
					$name = $element['name'];
					$code = $element['code'];// . "\n" .$selectItems ."\t" . $element['endCode'];
					$req = $element['required'];
					
					$outp .= $this->boxLayout($code, $name, $req, $desc);
					$i = ($i + 2);
					break;
				case 'password':
					$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$i = ($i + 2);
					break;
				case 'checkbox':
					$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$i = ($i + 2);
					break;
				case 'radio':
					$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$i = ($i + 2);
					break;
				case 'textarea':
					//$outp .= $row . "<td class='{$rowColor}'>{$element['description']}</td><td style='text-align:left;' class='{$rowColor}'>{$element['code']}{$element['required']}</td>";
					$outp .= '<div class="tier-2-aa">
								<div class="article-right">
									<div class="info-text-h">
									'.$element['description'].$element['code'].$element['required'].'
									</div>
								</div>
							</div>';
					$i = ($i + 2);
					break;
				case 'tray':
					$selectItems = $this->getSelectItems( $element,$form,'tray' );
					if( $element['id'] == $el_num - 1 )
					{
					//	$outp .= $rowEnd;
						$rowEnd = '';
					}
					//$outp .= "\t" . $element['beginCode'] . "\n" .					$selectItems .					"\t" . $element['endCode'] . "\n";
					$outp .= '<div class="tier-3-a">
							<div class="tier-3-b">
								<div class="config">
									<div class="config-name">
									</div>
									<div class="config-input">
									'.$selectItems.'
									</div>
								</div>
							</div></div>';
					break;
				case 'row':
					if($element['class'] != '')
					{
						$rowColor = $element['class'];
					}
					//$outp .= $row . "<td id='{$element['name']}' class='{$rowColor}' colspan='{$element['span']}'>{$element['description']}</td>";
					$outp .= '<div class="tier-3-a">
							<div class="tier-3-b">
								<div class="config">
									<div class="config-name">
									</div>
									<div class="config-input">
									'.$element['description'].'
									</div>
								</div>
							</div></div>';
					$i = ($i + $element['span']);
					break;
				case 'column':
					if($element['class'] != '')
					{
						$rowColor = $element['class'];
					}
					$outp .= $row . "<td id='{$element['name']}' class='{$rowColor}' colspan='{$element['span']}'>{$element['description']}</td>";

					$i = ($i + $element['span']);
					break;
			}
			$outp .= "\n";
			$element = $this->getNext($form);
			$outp .= $rowEnd;
		}

		return $outp;
	}
	function boxLayout($code, $name, $req, $desc)
	{
		$e = '<div class="tier-3-a">
							<div class="tier-3-b">
								<div class="config">
									<div class="config-name">
									
									<label for="'.$name.'">'.$desc.' '.$req.'</label>
									</div>
									<div class="config-input">
										'.$code.'
									</div>
								</div>
							</div>
						</div>';
		return $e;
	}
	/* get HTML output as a table
	 ** purpose: returns the HTML data from the container with the descriptions on the right and the form elements on the left */
	function getTableOfElements_2($rowColoring=0,$i=1)
	{
		$outp = "";
		$outp .= "<form method='{$this->method}' action='{$this->action}' name='{$this->name}' class='{$this->class}'>\n";
		//$outp .= "<table class='{$this->class}' cellspacing='0'>\n";
		$element = $this->getNext();
		$el_num = $this->numElements();
		$cols = $this->formCols;
		while( $element )
		{
			if( $i > $cols)
			{
				$i = 1;
			}

			if( $i <= $cols )
			{
				if( $i == 1 && $element['id'] != 0 )
				{
					$row = '</tr><tr>';
				}
				elseif( $i == 1 && $element['id'] == 0 )
				{
					$row = '<tr>';
				}
				else
				{
					$row = '';
				}
				if( $element['id'] == $el_num - 1 )
				{
					$rowEnd = '</tr>';
				}
			}

			if ($rowColoring == 1)
			{
				if ($element['id']&1)
				{
					$rowColor = 'membersRow1';
				}
				else
				{
					$rowColor = 'membersRow2';
				}
			}
			else
			{
				$rowColor = 'formElement';
			}

			$outp .= "\t";

			if ($element['required'] == 1)
			{
				$element['required'] = '<font color="#FF0000">*</font>';
			}
			else
			{
				$element['required'] = '';
			}

			switch($element['type'])
			{
				case 'dateselect':
					$selectItems['month'] = $this->getSelectItems( $element,'month' );
					$selectItems['day'] = $this->getSelectItems( $element,'day' );
					$selectItems['year'] = $this->getSelectItems( $element,'year' );
					$outp .= $row . "\n" .
					"\t<td class='{$rowColor}'>\n" .
					"\t" . $element['beginMonth'] . "\n" .
					$selectItems['month'] .
					"\t" . $element['endMonth'] . "\n" .
					"\t" . $element['beginDay'] . "\n" .
					$selectItems['day'] .
					"\t" . $element['endDay'] . "\n" .
					"\t" . $element['beginYear'] . "\n" .
					$selectItems['year'] .
					"\t" . $element['endYear'] . "\n" .
					"\t</td>\n" .
					"\t<td class='{$rowColor}'>{$element['description']}</td>\n" .
					"\t";
					$i = ($i + 2);
					break;
				case 'select':
					$selectItems = $this->getSelectItems( $element );
					$outp .= $row . "\n" .
					"\t<td class='{$rowColor}'>\n" .
					"\t{$element['required']}" . $element['beginCode'] . "\n" .
					$selectItems .
					"\t" . $element['endCode'] . "\n" .
					"\t</td>\n" .
					"\t<td class='{$rowColor}'>{$element['description']}</td>\n" .
					"\t";
					$i = ($i + 2);
					break;
				case 'formtext':
					$outp .= $row . "<td id='{$element['name']}' class='{$element['class']}' colspan='{$this->formCols}'>{$element['description']}</td>";
					$i = ($i + $cols);
					break;
				case 'text':
					$outp .= $row . "<td class='{$rowColor}'>{$element['required']}{$element['code']}</td><td class='{$rowColor}'>{$element['description']}</td>";
					$i = ($i + 2);
					break;
				case 'password':
					$outp .= $row . "<td class='{$rowColor}'>{$element['required']}{$element['code']}</td><td class='{$rowColor}'>{$element['description']}</td>";
					$i = ($i + 2);
					break;
				case 'checkbox':
					$outp .= $row . "<td class='{$rowColor}'>{$element['required']}{$element['code']}</td><td class='{$rowColor}'>{$element['description']}</td>";
					$i = ($i + 2);
					break;
				case 'radio':
					$outp .= $row . "<td class='{$rowColor}'>{$element['required']}{$element['code']}</td><td class='{$rowColor}'>{$element['description']}</td>";
					$i = ($i + 2);
					break;
				case 'textarea':
					$outp .= $row . "<td class='{$rowColor}'>{$element['required']}{$element['code']}</td><td class='areaText'>{$element['description']}</td>";
					$i = ($i + 2);
					break;
				case 'tray':
					$selectItems = $this->getSelectItems( $element );
					if( $element['id'] == $el_num - 1 )
					{
						$outp .= $rowEnd;
						$rowEnd = '';
					}
					$outp .= "\t" . $element['beginCode'] . "\n" .
					$selectItems .
					"\t" . $element['endCode'] . "\n";
					break;
				case 'column':
					if($element['class'] != '')
					{
						$rowColor = $element['class'];
					}
					$outp .= $row . "<td id='{$element['name']}' class='{$rowColor}' colspan='{$element['span']}'>{$element['description']}</td>";
					$i = ($i + $element['span']);
					break;
			}
			$outp .= "\n";
			$element = $this->getNext();
			$outp .= $rowEnd;
		}
		$outp .= "</table>\n";
		$outp .= "</form>\n";
		return $outp;
	}
}
/** test code
$user->form = new userForm('post','index.php','mainBody','formClass');
$selectId = $user->form->addSelect('selectbox name','description of the select box',1);
$user->form->addSelect_item($selectId,'item1val','item1description');
$user->form->addSelect_item($selectId,'item2val','item2description');
$user->form->addTextBox('textbox_name','textbox_value','description of the text box','css_class',1);
$user->form->addFormText('text_name','description of the text box','css_class');
$user->form->addCheckBox('checkbox_name','checkbox_value','checkbox_description',1);
$user->form->addRadioButton('radiobutton_name','radiobutton_value','radiobutton_description',1);
$user->form->addTextArea('textarea_name','textarea_value','description of the textarea',1);
echo $user->form->getTableOfElements_1(1); //1 for row colorizing
echo $user->form->getTableOfElements_2(); //blank or 0 for no colorizing
/**/
