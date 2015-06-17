console.clear()
console.info("++++reviewsec.js loaded")
// Get reviewsec.js host name
var jsList = document.getElementsByTagName("script");
reviewsecHost = (/^(https?:\/\/[a-z A-Z 0-9 \. \:]+)\//.exec(jsList[jsList.length-1].getAttributeNode("src").value))[1];
console.info("++++reviewsec host is " + reviewsecHost)
// Add css file
if (!window.revsecCssFileLoaded) {
	var cssTag = document.createElement('link');
	cssTag.setAttribute('rel', 'stylesheet');
	cssTag.setAttribute("type", "text/css");
	cssTag.setAttribute('href', reviewsecHost + '/css/reviewsec_embed.css');
	cssTag.setAttribute('onload', 'window.revsecCssFileLoaded=true;');
	javascript: document.getElementsByTagName('head')[0].appendChild(cssTag);
	console.info("++++reviewsec_embed.css loaded");
}

// Add jquery source
if (typeof $ == "undefined") {
	var jsTag = document.createElement('script');
	jsTag.setAttribute('src', 'http://code.jquery.com/jquery-1.10.2.js');
	jsTag.setAttribute('onload', 'injectJQueryUI()');
	javascript: document.getElementsByTagName('head')[0].appendChild(jsTag);
	console.info("++++jquery loaded")
} else {
	console.info("++++jquery found, skip loading")
	injectJQueryUI()
}

function injectJQueryUI() {
	ReviewSec();
	return false;
	
	if(jQuery.ui) {
		ReviewSec();
		return false;
	}
	var jsTag = document.createElement('script');
	jsTag.setAttribute('src', 'http://code.jquery.com/ui/1.11.2/jquery-ui.js');
	jsTag.setAttribute('onload', 'ReviewSec()');
	javascript: document.getElementsByTagName('head')[0].appendChild(jsTag);
	console.info("DBG: jQueryUI added")
}

function setExtractor(){
	window.reviewsec.extractor = {
			product:{
				fields:{
					title:{
						extractor:[{selector:"#productTitle", reader: "text"},
						           {selector:"#btAsinTitle", reader: "text"},
						           {selector:"#aiv-content-title", reader: "text"}],
						validator: /(\w.+\w)/,
						required: true
					},
					price:{
						extractor:[{selector:"#priceblock_ourprice", reader: "text"},
						           {selector:"#priceblock_saleprice", reader: "text"},
						           {selector:"#priceblock_dealprice > span", reader:"text"},
						           {selector:"#actualPriceValue > b", reader:"text"}],
						validator: /^\$(\d[\d,]*\.\d{2})/,
						required: true,
						converter: function(value){return value.replace(/,/g, '')}
					},
					averagerating:{
						extractor:[{selector:"#summaryStars > a", reader: "attr", argu: "title"},
						           {selector:"#platform-information-and-esrb-rating_feature_div span[title$=' stars']:first", reader: "attr", argu:"title"}],
						validator: /^(\d\.\d) out/,
						required: true
					},
					image:{
						extractor:[{selector:"#landingImage", reader: "attr", argu: "src"},
						           {selector:"#imgBlkFront", reader: "attr", argu: "src"},
						           {selector:"#main-image", reader: "attr", argu: "src"},
						           {selector:"#dv-dp-left-content > div.dp-left-meta.js-hide-on-play > div > div > img", reader: "attr", argu: "src"}],
						validator: /(^http.+\.\w{3,4}$)/,
						required: true
					},
					numberofreviews:{
						extractor:[{selector: "#acrCustomerReviewText", reader: "text"},
						           {selector:"[id^='acr-dpReviewsSummaryWithQuotes'] div.acrCount a", reader: "text"}],
						validator: /^(\d[\d,]*)/,
						required: true,
						converter: function(value){return value.replace(/,/g, '')}
					},
					category:{
						extractor:[{selector: "#searchDropdownBox > option[selected='selected']", reader: "text"}],
						validator: /(^.+$)/,
						required: true
					}
				}
			},
			review:{
				wrapper:["#productReviews > tbody > tr > td:nth-child(1) > div",
				         "#cm_cr-review_list>div"],
				fields:{
					helpfulvotes:{
						extractor:[{selector: "> div:nth-child(1)", reader: "text"}],
						validator: /([\d,]+) of .+helpful/,
						dflt: 0,
						converter: function(value){return value.replace(/,/g, '')}
					},
					totalvotes:{
						extractor:[{selector: "> div:nth-child(1)", reader: "text"}],
						validator: /\d+ of ([\d,]+) .+helpful/,
						dflt: 0,
						converter: function(value){return value.replace(/,/g, '')}
					},
					summary:{
						extractor:[{selector: "span>b:first", reader: "text"}],
						validator: /(^.+$)/,
						required: true
					},
					rating:{
						extractor:[{selector: "span[title$='5 stars']", reader: "attr", argu: "title"}],
						validator: /^(\d).+stars/,
						required: true
					},
					date:{
						extractor:[{selector: "nobr:first", reader: "text"}],
						validator: /(^.+\d{4}\w*$)/,
						required: true
					},
					customerid:{
						extractor:[{selector: "a[href^='/gp/pdp/profile']", reader: "attr", argu: "href"}],
						validator: /profile\/([A-Z0-9]+)\//,
						required: false,
						dflt: ""
					},
					text:{
						extractor:[{selector: "> div[class='reviewText']", reader: "text"}],
						validator: /(.+$)/,
						required: true
					},
					verified:{
						extractor:[{selector: "span[class='crVerifiedStripe']:first>b", reader: "text"}],
						validator: /(Verified)/,
						dflt: ""
					}
				}
			}
	};
}

function cleanUpOnShutdown() {
	$("#revsec-main").remove();
	$("script:last").remove();
	window.reviewsec = undefined
}

function extractData(data, handler) {
	var value;
	var sp;
	var found = false;
	$.each(handler.extractor, function(){
		if(this.argu) value = $(data).find(this.selector)[this.reader](this.argu);
		else value = $(data).find(this.selector)[this.reader]();
//		console.info(value)
		sp = handler.validator.exec(value)
		if(sp){
			value = sp[1];
			if(handler.converter) value = handler.converter(value);
			found = true;
			return false;
		} else {
			value = handler.dflt;
		}
	});
	if(handler.required && !found) {
		return {success: false, value: undefined}
	} else {
		return {success: true, value: value}
	}
}

function parseProductInfo(ASIN) {
	console.info("++++parsing product information")
	if (window.reviewsec.product) return;
	window.reviewsec.product = {success: 1, ASIN: ASIN};
	var success = true;
	var failedFields = [];
	$.each(window.reviewsec.extractor.product.fields, function(field, handler) {
		var x = extractData(document, handler);
		if(!x.success) {
			success = false;
			failedFields.push(field);
		}
		window.reviewsec.product[field] = x.value;
	});
	if(!success){
		//TODO: notify server with unknown product page
		console.info("unkown product page for fields:")
		console.info(failedFields)
		window.reviewsec.product.success = 0;
		window.reviewsec.product.failedFields = failedFields;
		window.reviewsec.product.message = "unkown product css path";
	}
	
	console.info(window.reviewsec.product)
}

function parseReviewInfo(data) {
	console.info("++++one review request return")
	var review = {
		success: 0,
		ASIN : window.reviewsec.product.ASIN,
		token : window.reviewsec.crawlerToken,
		data : new Array()
	};
	var urlSplit = /pageNumber\=(\d+)/.exec(this.url);
	if(!urlSplit) {
		review.data = 0;
		review.success = 0;
		review.message = "invalid amazon review url";
		review.url = this.url;
		console.info("####something wrong, the review page request url is invalid")
		console.info(this.url)
	} else{
		var success = true;
		var failedFields = [];
		review.pageNumber = Number(urlSplit[1]);
		$.each(window.reviewsec.extractor.review.wrapper, function(tmp, reviewSelector){// get review block
			var reviewWrapperArray = $(data).find(reviewSelector);
			if(reviewWrapperArray.length){// selector works
				//console.info(reviewSelector)
				//console.info(reviewWrapperArray[0])
				review.success = 1;
				$.each(reviewWrapperArray, function(i, reviewWrapper){// loop for each block, TODO: one review case???
					review.data[i]={};
					$.each(window.reviewsec.extractor.review.fields, function(field, handler) {//loop for each field
						var x = extractData(reviewWrapper, handler);
						if(!x.success) {
							success = false;
							failedFields.push(field);
						}
						review.data[i][field] = x.value;
					});
					if(!success){
						review.success = 0;
						return false;
					}
				});
				if(success) return false;// == break
			}
		});
		if(!success) {
			review.success = 0;
			review.message = "unkown css path";
			review.failedFields = failedFields;
			console.info("####"+review.pageNumber+"th page has unknow css path")
			console.info(failedFields)
			console.info(this.url)
//			review.data = 0;
		}
	}
	
	console.info("++++"+review.pageNumber+"th page has been parsed, now feed reviews to the server")
	console.info(review)
	$.ajax({
		url : reviewsecHost + "/application/index/feedreview",
		async: true,
		type: "POST",
		data: $.param(review, false),
		dataType: "json",
		success : function(data){
			window.reviewsec.reviewPageRemain--;
			console.info("++++the server responded feeding review")
			console.info(data);
			if(window.reviewsec.reviewPageRemain==0) {
				queryAnalysis()
			}
		}
	});
}

function startReviewCrawler() {
	console.info("++++start to send review page requests out")
	if(window.reviewsec.reviewPageQueue.length==0) {
		queryAnalysis()
		// send analysis request
	}
	$.each(window.reviewsec.reviewPageQueue, function(key, value) {
		$.ajax({
			url : "http://www.amazon.com/gp/product-reviews/"
					+ window.reviewsec.product.ASIN + "/ref=cm_cr_pr_top_link_"
					+ value + "?pageNumber=" + value
					+ "&showViewpoints=0&sortBy=bySubmissionDateAscending",
			success : parseReviewInfo,
			dataType : "html"
		});
		console.info("++++the "+value+"th page request sent")
	});
}

function getReviewPageQueue() {
	console.info("++++feeding product to the server, and retrieving review crawling tasks")
	window.reviewsec.reviewPageQueue = [];
	$.ajax({
		url : reviewsecHost + "/application/index/lookupproduct",
		async: false,
		type: "POST",
		data: $.param(window.reviewsec.product, false),
		dataType: "json",
		success : function(data){
			console.info(data);
			if(!data.success){
				throw "cannot lookup product";
			} else {
				window.reviewsec.reviewPageQueue = data.pageQueue;
				window.reviewsec.crawlerToken = data.token;
				window.reviewsec.reviewPageRemain = data.pageQueue.length;
				console.info("++++get "+data.pageQueue.length+ " review pages to crawl")
			}
		}
	});
}

function showResult(data) {
	$("#revsec-result").append("<p>Base on "+data.num_reviews+" reviews from "+data.start+
			" to "+data.end+", the consistency evaluation is</p>"+
			'<h1 class="center">'+Math.round(data.consistency*100)+"/100</h1>");
	
	$("#revsec-result").append('<br><p>Aspects people are talking:</p><ul><li>Price</li><li>Quality</li><li>Customer service</li><li>Color</li><li>Size</li></ul>');
	
	$("#revsec-result").append('<br><img src="http://aserv.ele.uri.edu:18537/img/sample_price_chart.png" width="240px">');
	
	$("#revsec-result").append('<p>For more details about the result, please go to our offical website <a href="http://aserv.ele.uri.edu:18537" target="_blank">our official website</a> '+ 
		'to submit your analysis request and learn more about us</p>');
}

function queryAnalysis() {
	console.info("++++sending analysis request to the server")
	$.ajax({
		url : reviewsecHost + "/application/index/queryjson",
		async: true,
		type: "POST",
		data: {ASIN: window.reviewsec.product.ASIN},
		dataType: "json",
		success : function(data){
			console.info("++++the server responded analysis results")
			console.info(data);
			if(data.success!=1) {
				// something wrong, we should fix it asap
				console.info("####: cannot get analysis result, success!=1")
				$("#revsec-main").append("<p>"+data.message+"</p>")
				hideLoading()
			} else {
				hideLoading()
				showResult(data)
			}			
		}
	});
}

function displayRedirection() {
	hideLoading();
	$("#revsec-main").append(
		'<p class="left">Sorry, but we are not supporting analysis for this page.</p><p>We recommend you to run this handy tool when viewing '+
		'an Amazon product page. You can also go to <a href="http://aserv.ele.uri.edu:18537" target="_blank">our official website</a> '+ 
		'to submit your analysis request and learn more about us</p>'
	);
}

function drawDisplayPanel() {
	console.info("++++drawning reviewsec box in the screen")
	$('body').append(
		'<div id="revsec-main">'+
			'<div id="revsec-close-button-div">'+
				'<img id="revsec-close-button"  title="Close the analyzer" onclick="cleanUpOnShutdown()"'+
					'src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxNDMxM0JBQUFCRTUxMUUyODZCQ0IyQzQxNEVBNDQ0NyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozNEI0Q0E1NkFDMDExMUUyODZCQ0IyQzQxNEVBNDQ0NyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjE0MzEzQkE4QUJFNTExRTI4NkJDQjJDNDE0RUE0NDQ3IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjE0MzEzQkE5QUJFNTExRTI4NkJDQjJDNDE0RUE0NDQ3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+VICatgAAA6dJREFUeNqcVktLW1EQnjxMKqkhVdMKWogKUmsNVLCmixSJXVgXsauCIAguXBb8A1JcKd26VXBbUNBNu9AgFKJWpSRqQy2RgA+sBhMrVZOY3M53ODdc85DUgUnOnTPzfec5c3RUXHTQeDzeZTab3Uaj8aXBYHiq0+keKYryO51O/7i+vl5OJBJfbTbbIvsqUksSgBtisdhbBgooJQj84I84GZ8HmPttTKVSn3nkXTBsbm7S2toaBYNB2t3dpWg0StXV1dTQ0EBOp5Pa29uptbVVBPPMFsvKyt6gqZ2VrgDBKhM839/fp7m5OZqfn6fT09Oi066srCSv10u9vb1UV1cHou9M1KEl0mkJksnkF3bwbG9v09TUFC0tLZW6xNTZ2UmDg4PU0tJCPFCfyWTqVon00kd/eHjoBQFmMDk5ST6fjzKZTMkKf8QhHjjAAy7JHzELXucPMMzMzNDCwgLxZv63Ig7xEIlnBL4gCYfDr5n9WSAQoNnZ2bxRjo2NFRx9ITvigQM84KokBqvV+grsfr+fjo+Pb4xufHycenp6aGtr64Yd37CjX2tHPHAgEtcgSMrLy3EaaGNjI28JhoeHs5sbCoWEDf+qoD83BjgQiStI9Hyjn2hBcrWxsTELurOzk23DXshfHYTE1WPT7/OljYNRDSomkUgk23Y4HEX9OP0Q7weaaU5DNrHxfIGisFRVVRU9OVoClbCYL3BkBoiqG69cXFz8grGpqangKdrb28uC19bWZtuwF/IHDkTiisuY4bQhdqqtrS1vVBMTE1nQmpoaYcO/KujPjQEOROJmBAlPfRlGt9stcpE2YGhoSJx9u91+w45v2NGvtSMeOHJJgZtBu4zVfn5+/hNpe3R0VGHHOyviIcADLvAxExynq/X19Y9g7O/vp+7u7julFcQhHiLxriS+yF1m1oe8hn6Mgi+T0tfXp1RUVJSs8EccBDjAk7jZcoKKZmF9fHZ2FoIjFyhlZGREqa+vVywWS1FFP/zgD0E8cCSeIa+eIBOwPjg5OfnEWfQFOlZXV0UaX1lZIdSZo6MjcbpQN1wuF3k8HuroEFkJVfMbH4h33IyxXuZWSJUIh8CKkfDJec91O1xKjYcf/OUMrBJHd2uNZzXJ6Vqnp6e7mpubXXwJnfwqcXDSs11eXvIjJh45ODgIcp5aGRgYwGvlD+tf1uRtNV5r02vI7kk1a0YIgBRrQp6gKw14ptAS3frukptnkMR6DUlGalpq0XfXPwEGAC/2hSkSgXukAAAAAElFTkSuQmCC">'+
			'</div>'+
			'<img id="revsec-logo" width="40px" src="data:image/png;base64,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">'+
			'<div id="revsec-title">ReviewSec</div>'+
			'<div id="revsec-loading">'+
				'<img id="revsec-loading-spinner" src="data:image/gif;base64,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">'+
				'<p>Analyzing</p>'+
			'</div><div id="revsec-result"></div>'+
		'</div>'			
	);
}

function hideLoading(){
	$("#revsec-loading").hide();
}

function getHostPageInfo(){
	console.info("++++getting host page information")
	window.reviewsec.hostPage = {};
	var supportSitesRegexes = /www\.(amazon)\.com.+\/([A-Z0-9]{10})(\/|$|\?)/;
	var urlSplit = supportSitesRegexes.exec(window.location.href);
	if(!urlSplit) {
		window.reviewsec.hostPage.supported = false;
		return;
	}
	window.reviewsec.hostPage.supported = true;
	window.reviewsec.hostPage.site = urlSplit[1];
	window.reviewsec.hostPage.ASIN = urlSplit[2];
}

function ReviewSec() {
	console.info('++++reviewsec main routine started')
	if (window.reviewsec) return
		
	window.reviewsec = {};
	
	drawDisplayPanel()
	
	getHostPageInfo()
	if(!window.reviewsec.hostPage.supported) {
		displayRedirection();
		return;
	}
	setExtractor()
	parseProductInfo(window.reviewsec.hostPage.ASIN)
		
	getReviewPageQueue()
	startReviewCrawler()
}