(function(e){function m(a,b,c){var d=parseInt(a.css("top"),10);"left"==b?(b="-"+this.image_wrapper_height+"px",a.css("top",this.image_wrapper_height+"px")):(b=this.image_wrapper_height+"px",a.css("top","-"+this.image_wrapper_height+"px"));c&&(c.css("bottom","-"+c[0].offsetHeight+"px"),c.animate({bottom:0},2*this.settings.animation_speed));this.current_description&&this.current_description.animate({bottom:"-"+this.current_description[0].offsetHeight+"px"},2*this.settings.animation_speed);return{old_image:{top:b},
new_image:{top:d}}}function n(a,b,c){var d=parseInt(a.css("left"),10);"left"==b?(b="-"+this.image_wrapper_width+"px",a.css("left",this.image_wrapper_width+"px")):(b=this.image_wrapper_width+"px",a.css("left","-"+this.image_wrapper_width+"px"));c&&(c.css("bottom","-"+c[0].offsetHeight+"px"),c.animate({bottom:0},2*this.settings.animation_speed));this.current_description&&this.current_description.animate({bottom:"-"+this.current_description[0].offsetHeight+"px"},2*this.settings.animation_speed);return{old_image:{left:b},
new_image:{left:d}}}function o(a){var b=a.width(),c=a.height(),d=parseInt(a.css("left"),10),e=parseInt(a.css("top"),10);a.css({width:0,height:0,top:this.image_wrapper_height/2,left:this.image_wrapper_width/2});return{old_image:{width:0,height:0,top:this.image_wrapper_height/2,left:this.image_wrapper_width/2},new_image:{width:b,height:c,top:e,left:d}}}function p(a){a.css("opacity",0);return{old_image:{opacity:0},new_image:{opacity:1}}}function q(a){a.css("opacity",0);return{old_image:{opacity:0},new_image:{opacity:1},
speed:0}}function k(a,b){this.init(a,b)}function l(a,b){this.init(a,b)}e.fn.adGallery=function(a){var b={loader_image:"./ckeditor/plugins/slideshow/3rdParty/ad-gallery/loader.gif",start_at_index:0,update_window_hash:!0,description_wrapper:!1,thumb_opacity:0.7,animate_first_image:!1,animation_speed:400,width:!1,height:!1,display_next_and_prev:!0,display_back_and_forward:!0,scroll_jump:0,slideshow:{enable:!0,autostart:!1,speed:5E3,start_label:"Start",stop_label:"Stop",stop_on_scroll:!0,countdown_prefix:"(",
countdown_sufix:")",onStart:!1,onStop:!1},effect:"slide-hori",enable_keyboard_move:!0,cycle:!0,hooks:{displayDescription:!1},callbacks:{init:!1,afterImageVisible:!1,beforeImageVisible:!1}},c=e.extend(!1,b,a);a&&a.slideshow&&(c.slideshow=e.extend(!1,b.slideshow,a.slideshow));c.slideshow.enable||(c.slideshow.autostart=!1);var d=[];e(this).each(function(){var a=new k(this,c);d[d.length]=a});return d};k.prototype={wrapper:!1,image_wrapper:!1,gallery_info:!1,nav:!1,loader:!1,preloads:!1,thumbs_wrapper:!1,
thumbs_wrapper_width:0,scroll_back:!1,scroll_forward:!1,next_link:!1,prev_link:!1,slideshow:!1,image_wrapper_width:0,image_wrapper_height:0,current_index:-1,current_image:!1,current_description:!1,nav_display_width:0,settings:!1,images:!1,in_transition:!1,animations:!1,init:function(a,b){var c=this;this.wrapper=e(a);this.settings=b;this.setupElements();this.setupAnimations();this.settings.width?(this.image_wrapper_width=this.settings.width,this.image_wrapper.width(this.settings.width),this.wrapper.width(this.settings.width)):
this.image_wrapper_width=this.image_wrapper.width();this.settings.height?(this.image_wrapper_height=this.settings.height,this.image_wrapper.height(this.settings.height)):this.image_wrapper_height=this.image_wrapper.height();this.nav_display_width=this.nav.width();this.current_index=-1;this.in_transition=this.current_description=this.current_image=!1;this.findImages();this.settings.display_next_and_prev&&this.initNextAndPrev();this.slideshow=new l(function(a){return c.nextImage(a)},this.settings.slideshow);
this.controls.append(this.slideshow.create());this.settings.slideshow.enable?this.slideshow.enable():this.slideshow.disable();this.settings.display_back_and_forward&&this.initBackAndForward();this.settings.enable_keyboard_move&&this.initKeyEvents();this.initHashChange();var d=parseInt(this.settings.start_at_index,10);"undefined"!=typeof this.getIndexFromHash()&&(d=this.getIndexFromHash());this.loading(!0);this.showImage(d,function(){if(c.settings.slideshow.autostart){c.preloadImage(d+1);c.slideshow.start()}});
this.fireCallback(this.settings.callbacks.init)},setupAnimations:function(){this.animations={"slide-vert":m,"slide-hori":n,resize:o,fade:p,none:q}},setupElements:function(){this.controls=this.wrapper.find(".ad-controls");this.gallery_info=e('<p class="ad-info"></p>');this.controls.append(this.gallery_info);this.image_wrapper=this.wrapper.find(".ad-image-wrapper");this.image_wrapper.empty();this.nav=this.wrapper.find(".ad-nav");this.thumbs_wrapper=this.nav.find(".ad-thumbs");this.preloads=e('<div class="ad-preloads"></div>');
this.loader=e('<img class="ad-loader" src="'+this.settings.loader_image+'">');this.image_wrapper.append(this.loader);this.loader.hide();e(document.body).append(this.preloads)},loading:function(a){a?this.loader.show():this.loader.hide()},addAnimation:function(a,b){e.isFunction(b)&&(this.animations[a]=b)},findImages:function(){var a=this;this.images=[];var b=0,c=this.thumbs_wrapper.find("a"),d=c.length;1>this.settings.thumb_opacity&&c.find("img").css("opacity",this.settings.thumb_opacity);c.each(function(c){var d=
e(this);d.data("ad-i",c);var g=d.attr("href"),j=d.find("img");a.whenImageLoaded(j[0],function(){var c=j[0].parentNode.parentNode.offsetWidth;0==j[0].width&&(c=50);a.thumbs_wrapper_width+=c;b++});a._initLink(d);a.images[c]=a._createImageData(d,g)});var g=setInterval(function(){d==b&&(a._setThumbListWidth(a.thumbs_wrapper_width),clearInterval(g))},300)},_setThumbListWidth:function(a){a+=25;this.nav.find(".ad-thumb-list").css("width",a+"px")},_initLink:function(a){var b=this;a.click(function(){b.showImage(a.data("ad-i"));
b.slideshow.stop();return!1}).hover(function(){!e(this).is(".ad-active")&&1>b.settings.thumb_opacity&&e(this).find("img").fadeTo(300,1);b.preloadImage(a.data("ad-i"))},function(){!e(this).is(".ad-active")&&1>b.settings.thumb_opacity&&e(this).find("img").fadeTo(300,b.settings.thumb_opacity)})},_createImageData:function(a,b){var c=!1,d=a.find("img");d.data("ad-link")?c=a.data("ad-link"):d.attr("longdesc")&&d.attr("longdesc").length&&(c=d.attr("longdesc"));var e=!1;d.data("ad-desc")?e=d.data("ad-desc"):
d.attr("alt")&&d.attr("alt").length&&(e=d.attr("alt"));var h=!1;d.data("ad-title")?h=d.data("ad-title"):d.attr("title")&&d.attr("title").length&&(h=d.attr("title"));return{thumb_link:a,image:b,error:!1,preloaded:!1,desc:e,title:h,size:!1,link:c}},initKeyEvents:function(){var a=this;e(document).keydown(function(b){39==b.keyCode?(a.nextImage(),a.slideshow.stop()):37==b.keyCode&&(a.prevImage(),a.slideshow.stop())})},getIndexFromHash:function(){if(window.location.hash&&0===window.location.hash.indexOf("#ad-image-")){var a=
window.location.hash.replace(/^#ad-image-/g,""),b=this.thumbs_wrapper.find("#"+a);if(b.length)return this.thumbs_wrapper.find("a").index(b);if(!isNaN(parseInt(a,10)))return parseInt(a,10)}},removeImage:function(a){if(0>a||a>=this.images.length)throw"Cannot remove image for index "+a;var b=this.images[a];this.images.splice(a,1);b=b.thumb_link;this.thumbs_wrapper_width-=b[0].parentNode.offsetWidth;b.remove();this._setThumbListWidth(this.thumbs_wrapper_width);this.gallery_info.html(this.current_index+
1+" / "+this.images.length);this.thumbs_wrapper.find("a").each(function(a){e(this).data("ad-i",a)});a==this.current_index&&0!=this.images.length&&this.showImage(0)},removeAllImages:function(){for(var a=this.images.length-1;0<=a;a--)this.removeImage(a)},addImage:function(a,b,c,d,g){var a=e('<li><a href="'+b+'" id="'+(c||"")+'"><img src="'+a+'" title="'+(d||"")+'" alt="'+(g||"")+'"></a></li>'),h=this;this.thumbs_wrapper.find("ul").append(a);var a=a.find("a"),f=a.find("img");f.css("opacity",this.settings.thumb_opacity);
this.whenImageLoaded(f[0],function(){var a=f[0].parentNode.parentNode.offsetWidth;0==f[0].width&&(a=100);h.thumbs_wrapper_width+=a;h._setThumbListWidth(h.thumbs_wrapper_width)});c=this.images.length;a.data("ad-i",c);this._initLink(a);this.images[c]=h._createImageData(a,b);this.gallery_info.html(this.current_index+1+" / "+this.images.length)},initHashChange:function(){var a=this;if("onhashchange"in window)e(window).bind("hashchange",function(){var b=a.getIndexFromHash();"undefined"!=typeof b&&b!=a.current_index&&
a.showImage(b)});else{var b=window.location.hash;setInterval(function(){if(window.location.hash!=b){b=window.location.hash;var c=a.getIndexFromHash();"undefined"!=typeof c&&c!=a.current_index&&a.showImage(c)}},200)}},initNextAndPrev:function(){this.next_link=e('<div class="ad-next"><div class="ad-next-image"></div></div>');this.prev_link=e('<div class="ad-prev"><div class="ad-prev-image"></div></div>');this.image_wrapper.append(this.next_link);this.image_wrapper.append(this.prev_link);var a=this;
this.prev_link.add(this.next_link).mouseover(function(){e(this).css("height",a.image_wrapper_height);e(this).find("div").show()}).mouseout(function(){e(this).find("div").hide()}).click(function(){e(this).is(".ad-next")?a.nextImage():a.prevImage();a.slideshow.stop()}).find("div").css("opacity",0.7)},initBackAndForward:function(){var a=this;this.scroll_forward=e('<div class="ad-forward"></div>');this.scroll_back=e('<div class="ad-back"></div>');this.nav.append(this.scroll_forward);this.nav.prepend(this.scroll_back);
var b=0,c=!1;e(this.scroll_back).add(this.scroll_forward).click(function(){var b=a.nav_display_width-50;0<a.settings.scroll_jump&&(b=a.settings.scroll_jump);b=e(this).is(".ad-forward")?a.thumbs_wrapper.scrollLeft()+b:a.thumbs_wrapper.scrollLeft()-b;a.settings.slideshow.stop_on_scroll&&a.slideshow.stop();a.thumbs_wrapper.animate({scrollLeft:b+"px"});return!1}).css("opacity",0.6).hover(function(){var d="left";e(this).is(".ad-forward")&&(d="right");c=setInterval(function(){b++;30<b&&a.settings.slideshow.stop_on_scroll&&
a.slideshow.stop();var c=a.thumbs_wrapper.scrollLeft()+1;"left"==d&&(c=a.thumbs_wrapper.scrollLeft()-1);a.thumbs_wrapper.scrollLeft(c)},10);e(this).css("opacity",1)},function(){b=0;clearInterval(c);e(this).css("opacity",0.6)})},_afterShow:function(){this.gallery_info.html(this.current_index+1+" / "+this.images.length);this.settings.cycle||(this.prev_link.show().css("height",this.image_wrapper_height),this.next_link.show().css("height",this.image_wrapper_height),this.current_index==this.images.length-
1&&this.next_link.hide(),0==this.current_index&&this.prev_link.hide());if(this.settings.update_window_hash){var a=this.images[this.current_index].thumb_link;window.location.hash=a.attr("id")?"#ad-image-"+a.attr("id"):"#ad-image-"+this.current_index}this.fireCallback(this.settings.callbacks.afterImageVisible)},_getContainedImageSize:function(a,b){if(b>this.image_wrapper_height)var c=a/b,b=this.image_wrapper_height,a=this.image_wrapper_height*c;a>this.image_wrapper_width&&(c=b/a,a=this.image_wrapper_width,
b=this.image_wrapper_width*c);return{width:a,height:b}},_centerImage:function(a,b,c){a.css("top","0px");c<this.image_wrapper_height&&(c=this.image_wrapper_height-c,a.css("top",c/2+"px"));a.css("left","0px");b<this.image_wrapper_width&&(c=this.image_wrapper_width-b,a.css("left",c/2+"px"))},_getDescription:function(a){var b="";if(a.desc.length||a.title.length){var c="";a.title.length&&(c='<strong class="ad-description-title">'+a.title+"</strong>");b="";a.desc.length&&(b="<span>"+a.desc+"</span>");b=
e('<p class="ad-image-description">'+c+b+"</p>")}return b},showImage:function(a,b){if(this.images[a]&&!this.in_transition&&a!=this.current_index){var c=this,d=this.images[a];this.in_transition=!0;d.preloaded?this._showWhenLoaded(a,b):(this.loading(!0),this.preloadImage(a,function(){c.loading(!1);c._showWhenLoaded(a,b)}))}},_showWhenLoaded:function(a,b){if(this.images[a]){var c=this,d=this.images[a],g=e(document.createElement("div")).addClass("ad-image"),h=e(new Image).attr("src",d.image);if(d.link){var f=
e('<a href="'+d.link+'" target="_blank"></a>');f.append(h);g.append(f)}else g.append(h);this.image_wrapper.prepend(g);f=this._getContainedImageSize(d.size.width,d.size.height);h.attr("width",f.width);h.attr("height",f.height);g.css({width:f.width+"px",height:f.height+"px"});this._centerImage(g,f.width,f.height);var i=this._getDescription(d);i&&(!this.settings.description_wrapper&&!this.settings.hooks.displayDescription?(g.append(i),d=f.width-parseInt(i.css("padding-left"),10)-parseInt(i.css("padding-right"),
10),i.css("width",d+"px")):this.settings.hooks.displayDescription?this.settings.hooks.displayDescription.call(this,d):this.settings.description_wrapper.append(i));this.highLightThumb(this.images[a].thumb_link);f="right";this.current_index<a&&(f="left");this.fireCallback(this.settings.callbacks.beforeImageVisible);if(this.current_image||this.settings.animate_first_image){d=this.settings.animation_speed;h="swing";f=this.animations[this.settings.effect].call(this,g,f,i);"undefined"!=typeof f.speed&&
(d=f.speed);"undefined"!=typeof f.easing&&(h=f.easing);if(this.current_image){var j=this.current_image,k=this.current_description;j.animate(f.old_image,d,h,function(){j.remove();k&&k.remove()})}g.animate(f.new_image,d,h,function(){c.current_index=a;c.current_image=g;c.current_description=i;c.in_transition=false;c._afterShow();c.fireCallback(b)})}else this.current_index=a,this.current_image=g,c.current_description=i,this.in_transition=!1,c._afterShow(),this.fireCallback(b)}},nextIndex:function(){var a;
if(this.current_index==this.images.length-1){if(!this.settings.cycle)return!1;a=0}else a=this.current_index+1;return a},nextImage:function(a){var b=this.nextIndex();if(!1===b)return!1;this.preloadImage(b+1);this.showImage(b,a);return!0},prevIndex:function(){var a;if(0==this.current_index){if(!this.settings.cycle)return!1;a=this.images.length-1}else a=this.current_index-1;return a},prevImage:function(a){var b=this.prevIndex();if(!1===b)return!1;this.preloadImage(b-1);this.showImage(b,a);return!0},
preloadAll:function(){function a(){c<b.images.length&&(c++,b.preloadImage(c,a))}var b=this,c=0;b.preloadImage(c,a)},preloadImage:function(a,b){if(this.images[a]){var c=this.images[a];if(this.images[a].preloaded)this.fireCallback(b);else{var d=e(new Image);d.attr("src",c.image);if(this.isImageLoaded(d[0]))c.preloaded=!0,c.size={width:d[0].width,height:d[0].height},this.fireCallback(b);else{this.preloads.append(d);var g=this;d.load(function(){c.preloaded=!0;c.size={width:this.width,height:this.height};
g.fireCallback(b)}).error(function(){c.error=!0;c.preloaded=!1;c.size=!1})}}}},whenImageLoaded:function(a,b){this.isImageLoaded(a)?b&&b():e(a).load(b)},isImageLoaded:function(a){return"undefined"!=typeof a.complete&&!a.complete||"undefined"!=typeof a.naturalWidth&&0==a.naturalWidth?!1:!0},highLightThumb:function(a){this.thumbs_wrapper.find(".ad-active").removeClass("ad-active");a.addClass("ad-active");1>this.settings.thumb_opacity&&(this.thumbs_wrapper.find("a:not(.ad-active) img").fadeTo(300,this.settings.thumb_opacity),
a.find("img").fadeTo(300,1));var b=a[0].parentNode.offsetLeft,b=b-(this.nav_display_width/2-a[0].offsetWidth/2);this.thumbs_wrapper.animate({scrollLeft:b+"px"})},fireCallback:function(a){e.isFunction(a)&&a.call(this)}};l.prototype={start_link:!1,stop_link:!1,countdown:!1,controls:!1,settings:!1,nextimage_callback:!1,enabled:!1,running:!1,countdown_interval:!1,init:function(a,b){this.nextimage_callback=a;this.settings=b},create:function(){this.start_link=e('<span class="ad-slideshow-start">'+this.settings.start_label+
"</span>");this.stop_link=e('<span class="ad-slideshow-stop">'+this.settings.stop_label+"</span>");this.countdown=e('<span class="ad-slideshow-countdown"></span>');this.controls=e('<div class="ad-slideshow-controls"></div>');this.controls.append(this.start_link).append(this.stop_link).append(this.countdown);this.countdown.hide();var a=this;this.start_link.click(function(){a.start()});this.stop_link.click(function(){a.stop()});e(document).keydown(function(b){83==b.keyCode&&(a.running?a.stop():a.start())});
return this.controls},disable:function(){this.enabled=!1;this.stop();this.controls.hide()},enable:function(){this.enabled=!0;this.controls.show()},toggle:function(){this.enabled?this.disable():this.enable()},start:function(){if(this.running||!this.enabled)return!1;this.running=!0;this.controls.addClass("ad-slideshow-running");this._next();this.fireCallback(this.settings.onStart);return!0},stop:function(){if(!this.running)return!1;this.running=!1;this.countdown.hide();this.controls.removeClass("ad-slideshow-running");
clearInterval(this.countdown_interval);this.fireCallback(this.settings.onStop);return!0},_next:function(){var a=this,b=this.settings.countdown_prefix,c=this.settings.countdown_sufix;clearInterval(a.countdown_interval);this.countdown.show().html(b+this.settings.speed/1E3+c);var d=0;this.countdown_interval=setInterval(function(){d+=1E3;d>=a.settings.speed&&(a.nextimage_callback(function(){a.running&&a._next();d=0})||a.stop(),d=0);var e=parseInt(a.countdown.text().replace(/[^0-9]/g,""),10);e--;0<e&&
a.countdown.html(b+e+c)},1E3)},fireCallback:function(a){e.isFunction(a)&&a.call(this)}}})(jQuery);