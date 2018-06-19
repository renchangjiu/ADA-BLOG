$(function() {
	initCat(); // 初始化博文分类列表
	
});



//根据目标页码改变form 的action, 然后提交表单
function pageCondition(targetNum) {
	$("#search_form_1").attr("action", "search/1" + targetNum);
	$("#search_form_1").submit();
}

//根据输入的页码改变form 的action, 然后提交表单
function _go() {
	var targetNum = $("#pageCode").val(); 
	if (!/^[1-9]\d*$/.test(targetNum)) { //对当前页码进行整数校验
		alert('请输入正确的页码！');
		return;
	}
	if (targetNum > 10) { //判断当前页码是否大于最大页
		alert('请输入正确的页码！');
		return;
	}
	$("#search_form_1").attr("action", "search/1" + targetNum);
	$("#search_form_1").submit();
}



//初始化博文分类列表
function initCat() {
	$.ajax({
		url:"cat/list",
		type:"POST",
		dataType:"json",
		async:true,
		cache:false, //是否允许缓存, 默认true
		success:function(result) {
			if(result.status == 0) {
				alert("服务器异常, 请稍后再试, 或私信博主");
			} else {
				var data = result.data;
				var $catDL = $("#catDL");
				//添加节点后的结果
				// <dd style="font-size: 15px;"><a href="#" target="_blank">移动开发</a></dd>
				for(var i = 0; i < data.length; i++) {
					var ddEle = $('<dd style="font-size: 15px;"><a href="art/cat/' + data[i].catId + 
								  '/1" target="_blank">'+data[i].catName + 
								  '('+ data[i].artCount +')' +
								  '</a></dd>');
					$catDL.append(ddEle);
				}
			}
		}
	});
}