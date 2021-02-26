/**
 * 创建一个函数，用于储钱页面判断输入金额不能为空，且要大于0
 */
function checkCun(form){
	for(var i=0;i<form.length;i++){
		if(form.elements[i].value==""){
			alert("表单不能为空");
			return false;
		}
		if(form.elements[i].value<=0){
			alert("存储金额不能小于等于0");
			return false;
		}
	}
	return true;
}
/**
 * 创建一个函数，用于取钱判断页面，取出金额不能为空且要大于0
 */
function checkQu(form){
	for(var i=0;i<form.length;i++){
		if(form.elements[i].value==""){
			alert("表单不能为空");
			return false;
		}
		if(form.elements[i].value<=0){
			alert("提取金额不能小于等于0");
			return false;
		}
	}
	return true;
}
/**
 * 创建一个函数，用于转账页面判断
 * 转账金额不能为空且要大于0，都不填提示整个表单都为空
 */
function checkZhuan(form){
	for(var i=0;i<form.length;i++){
		if(form.elements[i].value==""){
			alert("表单不能为空");
			return false;
		}else if(form.elements[i].value<=0){
			alert("提取金额不能小于等于0");
			return false;
		}
	}
	return true;
}



