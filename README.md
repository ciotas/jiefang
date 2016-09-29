###工程说明
微信点餐代码,以后的代码就通过git进行提交.  
测试代码流程:从git上下载代码到个人本地电脑,然后在本地进行开发,开发完成后将代码提交至git,然后登入到服务器121.41.49.199上的/var/www/html/目录下更新代码，然后再进行测试，比之前的开发步骤增加了一个代码的提交(push)和下拉(pull)步骤.  
###本地下载代码步骤
wechat项目中共有两个分支:master和development,其中master是主分支,主要用于保存稳定版本的代码以便随时可以进行代码的部署和修复,development分支主要用于代码的开发阶段,下面主要介绍下如何将development分支拉到本地进行开发的过程.如果有同学喜欢建立自己的分支进行开发也可以.  
1)使用git clone https://git.coding.net/jie_fang/wechat.git 命令将代码clone至自己机器上,如果不想使用wechat作为目录名可以采用下面的方式:  
 git clone https://git.coding.net/jie_fang/wechat.git xxx (xxx表示自己取的名字) 
  
2)用git branch -r可以查看到本地应该有类似下面的是哪个分支:  
origin/HEAD -> origin/master
origin/development
origin/master
  
3)使用命令git checkout -b dev origin/development将建立本地分支并将其和远程分支关联起来,其中dev代表你自己将要建立的分支名称  
  采用git branch可以查看当前自己处于哪个分支上  

4)在本地开发完成后,假如增加或者修改了某个文件,叫做xxx.php,可以按照下面的流程将其改动的内容推送到coding上的development分支上:  
git add xxx.php
git commit -m "add xxx.php" //引号内是提交的注释内容  
git push origin HEAD:development  


###基本git命令
可以参考https://coding.net/help/doc/git/index.html  
  
  
  
[如果发现本文档有错误,欢迎同学改正]
