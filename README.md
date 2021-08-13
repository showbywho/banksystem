1. 在該資料夾筆下開終端機 輸入 ：
     	rm -rf .git
   就會將版本控制的檔案清除，這樣就回到最原始的乾淨的資料夾
2. 建立新檔案可用
	touch 檔名
3. 初始化  
	git init
4. 將操作者寫進cofig檔案中
	git config user.name calvin_yang
5. 將操作者的mail寫進cofig檔案中
	git config uesr.email calvin_yang@mail.chungyo.net
6. 將修改的檔案放進 追蹤/索引/暫存 區
	git add 檔名
	git add .
7. 將	追蹤/索引/暫存 區檔案推上檔案庫(repository)
	git commit -m “本次推送註解說明”
8. 經過兩項檢查後,刪除檔案
    —cached：將檔案從追蹤狀態改成未追蹤
	git rm —cached 檔名
	git commit —message “本次推送說明”
9. 
	git reset HEAD^ 檔名
10. 查看commit節點資訊
	git log --graph
11. 查看最新commit節點資訊,show後面也可以接檔案名稱顯示該檔案最新修改狀況 
	git show HEAD
12. 為節點貼上自訂標籤名
	git tag 自訂標籤名 commit 節點識別碼或者標籤
13. 為節點刪除標籤名
	git tag -d commit 節點標籤
14. 讓檔案庫(repository)回到某一個節點狀態
	git reset 選項 commit 節點識別碼或是標籤
	選項：1. --mixed 預設選項 索引回到指定節點狀態 但資料夾檔案不受影響
	     2. --soft  只有(repository)資料會變更
	     3. --hard. (repository)&git索引＆資料夾中的檔案都將回復到指定節點狀態 	
15.從檔案庫(repository)取出某節點檔案病覆蓋本地檔案
	git checkout commit 節點識別碼或者標籤 檔名1 檔名2 ...
16.搜尋指定節點中所有檔案是否包含要找的字串
	git grep '要找的字串' commit 節點
17.變更檔案名稱	
	git mv 原檔案名稱 新檔案名稱
	git commit -m "本次修改動作註解"
18.建立分支
	git branch 分支名稱
19.查看現有分支
	git branch
20.
	a.刪除資料夾動作 r=資料夾
	  git rm -r .   
	  git commit -m "本次修改動作註解"  
	b.刪除檔案
	  git rm 檔案名稱
21.合併多餘commit
	a.  找到要合併的節點的“上一個”節點ID
	b.  git rebase -i 節點ID
	c.  將pick 改成squash
	d.  編輯commit文黨
	e.  git push --force
22.取得遠端數據庫的最新歷史記錄。
	git fetch  
23.將指定的commit 添加到當前位置
	git cherry-pick
24.讓遠端repository commit 只有一個的操作
	a.git add .
	b.git commit --amend
	c.編輯commit文檔
	d.git push --force




工作操作順序總結
1.先從git克隆專案到本機
	git clone ssh://git@gitlab.infinity:10022/training/calvin_yang.git 
2.開始作業
   A.新增檔案/修改檔案後執行下面指令將檔案移到 追蹤/索引/暫存 區
	git add .  或git add 檔案名稱
   B.將上述指令送出 並紀錄下註解
	git commit -m "本次執行原因註解"
3.將此專案修改推送至雲端gitlab
	git push



Docker容器MySQL IP查詢步驟
1.docker ps -a      查看容器name
2.docker inspect lnmp_mysql_1



ABtest用到的指令：

ab -n 10000 -c 1 -p /Users/calvin_yang/local/symfony/test.txt -T application/x-www-form-urlencoded -C "PHPSESSID=5af53jbphqlka3offo94qhu4hp" http://127.0.0.1:8000/incoming 


Symfony專案新建流程：
1.運用命令創建專案資料夾 並移動到該資料夾
	symfony new bank  --version=5.2
	cd bank
2.安裝依賴包
	symfony composer req profiler --dev
3.安裝log包	
	symfony composer req logger
	symfony composer req debug --dev
4.生成控制器
	symfony composer req maker --dev
	** 以下指令可以觀察目前可用的生成器 **	
	symfony console list make  
5.註解依賴包安裝
	symfony composer req annotations
