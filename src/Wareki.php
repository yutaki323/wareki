<?php
namespace Wareki;

/**
 * Date To Wareki
 *
 * @see https://github.com/yutaki323/wareki
 */
class Wareki
{
    protected $gengo_list = array(
        // 2019-05-01
        array('name' => '令和', 'name_short' => 'R', 'timestamp' =>  1556636400),
        // 1989-01-08
        array('name' => '平成', 'name_short' => 'H', 'timestamp' =>  600188400),
        // 1926-12-25'
        array('name' => '昭和', 'name_short' => 'S', 'timestamp' => -1357635600),
        // 1912-07-30
        array('name' => '大正', 'name_short' => 'T', 'timestamp' => -1812186000),
        // 1868-01-25
        array('name' => '明治', 'name_short' => 'M', 'timestamp' => -3216790800),
    );

    protected $kansuji_list = array(
        'placeholders' => array('000', '00', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
        'characters'   => array('千', '百', '十', '一','二', '三', '四', '五', '六', '七', '八', '九'),
    );

    protected $timestamp;

    protected $gengo;

    protected $nendo;

    public function __construct($date = null)
    {
        $this->setDate($date);
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        foreach ($this->gengo_list as $gengo) {
            if ($this->timestamp >= $gengo['timestamp']) {
                $this->gengo = $gengo;
                break;
            }
        }

        if ($this->gengo) {
            $this->nendo = intval((date('Y', $timestamp) - date('Y', $gengo['timestamp'])) + 1);
        }

        return !!$this->gengo;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setDate($date = null)
    {
        if ($date === null) {
            $timestamp = time();
        } elseif (($timestamp = strtotime($date)) === false) {
            return false;
        }

        return $this->setTimestamp($timestamp);
    }

    public function format($format = '{gengou}{nendo}年')
    {
        if (!$this->gengo) {
            return false;
        }

        $formatted = $format;

        $formatted = str_replace('{gengou}', $this->gengo['name'], $formatted);
        $formatted = str_replace('{gengou.short}', $this->gengo['name_short'], $formatted);
        $formatted = str_replace('{nendo}', $this->nendo, $formatted);
        $formatted = str_replace('{nendo.kanji}', $this->nendoToKanji($this->nendo, true), $formatted);
        $formatted = str_replace('{nendo.kansuji}', $this->nendoToKanji($this->nendo, false), $formatted);

        return $formatted;
    }

    public function nendoToKanji($nendo, $first_to_gannen = false)
    {
        $nendo = (int) floor($nendo);

        if ($nendo > 9999 || $nendo < 1) {
            throw new Exception('年度は自然数9999年までを指定してください。');
        }

        if ($nendo === 1 && $first_to_gannen) {
            return '元';
        }

        $numbers = str_split($nendo);
        $digit = strlen($nendo);

        $kansuji_parts = array();

        foreach ($numbers as $pos => $number) {
            if ($number === '0') {
                continue;
            }

            if ($pos + 1 < $digit) {
                $number = str_replace('1', '', $number);
            }

            $number .= str_repeat('0', $digit - $pos - 1);

            $kansuji_parts[$pos] = str_replace(
                $this->kansuji_list['placeholders'],
                $this->kansuji_list['characters'],
                strval($number)
            );
        }

        $kansuji =  implode($kansuji_parts);

        return $kansuji;
    }
}
