"use strict";function _classCallCheck(t,s){if(!(t instanceof s))throw new TypeError("Cannot call a class as a function")}function _defineProperties(t,s){for(var e=0;e<s.length;e++){var n=s[e];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function _createClass(t,s,e){return s&&_defineProperties(t.prototype,s),e&&_defineProperties(t,e),Object.defineProperty(t,"prototype",{writable:!1}),t}function _typeof(t){return(_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):"object"===("undefined"==typeof module?"undefined":_typeof(module))&&module.exports?module.exports=function(s,e){return void 0===e&&(e="undefined"!=typeof window?require("jquery"):require("jquery")(s)),t(e),e}:t(jQuery)}((function(t){var s={selected:0,theme:"basic",justified:!0,autoAdjustHeight:!0,backButtonSupport:!0,enableUrlHash:!0,transition:{animation:"none",speed:"400",easing:"",prefixCss:"",fwdShowCss:"",fwdHideCss:"",bckShowCss:"",bckHideCss:""},toolbar:{position:"bottom",showNextButton:!0,showPreviousButton:!0,extraHtml:""},anchor:{enableNavigation:!0,enableNavigationAlways:!1,enableDoneState:!0,markPreviousStepsAsDone:!0,unDoneOnBackNavigation:!1,enableDoneStateNavigation:!0},keyboard:{keyNavigation:!0,keyLeft:[37],keyRight:[39]},lang:{next:"Next",previous:"Previous"},style:{mainCss:"sw",navCss:"nav",navLinkCss:"nav-link",contentCss:"tab-content",contentPanelCss:"tab-pane",themePrefixCss:"sw-theme-",anchorDefaultCss:"default",anchorDoneCss:"done",anchorActiveCss:"active",anchorDisabledCss:"disabled",anchorHiddenCss:"hidden",anchorErrorCss:"error",anchorWarningCss:"warning",justifiedCss:"sw-justified",btnCss:"sw-btn",btnNextCss:"sw-btn-next",btnPrevCss:"sw-btn-prev",loaderCss:"sw-loading",progressCss:"progress",progressBarCss:"progress-bar",toolbarCss:"toolbar",toolbarPrefixCss:"toolbar-"},disabledSteps:[],errorSteps:[],warningSteps:[],hiddenSteps:[],getContent:null},e=function(){function e(n,i){var o=this;_classCallCheck(this,e),this.options=t.extend(!0,{},s,i),this.main=t(n),this.nav=this._getFirstDescendant("."+this.options.style.navCss),this.container=this._getFirstDescendant("."+this.options.style.contentCss),this.steps=this.nav.find("."+this.options.style.navLinkCss),this.pages=this.container.children("."+this.options.style.contentPanelCss),this.progressbar=this.main.find("."+this.options.style.progressCss),this.dir=this._getDir(),this.current_index=-1,this.is_init=!1,this._init(),setTimeout((function(){o._load()}),0)}return _createClass(e,[{key:"_init",value:function(){if(this._setElements(),this._setToolbar(),!0===this.is_init)return!0;this._setEvents(),this.is_init=!0,this._triggerEvent("initialized")}},{key:"_load",value:function(){this.pages.hide(),this.steps.removeClass([this.options.style.anchorDoneCss,this.options.style.anchorActiveCss]);var t=!(this.current_index=-1)!==(t=this._getURLHashIndex())?t:this.options.selected,s=this._getShowable(t-1,"forward");0<(t=null===s&&0<t?this._getShowable(-1,"forward"):s)&&this.options.anchor.enableDoneState&&this.options.anchor.markPreviousStepsAsDone&&this.steps.slice(0,t).addClass(this.options.style.anchorDoneCss),this._showStep(t),this._triggerEvent("loaded")}},{key:"_getFirstDescendant",value:function(s){var e=this.main.children(s);return 0<e.length?e:(this.main.children().each((function(n,i){var o=t(i).children(s);if(0<o.length)return e=o,!1})),0<e.length?e:(this._showError("Element not found "+s),!1))}},{key:"_getDir",value:function(){var t=this.main.prop("dir");return 0===t.length&&(t=document.documentElement.dir,this.main.prop("dir",t)),t}},{key:"_setElements",value:function(){var s=this;this.main.removeClass((function(t,e){return(e.match(new RegExp("(^|\\s)"+s.options.style.themePrefixCss+"\\S+","g"))||[]).join(" ")})).addClass(this.options.style.mainCss+" "+this.options.style.themePrefixCss+this.options.theme),this.main.toggleClass(this.options.style.justifiedCss,this.options.justified),!0===this.options.anchor.enableNavigationAlways&&!0===this.options.anchor.enableNavigation||this.steps.addClass(this.options.style.anchorDefaultCss),t.each(this.options.disabledSteps,(function(t,e){s.steps.eq(e).addClass(s.options.style.anchorDisabledCss)})),t.each(this.options.errorSteps,(function(t,e){s.steps.eq(e).addClass(s.options.style.anchorErrorCss)})),t.each(this.options.warningSteps,(function(t,e){s.steps.eq(e).addClass(s.options.style.anchorWarningCss)})),t.each(this.options.hiddenSteps,(function(t,e){s.steps.eq(e).addClass(s.options.style.anchorHiddenCss)}))}},{key:"_setEvents",value:function(){var s=this;this.steps.on("click",(function(e){var n;e.preventDefault(),!0===s.options.anchor.enableNavigation&&(n=t(e.currentTarget),s._isShowable(n)&&s._showStep(s.steps.index(n)))})),this.main.on("click",(function(e){t(e.target).hasClass(s.options.style.btnNextCss)?(e.preventDefault(),s._navigate("next")):t(e.target).hasClass(s.options.style.btnPrevCss)&&(e.preventDefault(),s._navigate("prev"))})),t(document).keyup((function(t){s._keyNav(t)})),t(window).on("hashchange",(function(t){var e;!0!==s.options.backButtonSupport||!1!==(e=s._getURLHashIndex())&&s._isShowable(s.steps.eq(e))&&(t.preventDefault(),s._showStep(e))})),t(window).on("resize",(function(t){s._fixHeight(s.current_index)}))}},{key:"_setToolbar",value:function(){this.main.find(".sw-toolbar-elm").remove();var t=this.options.toolbar.position;"none"!==t&&("both"==t?(this.container.before(this._createToolbar("top")),this.container.after(this._createToolbar("bottom"))):"top"==t?this.container.before(this._createToolbar("top")):this.container.after(this._createToolbar("bottom")))}},{key:"_createToolbar",value:function(s){var e=t("<div></div>").addClass("sw-toolbar-elm "+this.options.style.toolbarCss+" "+this.options.style.toolbarPrefixCss+s).attr("role","toolbar"),n=!1!==this.options.toolbar.showNextButton?t("<button></button>").text(this.options.lang.next).addClass("btn "+this.options.style.btnNextCss+" "+this.options.style.btnCss).attr("type","button"):null,i=!1!==this.options.toolbar.showPreviousButton?t("<button></button>").text(this.options.lang.previous).addClass("btn "+this.options.style.btnPrevCss+" "+this.options.style.btnCss).attr("type","button"):null;return e.append(i,n,this.options.toolbar.extraHtml)}},{key:"_navigate",value:function(t){this._showStep(this._getShowable(this.current_index,t))}},{key:"_showStep",value:function(t){var s=this;if(-1===t||null===t)return!1;if(t==this.current_index)return!1;if(!this.steps.eq(t))return!1;if(!this._isEnabled(this.steps.eq(t)))return!1;var e=this._getStepDirection(t);if(-1!==this.current_index&&!1===this._triggerEvent("leaveStep",[this._getStepAnchor(this.current_index),this.current_index,t,e]))return!1;this._loadContent(t,(function(){var n=s._getStepAnchor(t);s._setURLHash(n.attr("href")),s._setAnchor(t);var i=s._getStepPage(s.current_index),o=s._getStepPage(t);s._transit(o,i,e,(function(){s._fixHeight(t),s._triggerEvent("showStep",[n,t,e,s._getStepPosition(t)])})),s.current_index=t,s._setButtons(t),s._setProgressbar(t)}))}},{key:"_getShowable",value:function(s,e){var n=this,i=null;return("prev"==e?t(this.steps.slice(0,s).get().reverse()):this.steps.slice(s+1)).each((function(o,a){if(n._isEnabled(t(a)))return i="prev"==e?s-(o+1):o+s+1,!1})),i}},{key:"_isShowable",value:function(t){if(!this._isEnabled(t))return!1;var s=t.hasClass(this.options.style.anchorDoneCss);return!(!1===this.options.anchor.enableDoneStateNavigation&&s||!1===this.options.anchor.enableNavigationAlways&&!s)}},{key:"_isEnabled",value:function(t){return!t.hasClass(this.options.style.anchorDisabledCss)&&!t.hasClass(this.options.style.anchorHiddenCss)}},{key:"_getStepDirection",value:function(t){return this.current_index<t?"forward":"backward"}},{key:"_getStepPosition",value:function(t){return 0===t?"first":t===this.steps.length-1?"last":"middle"}},{key:"_getStepAnchor",value:function(t){return null==t||-1==t?null:this.steps.eq(t)}},{key:"_getStepPage",value:function(t){return null==t||-1==t?null:this.pages.eq(t)}},{key:"_loadContent",value:function(s,e){var n,i,o,a;t.isFunction(this.options.getContent)&&(n=this._getStepPage(s))?(i=this._getStepDirection(s),o=this._getStepPosition(s),a=this._getStepAnchor(s),this.options.getContent(s,i,o,a,(function(t){t&&n.html(t),e()}))):e()}},{key:"_transit",value:function(s,e,n,i){var o=t.fn.smartWizard.transitions[this.options.transition.animation];this._stopAnimations(),t.isFunction(o)?o(s,e,n,this,(function(t){!1===t&&(null!==e&&e.hide(),s.show()),i()})):(null!==e&&e.hide(),s.show(),i())}},{key:"_stopAnimations",value:function(){t.isFunction(this.container.finish)&&(this.pages.finish(),this.container.finish())}},{key:"_fixHeight",value:function(s){var e;!1!==this.options.autoAdjustHeight&&(e=this._getStepPage(s).outerHeight(),t.isFunction(this.container.finish)&&t.isFunction(this.container.animate)&&0<e?this.container.finish().animate({height:e},this.options.transition.speed):this.container.css({height:0<e?e:"auto"}))}},{key:"_setAnchor",value:function(t){var s,e;null!==this.current_index&&0<=this.current_index&&(s=this.options.style.anchorActiveCss,e="",!1!==this.options.anchor.enableDoneState&&(e+=this.options.style.anchorDoneCss,!1!==this.options.anchor.unDoneOnBackNavigation&&"backward"===this._getStepDirection(t)&&(s+=" "+this.options.style.anchorDoneCss)),this.steps.eq(this.current_index).addClass(e).removeClass(s)),this.steps.eq(t).removeClass(this.options.style.anchorDoneCss).addClass(this.options.style.anchorActiveCss)}},{key:"_setButtons",value:function(t){this.main.find("."+this.options.style.btnNextCss+", ."+this.options.style.btnPrevCss).removeClass(this.options.style.anchorDisabledCss);var s,e=this._getStepPosition(t);"first"===e||"last"===e?(s="first"===e?"."+this.options.style.btnPrevCss:"."+this.options.style.btnNextCss,this.main.find(s).addClass(this.options.style.anchorDisabledCss)):(null===this._getShowable(t,"next")&&this.main.find("."+this.options.style.btnNextCss).addClass(this.options.style.anchorDisabledCss),null===this._getShowable(t,"prev")&&this.main.find("."+this.options.style.btnPrevCss).addClass(this.options.style.anchorDisabledCss))}},{key:"_setProgressbar",value:function(t){var s=this.nav.width(),e=s/this.steps.length*(t+1)/s*100;document.documentElement.style.setProperty("--sw-progress-width",e+"%"),0<this.progressbar.length&&this.progressbar.find("."+this.options.style.progressBarCss).css("width",e+"%")}},{key:"_keyNav",value:function(s){if(this.options.keyboard.keyNavigation)if(-1<t.inArray(s.which,this.options.keyboard.keyLeft))this._navigate("prev"),s.preventDefault();else{if(!(-1<t.inArray(s.which,this.options.keyboard.keyRight)))return;this._navigate("next"),s.preventDefault()}}},{key:"_triggerEvent",value:function(s,e){var n=t.Event(s);return this.main.trigger(n,e),!n.isDefaultPrevented()&&n.result}},{key:"_setURLHash",value:function(t){this.options.enableUrlHash&&window.location.hash!==t&&history.pushState(null,null,t)}},{key:"_getURLHashIndex",value:function(){if(this.options.enableUrlHash){var t=window.location.hash;if(0<t.length){var s=this.nav.find("a[href*='"+t+"']");if(0<s.length)return this.steps.index(s)}}return!1}},{key:"_showError",value:function(t){console.error(t)}},{key:"_changeState",value:function(s,e,n){var i=this;n=!1!==n;var o="";"default"==e?o=this.options.style.anchorDefaultCss:"active"==e?o=this.options.style.anchorActiveCss:"done"==e?o=this.options.style.anchorDoneCss:"disable"==e?o=this.options.style.anchorDisabledCss:"hidden"==e?o=this.options.style.anchorHiddenCss:"error"==e?o=this.options.style.anchorErrorCss:"warning"==e&&(o=this.options.style.anchorWarningCss),t.each(s,(function(t,s){i.steps.eq(s).toggleClass(o,n)}))}},{key:"goToStep",value:function(t,s){1!=(s=!1!==s)&&!this._isShowable(this.steps.eq(t))||(!0===s&&0<t&&this.options.anchor.enableDoneState&&this.options.anchor.markPreviousStepsAsDone&&this.steps.slice(0,t).addClass(this.options.style.anchorDoneCss),this._showStep(t))}},{key:"next",value:function(){this._navigate("next")}},{key:"prev",value:function(){this._navigate("prev")}},{key:"reset",value:function(){this.steps.removeClass([this.options.style.anchorDoneCss,this.options.style.anchorActiveCss,this.options.style.anchorErrorCss,this.options.style.anchorWarningCss]),this._setURLHash("#"),this._init(),this._load()}},{key:"setState",value:function(t,s){this._changeState(t,s,!0)}},{key:"unsetState",value:function(t,s){this._changeState(t,s,!1)}},{key:"setOptions",value:function(s){this.options=t.extend(!0,{},this.options,s),this._init()}},{key:"getOptions",value:function(){return this.options}},{key:"getStepInfo",value:function(){return{currentStep:this.current_index?this.current_index:0,totalSteps:this.steps?this.steps.length:0}}},{key:"loader",value:function(t){this.main.toggleClass(this.options.style.loaderCss,"show"===t)}},{key:"fixHeight",value:function(){this._fixHeight(this.current_index)}}]),e}();t.fn.smartWizard=function(s){if(void 0===s||"object"===_typeof(s))return this.each((function(){t.data(this,"smartWizard")||t.data(this,"smartWizard",new e(this,s))}));if("string"==typeof s&&"_"!==s[0]&&"init"!==s){var n=t.data(this[0],"smartWizard");return"destroy"===s&&t.data(this,"smartWizard",null),n instanceof e&&"function"==typeof n[s]?n[s].apply(n,Array.prototype.slice.call(arguments,1)):this}},t.fn.smartWizard.transitions={fade:function(s,e,n,i,o){t.isFunction(s.fadeOut)?e?e.fadeOut(i.options.transition.speed,i.options.transition.easing,(function(){s.fadeIn(i.options.transition.speed,i.options.transition.easing,(function(){o()}))})):s.fadeIn(i.options.transition.speed,i.options.transition.easing,(function(){o()})):o(!1)},slideSwing:function(s,e,n,i,o){t.isFunction(s.slideDown)?e?e.slideUp(i.options.transition.speed,i.options.transition.easing,(function(){s.slideDown(i.options.transition.speed,i.options.transition.easing,(function(){o()}))})):s.slideDown(i.options.transition.speed,i.options.transition.easing,(function(){o()})):o(!1)},slideHorizontal:function(s,e,n,i,o){var a,r,h,l;t.isFunction(s.animate)?(a=function(t,s,e,n){t.css({position:"absolute",left:s}).show().animate({left:e},i.options.transition.speed,i.options.transition.easing,n)},-1==i.current_index&&i.container.height(s.outerHeight()),r=i.container.width(),e&&(h=e.css(["position","left"]),a(e,0,r*("backward"==n?1:-1),(function(){e.hide().css(h)}))),l=s.css(["position"]),a(s,r*("backward"==n?-2:1),0,(function(){s.css(l),o()}))):o(!1)},slideVertical:function(s,e,n,i,o){var a,r,h,l;t.isFunction(s.animate)?(a=function(t,s,e,n){t.css({position:"absolute",top:s}).show().animate({top:e},i.options.transition.speed,i.options.transition.easing,n)},-1==i.current_index&&i.container.height(s.outerHeight()),r=i.container.height(),e&&(h=e.css(["position","top"]),a(e,0,r*("backward"==n?-1:1),(function(){e.hide().css(h)}))),l=s.css(["position"]),a(s,r*("backward"==n?1:-2),0,(function(){s.css(l),o()}))):o(!1)},css:function(s,e,n,i,o){var a,r,h;0!=i.options.transition.fwdHideCss.length&&0!=i.options.transition.bckHideCss.length?(a=function(s,e,n){e&&0!=e.length||n(),s.addClass(e).one("animationend",(function(s){t(s.currentTarget).removeClass(e),n()})),s.addClass(e).one("animationcancel",(function(s){t(s.currentTarget).removeClass(e),n("cancel")}))},r=i.options.transition.prefixCss+" "+("backward"==n?i.options.transition.bckShowCss:i.options.transition.fwdShowCss),e?(h=i.options.transition.prefixCss+" "+("backward"==n?i.options.transition.bckHideCss:i.options.transition.fwdHideCss),a(e,h,(function(){e.hide(),a(s,r,(function(){o()})),s.show()}))):(a(s,r,(function(){o()})),s.show())):o(!1)}}}));