(function(){
	if (window.reviewsec) return;
	console.groupEnd()
	console.clear()
	console.group("ReviewSec Log Info:")
	console.debug("reviewsec.js loaded")	
	getReviewsecHost();
	injectCSS();
	injectjQuery();
})();

function afterjQueryLoaded() {
	console.debug('jQuery loaded')		  
	window.reviewsec = {};
	getHostPageInfo();
	drawMainPanel();
	$('#revsec-main').ready(afterMainPanelLoaded);
}

function afterMainPanelLoaded() {
	console.debug('Main panel loaded')		
	if(!window.reviewsec.hostPage.supported) {
	  displayRedirection();
	  return;
	}
	setExtractor();
	parseProductInfo();
	feedProductToReviewSec();
	//startReviewCrawler()
}

function afterFeedProduct(data) {
	console.debug(data)
	getReviewPageQueue()
}

function afterGetReviewPageQueue(data){
    console.debug(data);
    window.reviewsec.reviewPageQueue = [];
    if(data.success){
      window.reviewsec.reviewPageQueue = data.pageQueue;
      window.reviewsec.reviewPageRemain = data.pageQueue.length;
      console.debug("Get "+data.pageQueue.length+ " review pages to crawl")
      startReviewCrawlers();
    }
    if(window.reviewsec.reviewPageQueue.length==0) {
        queryAnalysis()
    }
  }

function afterFeedReviewToReviewSec(data) {
	window.reviewsec.reviewPageRemain--;
	console.debug("The server responded feeding review ["+window.reviewsec.reviewPageRemain+"]")
	console.debug(data);
	if (window.reviewsec.reviewPageRemain == 0) {
		queryAnalysis()
	}
}

/* Helper Functions: */

