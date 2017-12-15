<?php
namespace app\admins\controller;
use think\Controller;
use think\Db;
use PHPExcel_IOFactory;
use PHPExcel;
class Index extends Controller
{

    public function index(){
        $data = Db::name('register') -> paginate(8);
        $this -> assign('data',$data);
        return $this->fetch();
    }

    public function getexcel()
    {
        $res = Db::name('register') -> select();
        $num = count($res);
        $path = dirname(__FILE__); //找到当前脚本所在路径
        $PHPExcel = new PHPExcel(); //实例化PHPExcel类，类似于在桌面上新建一个Excel表格
        $PHPSheet = $PHPExcel->getActiveSheet(); //获得当前活动sheet的操作对象
        $PHPSheet->setTitle('register'); //给当前活动sheet设置名称
        $PHPSheet->getDefaultColumnDimension()->setWidth(30) ;

        $PHPSheet->setCellValue('A1','序号')->setCellValue('B1','姓名')->setCellValue('C1','性别')->setCellValue('D1','班级')->setCellValue('E1','联系电话')->setCellValue('F1','个人简介')->setCellValue('G1','添加时间');//给当前活动sheet填充数据，数据填充是按顺序一行一行填充的，假如想给A1留空，可以直接setCellValue(‘A1’,’’);
        
        for($i=0;$i<$num;$i++){
            $a = $i + 2;
            settype($i,"string");
            settype($a,"string");
            $PHPSheet->setCellValue('A'.$a,$res[$i]['id'])->setCellValue('B'.$a,$res[$i]['name'])->setCellValue('C'.$a,$res[$i]['pclass'])->setCellValue('D'.$a,$res[$i]['tel'])->setCellValue('E'.$a,$res[$i]['interest'])->setCellValue('F'.$a,$res[$i]['info'])->setCellValue('G'.$a,$res[$i]['addtime']);
        }
        
        
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//按照指定格式生成Excel文件，‘Excel2007’表示生成2007版本的xlsx，
        //$PHPWriter->save($path.'/register.xlsx'); //表示在$path路径下面生成demo.xlsx文件
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
        //header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
        header('Content-Disposition: attachment;filename="01simple.xlsx"');//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $PHPWriter->save("php://output");
    }
}
