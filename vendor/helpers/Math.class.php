<?php


class Math {

    /**
     * @var string|null $expression
     */
    private $expression;
    private $exp_tokens = ["===", "!=", "!==", "==", "<=", ">=", "<", ">", "!", "&", "|"];

    /**
     * Math constructor.
     * @param null $expression
     */
    public function __construct($expression=null){
        $this->setExpression($expression);
    }

    /**
     * Return stripped string (delete " and ')
     * @param $str
     * @return string|string[]
     */
    public function cleanString($str){
        return str_replace("\"", "", str_replace("'", "", str_replace(".", "/",$str)));
    }

    /**
     * Evaluate given math expression and return logical result
     * @return bool|null
     */
    public function evaluate(){
        if(! $this->validate()) return null;
        $exp = $this->getExpression();
        if(! $this->inExpression("&&") && ! $this->inExpression("||")){
            return $this->evaluateSubExpression($this->getExpression());
        }elseif(strpos($exp, "&&") !== false){
            $expressions = explode("&&", $exp);
            $values = [];
            foreach ($expressions as $e)
                $values[] = $this->evaluateSubExpression($e);
            $result = $values[0];
            for($i=1;$i<count($values);$i++)
                $result = $result && $values[$i];
            return $result;
        }else{
            $expressions = explode("||", $exp);
            $values = [];
            foreach ($expressions as $e)
                $values[] = $this->evaluateSubExpression($e);
            $result = $values[0];
            for($i=1;$i<count($values);$i++)
                $result = $result || $values[$i];
            return $result;
        }
        return null;
    }

    /**
     * Evaluate subexpression
     * @param $exp
     * @return bool|null
     */
    private function evaluateSubExpression($exp){
        $op = $this->getUsedLogicalOperator($exp);
        if($op != null && $op != ""){
            $parts = explode($op, $exp);
            switch ($op){
                case "==": return $parts[0] == $parts[1];
                case ">": return $parts[0] > $parts[1];
                case ">=": return $parts[0] >= $parts[1];
                case "<": return $parts[0] < $parts[1];
                case "<=": return $parts[0] <= $parts[1];
                case "!=": return $parts[0] != $parts[1];
                case "===": return $parts[0] === $parts[1];
                case "!==": return $parts[0] !== $parts[1];
                default: return null;
            }
        }else{
            return $exp ? true : false;
        } return null;
    }

    private function getUsedLogicalOperator($exp){
        foreach ($this->exp_tokens as $token)
            if(strpos($exp, $token) !== false)
                return $token;
        return null;
    }

    /**
     * Check if given tokens exists in expression
     * @param mixed ...$tokens
     * @return bool
     */
    public function inExpression(...$tokens){
        foreach ($tokens as $token)
            if(strpos($this->getExpression(), $token) === false)
                return false;
        return true;
    }

    /**
     * Validate given expression
     * @return bool
     */
    public function validate(){
        $exp = $this->getExpression();
        if(is_numeric(trim($exp))) return true;
        $valid = false;
        foreach (array_merge($this->exp_tokens, ["&&", "||"]) as $token){
            if(strpos($exp, $token) !== false)
                if(strlen($exp) > strlen($token)) $valid = true;
        } return $valid;
    }

    /**
     * @return string|null
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param string|null $expression
     */
    public function setExpression($expression){
        // replace all spaces with empty (normalization)
        $expression = preg_replace("/\s*/", "", $expression);
        $this->expression = $this->cleanString($expression);
    }

}