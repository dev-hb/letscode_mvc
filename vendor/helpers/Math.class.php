<?php


class Math {

    /**
     * @var string|null $expression
     */
    private $expression;
    private $exp_tokens = ["<", ">", "==", "<=", ">=", "!", "===", "!=", "!==", "&", "|"];

    /**
     * Math constructor.
     * @param null $expression
     */
    public function __construct($expression=null){
        $this->setExpression($expression);
    }

    /**
     * Evaluate given math expression and return logical result
     * @return bool|null
     */
    public function evaluate(){
        if(! $this->validate()) return null;
        $exp = $this->getExpression();
        if(! $this->inExpression("&&", "||")){
            return $this->evaluateSubExpression($this->getExpression());
        }
        return true;
    }

    private function evaluateSubExpression($exp){
        $token = $this->getUsedLogicalOperator($exp);
        var_dump($token);
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
        $this->expression = $expression;
    }

}