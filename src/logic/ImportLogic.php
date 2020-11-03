<?php
namespace xjryanse\system\logic;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * 导入逻辑
 */
class ImportLogic
{
    /**
     * 数据导入逻辑
     * @param string $file
     * @param int $sheet
     * @param int $columnCnt
     * @param type $options
     * @return type
     * @throws \Exception
     */
    public static function importExecl(string $file = '', int $sheet = 0, int $columnCnt = 0, &$options = [])
    {
        /* 转码 */
        $file = iconv("utf-8", "gb2312", $file);

        if (empty($file) OR !file_exists($file)) {
            throw new \Exception('文件不存在!');
        }

        /** @var Xlsx $objRead */
        $objRead = IOFactory::createReader('Xlsx');

        if (!$objRead->canRead($file)) {
            /** @var Xls $objRead */
            $objRead = IOFactory::createReader('Xls');

            if (!$objRead->canRead($file)) {
                throw new \Exception('只支持导入Excel文件！');
            }
        }

        /* 如果不需要获取特殊操作，则只读内容，可以大幅度提升读取Excel效率 */
        empty($options) && $objRead->setReadDataOnly(true);
        /* 建立excel对象 */
        $obj = $objRead->load($file);
        /* 获取指定的sheet表 */
        $currSheet = $obj->getSheet($sheet);

        if (isset($options['mergeCells'])) {
            /* 读取合并行列 */
            $options['mergeCells'] = $currSheet->getMergeCells();
        }

        if (0 == $columnCnt) {
            /* 取得最大的列号 */
            $columnH = $currSheet->getHighestColumn();
            /* 兼容原逻辑，循环时使用的是小于等于 */
            $columnCnt = Coordinate::columnIndexFromString($columnH);
        }

        /* 获取总行数 */
        $rowCnt = $currSheet->getHighestRow();
        $data   = [];

        /* 读取内容 */
        for ($_row = 1; $_row <= $rowCnt; $_row++) {
            $isNull = true;

            for ($_column = 1; $_column <= $columnCnt; $_column++) {
                $cellName = Coordinate::stringFromColumnIndex($_column);
                $cellId   = $cellName . $_row;
                $cell     = $currSheet->getCell($cellId);

                if (isset($options['format'])) {
                    /* 获取格式 */
                    $format = $cell->getStyle()->getNumberFormat()->getFormatCode();
                    /* 记录格式 */
                    $options['format'][$_row][$cellName] = $format;
                }

                if (isset($options['formula'])) {
                    /* 获取公式，公式均为=号开头数据 */
                    $formula = $currSheet->getCell($cellId)->getValue();

                    if (0 === strpos($formula, '=')) {
                        $options['formula'][$cellName . $_row] = $formula;
                    }
                }

                if (isset($format) && 'm/d/yyyy' == $format) {
                    /* 日期格式翻转处理 */
                    $cell->getStyle()->getNumberFormat()->setFormatCode('yyyy/mm/dd');
                }

                $data[$_row][$cellName] = trim($currSheet->getCell($cellId)->getFormattedValue());

                if (!empty($data[$_row][$cellName])) {
                    $isNull = false;
                }
            }

            /* 判断是否整行数据为空，是的话删除该行数据 */
            if ($isNull) {
                unset($data[$_row]);
            }
        }

        return $data;
    }

    

}
