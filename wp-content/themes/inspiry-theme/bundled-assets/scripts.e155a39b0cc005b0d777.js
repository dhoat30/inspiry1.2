!function(e){var l={};function t(o){if(l[o])return l[o].exports;var r=l[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=e,t.c=l,t.d=function(e,l,o){t.o(e,l)||Object.defineProperty(e,l,{enumerable:!0,get:o})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,l){if(1&l&&(e=t(e)),8&l)return e;if(4&l&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(t.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&l&&"string"!=typeof e)for(var r in e)t.d(o,r,function(l){return e[l]}.bind(null,r));return o},t.n=function(e){var l=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(l,"a",l),l},t.o=function(e,l){return Object.prototype.hasOwnProperty.call(e,l)},t.p="/wp-content/themes/inspiry-theme/bundled-assets/",t(t.s=1)}([function(e,l,t){},function(e,l,t){"use strict";t.r(l);t(0);jQuery;var o=class{constructor(){this.aClick=document.querySelectorAll(".bc-wish-list-item-anchor"),this.createBtn=document.querySelector(".bc-wish-list-btn--new"),this.events()}events(){this.aClick.forEach(e=>{e.addEventListener("click",this.runAjax.bind(this))})}runAjax(e){e.preventDefault();let l=e.path[0].href;var t=new XMLHttpRequest;t.open("GET",l,!0),document.querySelector(".loader-icon").style.display="inline-block",t.onload=function(){document.querySelector(".loader-icon").style.display="none",document.querySelector(".loader-confirmation").style.display="block",console.log("success")},document.querySelector(".loader-confirmation").style.display="none",t.send()}};let r=jQuery;var n=class{constructor(){this.btn=r(".bc-single-product__warranty h1"),this.events()}events(){this.btn.append('<i class="fal fa-plus"></i> ')}};var s=class{constructor(){this.show(),this.calc()}show(){const e=document.querySelector(".sizing-calculator-button"),l=document.querySelector(".calculator-overlay"),t=document.querySelector(".overlay-background"),o=document.querySelector(".close");e.addEventListener("click",()=>{console.log("it is working"),t.classList.add("overlay-background--visible"),l.classList.add("calculator-overlay--visible")}),o.addEventListener("click",()=>{t.classList.remove("overlay-background--visible"),l.classList.remove("calculator-overlay--visible")})}calc(){var e=jQuery.noConflict(),l=l||{};l.CALCULATORMODULE={calculateNumberOfRolls:function(e,l,t,o,r){var n=100*o,s=100*l,c=100*e;console.log("calculateNumberOfRolls widthMeter",e),console.log("calculateNumberOfRolls heightMeter",l),console.log("calculateNumberOfRolls rollWidthCentiMeter",t),console.log("calculateNumberOfRolls rollHeightMeter",o),console.log("calculateNumberOfRolls rollPatternRepeatCentiMeter",r);var a=n/(s+r),u=a<0?Math.ceil(a):Math.floor(a),i=u*t,d=Math.round(c/i*1e4)/1e4;console.log("strips",u),console.log("stripWidth",i),console.log("numRolls",d),Math.ceil(d),console.log("numRolls",d);var y={numberOfRolls:d,numberOfRollsRoundedUp:Math.ceil(d)};return console.log("WV.MODULES.calculateNumberOfRolls result",y),y}},e(document).ready((function(t){e("#estimate-roll").click((function(o){o.preventDefault();var r=function(l){var o=e(l);if(console.log(o),""==o.val())return 0;var r=o.val(),n=parseFloat(r.replace(",","."));return t.isNumeric(n)?(o.parent().addClass("has-success"),o.parent().removeClass("has-error")):(o.parent().removeClass("has-success"),o.parent().addClass("has-error")),n};let n=r("#calc-roll-width"),s=r("#calc-roll-height"),c=r("#calc-pattern-repeat"),a=0;for(let e=1;e<=4;e++){let t=r("#calc-wall-width"+e),o=r("#calc-wall-height"+e),u=l.CALCULATORMODULE.calculateNumberOfRolls(t,o,n,s,c);console.log("wall"+e+" "+u.numberOfRolls),a+=u.numberOfRolls,console.log("roll total "+e+" - "+a)}console.log("roll total "+a),a.numberOfRollsRoundedUp<=1?(e(".suffix-singular").show(),e(".suffix-plural").hide()):(e(".suffix-singular").hide(),e(".suffix-plural").show()),e(".calc-round").html(Math.ceil(a))}))}))}};var c=class{constructor(){this.laybuyBtn=document.querySelector(".lay-buy-open"),this.laybuyCloseBtn=document.querySelector(".close-laybuy"),this.events()}events(){this.laybuyBtn.addEventListener("click",this.openLaybuy),this.laybuyCloseBtn.addEventListener("click",this.closeLaybuy)}openLaybuy(){console.log("laybuy clicked"),document.getElementById("laybuy-popup").style.display="flex"}closeLaybuy(){document.getElementById("laybuy-popup").style.display="none"}};jQuery;jQuery;jQuery;jQuery;jQuery;jQuery;var a=class{constructor(){this.events()}events(){}};let u=jQuery;jQuery;new class{constructor(){this.events()}events(){u(".login-tag").on("click",this.showLogInForm)}showLogInForm(e){e.preventDefault();let l=u(e.target).closest("a").attr("href");var t=new XMLHttpRequest;t.open("GET",l,!0),u(e.target).closest("a").html('<div class="loader-div" style="display:block"></div>'),e.target.querySelector(".loader-div").classList.add("loader-icon"),t.onload=function(){u(".login-overlay").show(300),u(e.target).closest("a").html("LOGIN / REGISTER"),u(".form-content").append(this.responseText),u(".login-overlay .fa-times").on("click",()=>{u(".login-overlay").hide(300),u(".form-content").html("")})},t.send()}},new a,new o,new n,new s,new c}]);