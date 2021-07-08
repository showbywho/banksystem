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
			<?PHP foreach($comments as $c){ ?>
			<blockquote>
				<p><?PHP echo $c['contents']; ?></p>
				<small>
					<?PHP echo $c['onwer']; ?>, <?PHP echo $c['times']; ?>, 
					<button class="see" data-id="<?php echo $c['id']; ?>">查看回覆</button>,
					<button class="rep">回覆留言</button>
				</small> 
				<div class="append<?php echo $c['id']; ?>"></div>
				<div class="reply<?php echo $c['id']; ?>"></div>
			</blockquote>
			<?PHP } ?>
		</div>
		<footer>
		</footer>
	</div>
<script>
    $(".see").click(function(){
		var item=$(this).data("id");
		$.ajax({
			type	: "GET",
			url		: "./controller/api.php?pmid="+item,
			dataType: "json",
			success:function(res){
				$(".append"+item).empty();        
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
	});
</script>
</body>
</html>