6.透過指令生成controller 
	symfony console make:controller 檔名
	ex: symfony console make:controller Member
7.安裝doctrine類來操作數據庫
	symfony composer req "orm:^2"
8.透過指令生成實體(Entity)
	symfony console make:entity 類名	
	ex: symfony console make:entity Bank
9.透過創建的Entity創建資料庫實體表
	symfony console make:migration    			—創建語法檔案
	symfony console doctrine:migrations:migrate 	—將上一段執行至資料庫中
10.套用EasyAdmin 包快速建立後台
	symfony composer req “admin:^3”
	mkdir src/Controller/Admin/
	symfony console make:admin:dashboard     	—創建後台入口文件  直接按enter兩次
	symfony console make:admin:crud			—創建CURD的Controller檔案  後續全按enter
	
Controller 的一些use 類:
1. http 的請求類   （預防Xss攻擊） 
use Symfony\Component\HttpFoundation\Request;



Git專案修改流程
1. 在gitlab上創建issues
2. 修改程式
3. 完成後在gitlab讓點選Ｍerge Requsets
	tittle需要特定格式ex:  [Feature]+issues標題
					  [Bug]+issues標題
4. 學長在他的電腦code review


部門寫作注意事項：
** 一般類
— 沒用到的變數不要宣告
— 不用 array() 用 [] ，例如： array('a' => 1) 要用 ['a' => 1]
— 最後一行要空行
— 判斷句不用== 改用=== (psr規範)
— 判斷句前後都需要空一格
— foreach 前後需要空一格
— class不需要註解
— function命名要盡量符合其目的
— 每個目的要分開寫function (微服務概念,像是做分頁的例子)

** 框架類:
— getter and setter 不管有沒有用到都要宣告
— 使用的method需要符合語意 ex:findBy()跟findAll()

** 資料庫類:
—  表名使用big camel case
—  欄位名使用snake case


Nginx path:
The default port has been set in /usr/local/etc/nginx/nginx.conf to 8080 so that
nginx can run without sudo.

nginx will load all files in /usr/local/etc/nginx/servers/.
  
php -S 127.0.0.1:8000 -t public/    
symfony serve --port=8000
以上兩行命令相等    後者有http“s”可用
./bin/console doctrine:database:createphp
// argument 引數
getInt('p', 1);
getInt(argument, argument);

// parameter 參數
Function getInt(parameter, parameter, parameter){
		code…
}


註解範例
/**
     * 分頁資料獲取
     * @param  string $page 當前分頁頁面編號
     * @return array
     */

Symfony框架資料庫的操作方法：
Insert into:
$msgInsert = new MsgBoard();
        $msgInsert->setContents($contents);
        $msgInsert->setOwner($names);
        $msgInsert->setTimes($times);
        $msgInsert->setUserIp($ip);
        $em->persist($msgInsert);
        $em->flush();

update:
msgBoard = $em->find('App\Entity\MsgBoard', $id);
        $msgBoard->setContents($contents);
        $msgBoard->setUserIp($ip);
        $em->persist($msgBoard);
        $em->flush();

delete:
$msgBoard = $em->getRepository('App\Entity\MsgBoard')->find($id);
$em->remove($msgBoard);
//如有筆資料需要刪除
 $replyBoard = $em->getRepository('App\Entity\Reply')->findBy(['tag' => $id]);
foreach ($replyBoard as $value) {
   $em->remove($value);
}
$em->flush();

Select:
$em = $this->getEntityManager();
$query = $em->getRepository('App\Entity\Reply')->findby(['tag' => $pmId]);
$arrayQuery = [];
foreach ($query as $obj) {
	  $arrayQuery[] = $obj->toArray();
}
return $arrayQuery;




RESTful API的一些遵循準則:
1.用一個唯一的 URL 定位資源，將動作藏在 HTTP 的 method 裡面。
	Ex: 
	獲得資料GET     	/data 
	新增資料POST    	/data 
	刪除資料DELETE  	/data/1

2.無狀態：無狀態的意思，即 Client 端自行保存狀態，在請求 Server 的時候，一併附上給 Server 端，Server 端無保存 Client 端的狀態資訊。

3.對應: 
GET		=> 獲取、Select	,
POST	=> 新增、Create	,
PUT		=> 更新、Update	,
DELETE	=> 刪除、Delete	.