function getReviewsecHost() {
  var jsList = document.getElementsByTagName("script");
  reviewsecHost = (/^(https?:\/\/[a-z A-Z 0-9 \. \:]+)\//.exec(jsList[jsList.length-1].getAttributeNode("src").value))[1];
  console.debug("Reviewsec host is " + reviewsecHost)
}

function injectjQuery() {
  if (typeof $ == "undefined") {
    var jsTag = document.createElement('script');
    jsTag.setAttribute('src', 'http://code.jquery.com/jquery-1.10.2.js');
    jsTag.setAttribute('onload', 'afterjQueryLoaded();');
    javascript: document.getElementsByTagName('head')[0].appendChild(jsTag);
    console.debug("jQuery loaded")
  } else {
	  afterjQueryLoaded();
  }
}

function injectCSS() {
  if (!window.revsecCssFileLoaded) {
    var cssTag = document.createElement('link');
    cssTag.setAttribute('rel', 'stylesheet');
    cssTag.setAttribute("type", "text/css");
    cssTag.setAttribute('href', reviewsecHost + '/css/style-webembedded.css');
    cssTag.setAttribute('onload', 'window.revsecCssFileLoaded=true;');
    javascript: document.getElementsByTagName('head')[0].appendChild(cssTag);
    console.debug("style-webembedded.css loaded");
  }
}

function setExtractor(){
	console.debug('setExtractor')
  window.reviewsec.extractor = {
      product:{
        fields:{
          Name:{
            extractor:[{selector:"#productTitle", reader: "text"},
                       {selector:"#btAsinTitle", reader: "text"},
                       {selector:"#aiv-content-title", reader: "text"}],
            validator: /(\w.+\w)/,
            required: true
          },
          Price:{
            extractor:[{selector:"#priceblock_ourprice", reader: "text"},
                       {selector:"#priceblock_saleprice", reader: "text"},
                       {selector:"#priceblock_dealprice > span", reader:"text"},
                       {selector:"#actualPriceValue > b", reader:"text"},
                       {selector:"#priceblock_dealprice", reader: "text"}],
            validator: /^\$(\d[\d,]*\.\d{2})/,
            required: true,
            converter: function(value){return value.replace(/,/g, '')}
          },
          Discount:{
            extractor:[{selector:"#regularprice_savings > td:nth-child(2)", reader: "text"}],
            validator: /\((.+)\%\)/,
            required: false
          },
          AverageRating:{
            extractor:[{selector:"#summaryStars > a", reader: "attr", argu: "title"},
                       {selector:"#platform-information-and-esrb-rating_feature_div span[title$=' stars']:first", reader: "attr", argu:"title"}],
            validator: /^(\d\.\d) out/,
            required: true
          },
          ImageURL:{
            extractor:[{selector:".selected .imgTagWrapper", reader: "attr", argu: "src"},
                       {selector:"#landingImage", reader: "attr", argu: "src"},
                       {selector:"#imgBlkFront", reader: "attr", argu: "src"},
                       {selector:"#main-image", reader: "attr", argu: "src"},
                       {selector:"#dv-dp-left-content > div.dp-left-meta.js-hide-on-play > div > div > img", reader: "attr", argu: "src"}],
            validator: /(^http.+\.\w{3,4}$)/,
            required: true
          },
          NumberOfReviews:{
            extractor:[{selector: "#acrCustomerReviewText", reader: "text"},
                       {selector:"[id^='acr-dpReviewsSummaryWithQuotes'] div.acrCount a", reader: "text"}],
            validator: /^(\d[\d,]*)/,
            required: true,
            converter: function(value){return value.replace(/,/g, '')}
          },
          Category:{
            extractor:[{selector: "#searchDropdownBox > option[selected='selected']", reader: "text"}],
            validator: /(^.+$)/,
            required: true
          }
        }
      },
      review:{
        wrapper:["#productReviews > tbody > tr > td:nth-child(1) > div",
                 "#cm_cr-review_list>div.review"],
        fields:{
          HelpfulVotes:{
            extractor:[{selector: ".review-votes", reader: "text"},
                       {selector: "> div:nth-child(1)", reader: "text"}
                       ],
            validator: /([\d,]+) of .+helpful/,
            dflt: 0,
            converter: function(value){return value.replace(/,/g, '')}
          },
          TotalVotes:{
            extractor:[{selector: ".review-votes", reader: "text"},
                       {selector: "> div:nth-child(1)", reader: "text"}],
            validator: /\d+ of ([\d,]+) .+helpful/,
            dflt: 0,
            converter: function(value){return value.replace(/,/g, '')}
          },
          Summary:{
            extractor:[{selector: ".review-title", reader: "text"},
                       {selector: "span>b:first", reader: "text"}
                       ],
            validator: /(^.+$)/,
            required: true
          },
          Rating:{
            extractor:[{selector: ".review-rating>span", reader: "text"},
                       {selector: "span[title$='5 stars']", reader: "attr", argu: "title"}
                       ],
            validator: /^(\d)\D*/,
            required: true
          },
          Date:{
            extractor:[{selector: ".review-date", reader: "text"},
                       {selector: "nobr:first", reader: "text"}
                       ],
            validator: /(^.+\d{4}\w*$)/,
            required: true,
            converter: function(value){
            	d = new Date(value);
            	return d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
            }
          },
          CustomerID:{
            extractor:[{selector: "a[href^='/gp/pdp/profile']", reader: "attr", argu: "href"}],
            validator: /profile\/([A-Z0-9]+)\//,
            required: false,
            dflt: ""
          },
          Content:{
            extractor:[{selector: ".reviewText", reader: "text"},
                       {selector: ".review-text", reader: "text"}],
            validator: /(.+$)/,
            required: true
          },
          Verified:{
            extractor:[{selector: "span:contains('Verified Purchase')"},
                       {selector: "span.crVerifiedStripe:first>b", reader: "text"}],
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
	if(typeof this.reader == "undefined") this.reader = "text";
    if(this.argu) value = $(data).find(this.selector)[this.reader](this.argu);
    else value = $(data).find(this.selector)[this.reader]();
//    console.info(value)
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

function parseProductInfo() {
	ASIN = window.reviewsec.hostPage.ASIN;
  console.debug("Parsing product information")
  if (window.reviewsec.product) return;
  window.reviewsec.product = {success: 1, ASIN: ASIN, URL: window.location.href};
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
    console.warn("Unkown product page for fields:")
    console.warn(failedFields)
    window.reviewsec.product.success = 0;
    window.reviewsec.product.failedFields = failedFields;
    window.reviewsec.product.message = "unkown product css path";
  }
  console.debug(window.reviewsec.product)
}

function parseReviewInfo(url, data) {
  console.debug("One review request return")
  var review = {
    success: 0,
    ASIN : window.reviewsec.product.ASIN,
    data : new Array()
  };
  var urlSplit = /pageNumber\=(\d+)/.exec(url);
  if(!urlSplit) {
    review.data = 0;
    review.success = 0;
    review.message = "invalid amazon review url";
    review.url = this.url;
    console.warn("Something wrong, the review page request url is invalid")
    console.warn(url)
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
          review.data[i]['ASIN'] = window.reviewsec.product.ASIN;
        });
        if(success) return false;// == break
      }
    });
    if(!success) {
      review.success = 0;
      review.message = "unkown css path";
      review.failedFields = failedFields;
      console.warn("####"+review.pageNumber+"th page has unknow css path")
      console.warn(failedFields)
      console.warn(url)
//      review.data = 0;
    }
  }
  
  console.debug(review.pageNumber+"th page has been parsed, now feed reviews to the server")
  console.debug(review)
  
  return review;
}

