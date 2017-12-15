<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\Url;
use think\Route;
class Index extends Controller
{
    public function index()
    {
        url::root('/ApplyForDreams/public/index.php');
        return $this->fetch();
    }

    public function show(){
        echo "<script>";
        echo "alert('提交成功！');";
        echo 'window.location.href="index.html";';
        echo "</script>";
    }

    /*验证内容
    * @param  String $i      代表正则表达式的键
    * @param  String $index  表单传送过来的值
    * @return Int    $res    验证的结果
    */
    private function checkindex($i,$index){
        $match = [
            'name'      => '/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',//验证姓名 + 过滤敏感词
            'pclass'    => '/^[\x{4e00}-\x{9fa5}\d]+$/u',//验证专业班级名称
            'tel'       => '/^(1[34578])\d{9}$/',//验证电话号码
        ];
        $res = preg_match($match[$i],$index);
        return $res;
    }

    public function check(){
        if(request()->isPost()){
            $arr        = input('post.');
            $name       = $arr['name'];
            $pclass     = $arr['pclass'];
            $tel        = $arr['tel'];
            $interest   = $arr['interest'];
            $sex        = $arr['sex'];
            $other      = $arr['interest_other'];
            $info       = $arr['intro'];
            $interests  = '';

            for($i = 0;$i < count($interest);$i++){
                $interests .= $interest[$i];
            }
            $interests = substr($interests,0,strlen($interests));
            if($other != ''){
                $interests = $interests."-".$other;
            }
            //dump($_FILES['photo']);
            if($_FILES['photo']['tmp_name']){
                 $file = request()->file('photo');
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'static/upload');
                $data['pic'] = '/static/upload/'.$info ->getSaveName();
                $newname = $data['pic'];
            }else{
                $newname = "";
            }

            if($this->checkindex('name',$name) == 0){
                $this->error("姓名！");
            }

            if($this->checkindex('pclass',$pclass) == 0){
                $this->error("班级");
            }

            if($this->checkindex('tel',$tel) == 0){
                $this->error("电话");
            }

            $data = [
                'name'      => $name,
                'pclass'    => $pclass,
                'tel'       => $tel,
                'interest'  => $interests,
                'sex'       => $sex,
                'info'      => $info,
                'pic'       => $newname,
            ];

            $res = Db::name('register')->insert($data);
            if($res > 0){
                $this->redirect("show");
            }
        }
    }

}
