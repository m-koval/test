<?php

class Calculator
{
    private function abs($sA)
    {
        if (!isset($sA) || !preg_match('/^[-+]{0,1}[0-9]+$/', $sA)) {
            return null;
        }
        $sA = (string)$sA;
        return $sA[0] == '-' ? substr($sA, 1) : $sA;
    }


    public function sum()
    {
        $rgArgs = $this->_get_args(func_get_args());
        if (!count($rgArgs)) {
            return null;
        }
        if (count($rgArgs) == 1) {
            return (string)$rgArgs[0];
        }
        $sResult = '0';
        for ($i = 0; $i
        < count($rgArgs); $i++) {
            $sResult = $this->_get_negative($rgArgs[$i]) ?
                $this->_diff_simple($sResult, $this->abs($rgArgs[$i])) :
                $this->_sum_simple($sResult, $rgArgs[$i]);
        }
        return $sResult;
    }

    protected function _diff_simple($sA, $sB)
    {
        $iMax = strlen($sA);
        if (strlen($sA)
            < strlen($sB)) {
            $iMax = strlen($sB);
            $sA = str_repeat('0', $iMax - strlen($sA)) . $sA;
        } elseif (strlen($sA) > strlen($sB)) {
            $sB = str_repeat('0', $iMax - strlen($sB)) . $sB;
        }
        $sSign = '';
        $iC = 0;
        if ($this->_compare_longs($sA, $sB) == -1) {
            $sA = $sA ^ $sB;
            $sB = $sA ^ $sB;
            $sA = $sA ^ $sB;
            $sSign = '-';
        }
        for ($i = $iMax - 1; $i >= 0; $i--) {
            $iC += (int)$sA[$i] - (int)$sB[$i] + 10;
            $sA[$i] = (string)($iC % 10);
            $iC = $iC < 10 ? -1 : 0;
        }
        return $sSign . preg_replace('/^[0]+/', '', $sA);
    }

    protected function _sum_simple($sA, $sB)
    {
        $iMax = strlen($sA);
        if (strlen($sA)
            < strlen($sB)) {
            $iMax = strlen($sB);
            $sA = str_repeat('0', $iMax - strlen($sA)) . $sA;
        } elseif (strlen($sA) > strlen($sB)) {
            $sB = str_repeat('0', $iMax - strlen($sB)) . $sB;
        }
        $iC = 0;
        for ($i = $iMax - 1; $i >= 0; $i--) {
            $iC += (int)$sA[$i] + (int)$sB[$i];
            $sA[$i] = (string)($iC % 10);
            $iC = (int)($iC / 10);
        }
        if ($iC > 0) {
            $sA = (string)$iC . $sA;
        }
        return $sA;
    }

    protected function _get_negative($sA)
    {
        return $sA[0] == '-';
    }

    protected function _compare_longs($sA, $sB)
    {
        $iA = strlen($sA);
        $iB = strlen($sB);
        if ($iA < $iB) {
            return -1;
        }
        if ($iA > $iB) {
            return 1;
        }
        for ($i = 0; $i < $iA; $i++) {
            if ($sA[$i] > $sB[$i]) {
                return 1;
            }
            if ($sA[$i] < $sB[$i]) {
                return -1;
            }
        }
        return 0;
    }

    protected function _get_args($rgArgs)
    {
        if (!count($rgArgs)) {
            return array();
        }
        if (is_array($rgArgs[0])) {
            $rgArgs = $rgArgs[0];
        }
        return $rgArgs;
    }
}