function feedReviewToReviewSec(review) {
	$.ajax({
		url : reviewsecHost + "/rest",
		async : true,
		type : "POST",
		data : $.param({
			action: 'feed_amazon_review',
			data: review,
			timestamp: Date.now()
		}, false),
		dataType : "json",
		success : afterFeedReviewToReviewSec
	});
}

function startReviewCrawlers() {
  console.debug("Start to send review page requests out")
  $.each(window.reviewsec.reviewPageQueue, function(key, value) {
    $.ajax({
      url : "http://www.amazon.com/gp/product-reviews/"
          + window.reviewsec.product.ASIN + "/ref=cm_cr_pr_top_link_"
          + value + "?pageNumber=" + value
          + "&showViewpoints=0&sortBy=bySubmissionDateAscending",
      success : function(data){
    	  var review = parseReviewInfo(this.url, data);
    	  feedReviewToReviewSec(review);
      },
      dataType : "html"
    });
    console.debug("The "+value+"th page request sent")
  });
}

function feedProductToReviewSec() {
	console.debug("Feeding product to the server")
	  $.ajax({
	    url : reviewsecHost + "/rest",
	    async: true,
	    type: "POST",
	    data: $.param({
	    	action: 'feed_amazon_product',
	    	timestamp: Date.now(),
	    	data: window.reviewsec.product }, false),
	    dataType: "json",
	    success : afterFeedProduct
	  });
}

function getReviewPageQueue() {
  console.debug("Retrieving review crawling tasks")
  window.reviewsec.reviewPageQueue = [];
  $.ajax({
    url : reviewsecHost + "/rest",
    async: true,
    type: "POST",
    data: $.param({
    	action: 'get_amazon_review_pages_to_crawl',
    	ASIN: window.reviewsec.hostPage.ASIN,
    	timestamp: Date.now()
    }, false),
    dataType: "json",
    success : afterGetReviewPageQueue
  });
}

function queryAnalysis() {
  console.debug("Sending analysis request to the server")
  $.ajax({
    url : reviewsecHost + "/rest",
    async: true,
    type: "POST",
    data: $.param({
    	ASIN: window.reviewsec.product.ASIN,
    	action: 'query_analysis'
    	}, false),
    dataType: "json",
    success : function(data){
      console.debug("The reviewsec server responded analysis results")
      console.debug(data);
      if(data.success!=1) {
        // something wrong, we should fix it asap
        console.warn("Cannot get analysis result, success!=1")
        $("#revsec-result").after("<p>"+data.message+"</p>")
        hideLoading()
      } else {
        hideLoading()
        data = data.results[0];
        if(data.malicious) {
        	$("#revsec-report-img").attr("alt", "Suspicous");
        	$("#revsec-report-img").attr("src", reviewsecHost+'/img/question.png');
        } else {
        	$("#revsec-report-img").attr("alt", "Good");
        	$("#revsec-report-img").attr("src", reviewsecHost+'/img/check.png');
        }
        
        $("#revsec-report-confidence").html(data.confidence);
        
        $("#revsec-result").show();
      }      
    }
  });
}

function displayRedirection() {
  hideLoading();
  $("#revsec-unsupported").show();
}

