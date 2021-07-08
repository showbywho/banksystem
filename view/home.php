<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>留言板</title>
	<meta name="viewport" content="width=device-width; initial-scale=1.0" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		.container{
			margin:15px auto;
		}
		
		.comment-count{
			margin-bottom: 10px;
		}
		
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
		<header>
			<h1>留言板</h1>
		</header>
		<div>
			<div class="comment-count">
				<span class="label notice">Notice</span>
				共有 <?PHP echo intval($totalComments); ?> 則留言.
			</div>
			<div>
				<button class="newre">新增留言</button>
				<div class="newform"></div>
			</div>
			<?PHP foreach($comments as $c){ ?>
			<blockquote>
				<div data-id="<?php echo $c['id']; ?>" class="p"><p class="pc<?php echo $c['id']; ?>"><?PHP echo $c['contents']; ?></p></div>
				<small>
					<?PHP echo $c['onwer']; ?>, <?PHP echo $c['times']; ?>, <?PHP echo $c['userIP']; ?>
					<button class="see" data-id="<?php echo $c['id']; ?>">查看回覆</button>,
					<button class="rep" data-id="<?php echo $c['id']; ?>">回覆留言</button>,
					<form action='./controller/api.php?tag=del' method='post' style="display:inline;">
						<input id='listid' name='listid'  type='hidden' value='<?php echo $c['id']; ?>' required/>
						<input class='button' name='commit' type='submit' value='刪除留言' />
					</form>
				</small>  
				<div class="append<?php echo $c['id']; ?>"></div>
				<div class="reply<?php echo $c['id']; ?>"></div>
			</blockquote>
			<?PHP } ?>
		</div>
		<footer>
			<a href="?p=1"><button>首頁</button></a>
			<a href="?p=<?php echo (($page-1)==0)?1:($page-1); ?>"><button>上一頁</button></a>
			<?PHP
				for ($i=1;$i<=$totalPage;$i++) {
					echo '<a href="?p='.$i.'"><button>'.$i.'</button></a>&nbsp';
				}
			?>
			
			<a href="?p=<?php echo ($page+1); ?>"><button>下一頁</button></a>
			<a href="?p=<?php echo $totalPage; ?>"><button>頁尾</button></a>
			
		</footer>
	</div>
<script>
    $(".see").click(function(){
		var item=$(this).data("id");
		var value = $('.append'+item).html();
		if(!value){
			$.ajax({
				type	: "GET",
				url		: "./controller/api.php?tag=see&pmid="+item,
				dataType: "json",
				success:function(res){
					    
					if(res!=""){
						$.each(res, function(key,val) {
							$(".append"+item).append("<blockquote><p>"+val.contents+"</p><small>,"+val.contents+","+val.contents+"</small></blockquote>")
						}); 
					}else{
						$(".append"+item).append("<p>暫無回覆留言</p>")
					}

					console.log(res)
				},error:function(err){
					console.log(err)
				}
			});
		}else{
			$(".append"+item).empty();    
		}
	});

	$(".rep").click(function(){
		var item=$(this).data("id");
		var value = $('.reply'+item).html();
		if(!value){
			var form=	`<div id='signup-form'>
							<form action='./controller/api.php?tag=reply&pmid=`+item+`' method='post'>
								<p><label for='names'>暱稱</label>
								<input id='names' name='names' size='30' type='text' required/>
								</p>
								<p><label for='contents'>內容</label>
								<input id='contents' name='contents' size='30' type='text' required/>
								</p>
								<p><input class='button' name='commit' type='submit' value='送出' /></p>
							</form>
						</div>`;
			$(".reply"+item).append(form)
		}else{
			$(".reply"+item).empty();        
		}
	});

	$(".newre").click(function(){
		var value = $('.newform').html();
		if(!value){
			var item=$(this).data("id");
			var form=	`<div id='signup-form'>
							<form action='./controller/api.php?tag=newre' method='post'>
								<p><label for='names'>暱稱</label>
								<input id='names' name='names' size='30' type='text' required/>
								</p>
								<p><label for='contents'>內容</label>
								<input id='contents' name='contents' size='30' type='text' required/>
								</p>
								<p><input class='button' name='commit' type='submit' value='送出' /></p>
							</form>
						</div>`;
			$(".newform").append(form)	
		}else{
			$(".newform").empty();  
		}
		
	});

	$(".del").click(function(){
		var item=$(this).data("id");
		var item=$(this).data("id");
		var form=	`<div id='signup-form'>
						<form action='./controller/api.php?tag=newre' method='post'>
							<p><label for='names'>暱稱</label>
							<input id='names' name='names' size='30' type='text' required/>
							</p>
							<p><label for='contents'>內容</label>
							<input id='contents' name='contents' size='30' type='text' required/>
							</p>
							<p><input class='button' name='commit' type='submit' value='送出' /></p>
						</form>
					</div>`;
		$(".newform").append(form)	
		
		
	});

	$(".p").dblclick(function(){
		var item=$(this).data("id");
		var html=$(this);
		var p=$(".pc"+item).html();
		html.empty();
		var form=	`<div id='signup-form'>
						<form action='./controller/api.php?tag=updatere&id=`+item+`' method='post'>
							<p>
								<input id='contents' name='contents' size='30' type='text' value='`+p+`'/>
							</p>
							<p><input class='button' name='commit' type='submit' value='送出' /></p>
						</form>
					</div>`;
		$(this).append(form)	
	});
</script>
</body>
</html>
