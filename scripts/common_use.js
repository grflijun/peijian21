$.extend($.fn.validatebox.defaults.rules, {
	/* 必须和某个字段相等 */
	equalTo: { validator: function (value, param) { return $(param[0]).val() == value; }, message: '字段不匹配' }
});

$.extend($.fn.validatebox.defaults.rules, {
	/* 原来用Length总是不成功，所以重新写一个 */
	lenRange: { validator:function(value,param) { return value.length >= param[0] && value.length <= param[1]; },
		message: '数据的长度必须在指定的范围内！' }
});

$.extend($.fn.validatebox.defaults.rules, {
	maxLength: { validator: function(value, param){ return param[0] >= value.length; },   
 		message: '请输入最大{0}位字符!' }
});

$.extend($.fn.validatebox.methods, {  
	remove: function(jq, newposition){  
		return jq.each(function(){  
			$(this).removeClass("validatebox-text validatebox-invalid").unbind('focus.validatebox').unbind('blur.validatebox');
		});  
	},
	reuse: function(jq, newposition){  
		return jq.each(function(){  
		   var opt = $(this).data().validatebox.options;
		   $(this).addClass("validatebox-text").validatebox(opt);
		});  
	}	
});


function test_pass_strength(ctl, check)
{
	var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	var leastRegex = new RegExp("(?=.{6,}).*", "g");

	if(false == leastRegex.test($(ctl).val()))
	{
		check.css("color", "black");
		check.html('密码长度应在6到16个字符之间。');
	}
	else if(strongRegex.test($(ctl).val()))
	{
		check.css("color", "green");
		check.html('密码强度：强');
	}
	else if(mediumRegex.test($(ctl).val()))
	{
		check.css("color", "blue");
		check.html('密码强度：中等');
	}
	else
	{
		check.css("color", "red");
		check.html('密码强度：弱');
	}

	return true;
}
