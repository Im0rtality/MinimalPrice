<?php
	class CodegenForm {
		
        public static function FormText($Data) {
            $disabled = "";
            if ($Data["disabled"] === true) {
                $disabled = " disabled";
            }
            return "<input type='text' name='{$Data['id']}' id='{$Data['id']}' value='{$Data['value']}'{$disabled}>";
        }

        public static function FormSelect($Data) {
            $disabled = "";
            if ($Data["disabled"] === true) {
                $disabled = " disabled";
            }
            $code = "<select name='{$Data['id']}' id='{$Data['id']}'{$disabled}>";
            foreach($Data['values'] as $Row) {
                $keys = array_keys($Row);

                $selected = "";
                if ($Data['value'] == $Row[$keys[0]]) {
                    $selected = " selected";
                }
                $code .= "<option value='{$Row['id']}'{$selected}>{$Row[$keys[1]]}</option>";
            }
            $code .= "</select>";
            return $code;
        }

         public static function FormTextarea($Data) {
            $disabled = "";
            if ($Data["disabled"] === true) {
                $disabled = " disabled";
            }
            return "<textarea name='{$Data['id']}' id='{$Data['id']}' rows='10' cols='80' style='width:80%' {$disabled}>".htmlentities($Data['value'])."</textarea>";
        }

        public static function FormAddLabel($Code, $for, $label) {
            $code = '<div class="control-group">';
            $code .= "<label class='control-label' for='{$for}'>{$label}</label>";
            $code .= '<div class="controls">';
            $code .= $Code;
            $code .= '</div>';
            $code .= '</div>';
            return $code;
        }

        public static function Form($Data) {
            $code = "<form class='form-horizontal' method='POST' action='?page={$Data['page']}&action=save'>";
            foreach ($Data['fields'] as $value) {
                if (!isset($value['disabled'])) { 
                    $value['disabled'] = false; 
                }
                switch ($value['type']) {
                    case 'text':
                        $ctrl = CodegenForm::FormText($value);
                         break;
                    case 'textarea':
                        $ctrl = CodegenForm::FormTextarea($value);
                        break;
                    case 'select':
                        $ctrl = CodegenForm::FormSelect($value);
                        break;
                }                
                $code .= CodegenForm::FormAddLabel($ctrl, $value['id'], $value['label']);
            }
            $code .= "<input type='hidden' name='id' value='{$Data['id']}'>";
            $code .= '<div class="form-actions">';
            $code .= '<button type="submit" class="btn btn-primary">Save changes</button>';
            $code .= '<button type="reset" class="btn">Cancel</button>';
            $code .= '</div>';
            $code .= "</form>";
            return $code;
        }
	}
?>