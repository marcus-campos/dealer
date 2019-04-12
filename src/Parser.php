<?php

namespace MarcusCampos\Dealer;

class Parser 
{
    /**
     * @var string
     */
    private $regex = "/[,]+(?![^\(]*\))/";

    /**
     * Parse
     *
     * @param string $queryText
     * @return array
     */
    public function parse($queryText)
    {
        $splitedValues = explode('->', $queryText);
        $response = [];
        $hasModel = false;

        foreach ($splitedValues as $value) {
            $key = substr($value, 0, strpos($value, '('));
            $stringBetweenParentheses = $this->getStringBetween($value, '(', ')');

            switch($key) {
                case 'filters':
                    $response['filters'] = preg_split($this->regex, $stringBetweenParentheses);
                    $response = $this->parseArgs('filters', $response, false);
                    break;

                case 'orderBy':
                    $response['orderBy'] = explode(',', $this->getStringBetween($value, '(', ')'));
                    break;

                case 'limit':
                    $response['limit'] = $this->getStringBetween($value, '(', ')');
                    break;

                case 'paginate':
                    $response['paginate'] = $this->getStringBetween($value, '(', ')');
                    break;

                case 'groupBy':
                    $response['groupBy'] = $this->getStringBetween($value, '(', ')');
                    break;

                default:
                    if (!$hasModel) {
                        $response['model'] = ucfirst($key);
                        $response['fields'] = preg_split($this->regex, $stringBetweenParentheses);
                        $response = $this->parseArgs('fields',$response, true);
                        $hasModel = true;
                    } else {
                        throw new Exception('You have a syntax problem near "'.$key.'"');
                    }

                    break;
            }
        }

        return $response;
    }

    /**
     * Get string between chars
     *
     * @param string $str
     * @param string $from
     * @param string $to
     * @return string
     */
    private function getStringBetween($str, $from, $to)
    {
        $sub = substr($str, strpos($str, $from)+strlen($from),strlen($str));
        return substr($sub,0, strrpos($sub, $to));
    }

    /**
     * Parse arguments
     *
     * @param string $element
     * @param array $data
     * @return array
     */
    private function parseArgs(string $element, array $data)
    {
        $response = $data;

        foreach ($response[$element] as $index => $value) {
            if (strpos($value, '(') + strpos($value, ')') > 1) {
                $fKey = substr($value, 0, strpos($value, '('));
                $fStringBetween = $this->getStringBetween($value, '(', ')');
                $response[$element]['extends'][] = [
                    "name" => $fKey, 'args' => $fStringBetween ? preg_split($this->regex, str_replace('\'', '',$fStringBetween)) : null
                ];
                unset($response[$element][$index]);
                continue;
            }

            $response[$element]['only'][] = $value;
            unset($response[$element][$index]);
        }

        return $response;
    }
}