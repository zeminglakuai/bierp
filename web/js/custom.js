function create_url(url){
	var if_cunzai =  url.indexOf('?');
	if (if_cunzai > 0) {
		return url+'&';
	}else{
		return url+'?';
	}
}