function drawMainPanel() {
  console.debug("Drawning reviewsec box in the screen")
  $('body').append(
        '<!--  BEGIN  -->'
        +'<div id="revsec-main">'
        +'<div id="revsec-close-button-div">'
        +'<img id="revsec-close-button"  title="Close the analyzer" onclick="cleanUpOnShutdown()"'
        +'src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxNDMxM0JBQUFCRTUxMUUyODZCQ0IyQzQxNEVBNDQ0NyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozNEI0Q0E1NkFDMDExMUUyODZCQ0IyQzQxNEVBNDQ0NyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjE0MzEzQkE4QUJFNTExRTI4NkJDQjJDNDE0RUE0NDQ3IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjE0MzEzQkE5QUJFNTExRTI4NkJDQjJDNDE0RUE0NDQ3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+VICatgAAA6dJREFUeNqcVktLW1EQnjxMKqkhVdMKWogKUmsNVLCmixSJXVgXsauCIAguXBb8A1JcKd26VXBbUNBNu9AgFKJWpSRqQy2RgA+sBhMrVZOY3M53ODdc85DUgUnOnTPzfec5c3RUXHTQeDzeZTab3Uaj8aXBYHiq0+keKYryO51O/7i+vl5OJBJfbTbbIvsqUksSgBtisdhbBgooJQj84I84GZ8HmPttTKVSn3nkXTBsbm7S2toaBYNB2t3dpWg0StXV1dTQ0EBOp5Pa29uptbVVBPPMFsvKyt6gqZ2VrgDBKhM839/fp7m5OZqfn6fT09Oi066srCSv10u9vb1UV1cHou9M1KEl0mkJksnkF3bwbG9v09TUFC0tLZW6xNTZ2UmDg4PU0tJCPFCfyWTqVon00kd/eHjoBQFmMDk5ST6fjzKZTMkKf8QhHjjAAy7JHzELXucPMMzMzNDCwgLxZv63Ig7xEIlnBL4gCYfDr5n9WSAQoNnZ2bxRjo2NFRx9ITvigQM84KokBqvV+grsfr+fjo+Pb4xufHycenp6aGtr64Yd37CjX2tHPHAgEtcgSMrLy3EaaGNjI28JhoeHs5sbCoWEDf+qoD83BjgQiStI9Hyjn2hBcrWxsTELurOzk23DXshfHYTE1WPT7/OljYNRDSomkUgk23Y4HEX9OP0Q7weaaU5DNrHxfIGisFRVVRU9OVoClbCYL3BkBoiqG69cXFz8grGpqangKdrb28uC19bWZtuwF/IHDkTiisuY4bQhdqqtrS1vVBMTE1nQmpoaYcO/KujPjQEOROJmBAlPfRlGt9stcpE2YGhoSJx9u91+w45v2NGvtSMeOHJJgZtBu4zVfn5+/hNpe3R0VGHHOyviIcADLvAxExynq/X19Y9g7O/vp+7u7julFcQhHiLxriS+yF1m1oe8hn6Mgi+T0tfXp1RUVJSs8EccBDjAk7jZcoKKZmF9fHZ2FoIjFyhlZGREqa+vVywWS1FFP/zgD0E8cCSeIa+eIBOwPjg5OfnEWfQFOlZXV0UaX1lZIdSZo6MjcbpQN1wuF3k8HuroEFkJVfMbH4h33IyxXuZWSJUIh8CKkfDJec91O1xKjYcf/OUMrBJHd2uNZzXJ6Vqnp6e7mpubXXwJnfwqcXDSs11eXvIjJh45ODgIcp5aGRgYwGvlD+tf1uRtNV5r02vI7kk1a0YIgBRrQp6gKw14ptAS3frukptnkMR6DUlGalpq0XfXPwEGAC/2hSkSgXukAAAAAElFTkSuQmCC">'
        +'</div><!-- END Close Button -->'
        +'<div id="revsec-header">'
        +'<img id="revsec-logo" height="40px" src="'+reviewsecHost+'/img/review-sec.png">'
        +'<span>ReviewSec</span>'
        +'</div><!-- END Header -->'
        +'<div id="revsec-loading">'
        +'<img id="revsec-loading-spinner" src="data:image/gif;base64,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">'
        +'<p>We are working hard analysing ...</p>'
        +'</div><!-- END Loading -->'
        +'<div id="revsec-result">'
        +'<img id="revsec-report-img" alt="Good" src="'+reviewsecHost+'/img/question.png" id="revsec-suspicious">'
        +'<span id="revsec-confidence">With confidence <span id="revsec-report-confidence">73</span>/100</span>'
        +'</div><!-- END Result -->'
        +'<div id="revsec-unsupported">'
        +'<p class="left">Sorry, but we are not supporting analysis for this page.</p>'
        +'<p>We recommend you to run this handy tool when viewing an Amazon product page.</p>'
        +'</div>'
        +'<div id="revsec-redirect">'
        +'<p><a href="'+reviewsecHost+'" target="_blank">Click here</a> come to our website and learn more about ReviewSec.</p>'
        +'</div>'
        +'</div>'
        +'<iframe src="'+reviewsecHost+'/web/tracking"></iframe>'
        +'<!-- END WEB-EMBEDDED -->'
  );
}

function hideLoading(){
  $("#revsec-loading").hide();
}

function getHostPageInfo(){
  console.debug("Getting host page information")
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
  window.reviewsec.hostPage.URL = window.location.href;
}
