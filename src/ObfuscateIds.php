<?php
// +----------------------------------------------------------------------
// | AY [ 嘉游科技 ]
// +----------------------------------------------------------------------
// | Copyright © 2018~2019 https://ayjiayou.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: allyfireshen
// +----------------------------------------------------------------------
// | DateTime: 2019/4/10 16:18
// +----------------------------------------------------------------------

namespace ObfuscateIds;


class ObfuscateIds
{
    /**
     * The alphabet string
     * @var string
     */
    protected $alphabet;

    /**
     * The random string
     * @var string
     */
    protected $random;

    /**
     * The alphabet length
     * @var integer
     */
    protected $size;

    /**
     * The result length
     * @var integer
     */
    protected $length;

    /**
     * The rule of the regex
     * @var string
     */
    protected $rules;

    public function __construct($alphabet = "C7YQTHLKRGW6F8AMNB42EVXJ", $random = "5DPU3", $length = 6)
    {
        $alphabet = \mb_convert_encoding($alphabet, 'UTF-8', \mb_detect_encoding($alphabet));
        $this->alphabet = \implode("", \array_unique($this->multiByteSplit($alphabet)));

        $random = \mb_convert_encoding($random, 'UTF-8', \mb_detect_encoding($random));
        $this->random = $random;
        $this->length = $length;

        $this->size = \mb_strlen($this->alphabet);

        if (\mb_strlen($this->alphabet) < 16) {
            throw new ObfuscateIdsException("The alphabet must contain at least 16 unique characters.");
        }

        if (false !== \mb_strpos($this->alphabet, " ")) {
            throw new ObfuscateIdsException("The alphabet can\'t contain spaces.");
        }

        $this->rules = '/[' . $this->random . ']/';
        if (\preg_match($this->rules, $this->alphabet) == 1) {
            throw new ObfuscateIdsException("The alphabet and the random can't have the same characters and numbers.");
        }
    }

    /**
     * Generate the id
     * @param number $number
     * @return string
     */
    public function encode($number) : string
    {
        $ret = "";
        $size = $this->size;
        $length = $this->length;

        if (!\is_numeric($number)) {
            throw new ObfuscateIdsException("Please enter the the digit!");
        } else {
            $number = \intval($number);
        }

        while (($mod = $number % $size) != 0 | ($number = (integer)($number / $size))) {
            $ret .= $this->convert($mod);

            if ($number < $size) {
                if ($number != 0) {
                    $ret .= $this->convert($number);
                }
                break;
            }
        }

        if (\mb_strlen($ret) < $length) {
            $ret_array = \str_split($ret);
            $random_count = $length - \sizeof($ret_array);
            while ($random_count > 0) {
                $ret_array[] = $this->getRandomString();
                $random_count--;
            }
            $ret = \implode("", $ret_array);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $ret .= "-";
            }
        }

        return $ret;
    }

    /**
     * Decode the code
     * @param $code
     * @return float|int
     */
    public function decode($code)
    {
        $number = 0;
        $ret_array = [];
        $key_list = [];
        $alphabet = $this->alphabet;
        $code_array = \str_split($code);
        $alphabet_array = \str_split($alphabet);
        $rules = $this->rules;
        $size = $this->size;

        if (\preg_match($rules, $code) == 1) {
            foreach ($code_array as $key => $value) {
                if (!\preg_match($rules, $value)) {
                    $ret_array[$key] = $value;
                }
            }
        } else {
            $ret_array = $code_array;
        }

        foreach ($ret_array as $k => $val) {
            $key_list[] = \array_search($val, $alphabet_array, true);
        }

        $key_count = \count($key_list) - 1;
        foreach ($key_list as $k => $v) {
            $number += intval($v) * \pow($size, $key_count - $k);
        }

        return $number;
    }

    /**
     * Get the random string according to the random
     * @return mixed
     */
    protected function getRandomString()
    {
        $random = $this->random;
        $random_array = \str_split($random);
        return $random_array[\rand(0, sizeof($random_array) - 1)];
    }

    /**
     * Convert the number
     * @param number $decNum
     * @return string
     */
    protected function convert($decNum) : string
    {
        $alphabet = $this->alphabet;
        $size = \mb_strlen($alphabet);
        $alphabetArray = \str_split($alphabet);

        if ($decNum < 0) {
            return "-";
        } elseif ($decNum < $size) {
            return $alphabetArray[$decNum];
        } else {
            return "-";
        }
    }

    /**
     * Replace single byte string with multi byte string
     * @param  string $var
     * @return array
     */
    protected function multiByteSplit($var) : array
    {
        return \preg_split('/(?!^)(?=.)/', $var) ? : [];
    }
}